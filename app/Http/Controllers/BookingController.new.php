<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * عرض صفحة اختيار المختصين (الخطوة الأولى)
     *
     * @return \Illuminate\View\View
     */
    public function showSpecialists()
    {
        $specialists = Specialist::where('is_active', true)
            ->with(['media', 'services'])
            ->get();
            
        return view('booking.specialists', [
            'specialists' => $specialists,
            'step' => 1,
            'steps_count' => 4,
        ]);
    }
    
    /**
     * عرض صفحة اختيار الخدمات للمختص المحدد (الخطوة الثانية)
     *
     * @param Specialist $specialist
     * @return \Illuminate\View\View
     */
    public function showServices(Specialist $specialist)
    {
        $services = $specialist->services()
            ->where('is_active', true)
            ->get();
            
        return view('booking.services', [
            'specialist' => $specialist,
            'services' => $services,
            'step' => 2,
            'steps_count' => 4,
        ]);
    }
    
    /**
     * عرض صفحة اختيار الموعد (الخطوة الثالثة)
     *
     * @param Specialist $specialist
     * @param Service $service
     * @return \Illuminate\View\View
     */
    public function showSchedule(Specialist $specialist, Service $service)
    {
        // التحقق من أن الخدمة تنتمي للمختص
        if ($service->specialist_id != $specialist->id) {
            return redirect()->route('booking.specialists')
                ->with('error', __('messages.service_specialist_mismatch'));
        }
        
        // الحصول على المواعيد المتاحة للأسبوعين القادمين
        $availableTimes = $this->generateAvailableTimes($specialist);
            
        return view('booking.schedule', [
            'specialist' => $specialist,
            'service' => $service,
            'availableTimes' => $availableTimes,
            'step' => 3,
            'steps_count' => 4,
        ]);
    }
    
    /**
     * عرض صفحة تأكيد الحجز (الخطوة الرابعة)
     *
     * @param Specialist $specialist
     * @param Service $service
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showConfirmation(Specialist $specialist, Service $service, Request $request)
    {
        // التحقق من أن الخدمة تنتمي للمختص
        if ($service->specialist_id != $specialist->id) {
            return redirect()->route('booking.specialists')
                ->with('error', __('messages.service_specialist_mismatch'));
        }
        
        // التحقق من وجود التاريخ والوقت في الجلسة
        if (!$request->has('date') || !$request->has('time')) {
            return redirect()->route('booking.schedule', [$specialist->id, $service->id])
                ->with('error', __('messages.select_date_time_first'));
        }
        
        $date = $request->input('date');
        $time = $request->input('time');
        
        return view('booking.confirm', [
            'specialist' => $specialist,
            'service' => $service,
            'date' => $date,
            'time' => $time,
            'step' => 4,
            'steps_count' => 4,
            'is_authenticated' => Auth::check(),
        ]);
    }
    
    /**
     * معالجة طلب الحجز
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBooking(Request $request)
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
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // الحصول على سعر الخدمة
            $service = Service::findOrFail($request->service_id);
            $specialist = Specialist::findOrFail($request->specialist_id);
            
            // تجهيز الحجز الجديد
            $bookingData = [
                'specialist_id' => $request->specialist_id,
                'service_id' => $request->service_id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'notes' => $request->notes,
                'price' => $service->price,
                'duration' => $service->duration,
                'status' => 'pending',
                'payment_status' => 'pending',
                'reference' => $this->generateUniqueReference(),
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
            
            $booking = Booking::create($bookingData);
            
            // تخزين معلومات الحجز في الجلسة لعرضها في صفحة النجاح
            session(['booking_reference' => $booking->reference]);
            
            // توجيه المستخدم إلى صفحة الدفع
            return redirect()->route('booking.payment', $booking->reference);
            
        } catch (\Exception $e) {
            // تسجيل الخطأ وإعادة المستخدم مع رسالة خطأ
            \Illuminate\Support\Facades\Log::error('حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.booking_creation_failed'))
                ->withInput();
        }
    }
    
    /**
     * عرض صفحة نجاح الحجز
     *
     * @param string $reference
     * @return \Illuminate\View\View
     */
    public function showSuccess($reference)
    {
        $booking = Booking::where('reference', $reference)->firstOrFail();
        
        return view('booking.success', [
            'booking' => $booking,
        ]);
    }
    
    /**
     * عرض لوحة تحكم المستخدم للحجوزات
     *
     * @return \Illuminate\View\View
     */
    public function userDashboard()
    {
        $bookings = Auth::user()->bookings()
            ->with(['specialist', 'service'])
            ->latest()
            ->paginate(10);
            
        return view('booking.dashboard', [
            'bookings' => $bookings,
        ]);
    }
    
    /**
     * عرض تفاصيل الحجز
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function viewBooking(Booking $booking)
    {
        // التحقق من أن الحجز ينتمي للمستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا الحجز');
        }
        
        return view('booking.view', [
            'booking' => $booking->load(['specialist', 'service']),
        ]);
    }
    
    /**
     * إلغاء الحجز
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelBooking(Booking $booking)
    {
        // التحقق من أن الحجز ينتمي للمستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا الحجز');
        }
        
        // التحقق من أن الحجز لا يزال قابلاً للإلغاء
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', __('messages.booking_cannot_be_cancelled'));
        }
        
        // تغيير حالة الحجز إلى ملغي
        $booking->status = 'cancelled';
        $booking->save();
        
        return redirect()->route('bookings.dashboard')
            ->with('success', __('messages.booking_cancelled_successfully'));
    }
    
    /**
     * عرض صفحة إعادة جدولة الحجز
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function showReschedule(Booking $booking)
    {
        // التحقق من أن الحجز ينتمي للمستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا الحجز');
        }
        
        // التحقق من أن الحجز لا يزال قابلاً لإعادة الجدولة
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', __('messages.booking_cannot_be_rescheduled'));
        }
        
        // الحصول على المواعيد المتاحة
        $availableTimes = $this->generateAvailableTimes($booking->specialist);
        
        return view('booking.reschedule', [
            'booking' => $booking->load(['specialist', 'service']),
            'availableTimes' => $availableTimes,
        ]);
    }
    
    /**
     * معالجة طلب إعادة جدولة الحجز
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processReschedule(Request $request, Booking $booking)
    {
        // التحقق من أن الحجز ينتمي للمستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا الحجز');
        }
        
        // التحقق من أن الحجز لا يزال قابلاً لإعادة الجدولة
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', __('messages.booking_cannot_be_rescheduled'));
        }
        
        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // تحديث بيانات الحجز
        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $request->booking_time;
        $booking->save();
        
        return redirect()->route('bookings.view', $booking->id)
            ->with('success', __('messages.booking_rescheduled_successfully'));
    }
    
    /**
     * توليد المواعيد المتاحة للمختص
     *
     * @param Specialist $specialist
     * @return array
     */
    private function generateAvailableTimes(Specialist $specialist)
    {
        // في هذه الدالة، سيتم توليد المواعيد المتاحة للمختص بناءً على جدوله وحجوزاته الحالية
        // قد تحتاج إلى تكييف هذه الدالة وفقًا لكيفية تخزين جدول المختص والحجوزات في قاعدة البيانات الخاصة بك
        
        $availableTimes = [];
        $startDate = now()->startOfDay();
        $endDate = now()->addDays(14)->endOfDay();
        
        // توليد مواعيد متاحة وهمية للعرض التوضيحي
        // في التطبيق الحقيقي، ستحتاج إلى استخدام جدول المختص وحجوزاته الحالية
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            // تخطي يوم الجمعة ويوم السبت (يفترض أن المختص لا يعمل في هذه الأيام)
            if ($date->dayOfWeek == 5 || $date->dayOfWeek == 6) {
                continue;
            }
            
            $dateStr = $date->format('Y-m-d');
            $availableTimes[$dateStr] = [];
            
            // إضافة مواعيد من الساعة 9 صباحًا إلى 5 مساءً، كل ساعة
            for ($hour = 9; $hour < 17; $hour++) {
                $availableTimes[$dateStr][] = sprintf('%02d:00', $hour);
            }
        }
        
        return $availableTimes;
    }
    
    /**
     * توليد رمز مرجعي فريد للحجز
     *
     * @return string
     */
    private function generateUniqueReference()
    {
        $reference = strtoupper(Str::random(8));
        
        // التأكد من عدم وجود حجز آخر بنفس الرمز المرجعي
        while (Booking::where('reference', $reference)->exists()) {
            $reference = strtoupper(Str::random(8));
        }
        
        return $reference;
    }
}
