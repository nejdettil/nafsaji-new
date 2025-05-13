<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()->with(['specialist', 'service'])->latest()->paginate(10);
        
        return view('booking.index', compact('bookings'));
    }

    /**
     * عرض صفحة اختيار المتخصصين (الخطوة الأولى في عملية الحجز)
     *
     * @return \Illuminate\View\View
     */
    public function showSpecialists()
    {
        $specialists = Specialist::with('services')->where('is_active', true)->get();
        
        return view('booking.specialists', [
            'specialists' => $specialists,
            'step' => 1,
            'steps_count' => 4
        ]);
    }
    
    /**
     * عرض صفحة اختيار الخدمات للمتخصص المحدد (الخطوة الثانية)
     *
     * @param int $specialistId
     * @return \Illuminate\View\View
     */
    public function showServices($specialistId)
    {
        $specialist = Specialist::with('services')->findOrFail($specialistId);
        $services = $specialist->services;
        
        return view('booking.services', [
            'specialist' => $specialist,
            'services' => $services,
            'step' => 2,
            'steps_count' => 4
        ]);
    }
    
    /**
     * عرض صفحة اختيار التاريخ والوقت (الخطوة الثالثة)
     *
     * @param int $specialistId
     * @param int $serviceId
     * @return \Illuminate\View\View
     */
    public function showSchedule($specialistId, $serviceId)
    {
        $specialist = Specialist::findOrFail($specialistId);
        $service = Service::findOrFail($serviceId);
        
        return view('booking.schedule', [
            'specialist' => $specialist,
            'service' => $service,
            'step' => 3,
            'steps_count' => 4
        ]);
    }
    
    /**
     * عرض صفحة تأكيد الحجز (الخطوة الرابعة)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showConfirmation(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
        ]);
        
        $specialist = Specialist::findOrFail($request->specialist_id);
        $service = Service::findOrFail($request->service_id);
        $booking_date = \Carbon\Carbon::parse($request->booking_date);
        $booking_time = $request->booking_time;
        
        return view('booking.confirm', [
            'specialist' => $specialist,
            'service' => $service,
            'booking_date' => $booking_date,
            'booking_time' => $booking_time,
            'step' => 4,
            'steps_count' => 4
        ]);
    }
    
    /**
     * عرض نموذج صفحة تأكيد الحجز عند الوصول بطريقة GET
     * إذا تم الوصول مباشرة دون معلومات كافية، سيتم إعادة توجيه المستخدم إلى صفحة الحجز الرئيسية
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showConfirmationForm()
    {
        // إذا كان المستخدم يحاول الوصول إلى صفحة التأكيد مباشرة دون متابعة خطوات الحجز
        // قم بإعادة توجيهه إلى صفحة الحجز الرئيسية مع رسالة توضيحية
        return redirect()->route('booking.specialists')
                         ->with('info', __('messages.please_follow_booking_steps'));
    }

    public function create(Request $request)
    {
        $specialists = Specialist::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        
        // If a specialist ID was provided in the query parameters
        $selectedSpecialist = null;
        if ($request->has('specialist_id')) {
            $selectedSpecialist = Specialist::findOrFail($request->specialist_id);
        }
        
        // If a service ID was provided in the query parameters
        $selectedService = null;
        if ($request->has('service_id')) {
            $selectedService = Service::findOrFail($request->service_id);
        }
        
        return view('booking.create', compact('specialists', 'services', 'selectedSpecialist', 'selectedService'));
    }

    /**
     * معالجة نموذج الحجز
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBooking(Request $request)
    {
        // تسجيل بدء معالجة الحجز
        Log::info('بدء معالجة نموذج الحجز', $request->all());
        
        // التحقق من البيانات
        $validatedData = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255',
            'phone' => 'required_without:user_id|string|max:20',
            'notes' => 'nullable|string',
            'terms' => 'required|accepted',
            'payment_method' => 'required|in:stripe',
        ]);
        
        // إنشاء رمز مرجعي للحجز
        $reference = 'BK-' . strtoupper(uniqid());
        
        // إنشاء كائن الحجز
        $booking = new Booking();
        $booking->transaction_id = $reference;
        $booking->specialist_id = $request->specialist_id;
        $booking->service_id = $request->service_id;
        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $request->booking_time;
        $booking->price = $request->price;
        $booking->notes = $request->notes;
        $booking->status = 'pending';
        $booking->payment_status = 'unpaid';
        $booking->payment_method = 'online';
        
        // تجهيز معلومات المستخدم
        $userData = [];
        
        // إذا كان المستخدم مسجل دخول
        if (Auth::check()) {
            $booking->user_id = Auth::id();
            $user = Auth::user();
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];
        } elseif ($request->create_account && $request->filled('password')) {
            // إنشاء حساب جديد للزائر إذا طلب ذلك
            $validatedData = $request->validate([
                'password' => 'required|min:8|confirmed',
            ]);
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
            ]);
            
            $booking->user_id = $user->id;
            
            // تسجيل دخول المستخدم الجديد
            Auth::login($user);
            
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];
        } else {
            // تخزين معلومات الزائر
            $guestDetails = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            
            $booking->guest_details = json_encode($guestDetails);
            $userData = $guestDetails;
        }
        
        // حفظ الحجز
        $booking->save();
        
        // حفظ معرف المعاملة في الجلسة لاستخدامه في صفحة الدفع المباشر
        session(['last_booking_transaction_id' => $booking->transaction_id]);
        
        Log::info('تم حفظ الحجز بنجاح', ['booking_id' => $booking->id, 'transaction_id' => $booking->transaction_id]);
        
        // إرسال إشعار للمسؤول بالحجز الجديد
        try {
            Notification::route('mail', config('app.admin_email'))
                    ->notify(new NewBookingNotification($booking));
            Log::info('تم إرسال إشعار الحجز بنجاح');
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال إشعار الحجز', ['error' => $e->getMessage()]);
        }
        
        // الحصول على خدمة Stripe
        $stripeService = app(\App\Services\StripeService::class);
        
        try {
            // إنشاء جلسة دفع مباشرة
            Log::info('جاري إنشاء جلسة دفع Stripe مباشرة', [
                'booking_id' => $booking->id,
                'transaction_id' => $booking->transaction_id
            ]);
            
            $paymentSession = $stripeService->createBookingPaymentSession($booking, $userData);
            
            // توجيه المستخدم إلى صفحة دفع Stripe مباشرة
            $returnUrl = route('booking.payment.success', $booking->transaction_id);
            $cancelUrl = route('booking.payment.cancel', $booking->transaction_id);
            
            // تحديث وعرض صفحة الدفع
            return view('booking.payment', [
                'booking' => $booking->load(['specialist', 'service']),
                'clientSecret' => $paymentSession->client_secret,
                'stripeKey' => config('services.stripe.key'),
                'paymentIntentId' => $paymentSession->id,
                'amount' => $booking->price,
                'currency' => 'sar',
                'description' => 'حجز خدمة: ' . ($booking->service->name ?? '') . ' مع المتخصص: ' . ($booking->specialist->name ?? ''),
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
                'userData' => $userData,
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء جلسة دفع Stripe', ['error' => $e->getMessage()]);
            
            return redirect()->back()->with('error', __('messages.payment_session_error'));
        }
    }
    
    /**
     * عرض صفحة نجاح الحجز
     *
     * @param string $reference رمز الحجز المرجعي
     * @return \Illuminate\View\View
     */
    public function showSuccess($reference)
    {
        $booking = Booking::where('transaction_id', $reference)->firstOrFail();
        
        return view('booking.success', [
            'booking' => $booking->load(['specialist', 'service']),
        ]);
    }
    
    public function store(Request $request)
    {
        // قواعد التحقق المختلفة للمستخدمين المسجلين والزوار
        $rules = [
            'specialist_id' => ['required', 'exists:specialists,id'],
            'service_id' => ['required', 'exists:services,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
        
        // إضافة قواعد التحقق الخاصة بالمستخدمين الغير مسجلين (الزوار)
        if (!Auth::check()) {
            $rules = array_merge($rules, [
                'guest_name' => ['required', 'string', 'max:255'],
                'guest_email' => ['required', 'email', 'max:255'],
                'guest_phone' => ['required', 'string', 'max:20'],
            ]);
        }
        
        $request->validate($rules);
        
        // تجهيز الحجز الجديد
        $bookingData = [
            'specialist_id' => $request->specialist_id,
            'service_id' => $request->service_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ];
        
        // إذا كان المستخدم مسجل، قم بربط الحجز بحسابه
        if (Auth::check()) {
            $bookingData['user_id'] = Auth::id();
        } else {
            // للزوار، قم بتخزين بياناتهم في حقل guest_details
            $bookingData['guest_details'] = json_encode([
                'name' => $request->guest_name,
                'email' => $request->guest_email,
                'phone' => $request->guest_phone,
            ]);
        }
        
        $booking = new Booking($bookingData);
        $booking->save();
        
        // تسجيل المعلومات في ملف السجل
        \Illuminate\Support\Facades\Log::info('تم إنشاء حجز جديد', [
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id ?? null,
            'specialist_id' => $booking->specialist_id,
            'service_id' => $booking->service_id,
            'booking_date' => $booking->booking_date,
            'booking_time' => $booking->booking_time,
            'is_guest' => !Auth::check(),
        ]);
        
        // التعامل مع الإشعارات إذا كان المستخدم مسجل
        if (Auth::check()) {
            // مستقبلًا يمكن تفعيل الإشعارات للمسؤولين والمتخصصين
            // $admins = User::where('is_admin', true)->get();
            // Notification::send($admins, new NewBookingNotification($booking));
            // $specialist = $booking->specialist;
            // Notification::send($specialist, new NewBookingNotification($booking));
            
            // توجيه المستخدم المسجل إلى صفحة حجوزاته
            return redirect()->route('booking.my_bookings')
                ->with('success', __('messages.booking_created'));
        } else {
            // الحصول على سعر الخدمة
            $servicePrice = Service::find($request->service_id)->price ?? 0;
            
            // تحديث سعر الحجز
            $booking->price = $servicePrice;
            $booking->save();
            
            // توجيه الزائر إلى صفحة تأكيد الحجز مع التفاصيل
            // قم بتخزين بيانات الحجز في الجلسة لعرضها في صفحة التأكيد
            session(['guest_booking' => [
                'id' => $booking->id,
                'specialist_name' => $booking->specialist->name,
                'service_name' => $booking->service->name,
                'booking_date' => $booking->booking_date,
                'booking_time' => $booking->booking_time,
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'price' => $servicePrice, // إضافة سعر الخدمة
                'reference_code' => strtoupper(substr(md5($booking->id . time()), 0, 8)),
            ]]);
            
            return redirect()->route('booking.confirmation')
                ->with('success', __('messages.booking_created'));
        }
    }

    /**
     * عرض صفحة تأكيد الحجز للزوار
     * 
     * @return \Illuminate\View\View
     */
    public function guestConfirmation()
    {
        // التحقق من وجود بيانات الحجز في الجلسة
        if (!session('guest_booking')) {
            return redirect()->route('booking.create');
        }
        
        return view('booking.confirmation');
    }
    
    /**
     * معالجة نجاح عملية الدفع للحجز
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $paymentIntentId = $request->input('payment_intent');
        
        if (!$bookingId) {
            return redirect()->route('home')->with('error', __('messages.booking_not_found'));
        }
        
        // البحث عن الحجز في قاعدة البيانات
        $booking = Booking::find($bookingId);
        
        if (!$booking) {
            return redirect()->route('home')->with('error', __('messages.booking_not_found'));
        }
        
        // تحديث حالة الحجز والدفع
        $booking->status = 'confirmed';
        $booking->payment_status = 'paid';
        $booking->payment_method = 'stripe';
        $booking->transaction_id = $paymentIntentId;
        $booking->save();
        
        // التحقق من وجود مستخدم مسجل أو زائر
        if ($booking->user_id) {
            return redirect()->route('booking.my_bookings')->with('success', __('messages.booking_payment_successful'));
        } else {
            // للزائر، توجيه إلى الصفحة الرئيسية
            return redirect()->route('home')->with('success', __('messages.booking_payment_successful'));
        }
    }
    
    /**
     * معالجة إلغاء عملية الدفع للحجز
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentCancel(Request $request)
    {
        $bookingId = $request->input('booking_id');
        
        if (!$bookingId) {
            return redirect()->route('home')->with('warning', __('messages.payment_cancelled'));
        }
        
        // البحث عن الحجز في قاعدة البيانات
        $booking = Booking::find($bookingId);
        
        if ($booking) {
            // تحديث حالة الدفع
            $booking->payment_status = 'cancelled';
            $booking->save();
        }
        
        return redirect()->route('home')->with('warning', __('messages.payment_cancelled'));
    }

    /**
     * Display the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
                    ->with(['specialist', 'service'])
                    ->findOrFail($id);
        
        return view('booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        $specialists = Specialist::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        
        return view('booking.edit', compact('booking', 'specialists', 'services'));
    }

    /**
     * Update the specified booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow editing if the booking is still pending
        if ($booking->status !== 'pending') {
            return redirect()->route('booking.index')
                ->with('error', __('messages.booking_cannot_be_modified'));
        }
        
        $request->validate([
            'specialist_id' => ['required', 'exists:specialists,id'],
            'service_id' => ['required', 'exists:services,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        $booking->update([
            'specialist_id' => $request->specialist_id,
            'service_id' => $request->service_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('booking.index')
            ->with('success', __('messages.booking_updated'));
    }

    /**
     * Remove the specified booking from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        
        // Update status to cancelled instead of actual deletion
        $booking->update(['status' => 'cancelled']);
        
        return redirect()->route('booking.index')
            ->with('success', __('messages.booking_cancelled'));
    }
}
