<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Contact;
use App\Models\Specialist;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected ?string $heading = 'إحصائيات عامة';
    protected int | string | array $columnSpan = 'full';
    
    /**
     * التحقق من وجود جدول في قاعدة البيانات
     *
     * @param string $table
     * @return bool
     */
    protected function tableExists(string $table): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    protected function getStats(): array
    {
        // التحقق من وجود الجداول واستخدام قيم افتراضية إذا لم تكن موجودة
        $hasUsersTable = $this->tableExists('users');
        $hasSpecialistsTable = $this->tableExists('specialists');
        $hasBookingsTable = $this->tableExists('bookings');
        $hasContactsTable = $this->tableExists('contacts');
        
        // إحصائيات المستخدمين
        $totalUsers = $hasUsersTable ? User::where('role', 'user')->count() : 0;
        $newUsersThisMonth = $hasUsersTable ? User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->count() : 0;
            
        // إحصائيات المختصين
        $totalSpecialists = $hasSpecialistsTable ? Specialist::count() : 0;
        $activeSpecialists = $hasSpecialistsTable ? Specialist::where('is_active', true)->count() : 0;
        
        // إحصائيات الحجوزات
        $totalBookings = $hasBookingsTable ? Booking::count() : 0;
        $pendingBookings = $hasBookingsTable ? Booking::where('status', 'pending')->count() : 0;
        $confirmedBookings = $hasBookingsTable ? Booking::where('status', 'confirmed')->count() : 0;
        $completedBookings = $hasBookingsTable ? Booking::where('status', 'completed')->count() : 0;
        
        // حساب الإيرادات من الحجوزات المكتملة
        $totalRevenue = $hasBookingsTable ? (Booking::where('status', 'completed')->sum('price') ?? 0) : 0;
        
        // رسائل الاتصال
        $totalContacts = $hasContactsTable ? Contact::count() : 0;
        $unreadContacts = $hasContactsTable ? Contact::where('is_read', false)->count() : 0;
        
        return [
            Stat::make('المستخدمين', number_format($totalUsers))
                ->description("$newUsersThisMonth مستخدمين جدد هذا الشهر")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, $newUsersThisMonth])
                ->color('primary')
                ->icon('heroicon-o-user-group')
                ->extraAttributes([
                    'class' => 'ring-1 ring-primary-200 dark:ring-primary-900 shadow-sm',
                ]),
                
            Stat::make('المختصين', number_format($totalSpecialists))
                ->description("$activeSpecialists مختص نشط حالياً")
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([2, 1, 3, 5, 4, $activeSpecialists])
                ->color('success')
                ->icon('heroicon-o-academic-cap')
                ->extraAttributes([
                    'class' => 'ring-1 ring-success-200 dark:ring-success-900 shadow-sm',
                ]),
                
            Stat::make('إجمالي الحجوزات', number_format($totalBookings))
                ->description("$pendingBookings في انتظار التأكيد | $confirmedBookings مؤكد")
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([$completedBookings, $confirmedBookings, $pendingBookings])
                ->color('warning')
                ->icon('heroicon-o-calendar-days')
                ->extraAttributes([
                    'class' => 'ring-1 ring-warning-200 dark:ring-warning-900 shadow-sm',
                ]),
                
            Stat::make('الجلسات المكتملة', number_format($completedBookings))
                ->description(number_format($completedBookings / (max($totalBookings, 1)) * 100, 1) . '% من إجمالي الحجوزات')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([3, 5, 7, 9, 12, $completedBookings])
                ->color('info')
                ->icon('heroicon-o-check-circle')
                ->extraAttributes([
                    'class' => 'ring-1 ring-info-200 dark:ring-info-900 shadow-sm',
                ]),
                
            Stat::make('الإيرادات', number_format($totalRevenue) . ' ريال')
                ->description(number_format($totalRevenue / max($completedBookings, 1), 0) . ' ريال/جلسة')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([1000, 2500, 3200, 4800, 5100, $totalRevenue])
                ->color('success')
                ->icon('heroicon-o-currency-dollar')
                ->extraAttributes([
                    'class' => 'ring-1 ring-success-200 dark:ring-success-900 shadow-sm',
                ]),
                
            Stat::make('رسائل الاتصال', number_format($totalContacts))
                ->description("$unreadContacts رسائل غير مقروءة")
                ->descriptionIcon($unreadContacts > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->color($unreadContacts > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-envelope')
                ->extraAttributes([
                    'class' => $unreadContacts > 0 ? 'ring-1 ring-danger-200 dark:ring-danger-900 shadow-sm animate-pulse' : 'ring-1 ring-success-200 dark:ring-success-900 shadow-sm',
                ]),
        ];
    }
}
