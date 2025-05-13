<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    /**
     * @var StripeClient
     */
    protected $stripe;

    /**
     * StripeService constructor.
     */
    public function __construct()
    {
        // التأكد من وجود المفتاح السري لـ Stripe
        $stripeSecret = config('services.stripe.secret');
        
        // إذا كان المفتاح غير متوفر في ملف الإعدادات، استخدم قيمة من المتغيرات البيئية أو قيمة افتراضية للاختبار
        if (empty($stripeSecret)) {
            $stripeSecret = env('STRIPE_SECRET_KEY', 'sk_test_51OMRoTCwOdKUhh3YDQIVkK8MIgfCPqFWRVzJdSCzDh3NpGkJOODhcFSXdlLOBJdmb5RgMgNRwzYt7HVD4beuNVcI00V46i5UpM');
        }
        
        $this->stripe = new StripeClient($stripeSecret);
    }

    /**
     * إنشاء معاملة دفع جديدة
     *
     * @param User $user
     * @param float $amount
     * @param string $currency
     * @param string $paymentMethod
     * @param string $description
     * @param array $metadata
     * @param mixed|null $payable
     * @return Payment
     * @throws ApiErrorException
     */
    public function createPayment(
        User $user,
        float $amount,
        string $currency = 'SAR',
        string $paymentMethod = null,
        string $description = null,
        array $metadata = [],
        $payable = null
    ): Payment {
        try {
            // 1. إنشاء أو الحصول على معرف العميل في Stripe
            $stripeCustomer = $this->getOrCreateCustomer($user);

            // 2. إنشاء الدفعة في Stripe
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $this->convertAmountToCents($amount, $currency),
                'currency' => strtolower($currency),
                'customer' => $stripeCustomer->id,
                'payment_method' => $paymentMethod,
                'description' => $description,
                'metadata' => array_merge($metadata, [
                    'user_id' => $user->id,
                ]),
                'confirm' => $paymentMethod ? true : false,
            ]);

            // 3. إنشاء سجل الدفع في قاعدة البيانات
            $payment = new Payment([
                'user_id' => $user->id,
                'amount' => $amount,
                'currency' => $currency,
                'payment_method' => $paymentMethod,
                'status' => Payment::STATUS_PENDING,
                'stripe_payment_id' => $paymentIntent->id,
                'stripe_customer_id' => $stripeCustomer->id,
                'description' => $description,
                'metadata' => $metadata,
            ]);

            // إذا كان هناك payable object
            if ($payable) {
                $payment->payable()->associate($payable);
            }

            $payment->save();

            return $payment;
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment creation error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'amount' => $amount,
                'currency' => $currency,
            ]);

            // إنشاء سجل الدفع مع حالة فشل
            $payment = new Payment([
                'user_id' => $user->id,
                'amount' => $amount,
                'currency' => $currency,
                'status' => Payment::STATUS_FAILED,
                'error_message' => $e->getMessage(),
                'metadata' => $metadata,
            ]);

            if ($payable) {
                $payment->payable()->associate($payable);
            }

            $payment->save();

            throw $e;
        }
    }

    /**
     * تحقق من حالة الدفع وتحديثها
     *
     * @param Payment $payment
     * @return Payment
     * @throws ApiErrorException
     */
    public function checkPaymentStatus(Payment $payment): Payment
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($payment->stripe_payment_id);

            // تحديث حالة الدفع بناءً على حالة Stripe
            $statusMap = [
                'succeeded' => Payment::STATUS_COMPLETED,
                'processing' => Payment::STATUS_PENDING,
                'requires_payment_method' => Payment::STATUS_PENDING,
                'requires_capture' => Payment::STATUS_PENDING,
                'requires_confirmation' => Payment::STATUS_PENDING,
                'requires_action' => Payment::STATUS_PENDING,
                'canceled' => Payment::STATUS_CANCELLED,
            ];

            $payment->status = $statusMap[$paymentIntent->status] ?? Payment::STATUS_FAILED;
            
            if ($payment->status === Payment::STATUS_COMPLETED) {
                $payment->receipt_url = $this->getReceiptUrl($payment);
            }

            $payment->save();

            return $payment;
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment status check error: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'stripe_payment_id' => $payment->stripe_payment_id,
            ]);

            throw $e;
        }
    }

    /**
     * الحصول على رابط الإيصال الخاص بالدفعة
     *
     * @param Payment $payment
     * @return string|null
     */
    public function getReceiptUrl(Payment $payment): ?string
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($payment->stripe_payment_id);

            if ($paymentIntent->status === 'succeeded' && isset($paymentIntent->charges->data[0])) {
                $charge = $paymentIntent->charges->data[0];
                return $charge->receipt_url;
            }

            return null;
        } catch (ApiErrorException $e) {
            Log::error('Error getting receipt URL: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * استرداد مبلغ معين أو كامل المبلغ
     *
     * @param Payment $payment
     * @param float|null $amount
     * @param string $reason
     * @return bool
     */
    public function refundPayment(Payment $payment, float $amount = null, string $reason = ''): bool
    {
        try {
            $refundParams = [
                'payment_intent' => $payment->stripe_payment_id,
                'reason' => $reason ?: 'requested_by_customer',
            ];

            // إذا تم تحديد مبلغ للاسترداد (استرداد جزئي)
            if ($amount !== null) {
                $refundParams['amount'] = $this->convertAmountToCents($amount, $payment->currency);
            }

            $refund = $this->stripe->refunds->create($refundParams);

            if ($refund->status === 'succeeded') {
                // تحديث حالة الدفع إلى "مسترد"
                $payment->status = Payment::STATUS_REFUNDED;
                $payment->refund_id = $refund->id;
                $payment->refunded_amount = $amount ?: $payment->amount;
                $payment->save();

                return true;
            }

            return false;
        } catch (ApiErrorException $e) {
            Log::error('Stripe refund error: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'stripe_payment_id' => $payment->stripe_payment_id,
            ]);

            return false;
        }
    }

    /**
     * إنشاء أو الحصول على معرف العميل في Stripe
     *
     * @param User $user
     * @return \Stripe\Customer
     * @throws ApiErrorException
     */
    public function getOrCreateCustomer(User $user)
    {
        // البحث عن العميل في Stripe بناءً على معرف المستخدم
        $customers = $this->stripe->customers->all([
            'email' => $user->email,
            'limit' => 1,
        ]);

        if (!empty($customers->data)) {
            return $customers->data[0];
        }

        // إنشاء عميل جديد في Stripe
        return $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);
    }

    /**
     * إنشاء جلسة دفع للحجز باستخدام Stripe
     *
     * @param \App\Models\Booking $booking
     * @param array $userData
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createBookingPaymentSession($booking, array $userData = [])
    {
        try {
            // إنشاء بيانات وصفية للدفع
            $metadata = [
                'booking_id' => $booking->id,
                'specialist_id' => $booking->specialist_id,
                'service_id' => $booking->service_id,
                'booking_date' => $booking->booking_date,
                'booking_time' => $booking->booking_time,
                'payment_type' => 'booking',
            ];

            // إذا كان هناك بيانات مستخدم، أضفها إلى البيانات الوصفية
            if (!empty($userData)) {
                $metadata['user_name'] = $userData['name'] ?? null;
                $metadata['user_email'] = $userData['email'] ?? null;
                $metadata['user_phone'] = $userData['phone'] ?? null;
            }

            // إنشاء PaymentIntent في Stripe
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $this->convertAmountToCents((float)$booking->price, 'SAR'),
                'currency' => 'sar',
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'description' => 'حجز خدمة: ' . ($booking->service->name ?? '') . ' مع المتخصص: ' . ($booking->specialist->name ?? ''),
            ]);

            return $paymentIntent;
            
        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء جلسة دفع الحجز: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * التحقق من حالة PaymentIntent مباشرة
     *
     * @param string $paymentIntentId
     * @return string
     * @throws \Exception
     */
    public function checkPaymentIntentStatus($paymentIntentId)
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            return $paymentIntent->status;
        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من حالة PaymentIntent: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * تحويل المبلغ إلى سنتات (أصغر وحدة للعملة)
     *
     * @param float $amount
     * @param string $currency
     * @return int
     */
    protected function convertAmountToCents(float $amount, string $currency): int
    {
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];
        
        return in_array(strtoupper($currency), $zeroDecimalCurrencies) ? (int) $amount : (int) ($amount * 100);
    }
}
