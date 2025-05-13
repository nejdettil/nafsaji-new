<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;

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
     * @param string $reference رمز الحجز المرجعي
     * @return \Illuminate\View\View
     */
    public function showPayment($reference)
    {
        // البحث عن الحجز بواسطة الرمز المرجعي
        $booking = Booking::where('reference', $reference)->firstOrFail();
        
        // التحقق من حالة الدفع
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking->reference)
                ->with('info', __('messages.booking_already_paid'));
        }
        
        // التحقق من أن الحجز لا يزال قابلاً للدفع
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->route('booking.specialists')
                ->with('error', __('messages.booking_cannot_be_paid'));
        }
        
        return view('booking.payment', [
            'booking' => $booking->load(['specialist', 'service']),
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * معالجة طلب الدفع
     *
     * @param Request $request
     * @param string $reference رمز الحجز المرجعي
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request, $reference)
    {
        try {
            // البحث عن الحجز بواسطة الرمز المرجعي
            $booking = Booking::where('reference', $reference)->firstOrFail();
            
            // التحقق من حالة الدفع
            if ($booking->payment_status === 'paid') {
                return response()->json([
                    'success' => false, 
                    'message' => __('messages.booking_already_paid'),
                    'redirect' => route('booking.success', $booking->reference),
                ]);
            }
            
            // تكوين Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $stripeClient = new StripeClient(config('services.stripe.secret'));
            
            // إنشاء بيانات وصفية للدفع
            $metadata = [
                'booking_reference' => $booking->reference,
                'service_name' => $booking->service->name,
                'specialist_name' => $booking->specialist->name,
                'booking_date' => $booking->booking_date->format('Y-m-d'),
                'booking_time' => $booking->booking_time,
            ];
            
            // إضافة معرف المستخدم إذا كان مسجل الدخول
            if (Auth::check()) {
                $metadata['user_id'] = Auth::id();
                $metadata['user_email'] = Auth::user()->email;
            } else if ($booking->guest_details) {
                // إضافة بيانات الزائر إذا كانت متوفرة
                $guestDetails = json_decode($booking->guest_details, true);
                $metadata['guest_email'] = $guestDetails['email'] ?? '';
                $metadata['guest_name'] = $guestDetails['name'] ?? '';
                $metadata['guest_phone'] = $guestDetails['phone'] ?? '';
            }
            
            // إنشاء نية الدفع في Stripe
            $amount = (int) ($booking->price * 100); // تحويل إلى أصغر وحدة (هللة)
            $paymentIntent = $stripeClient->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'SAR', // استخدام الريال السعودي كعملة افتراضية
                'metadata' => $metadata,
                'payment_method_types' => ['card'],
                'description' => __('messages.payment_for_booking', [
                    'service' => $booking->service->name,
                    'specialist' => $booking->specialist->name,
                ]),
            ]);
            
            // إنشاء سجل دفع في قاعدة البيانات
            $payment = new Payment([
                'amount' => $booking->price,
                'currency' => 'SAR',
                'status' => Payment::STATUS_PENDING,
                'stripe_payment_id' => $paymentIntent->id,
                'description' => __('messages.payment_for_booking', [
                    'service' => $booking->service->name,
                    'specialist' => $booking->specialist->name,
                ]),
                'metadata' => [
                    'booking_reference' => $booking->reference,
                    'return_url' => route('booking.payment.success', $booking->reference),
                    'cancel_url' => route('booking.payment.cancel', $booking->reference),
                ],
            ]);
            
            // إضافة معرف المستخدم إذا كان مسجل الدخول
            if (Auth::check()) {
                $payment->user_id = Auth::id();
            }
            
            // ربط الدفع بالحجز
            $payment->payable_type = get_class($booking);
            $payment->payable_id = $booking->id;
            
            $payment->save();
            
            // إعادة client secret لاستخدامه في الواجهة الأمامية
            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ في معالجة الدفع: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => __('messages.payment_processing_error'),
            ], 500);
        }
    }

    /**
     * معالجة نجاح الدفع
     *
     * @param Request $request
     * @param string $reference رمز الحجز المرجعي
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess(Request $request, $reference)
    {
        // البحث عن الحجز بواسطة الرمز المرجعي
        $booking = Booking::where('reference', $reference)->firstOrFail();
        $paymentIntentId = $request->input('payment_intent');
        
        if (!$paymentIntentId) {
            return redirect()->route('booking.payment', $booking->reference)
                ->with('error', __('messages.payment_not_completed'));
        }
        
        try {
            // التحقق من حالة الدفع في Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $stripeClient = new StripeClient(config('services.stripe.secret'));
            $paymentIntent = $stripeClient->paymentIntents->retrieve($paymentIntentId);
            
            // التحقق من أن الدفع تم بنجاح
            if ($paymentIntent->status === 'succeeded') {
                // تحديث حالة الحجز والدفع
                $booking->status = 'confirmed';
                $booking->payment_status = 'paid';
                $booking->payment_method = 'stripe';
                $booking->transaction_id = $paymentIntentId;
                $booking->save();
                
                // تحديث سجل الدفع في قاعدة البيانات
                $payment = Payment::where('stripe_payment_id', $paymentIntentId)->first();
                if ($payment) {
                    $payment->status = Payment::STATUS_COMPLETED;
                    $payment->save();
                }
                
                // توجيه المستخدم إلى صفحة النجاح
                return redirect()->route('booking.success', $booking->reference)
                    ->with('success', __('messages.payment_successful'));
            } else {
                // إذا لم يتم الدفع بنجاح، إعادة توجيه المستخدم إلى صفحة الدفع
                return redirect()->route('booking.payment', $booking->reference)
                    ->with('error', __('messages.payment_not_completed'));
            }
        } catch (ApiErrorException $e) {
            Log::error('خطأ في Stripe: ' . $e->getMessage());
            
            return redirect()->route('booking.payment', $booking->reference)
                ->with('error', __('messages.payment_verification_error'));
        }
    }

    /**
     * معالجة إلغاء الدفع
     *
     * @param Request $request
     * @param string $reference رمز الحجز المرجعي
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentCancel(Request $request, $reference)
    {
        // البحث عن الحجز بواسطة الرمز المرجعي
        $booking = Booking::where('reference', $reference)->firstOrFail();
        $paymentIntentId = $request->input('payment_intent');
        
        if ($paymentIntentId) {
            // تحديث سجل الدفع في قاعدة البيانات
            $payment = Payment::where('stripe_payment_id', $paymentIntentId)->first();
            if ($payment) {
                $payment->status = Payment::STATUS_CANCELLED;
                $payment->save();
            }
        }
        
        // إعادة توجيه المستخدم إلى صفحة الدفع
        return redirect()->route('booking.payment', $booking->reference)
            ->with('warning', __('messages.payment_cancelled'));
    }
}
