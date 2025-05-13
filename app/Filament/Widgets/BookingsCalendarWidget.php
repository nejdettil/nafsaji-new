<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Service;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class BookingsCalendarWidget extends Widget
{
    protected static string $view = 'filament.widgets.bookings-calendar-widget';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    
    // حالة لتتبع الشهر الحالي المعروض في التقويم
    public $currentMonth;
    public $currentYear;
    public $weekdayLabels = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
    public $monthLabels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    public $calendarData = [];
    
    public function mount()
    {
        $today = Carbon::today();
        $this->currentMonth = (int) $today->format('m');
        $this->currentYear = (int) $today->format('Y');
        $this->generateCalendarData();
    }
    
    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = (int) $date->format('m');
        $this->currentYear = (int) $date->format('Y');
        $this->generateCalendarData();
    }
    
    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = (int) $date->format('m');
        $this->currentYear = (int) $date->format('Y');
        $this->generateCalendarData();
    }
    
    public function goToToday()
    {
        $today = Carbon::today();
        $this->currentMonth = (int) $today->format('m');
        $this->currentYear = (int) $today->format('Y');
        $this->generateCalendarData();
    }
    
    public function getCurrentMonthLabel()
    {
        return $this->monthLabels[$this->currentMonth - 1] . ' ' . $this->currentYear;
    }
    
    public function generateCalendarData()
    {
        $data = [];
        
        // أول يوم في الشهر
        $firstDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        
        // آخر يوم في الشهر
        $lastDay = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth();
        
        // عدد أيام الشهر
        $daysInMonth = $lastDay->day;
        
        // اليوم الأول من الشهر (0 = الأحد، 6 = السبت)
        $firstDayOfWeek = $firstDay->dayOfWeek;
        
        // جلب بيانات الحجوزات للشهر الحالي
        $bookings = Booking::whereBetween('date', [
                $firstDay->format('Y-m-d'),
                $lastDay->format('Y-m-d')
            ])
            ->with(['user', 'specialist', 'service'])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->date)->format('Y-m-d');
            });
        
        // إنشاء مصفوفة الأسابيع والأيام
        $weeks = [];
        $dayCount = 1;
        
        // اليوم الحالي
        $today = Carbon::today();
        $isCurrentMonth = ($this->currentMonth == $today->month && $this->currentYear == $today->year);
        
        for ($week = 0; $week < 6; $week++) {
            $days = [];
            
            for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) {
                $currentDay = ($week === 0 && $dayOfWeek < $firstDayOfWeek) || ($dayCount > $daysInMonth);
                
                if ($currentDay) {
                    // أيام خارج الشهر الحالي
                    $days[] = [
                        'day' => null,
                        'isToday' => false,
                        'bookings' => [],
                        'date' => null,
                    ];
                } else {
                    // أيام الشهر الحالي
                    $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, $dayCount);
                    $dateString = $date->format('Y-m-d');
                    
                    $days[] = [
                        'day' => $dayCount,
                        'isToday' => $isCurrentMonth && $dayCount === (int) $today->format('d'),
                        'bookings' => $bookings[$dateString] ?? [],
                        'date' => $dateString,
                    ];
                    $dayCount++;
                }
            }
            
            $weeks[] = $days;
            
            // توقف إذا تم استكمال جميع أيام الشهر
            if ($dayCount > $daysInMonth) {
                // تحقق مما إذا كان الأسبوع الأخير فارغًا
                $lastWeekHasDays = false;
                foreach ($days as $day) {
                    if ($day['day'] !== null) {
                        $lastWeekHasDays = true;
                        break;
                    }
                }
                
                if (!$lastWeekHasDays) {
                    array_pop($weeks);
                }
                
                break;
            }
        }
        
        $this->calendarData = $weeks;
    }
    
    public function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'bg-gray-300',
            'confirmed' => 'bg-green-500',
            'completed' => 'bg-blue-500',
            'cancelled' => 'bg-red-500',
            default => 'bg-gray-300',
        };
    }
    
    public function getStatusLabel($status)
    {
        return match($status) {
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => $status,
        };
    }
    
    protected function getViewData(): array
    {
        return [
            'weekdayLabels' => $this->weekdayLabels,
            'calendarData' => $this->calendarData,
            'currentMonthLabel' => $this->getCurrentMonthLabel(),
        ];
    }
}
