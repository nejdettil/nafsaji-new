<?php

namespace App\Filament\SpecialistPages;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            SpecialistStatsOverview::class,
            UpcomingBookingsWidget::class,
        ];
    }

    public function getTitle(): string
    {
        return 'لوحة تحكم المختص';
    }
}

class SpecialistStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $specialistId = auth()->id();
        
        $totalBookings = Booking::where('specialist_id', $specialistId)->count();
        $pendingBookings = Booking::where('specialist_id', $specialistId)
            ->where('status', 'pending')
            ->count();
        $upcomingBookings = Booking::where('specialist_id', $specialistId)
            ->where('status', 'confirmed')
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->count();
        
        return [
            Stat::make('إجمالي الحجوزات', $totalBookings)
                ->description('جميع الحجوزات المرتبطة بك')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('purple'),
            
            Stat::make('الحجوزات المعلقة', $pendingBookings)
                ->description('تحتاج إلى تأكيد')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('الحجوزات القادمة', $upcomingBookings)
                ->description('جلسات مؤكدة قادمة')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
        ];
    }
}

class UpcomingBookingsWidget extends \Filament\Widgets\TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        return Booking::query()
            ->where('specialist_id', auth()->id())
            ->where('status', 'confirmed')
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->latest('booking_date');
    }
    
    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('user.name')
                ->label('اسم العميل')
                ->searchable(),
            
            \Filament\Tables\Columns\TextColumn::make('booking_date')
                ->label('تاريخ الجلسة')
                ->date('d/m/Y')
                ->sortable(),
            
            \Filament\Tables\Columns\TextColumn::make('booking_time')
                ->label('وقت الجلسة')
                ->time('h:i A'),
            
            \Filament\Tables\Columns\TextColumn::make('session_type')
                ->label('نوع الجلسة'),
            
            \Filament\Tables\Columns\BadgeColumn::make('status')
                ->label('الحالة')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'confirmed',
                ])
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'قيد الانتظار',
                    'confirmed' => 'مؤكد',
                    default => $state,
                }),
        ];
    }
    
    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }
    
    protected function getTableHeading(): string 
    {
        return 'الجلسات القادمة';
    }
}
