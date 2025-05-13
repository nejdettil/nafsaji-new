<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingStripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingPaymentController extends Controller
{
    /**
     * @var BookingStripeService
     */
    protected $stripeService;

    /**
     * إنشاء مثيل جديد للتحكم
     *
     * @param BookingStripeService $stripeService
     */
    public function __construct(BookingStripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * معالجة الدفع الناجح
     *
     * @param Request $request
     * @param string $transactionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleSuccess(Request $request, $transactionId)
    {
        try {
            // البحث عن الحجز باستخدام معرف المعاملة
            $booking = Booking::where('transaction_id', $transactionId)->firstOrFail();
            
            Log::info('تم استقبال طلب النجاح من Stripe', [
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
            ]);
            
            // التحقق من حالة الدفع إذا كان هناك معرف جلسة Stripe
            if (!empty($booking->metadata['stripe_session_id'])) {
                // التحقق من حالة جلسة Stripe
                $sessionResult = $this->stripeService->checkSessionStatus($booking->metadata['stripe_session_id']);
                
                if ($sessionResult['success'] && $sessionResult['status'] === 'paid') {
                    // معالجة الدفع الناجح
                    $result = $this->stripeService->processSuccessfulPayment(
                        $booking, 
                        $booking->metadata['stripe_session_id']
                    );
                    
                    if ($result['success']) {
                        return redirect()->route('booking.payment.success.page', ['transaction_id' => $transactionId])
                            ->with('success', 'تم الدفع بنجاح! تم تأكيد حجزك.');
                    }
                }
            }
            
            // في حالة لم يتم التحقق من الدفع، نعتبر أن الدفع لم يكتمل بعد
            return redirect()->route('booking.payment.success.page', ['transaction_id' => $transactionId])
                ->with('warning', 'تم استلام طلب الدفع، وسيتم التحقق من حالة الدفع قريباً.');
            
        } catch (\Exception $e) {
            Log::error('خطأ في معالجة نجاح الدفع', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('booking.start')
                ->with('error', 'حدث خطأ أثناء معالجة الدفع. يرجى الاتصال بالدعم.');
        }
    }

    /**
     * معالجة إلغاء الدفع
     *
     * @param Request $request
     * @param string $transactionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCancel(Request $request, $transactionId)
    {
        try {
            // البحث عن الحجز باستخدام معرف المعاملة
            $booking = Booking::where('transaction_id', $transactionId)->firstOrFail();
            
            Log::info('تم استقبال طلب إلغاء من Stripe', [
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
            ]);
            
            return redirect()->route('booking.payment.cancel.page', ['transaction_id' => $transactionId])
                ->with('info', 'تم إلغاء عملية الدفع. يمكنك المحاولة مرة أخرى في أي وقت.');
            
        } catch (\Exception $e) {
            Log::error('خطأ في معالجة إلغاء الدفع', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('booking.start')
                ->with('error', 'حدث خطأ أثناء معالجة إلغاء الدفع.');
        }
    }

    /**
     * عرض صفحة نجاح الدفع
     *
     * @param string $transactionId
     * @return \Illuminate\View\View
     */
    public function showSuccessPage($transactionId)
    {
        try {
            $booking = Booking::where('transaction_id', $transactionId)->firstOrFail();
            
            return view('booking.payment-success', compact('booking'));
            
        } catch (\Exception $e) {
            Log::error('خطأ في عرض صفحة نجاح الدفع', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('booking.start')
                ->with('error', 'حدث خطأ أثناء عرض تفاصيل الحجز.');
        }
    }

    /**
     * عرض صفحة إلغاء الدفع
     *
     * @param string $transactionId
     * @return \Illuminate\View\View
     */
    public function showCancelPage($transactionId)
    {
        try {
            $booking = Booking::where('transaction_id', $transactionId)->firstOrFail();
            
            return view('booking.payment-cancel', compact('booking'));
            
        } catch (\Exception $e) {
            Log::error('خطأ في عرض صفحة إلغاء الدفع', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('booking.start')
                ->with('error', 'حدث خطأ أثناء عرض تفاصيل الحجز.');
        }
    }

    /**
     * إعادة محاولة الدفع لحجز موجود
     *
     * @param string $transactionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retryPayment($transactionId)
    {
        try {
            $booking = Booking::where('transaction_id', $transactionId)
                ->where('payment_status', 'unpaid')
                ->firstOrFail();
            
            // إنشاء جلسة دفع Stripe جديدة
            $checkoutSession = $this->stripeService->createCheckoutSession($booking);
            
            if ($checkoutSession['success']) {
                // تحديث معرف جلسة Stripe في البيانات الوصفية للحجز
                $booking->metadata = array_merge($booking->metadata ?? [], [
                    'stripe_session_id' => $checkoutSession['session_id']
                ]);
                $booking->save();
                
                return redirect($checkoutSession['checkout_url']);
            } else {
                throw new \Exception('فشل في إنشاء جلسة الدفع');
            }
            
        } catch (\Exception $e) {
            Log::error('خطأ في إعادة محاولة الدفع', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء معالجة طلب الدفع. يرجى المحاولة مرة أخرى لاحقاً.');
        }
    }
}
