<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class BookingStripeService
{
    /**
     * مثيل Stripe Client
     *
     * @var \Stripe\StripeClient
     */
    protected $stripe;

    /**
     * إنشاء مثيل جديد من BookingStripeService
     */
    public function __construct()
    {
        // استخدام مفتاح Stripe من ملف التكوين
        $stripeKey = config('services.stripe.secret');
        
        // تهيئة عميل Stripe باستخدام المفتاح السري
        $this->stripe = new StripeClient($stripeKey);
    }

    /**
     * إنشاء جلسة دفع جديدة لحجز
     *
     * @param Booking $booking
     * @return array
     * @throws \Exception
     */
    public function createCheckoutSession(Booking $booking)
    {
        try {
            // إنشاء بيانات العميل
            $customerName = $booking->getCustomerName();
            $customerEmail = $booking->getCustomerEmail();
            $customerPhone = $booking->getCustomerPhone();
            
            // معلومات التخصص والخدمة
            $specialistName = $booking->specialist ? $booking->specialist->name : 'غير معروف';
            $serviceName = $booking->service ? $booking->service->name : 'غير معروف';
            
            // إعداد وصف للمنتج
            $description = "حجز مع {$specialistName} - {$serviceName} في {$booking->booking_date->format('Y-m-d')} الساعة {$booking->booking_time}";
            
            // التأكد من وجود بيانات الحجز المطلوبة
            if (empty($customerEmail) || empty($booking->price)) {
                throw new \Exception('بيانات الحجز غير مكتملة');
            }
            
            // إنشاء جلسة Checkout الفعلية مع Stripe
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'customer_email' => $customerEmail,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'sar',
                            'product_data' => [
                                'name' => $serviceName,
                                'description' => $description,
                                'images' => [url('/images/nafsaji-logo.png')],
                            ],
                            'unit_amount' => $booking->price * 100, // بالهللات 
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'transaction_id' => $booking->transaction_id,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'customer_phone' => $customerPhone,
                ],
                'mode' => 'payment',
                'success_url' => route('booking.payment.success', ['transaction_id' => $booking->transaction_id]),
                'cancel_url' => route('booking.payment.cancel', ['transaction_id' => $booking->transaction_id]),
                'locale' => 'auto', // استخدام 'auto' للكشف التلقائي أو 'en' للغة الإنجليزية
            ]);
            
            Log::info('تم إنشاء جلسة Stripe بنجاح', [
                'booking_id' => $booking->id,
                'session_id' => $session->id,
                'amount' => $booking->price,
            ]);

            return [
                'success' => true,
                'session_id' => $session->id,
                'checkout_url' => $session->url,
            ];
        } catch (ApiErrorException $e) {
            Log::error('خطأ في إنشاء جلسة Stripe', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            
            throw new \Exception('حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من حالة الدفع
     *
     * @param string $sessionId
     * @return array
     */
    public function checkSessionStatus($sessionId)
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);
            
            return [
                'success' => true,
                'status' => $session->payment_status,
                'session' => $session,
            ];
        } catch (ApiErrorException $e) {
            Log::error('خطأ في التحقق من حالة جلسة Stripe', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * معالجة دفع ناجح وتحديث حالة الحجز
     *
     * @param Booking $booking
     * @param string $sessionId
     * @return array
     */
    public function processSuccessfulPayment(Booking $booking, $sessionId)
    {
        try {
            // استرجاع معلومات الجلسة من Stripe
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);
            
            // التحقق من أن الدفع تم بنجاح
            if ($session->payment_status === 'paid') {
                // تحديث حالة الحجز
                $booking->payment_status = 'paid';
                $booking->status = 'confirmed';
                // تخزين معرف الدفع في حقل metadata
                $booking->metadata = array_merge($booking->metadata ?? [], [
                    'stripe_payment_id' => $session->payment_intent,
                    'payment_completed_at' => now()->toDateTimeString(),
                ]);
                $booking->save();
                
                // إنشاء سجل دفع
                $payment = new Payment([
                    'user_id' => $booking->user_id,
                    'amount' => $booking->price,
                    'currency' => 'sar',
                    'payment_method' => 'credit_card', // Usando credit_card en vez de stripe ya que es un ENUM con valores específicos
                    'status' => Payment::STATUS_COMPLETED,
                    'stripe_payment_id' => $session->payment_intent,
                    'description' => 'دفع حجز رقم #' . $booking->id,
                    'metadata' => [
                        'booking_id' => $booking->id,
                        'transaction_id' => $booking->transaction_id,
                        'session_id' => $sessionId,
                        'payment_provider' => 'stripe', // Guardamos el proveedor en los metadatos
                    ]
                ]);
                
                // ربط الدفع بالحجز (علاقة متعددة الأشكال)
                $booking->payments()->save($payment);
                
                Log::info('تم معالجة الدفع بنجاح', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'session_id' => $sessionId,
                ]);
                
                return [
                    'success' => true,
                    'booking' => $booking,
                    'payment' => $payment,
                ];
            }
            
            return [
                'success' => false,
                'message' => 'لم يتم اكتمال الدفع بعد',
            ];
        } catch (ApiErrorException $e) {
            Log::error('خطأ في معالجة الدفع الناجح', [
                'booking_id' => $booking->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الدفع: ' . $e->getMessage(),
            ];
        }
    }
}
