<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\ApiErrorException;

class BookingPaymentController extends Controller
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
    }

    /**
     * عرض صفحة الدفع للحجز
     *
     * @param  string  $transaction_id
     * @return \Illuminate\View\View
     */
    public function showPayment($transaction_id)
    {
        // البحث عن الحجز باستخدام رمز المعاملة
        $booking = Booking::where('transaction_id', $transaction_id)->firstOrFail();

        // التحقق من أن الحجز لم يتم دفعه بالفعل
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking->transaction_id)
                         ->with('info', __('messages.booking_already_paid'));
        }

        // بيانات المستخدم للدفع
        $user = Auth::user();
        $userData = [];

        // إذا كان المستخدم مسجل دخول
        if ($user) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];
        }
        // إذا كان ضيف، استخدم بيانات الضيف
        elseif ($booking->guest_details) {
            $guestDetails = is_string($booking->guest_details) 
                ? json_decode($booking->guest_details, true) 
                : $booking->guest_details;

            $userData = [
                'name' => $guestDetails['name'] ?? '',
                'email' => $guestDetails['email'] ?? '',
                'phone' => $guestDetails['phone'] ?? '',
            ];
        }

        try {
            // تسجيل بدء محاولة إنشاء جلسة دفع للتشخيص
            Log::info('جاري إنشاء جلسة دفع للحجز:', [
                'booking_id' => $booking->id,
                'transaction_id' => $booking->transaction_id,
                'price' => $booking->price,
            ]);
            
            // إنشاء جلسة دفع جديدة مع Stripe
            $paymentSession = $this->stripeService->createBookingPaymentSession($booking, $userData);
            
            // تسجيل نجاح إنشاء جلسة الدفع للتشخيص
            Log::info('تم إنشاء جلسة دفع بنجاح:', [
                'payment_intent_id' => $paymentSession->id,
            ]);

            // عرض صفحة الدفع باستخدام قالب booking/payment.blade.php لتوحيد تدفق العمل
            Log::info('توجيه المستخدم إلى صفحة الدفع', [
                'transaction_id' => $booking->transaction_id,
                'template' => 'booking.payment'
            ]);
            
            return view('booking.payment', [
                'booking' => $booking->load(['specialist', 'service']),
                'clientSecret' => $paymentSession->client_secret,
                'stripeKey' => config('services.stripe.key'),
                'paymentIntentId' => $paymentSession->id,
                'amount' => $booking->price,
                'currency' => 'sar',
                'description' => 'حجز خدمة: ' . ($booking->service->name ?? '') . ' مع المتخصص: ' . ($booking->specialist->name ?? ''),
                'returnUrl' => route('booking.payment.success', $booking->transaction_id),
                'cancelUrl' => route('booking.payment.cancel', $booking->transaction_id),
                'userData' => $userData,
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء جلسة دفع الحجز: ' . $e->getMessage());
            return redirect()->back()->with('error', __('messages.payment_session_error'));
        }
    }

    /**
     * معالجة طلب الدفع
     *
     * @param  Request  $request
     * @param  string  $transaction_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, $transaction_id)
    {
        // البحث عن الحجز باستخدام رمز المعاملة
        $booking = Booking::where('transaction_id', $transaction_id)->firstOrFail();

        // التحقق من أن الحجز لم يتم دفعه بالفعل
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking->transaction_id)
                        ->with('info', __('messages.booking_already_paid'));
        }

        try {
            // الحصول على معرف PaymentIntent من الطلب
            $paymentIntentId = $request->input('payment_intent_id');

            // التحقق من حالة الدفع باستخدام Stripe
            $paymentStatus = $this->stripeService->checkPaymentIntentStatus($paymentIntentId);

            // تحديث حالة الحجز إذا تم الدفع بنجاح
            if ($paymentStatus === 'succeeded') {
                $booking->status = 'confirmed';
                $booking->payment_status = 'paid';
                $booking->save();

                return redirect()->route('booking.success', $booking->transaction_id)
                            ->with('success', __('messages.payment_successful'));
            }

            // إذا لم تتم عملية الدفع بنجاح
            return redirect()->route('booking.payment', $booking->transaction_id)
                        ->with('error', __('messages.payment_not_completed'));

        } catch (\Exception $e) {
            Log::error('خطأ في معالجة دفع الحجز: ' . $e->getMessage());
            return redirect()->route('booking.payment', $booking->transaction_id)
                        ->with('error', __('messages.payment_processing_error'));
        }
    }

    /**
     * معالجة نجاح عملية الدفع
     *
     * @param  string  $transaction_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess($transaction_id)
    {
        // البحث عن الحجز باستخدام رمز المعاملة
        $booking = Booking::where('transaction_id', $transaction_id)->firstOrFail();

        // تحديث حالة الحجز إلى مدفوع ومؤكد
        $booking->status = 'confirmed';
        $booking->payment_status = 'paid';
        $booking->save();

        return redirect()->route('booking.success', $booking->transaction_id)
                    ->with('success', __('messages.payment_successful'));
    }

    /**
     * معالجة إلغاء عملية الدفع
     *
     * @param  string  $transaction_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentCancel($transaction_id)
    {
        $booking = Booking::where('transaction_id', $transaction_id)->firstOrFail();
        
        return redirect()->route('booking.index')
                         ->with('error', __('messages.payment_cancelled'));
    }

    /**
     * مسار مباشر لخدمة Stripe Checkout
     * يقوم بإنشاء جلسة دفع مباشرة وإعادة توجيه المستخدم إلى صفحة Stripe
     *
     * @param  string  $transaction_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stripeCheckout($transaction_id)
    {
        // تسجيل بدء عملية الدفع المباشرة
        Log::info('بدء عملية الدفع المباشرة باستخدام Stripe', ['transaction_id' => $transaction_id]);

        try {
            // البحث عن الحجز باستخدام رمز المعاملة
            $booking = Booking::where('transaction_id', $transaction_id)->firstOrFail();

            // التحقق من أن الحجز لم يتم دفعه بالفعل
            if ($booking->payment_status === 'paid') {
                return redirect()->route('booking.success', $booking->transaction_id)
                             ->with('info', __('messages.booking_already_paid'));
            }

            // تجهيز بيانات المستخدم للدفع
            $userData = [];
            if ($booking->user_id) {
                $user = User::find($booking->user_id);
                if ($user) {
                    $userData = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? '',
                    ];
                }
            } elseif ($booking->guest_details) {
                $guestDetails = is_string($booking->guest_details) 
                    ? json_decode($booking->guest_details, true) 
                    : $booking->guest_details;

                $userData = [
                    'name' => $guestDetails['name'] ?? '',
                    'email' => $guestDetails['email'] ?? '',
                    'phone' => $guestDetails['phone'] ?? '',
                ];
            }

            // إنشاء جلسة دفع Stripe مباشرة
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'sar',
                        'product_data' => [
                            'name' => isset($booking->service) && $booking->service ? $booking->service->name : 'حجز خدمة',
                            'description' => isset($booking->specialist) && $booking->specialist ? 'مع المتخصص: ' . $booking->specialist->name : ''
                        ],
                        'unit_amount' => (int)($booking->price * 100), // السعر بالهللات (سنت)
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'transaction_id' => $booking->transaction_id,
                ],
                'customer_email' => $userData['email'] ?? null,
                'client_reference_id' => $booking->transaction_id,
                'mode' => 'payment',
                'success_url' => route('booking.payment.success', $booking->transaction_id),
                'cancel_url' => route('booking.payment.cancel', $booking->transaction_id),
                'locale' => 'ar',
            ]);

            // تسجيل نجاح إنشاء جلسة الدفع
            Log::info('تم إنشاء جلسة Stripe بنجاح', [
                'session_id' => $session->id,
                'checkout_url' => $session->url,
            ]);

            // إعادة توجيه المستخدم مباشرة إلى صفحة دفع Stripe
            return redirect($session->url);
            
        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء جلسة Stripe', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction_id,
            ]);
            
            return redirect()->route('booking.index')
                         ->with('error', __('messages.payment_processing_error'));
        }
    }
}
