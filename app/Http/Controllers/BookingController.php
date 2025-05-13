<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Services\BookingStripeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
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
     * عرض صفحة البدء للحجز مع اختيار طريقة الحجز
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('booking.start');
    }
    
    /**
     * عرض صفحة اختيار المتخصص (الطريقة التقليدية)
     *
     * @return \Illuminate\View\View
     */
    public function showSpecialists()
    {
        $specialists = Specialist::with(['services', 'user'])->active()->get();
        
        return view('booking.specialists', compact('specialists'));
    }
    
    /**
     * عرض صفحة جميع الخدمات المتاحة (طريقة اختيار الخدمة أولاً)
     *
     * @return \Illuminate\View\View
     */
    public function showAllServices()
    {
        $services = Service::with('specialists')
            ->whereHas('specialists', function($query) {
                $query->where('is_active', true);
            })
            ->get();
        
        return view('booking.allservices', compact('services'));
    }
    
    /**
     * عرض المتخصصين المتاحين لخدمة محددة
     *
     * @param int $serviceId
     * @return \Illuminate\View\View
     */
    public function showServiceSpecialists($serviceId)
    {
        $service = Service::with(['specialists' => function($query) {
            $query->where('is_active', true);
        }])->findOrFail($serviceId);
        
        $specialists = $service->specialists;
        
        if ($specialists->isEmpty()) {
            return redirect()->route('booking.allservices')
                ->with('error', 'لا يوجد متخصصين متاحين لهذه الخدمة حالياً');
        }
        
        return view('booking.service_specialists', compact('service', 'specialists'));
    }

    /**
     * عرض خدمات المتخصص المختار
     *
     * @param int $specialistId
     * @return \Illuminate\View\View
     */
    public function showServices($specialistId)
    {
        $specialist = Specialist::with('services')->findOrFail($specialistId);
        
        // التحقق من وجود خدمات نشطة
        if ($specialist->services->isEmpty()) {
            return redirect()->route('booking.start')
                ->with('error', 'لا توجد خدمات متاحة لهذا المتخصص حالياً');
        }
        
        return view('booking.services', compact('specialist'));
    }

    /**
     * عرض مواعيد متاحة لخدمة معينة (المسار التقليدي: متخصص ثم خدمة)
     *
     * @param int $specialistId
     * @param int $serviceId
     * @return \Illuminate\View\View
     */
    public function showTimeSlots($specialistId, $serviceId)
    {
        $specialist = Specialist::findOrFail($specialistId);
        $service = Service::findOrFail($serviceId);
        
        // احصل على الأيام المتاحة للأسبوعين القادمين
        $availableDays = $this->getAvailableDays($specialist);
        
        return view('booking.timeslots', compact('specialist', 'service', 'availableDays'));
    }
    
    /**
     * عرض مواعيد متاحة لمتخصص وخدمة معينة (من مسار اختيار الخدمة أولاً)
     *
     * @param int $serviceId
     * @param int $specialistId
     * @return \Illuminate\View\View
     */
    public function showTimeSlotsFromService($serviceId, $specialistId)
    {
        $specialist = Specialist::findOrFail($specialistId);
        $service = Service::findOrFail($serviceId);
        
        // التحقق من أن المتخصص يقدم هذه الخدمة
        $hasService = $specialist->services()->where('services.id', $service->id)->exists();
        
        if (!$hasService) {
            return redirect()->route('booking.service.specialists', $service->id)
                ->with('error', 'هذا المتخصص لا يقدم الخدمة المحددة');
        }
        
        // احصل على الأيام المتاحة للأسبوعين القادمين
        $availableDays = $this->getAvailableDays($specialist);
        
        // نستخدم نفس قالب عرض المواعيد ولكن مع مسار عودة مختلف
        return view('booking.timeslots', [
            'specialist' => $specialist,
            'service' => $service,
            'availableDays' => $availableDays,
            'fromServicePath' => true
        ]);
    }

    /**
     * استرجاع الفترات الزمنية المتاحة ليوم معين
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeSlotsForDay(Request $request)
    {
        try {
            // التحقق من صحة البيانات المدخلة
            $validated = $request->validate([
                'specialist_id' => 'required|exists:specialists,id',
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date',
            ]);
            
            $specialistId = $validated['specialist_id'];
            $serviceId = $validated['service_id'];
            $date = $validated['date'];
            
            $specialist = Specialist::find($specialistId);
            
            if (!$specialist) {
                return response()->json([
                    'success' => false,
                    'message' => 'المتخصص غير موجود',
                    'timeSlots' => [],
                ], 404);
            }
            
            // استرجاع الفترات الزمنية المتاحة
            $timeSlots = $this->calculateAvailableTimeSlots($specialist, $date);
            
            return response()->json([
                'success' => true,
                'timeSlots' => $timeSlots,
            ]);
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('Error in getTimeSlotsForDay', [
                'error' => $e->getMessage(),
                'specialist_id' => $request->input('specialist_id'),
                'service_id' => $request->input('service_id'),
                'date' => $request->input('date'),
            ]);
            
            // إرجاع رد JSON بالخطأ
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب المواعيد المتاحة',
                'timeSlots' => [],
            ], 500);
        }
    }

    /**
     * عرض نموذج تأكيد الحجز
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showConfirmation(Request $request)
    {
        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ]);
        
        $specialist = Specialist::findOrFail($validated['specialist_id']);
        $service = Service::findOrFail($validated['service_id']);
        $date = $validated['date'];
        $time = $validated['time'];
        
        // التحقق من أن الوقت لا يزال متاحاً
        $isAvailable = $this->checkTimeSlotAvailability($specialist, $date, $time);
        
        if (!$isAvailable) {
            return redirect()->back()
                ->with('error', 'عذراً، هذا الموعد لم يعد متاحاً، يرجى اختيار موعد آخر');
        }
        
        // بيانات المستخدم إذا كان مسجلاً
        $user = Auth::user();
        
        return view('booking.confirm', compact('specialist', 'service', 'date', 'time', 'user'));
    }

    /**
     * معالجة الحجز وإنشاء سجل الحجز
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBooking(Request $request)
    {
        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'create_account' => 'nullable|boolean',
            'password' => 'nullable|required_if:create_account,1|min:8',
        ]);
        
        // تأكيد أن الوقت لا يزال متاحاً
        $specialist = Specialist::findOrFail($validated['specialist_id']);
        $isAvailable = $this->checkTimeSlotAvailability(
            $specialist, 
            $validated['booking_date'], 
            $validated['booking_time']
        );
        
        if (!$isAvailable) {
            return redirect()->route('booking.timeslots', [
                    'specialist' => $validated['specialist_id'],
                    'service' => $validated['service_id'],
                ])
                ->with('error', 'عذراً، هذا الموعد لم يعد متاحاً، يرجى اختيار موعد آخر');
        }
        
        // الحصول على معلومات الخدمة
        $service = Service::findOrFail($validated['service_id']);
        
        try {
            // تحديد ما إذا كان حجز ضيف أو مستخدم مسجل
            $userId = null;
            if (Auth::check()) {
                $userId = Auth::id();
            }
            
            // إنشاء سجل الحجز
            $booking = new Booking();
            $booking->user_id = $userId;
            $booking->specialist_id = $validated['specialist_id'];
            $booking->service_id = $validated['service_id'];
            $booking->booking_date = $validated['booking_date'];
            $booking->booking_time = $validated['booking_time'];
            $booking->duration = $service->duration ?? 60;
            $booking->price = $service->price;
            $booking->status = 'pending';
            $booking->payment_status = 'unpaid';
            $booking->payment_method = 'online';
            $booking->transaction_id = 'TXN-' . Str::random(16);
            $booking->notes = $validated['notes'];
            
            // إضافة معلومات الضيف إذا لم يكن مستخدماً مسجلاً
            if (!$userId) {
                $booking->guest_details = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ];
            }
            
            // حفظ الحجز
            $booking->save();
            
            // حفظ معرف المعاملة في الجلسة
            session(['last_booking_transaction_id' => $booking->transaction_id]);
            
            Log::info('تم إنشاء حجز جديد', [
                'booking_id' => $booking->id,
                'transaction_id' => $booking->transaction_id,
            ]);
            
            // توجيه المستخدم إلى صفحة الدفع
            return $this->redirectToPayment($booking);
            
        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء الحجز', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الحجز. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * توجيه المستخدم إلى صفحة الدفع
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToPayment(Booking $booking)
    {
        try {
            // إنشاء جلسة دفع Stripe
            $checkoutSession = $this->stripeService->createCheckoutSession($booking);
            
            if ($checkoutSession['success']) {
                // تخزين معرف جلسة Stripe في البيانات الوصفية للحجز
                $booking->metadata = array_merge($booking->metadata ?? [], [
                    'stripe_session_id' => $checkoutSession['session_id']
                ]);
                $booking->save();
                
                // إعادة توجيه المستخدم إلى صفحة الدفع
                return redirect($checkoutSession['checkout_url']);
            } else {
                throw new \Exception('فشل في إنشاء جلسة الدفع');
            }
        } catch (\Exception $e) {
            Log::error('خطأ في توجيه المستخدم إلى الدفع', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            
            // في حالة الفشل، عرض صفحة تأكيد الحجز مع خيار المحاولة مرة أخرى
            return redirect()->route('booking.confirmation', ['transaction_id' => $booking->transaction_id])
                ->with('error', 'حدث خطأ أثناء إنشاء جلسة الدفع. يمكنك المحاولة مرة أخرى لاحقاً.');
        }
    }

    /**
     * عرض صفحة تأكيد نجاح الحجز
     *
     * @param string $transactionId
     * @return \Illuminate\View\View
     */
    public function showBookingConfirmation($transactionId)
    {
        $booking = Booking::where('transaction_id', $transactionId)->firstOrFail();
        
        return view('booking.confirmation', compact('booking'));
    }

    /**
     * فتح صفحة الدفع مباشرة للحجز المحدد
     *
     * @param string $transactionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payNow($transactionId)
    {
        $booking = Booking::where('transaction_id', $transactionId)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();
        
        return $this->redirectToPayment($booking);
    }

    /**
     * حساب الأيام المتاحة للمتخصص
     *
     * @param Specialist $specialist
     * @param int $daysToShow
     * @return array
     */
    protected function getAvailableDays(Specialist $specialist, $daysToShow = 14)
    {
        $availableDays = [];
        $today = Carbon::today();
        
        // يفترض أن المتخصص متاح في أيام العمل من الأحد إلى الخميس
        $workingDays = [0, 1, 2, 3, 4]; // الأحد إلى الخميس
        
        for ($i = 0; $i < $daysToShow; $i++) {
            $date = $today->copy()->addDays($i);
            
            // التحقق من أن اليوم هو يوم عمل
            if (in_array($date->dayOfWeek, $workingDays)) {
                $availableDays[] = [
                    'date' => $date->format('Y-m-d'),
                    'dayName' => $this->getArabicDayName($date),
                    'formattedDate' => $date->format('d/m/Y'),
                ];
            }
        }
        
        return $availableDays;
    }

    /**
     * حساب الفترات الزمنية المتاحة ليوم معين
     *
     * @param Specialist $specialist
     * @param string $date
     * @return array
     */
    protected function calculateAvailableTimeSlots(Specialist $specialist, $date)
    {
        try {
            // للتبسيط, عرض ساعات ثابتة متاحة بدلاً من الاستعلام عن قاعدة البيانات
            $fixedTimeSlots = [
                ['time' => '10:00', 'formattedTime' => $this->formatTimeInArabic('10:00'), 'available' => true],
                ['time' => '11:00', 'formattedTime' => $this->formatTimeInArabic('11:00'), 'available' => true],
                ['time' => '12:00', 'formattedTime' => $this->formatTimeInArabic('12:00'), 'available' => true],
                ['time' => '13:00', 'formattedTime' => $this->formatTimeInArabic('13:00'), 'available' => true],
                ['time' => '14:00', 'formattedTime' => $this->formatTimeInArabic('14:00'), 'available' => true],
                ['time' => '15:00', 'formattedTime' => $this->formatTimeInArabic('15:00'), 'available' => true],
                ['time' => '16:00', 'formattedTime' => $this->formatTimeInArabic('16:00'), 'available' => true],
                ['time' => '17:00', 'formattedTime' => $this->formatTimeInArabic('17:00'), 'available' => true],
            ];
            
            // التحقق من الحجوزات الموجودة للتخصص والتاريخ
            $bookings = Booking::where('specialist_id', $specialist->id)
                ->where('booking_date', $date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get(['booking_time']);
            
            // تسجيل الحجوزات الموجودة للتصحيح
            \Illuminate\Support\Facades\Log::info('Found bookings for date ' . $date, [
                'specialist_id' => $specialist->id,
                'date' => $date,
                'bookings_count' => $bookings->count(),
                'booking_times' => $bookings->pluck('booking_time')->toArray()
            ]);
                
            // تحديث حالة توفر المواعيد بناءً على الحجوزات الموجودة
            foreach ($fixedTimeSlots as $key => $slot) {
                $isBooked = $bookings->contains('booking_time', $slot['time']);
                $fixedTimeSlots[$key]['available'] = !$isBooked;
                
                // تسجيل حالة كل موعد
                \Illuminate\Support\Facades\Log::info('Time slot status', [
                    'time' => $slot['time'],
                    'isBooked' => $isBooked,
                    'available' => !$isBooked,
                    'slot_after_update' => $fixedTimeSlots[$key]
                ]);
            }
            
            return $fixedTimeSlots;
            
        } catch (\Exception $e) {
            // تسجيل الخطأ للتصحيح
            Log::error('Error calculating available time slots', [
                'error' => $e->getMessage(),
                'specialist_id' => $specialist->id,
                'date' => $date
            ]);
            
            // إرجاع مصفوفة فارغة في حالة حدوث خطأ
            return [];
        }
    }

    /**
     * التحقق من توفر فترة زمنية معينة
     *
     * @param Specialist $specialist
     * @param string $date
     * @param string $time
     * @return bool
     */
    protected function checkTimeSlotAvailability(Specialist $specialist, $date, $time)
    {
        // التحقق من إذا كان الوقت محجوزًا بالفعل
        $existingBooking = Booking::where('specialist_id', $specialist->id)
            ->where('booking_date', $date)
            ->where('booking_time', $time)
            ->whereIn('status', ['pending', 'confirmed']) // تحقق فقط من الحجوزات المؤكدة أو قيد الانتظار
            ->exists();
        
        return !$existingBooking;
    }
    
    /**
     * API للتحقق من توفر موعد محدد
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTimeSlotAvailabilityApi(Request $request)
    {
        try {
            // التحقق من صحة البيانات المدخلة
            $validated = $request->validate([
                'specialist_id' => 'required|exists:specialists,id',
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date',
                'time' => 'required',
            ]);
            
            $specialist = Specialist::findOrFail($validated['specialist_id']);
            $date = $validated['date'];
            $time = $validated['time'];
            
            // التحقق من توفر الموعد
            $isAvailable = $this->checkTimeSlotAvailability($specialist, $date, $time);
            
            return response()->json([
                'success' => true,
                'available' => $isAvailable,
                'specialist_id' => $specialist->id,
                'date' => $date,
                'time' => $time
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking time slot availability', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من توفر الموعد',
                'available' => false
            ], 500);
        }
    }
    
    /**
     * الحصول على اسم اليوم بالعربية
     *
     * @param string $date
     * @return string
     */
    protected function getArabicDayName($date)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        $days = [
            0 => 'الأحد',
            1 => 'الإثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];
        
        return $days[$carbonDate->dayOfWeek];
    }

    /**
     * تنسيق الوقت بالعربية
     *
     * @param string $time
     * @return string
     */
    protected function formatTimeInArabic($time)
    {
        $parts = explode(':', $time);
        $hour = (int) $parts[0];
        $minute = $parts[1];
        
        // تحويل الوقت إلى صيغة 12 ساعة
        $suffix = $hour >= 12 ? 'مساءً' : 'صباحاً';
        $hour = $hour % 12;
        if ($hour === 0) $hour = 12;
        
        return $hour . ':' . $minute . ' ' . $suffix;
    }
}
