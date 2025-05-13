<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Widget;

class Dashboard extends BaseDashboard
{
    /**
     * إعدادات لوحة التحكم الرئيسية
     */
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -2;
    protected static ?string $navigationGroup = 'نظرة عامة';
    protected ?string $heading = 'لوحة تحكم نفسجي';
    protected ?string $subheading = 'مرحباً بك في لوحة تحكم نفسجي - منصة العلاج النفسي عن بعد';
    protected static string $view = 'filament.pages.dashboard';
    protected ?string $maxContentWidth = MaxWidth::Full->value;
    
    /**
     * ترتيب وتنظيم الويدجت في لوحة التحكم
     */
    public function getWidgets(): array
    {
        return [
            // ويدجت الترحيب والملخص السريع
            \App\Filament\Widgets\WelcomeWidget::class,
            
            // ويدجت الإحصائيات العامة
            \App\Filament\Widgets\StatsOverviewWidget::class,
            
            // ويدجت الرسم البياني للحجوزات
            \App\Filament\Widgets\BookingsChart::class,
            
            // ويدجت الإحصائيات المالية
            \App\Filament\Widgets\FinancialStatsWidget::class,
            
            // ويدجت رسم بياني للمدفوعات
            \App\Filament\Widgets\PaymentsChart::class,
            
            // ويدجت أفضل المتخصصين
            \App\Filament\Widgets\TopSpecialistsWidget::class,
            
            // ويدجت آخر الإشعارات
            \App\Filament\Widgets\LatestNotificationsWidget::class,
            
            // ويدجت تقويم الحجوزات
            \App\Filament\Widgets\BookingsCalendarWidget::class,
        ];
    }
    
    /**
     * تحديد مواقع وترتيب الويدجت على الشاشة
     */
    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\WelcomeWidget::class,
            \App\Filament\Widgets\StatsOverviewWidget::class,
        ];
    }
    
    /**
     * تحديد الويدجت الرئيسية في الجسم
     */
    public function getWidgetsColumns(): array|int
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
    
    /**
     * تحديد الويدجت في التذييل
     */
    public function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\LatestBookingsWidget::class,
            \App\Filament\Widgets\LatestContactsWidget::class,
            \App\Filament\Widgets\LatestNotificationsWidget::class,
            \App\Filament\Widgets\TopSpecialistsWidget::class,
        ];
    }
    
    /**
     * تحديد عدد الأعمدة للويدجتات
     */
    public function getHeaderWidgetsColumns(): array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
        ];
    }
    
    /**
     * تحديد عدد الأعمدة للويدجتات في التذييل
     */
    public function getFooterWidgetsColumns(): array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
        ];
    }
    
    /**
     * عنوان لوحة التحكم
     */
    public function getTitle(): string
    {
        return 'لوحة تحكم نفسجي';
    }
}

// مثال على رسم بياني مخصص
class UserSessionsChart extends ChartWidget
{
    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            'datasets' => [
                [
                    'label' => 'الجلسات',
                    'data' => [10, 20, 15, 25, 30, 35],
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.2)',
                ]
            ]
        ];
    }
}

class BookingsWidget extends \Filament\Widgets\StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            \Filament\Widgets\StatsOverviewWidget\Stat::make(
                'إجمالي الحجوزات', 
                \App\Models\Booking::count()
            )->description('الحجوزات هذا الشهر')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            
            \Filament\Widgets\StatsOverviewWidget\Stat::make(
                'الجلسات النشطة', 
                \App\Models\Session::where('status', 'active')->count()
            )->description('الجلسات الجارية')
            ->descriptionIcon('heroicon-m-check-circle')
            ->color('primary'),
            
            \Filament\Widgets\StatsOverviewWidget\Stat::make(
                'إجمالي المختصين', 
                \App\Models\Specialist::count()
            )->description('المختصون المسجلون')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('warning')
        ];
    }
}
