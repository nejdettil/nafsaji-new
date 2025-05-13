<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class FinancialStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '30s';
    
    protected function getStats(): array
    {
        // إجمالي الإيرادات من الحجوزات
        $totalRevenue = $this->tableExists('bookings') 
            ? Booking::whereIn('status', ['confirmed', 'completed'])->sum('price') 
            : 0;
        
        // إيرادات هذا الشهر من الحجوزات
        $currentMonthRevenue = $this->tableExists('bookings') 
            ? Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('price') 
            : 0;
        
        // إيرادات الشهر الماضي من الحجوزات
        $lastMonthRevenue = $this->tableExists('bookings') 
            ? Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('price') 
            : 0;
        
        // متوسط قيمة الحجز
        $averageBookingPrice = $this->tableExists('bookings')
            ? (Booking::whereIn('status', ['confirmed', 'completed'])->count() > 0
                ? round(Booking::whereIn('status', ['confirmed', 'completed'])->sum('price') / 
                    Booking::whereIn('status', ['confirmed', 'completed'])->count(), 2)
                : 0)
            : 0;
        
        return [
            Stat::make('إجمالي الإيرادات', number_format($totalRevenue, 2) . ' ر.س')
                ->description('من الحجوزات المؤكدة والمكتملة')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([
                    $lastMonthRevenue / max(1, max($lastMonthRevenue, $currentMonthRevenue) / 100), 
                    $currentMonthRevenue / max(1, max($lastMonthRevenue, $currentMonthRevenue) / 100)
                ])
                ->color('success'),
                
            Stat::make('إيرادات هذا الشهر', number_format($currentMonthRevenue, 2) . ' ر.س')
                ->description($this->getRevenueChangePercentage($lastMonthRevenue, $currentMonthRevenue))
                ->descriptionIcon($currentMonthRevenue >= $lastMonthRevenue ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($currentMonthRevenue >= $lastMonthRevenue ? 'success' : 'danger'),
                
            Stat::make('متوسط قيمة الحجز', number_format($averageBookingPrice, 2) . ' ر.س')
                ->description('للحجوزات المؤكدة والمكتملة')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),
        ];
    }
    
    /**
     * حساب نسبة التغيير في الإيرادات
     */
    private function getRevenueChangePercentage($lastMonth, $currentMonth): string
    {
        if ($lastMonth == 0) {
            return 'لا توجد إيرادات في الشهر السابق';
        }
        
        $percentage = round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        $sign = $percentage >= 0 ? '+' : '';
        
        return "$sign$percentage% مقارنة بالشهر الماضي";
    }
    
    /**
     * التحقق من وجود جدول في قاعدة البيانات
     */
    protected function tableExists(string $table): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }
}
