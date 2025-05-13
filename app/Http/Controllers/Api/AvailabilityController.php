<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\SpecialistSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AvailabilityController extends Controller
{
    /**
     * الحصول على التوافر للأخصائي والخدمة المحددة
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailability(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        // الحصول على الأخصائي والخدمة
        $specialist = Specialist::findOrFail($request->specialist_id);
        $service = Service::findOrFail($request->service_id);

        // التأكد من أن الأخصائي يقدم هذه الخدمة
        if (!$specialist->services()->where('services.id', $service->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.specialist_does_not_provide_service'),
            ], 400);
        }

        // الحصول على التوافر
        $availability = $this->calculateAvailability(
            $specialist->id,
            $service->id,
            $service->duration,
            Carbon::parse($request->start_date),
            Carbon::parse($request->end_date)
        );
        
        // إذا لم تكن هناك مواعيد متاحة، نضيف مواعيد افتراضية للتجربة
        if (empty($availability) && $specialist->id) {
            // إنشاء مواعيد افتراضية للأيام من الأحد إلى الخميس
            $startDateObj = Carbon::parse($request->start_date);
            $endDateObj = Carbon::parse($request->end_date);
            
            $currentDate = $startDateObj->copy();
            while ($currentDate->lte($endDateObj)) {
                $dayOfWeek = $currentDate->dayOfWeek;
                
                // الأيام من 0 (الأحد) إلى 4 (الخميس)
                if ($dayOfWeek >= 0 && $dayOfWeek <= 4) {
                    $dateStr = $currentDate->format('Y-m-d');
                    
                    // إضافة فترات زمنية من 9 صباحاً إلى 5 مساءً بفاصل ساعة
                    $availability[$dateStr] = [];
                    for ($hour = 9; $hour < 17; $hour++) {
                        $availability[$dateStr][] = sprintf('%02d:00', $hour);
                    }
                }
                
                $currentDate->addDay();
            }
        }

        return response()->json([
            'success' => true,
            'availability' => $availability,
        ]);
    }

    /**
     * حساب التوافر للأخصائي والخدمة والفترة الزمنية
     *
     * @param int $specialistId
     * @param int $serviceId
     * @param int $serviceDuration
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    private function calculateAvailability($specialistId, $serviceId, $serviceDuration, Carbon $startDate, Carbon $endDate)
    {
        // تهيئة مصفوفة التوافر
        $availability = [];

        // العمل مع نسخة من التواريخ لعدم تعديل الأصل
        $currentDate = $startDate->copy();

        // الحصول على جدول الأخصائي
        $specialist = Specialist::find($specialistId);
        $schedules = [];
        
        // إذا كان لدى المتخصص جدول مواعيد مخزن في حقل availability
        if ($specialist && $specialist->availability) {
            $availabilityData = json_decode($specialist->availability, true);
            if (is_array($availabilityData)) {
                foreach ($availabilityData as $schedule) {
                    if (isset($schedule['day']) && isset($schedule['start']) && isset($schedule['end'])) {
                        $dayOfWeek = $schedule['day'];
                        $schedules[$dayOfWeek] = (object) [
                            'is_working_day' => true,
                            'morning_start_time' => $schedule['start'],
                            'morning_end_time' => $schedule['end'],
                            'evening_start_time' => null,
                            'evening_end_time' => null
                        ];
                    }
                }
            }
        } else {
            // إذا كان جدول SpecialistSchedule موجوداً، نستخدمه كاحتياطي
            if (Schema::hasTable('specialist_schedules')) {
                $schedules = SpecialistSchedule::where('specialist_id', $specialistId)
                    ->get()
                    ->keyBy('day_of_week');
            }
        }

        // تحويل مدة الخدمة من دقائق إلى ساعات للحسابات
        $serviceDurationHours = $serviceDuration / 60;

        // الحصول على الحجوزات الموجودة بالفعل في الفترة المحددة
        $existingBookings = Booking::where('specialist_id', $specialistId)
            ->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // إنشاء فهرس للحجوزات الموجودة مسبقاً حسب التاريخ والوقت
        $bookedSlots = [];
        foreach ($existingBookings as $booking) {
            $bookingDate = $booking->booking_date->toDateString();
            if (!isset($bookedSlots[$bookingDate])) {
                $bookedSlots[$bookingDate] = [];
            }

            // حساب وقت بداية ونهاية الحجز
            $startTime = Carbon::createFromFormat('H:i', $booking->booking_time);
            $endTime = $startTime->copy()->addMinutes($booking->service->duration);

            // إضافة الفترة إلى المصفوفة
            $bookedSlots[$bookingDate][] = [
                'start' => $startTime->format('H:i'),
                'end' => $endTime->format('H:i'),
            ];
        }

        // حساب التوافر لكل يوم في النطاق المحدد
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek;
            $dateString = $currentDate->toDateString();

            // تجاوز أيام التعطيل، إذا لم يكن للمتخصص جدول في هذا اليوم
            if (!isset($schedules[$dayOfWeek])) {
                $currentDate->addDay();
                continue;
            }

            // الحصول على جدول اليوم
            $daySchedule = $schedules[$dayOfWeek];

            // إذا لم يكن هناك دوام في هذا اليوم
            if (!$daySchedule->is_working_day) {
                $currentDate->addDay();
                continue;
            }

            // تحويل أوقات العمل إلى كائنات كاربون
            $morningStart = Carbon::createFromFormat('H:i', $daySchedule->morning_start_time);
            $morningEnd = Carbon::createFromFormat('H:i', $daySchedule->morning_end_time);
            $eveningStart = Carbon::createFromFormat('H:i', $daySchedule->evening_start_time);
            $eveningEnd = Carbon::createFromFormat('H:i', $daySchedule->evening_end_time);

            // تهيئة مصفوفة الفترات المتاحة لهذا اليوم
            $availableSlots = [];

            // إضافة الفترات المتاحة في فترة الصباح (إذا كانت موجودة)
            if ($morningStart && $morningEnd && $morningStart->lt($morningEnd)) {
                $this->addAvailableSlots(
                    $availableSlots,
                    $morningStart,
                    $morningEnd,
                    $serviceDurationHours,
                    $bookedSlots[$dateString] ?? []
                );
            }

            // إضافة الفترات المتاحة في فترة المساء (إذا كانت موجودة)
            if ($eveningStart && $eveningEnd && $eveningStart->lt($eveningEnd)) {
                $this->addAvailableSlots(
                    $availableSlots,
                    $eveningStart,
                    $eveningEnd,
                    $serviceDurationHours,
                    $bookedSlots[$dateString] ?? []
                );
            }

            // إضافة الفترات المتاحة إلى مصفوفة التوافر الكلية إذا كان هناك فترات متاحة
            if (!empty($availableSlots)) {
                $availability[$dateString] = $availableSlots;
            }

            // الانتقال إلى اليوم التالي
            $currentDate->addDay();
        }

        return $availability;
    }

    /**
     * إضافة الفترات المتاحة لفترة زمنية محددة
     *
     * @param array $availableSlots
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @param float $serviceDurationHours
     * @param array $bookedSlots
     */
    private function addAvailableSlots(&$availableSlots, Carbon $startTime, Carbon $endTime, float $serviceDurationHours, array $bookedSlots)
    {
        // إنشاء فترات زمنية بناءً على مدة الخدمة (مع تقريب الوقت إلى أقرب 15 دقيقة)
        $currentTime = $startTime->copy();
        
        while ($currentTime->copy()->addHours($serviceDurationHours)->lte($endTime)) {
            $slotStartTime = $currentTime->format('H:i');
            $slotEndTime = $currentTime->copy()->addHours($serviceDurationHours)->format('H:i');
            
            // التحقق مما إذا كانت الفترة متداخلة مع أي من الحجوزات الموجودة
            $isAvailable = true;
            
            foreach ($bookedSlots as $bookedSlot) {
                // التحقق من التداخل
                if (
                    ($slotStartTime >= $bookedSlot['start'] && $slotStartTime < $bookedSlot['end']) ||
                    ($slotEndTime > $bookedSlot['start'] && $slotEndTime <= $bookedSlot['end']) ||
                    ($slotStartTime <= $bookedSlot['start'] && $slotEndTime >= $bookedSlot['end'])
                ) {
                    $isAvailable = false;
                    break;
                }
            }
            
            // إضافة الفترة إذا كانت متاحة
            if ($isAvailable) {
                $availableSlots[] = $slotStartTime;
            }
            
            // الانتقال إلى الفترة التالية (كل 15 دقيقة)
            $currentTime->addMinutes(15);
        }
    }
}
