<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Webhook;

class PaymentController extends Controller
{
    /**
     * خدمة Stripe
     *
     * @var StripeService
     */
    protected $stripeService;

    /**
     * Constructor
     *
     * @param StripeService $stripeService
     */
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        
        // تطبيق middleware للمصادقة على المستخدم باستثناء المسارات التي يمكن للزوار الوصول إليها
        $this->middleware('auth')->except(['webhook', 'paymentStatus', 'checkout', 'success', 'cancel']);
    }

    /**
     * إنشاء صفحة إتمام عملية الدفع
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function checkout(Request $request)
    {
        // استخراج معلومات المنتج/الخدمة من الطلب
        $amount = $request->input('amount');
        $currency = $request->input('currency', 'SAR');
        $description = $request->input('description');
        $returnUrl = $request->input('return_url', route('payment.success'));
        $cancelUrl = $request->input('cancel_url', route('payment.cancel'));
        $bookingId = $request->input('booking_id');
        
        // تحقق من المبلغ
        if (!$amount || $amount <= 0) {
            return redirect()->back()->with('error', 'المبلغ غير صالح');
        }
        
        // التحقق من وجود حجز إذا تم تقديم معرف الحجز
        $booking = null;
        if ($bookingId) {
            $booking = \App\Models\Booking::find($bookingId);
            if (!$booking) {
                return redirect()->back()->with('error', __('messages.booking_not_found'));
            }
        }
        
        // بيانات المستخدم للدفع
        $user = Auth::user();
        
        // إنشاء واجهة الدفع
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            
            // إنشاء بيانات وصفية للدفع
            $metadata = [
                'description' => $description ?? 'دفع في موقع نفسجي',
            ];
            
            // إضافة معرف المستخدم إذا كان مسجل الدخول
            if (Auth::check()) {
                $metadata['user_id'] = $user->id;
            }
            
            // إضافة معرف الحجز إذا كان موجوداً
            if ($bookingId) {
                $metadata['booking_id'] = $bookingId;
                $metadata['payment_type'] = 'booking';
                
                // إذا كان الحجز لزائر غير مسجل، نضيف بياناته
                if ($booking && !$booking->user_id && $booking->guest_details) {
                    $guestDetails = $booking->guest_details;
                    $metadata['guest_email'] = $guestDetails['email'] ?? '';
                    $metadata['guest_name'] = $guestDetails['name'] ?? '';
                    $metadata['guest_phone'] = $guestDetails['phone'] ?? '';
                }
            }
            
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => (int) ($amount * 100), // تحويل إلى أصغر وحدة (هللة)
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            
            // إنشاء سجل عملية الدفع في قاعدة البيانات
            $paymentData = [
                'amount' => $amount,
                'currency' => $currency,
                'status' => Payment::STATUS_PENDING,
                'stripe_payment_id' => $paymentIntent->id,
                'description' => $description ?? 'دفع في موقع نفسجي',
                'metadata' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ],
            ];
            
            // إضافة معرف المستخدم إذا كان مسجل الدخول
            if (Auth::check()) {
                $paymentData['user_id'] = $user->id;
            }
            
            // إضافة ارتباط بالحجز إذا كان موجوداً
            if ($booking) {
                $paymentData['payable_type'] = get_class($booking);
                $paymentData['payable_id'] = $booking->id;
            }
            
            $payment = new Payment($paymentData);
            
            $payment->save();
            
            // إرسال المعلومات إلى صفحة الدفع
            return view('payments.checkout', [
                'payment' => $payment,
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => config('services.stripe.key'),
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description,
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
                'user' => $user,
                'booking' => $booking,
                'isGuest' => !Auth::check(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ أثناء إنشاء عملية الدفع: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إعداد عملية الدفع، يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * معالجة نجاح عملية الدفع
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent');
        
        if (!$paymentIntentId) {
            return redirect()->route('home')->with('error', 'لم يتم العثور على تفاصيل الدفع');
        }
        
        // البحث عن عملية الدفع في قاعدة البيانات
        $payment = Payment::where('stripe_payment_id', $paymentIntentId)->first();
        
        if (!$payment) {
            return redirect()->route('home')->with('error', 'لم يتم العثور على عملية الدفع');
        }
        
        try {
            // تحديث حالة الدفع
            $payment = $this->stripeService->checkPaymentStatus($payment);
            
            if ($payment->isCompleted()) {
                // إذا كانت عملية الدفع مرتبطة بحجز
                if (isset($payment->metadata['booking_id']) && $payment->metadata['payment_type'] === 'booking') {
                    // البحث عن الحجز وتحديث حالته
                    $booking = \App\Models\Booking::find($payment->metadata['booking_id']);
                    
                    if ($booking) {
                        $booking->status = 'confirmed';
                        $booking->payment_status = 'paid';
                        $booking->payment_method = 'online';
                        $booking->transaction_id = $paymentIntentId;
                        $booking->save();
                    }
                }
                
                // إذا كان هناك URL محدد للعودة في البيانات الوصفية
                $returnUrl = $payment->metadata['return_url'] ?? route('home');
                
                return redirect($returnUrl)->with('success', 'تمت عملية الدفع بنجاح!');
            }
            
            return redirect()->route('payment.status', $payment->id)->with('warning', 'عملية الدفع قيد المعالجة');
            
        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من حالة الدفع: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء التحقق من حالة الدفع');
        }
    }

    /**
     * معالجة إلغاء عملية الدفع
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent');
        
        if ($paymentIntentId) {
            $payment = Payment::where('stripe_payment_id', $paymentIntentId)->first();
            
            if ($payment) {
                $payment->status = Payment::STATUS_CANCELLED;
                $payment->save();
                
                // استخدام رابط الإلغاء المخصص إذا كان موجودًا
                $cancelUrl = $payment->metadata['cancel_url'] ?? route('home');
                return redirect($cancelUrl)->with('warning', 'تم إلغاء عملية الدفع');
            }
        }
        
        return redirect()->route('home')->with('warning', 'تم إلغاء عملية الدفع');
    }

    /**
     * عرض حالة الدفع
     *
     * @param Payment $payment
     * @return \Illuminate\View\View
     */
    public function paymentStatus(Payment $payment)
    {
        if (Auth::check() && Auth::id() !== $payment->user_id) {
            abort(403, 'غير مصرح بالوصول إلى هذه المعلومات');
        }
        
        try {
            // تحديث حالة الدفع
            $payment = $this->stripeService->checkPaymentStatus($payment);
        } catch (\Exception $e) {
            Log::error('خطأ في التحقق من حالة الدفع: ' . $e->getMessage());
        }
        
        return view('payments.status', [
            'payment' => $payment,
        ]);
    }

    /**
     * استقبال ومعالجة إشعارات Stripe webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        // التحقق من التوقيع لضمان أن الطلب قادم فعلًا من Stripe
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook.secret');
        
        try {
            if (!$webhookSecret) {
                Log::error('Webhook secret is not configured');
                return response()->json(['error' => 'Webhook secret not configured'], 500);
            }
            
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            
            // معالجة الأحداث المختلفة من Stripe
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handleSuccessfulPayment($event->data->object);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $this->handleFailedPayment($event->data->object);
                    break;
                    
                case 'charge.refunded':
                    $this->handleRefundedPayment($event->data->object);
                    break;
                    
                default:
                    // تسجيل الأحداث الأخرى ولكن عدم معالجتها
                    Log::info('Received Stripe webhook: ' . $event->type);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * معالجة حدث نجاح الدفع
     *
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    protected function handleSuccessfulPayment(PaymentIntent $paymentIntent)
    {
        $payment = Payment::where('stripe_payment_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->status = Payment::STATUS_COMPLETED;
            
            // حفظ رابط الإيصال إذا كان متاحًا
            if (isset($paymentIntent->charges->data[0])) {
                $payment->receipt_url = $paymentIntent->charges->data[0]->receipt_url;
            }
            
            $payment->save();
            
            Log::info('تم تحديث حالة الدفع إلى مكتملة', [
                'payment_id' => $payment->id,
                'stripe_payment_id' => $paymentIntent->id,
            ]);
            
            // هنا يمكن إضافة معالجة إضافية مثل إرسال إشعار للمستخدم، أو تحديث حالة الطلب... إلخ
        } else {
            Log::warning('تم استلام إشعار بنجاح الدفع ولكن لم يتم العثور على السجل المقابل', [
                'stripe_payment_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * معالجة حدث فشل الدفع
     *
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    protected function handleFailedPayment(PaymentIntent $paymentIntent)
    {
        $payment = Payment::where('stripe_payment_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->status = Payment::STATUS_FAILED;
            
            // حفظ رسالة الخطأ إذا كانت متاحة
            if (isset($paymentIntent->last_payment_error)) {
                $payment->error_message = $paymentIntent->last_payment_error->message;
            }
            
            $payment->save();
            
            Log::info('تم تحديث حالة الدفع إلى فاشلة', [
                'payment_id' => $payment->id,
                'stripe_payment_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * معالجة حدث استرداد المبلغ
     *
     * @param object $charge
     * @return void
     */
    protected function handleRefundedPayment($charge)
    {
        // البحث عن سجل الدفع بناءً على معرف PaymentIntent المرتبط بالـ Charge
        if (isset($charge->payment_intent)) {
            $payment = Payment::where('stripe_payment_id', $charge->payment_intent)->first();
            
            if ($payment) {
                $payment->status = Payment::STATUS_REFUNDED;
                $payment->metadata = array_merge($payment->metadata ?? [], [
                    'refunded_at' => now()->toIso8601String(),
                    'refund_id' => $charge->refunds->data[0]->id ?? null,
                    'refund_amount' => $charge->amount_refunded / 100, // تحويل من سنتات إلى وحدة العملة الأساسية
                    'refund_reason' => $charge->refunds->data[0]->reason ?? null,
                ]);
                
                $payment->save();
                
                Log::info('تم تحديث حالة الدفع إلى مسترد', [
                    'payment_id' => $payment->id,
                    'stripe_payment_id' => $charge->payment_intent,
                ]);
            }
        }
    }
}
