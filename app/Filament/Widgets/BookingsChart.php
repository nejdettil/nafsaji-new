<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class BookingsChart extends ChartWidget
{
    protected static ?string $heading = 'إحصائيات الحجوزات الشهرية';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // تجهيز مصفوفات البيانات
        $labels = [];
        $pendingData = [];
        $confirmedData = [];
        
        // توليد بيانات الأشهر الستة الأخيرة
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');
            
            // بيانات عشوائية للعرض
            $pendingData[] = rand(5, 15);
            $confirmedData[] = rand(10, 25);
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'قيد الانتظار',
                    'data' => $pendingData,
                    'borderColor' => '#9333ea',
                    'backgroundColor' => 'rgba(147, 51, 234, 0.1)', 
                ],
                [
                    'label' => 'مؤكدة',
                    'data' => $confirmedData,
                    'borderColor' => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                ]
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ]
            ]
        ];
    }
}
