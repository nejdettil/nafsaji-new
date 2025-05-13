<?php

namespace App\Filament\UserPages;

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
            UserStatsOverview::class,
            MyBookingsWidget::class,
        ];
    }

    public function getTitle(): string
    {
        return 'لوحة تحكم العميل';
    }
}

class UserStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();
        
        $totalBookings = Booking::where('user_id', $userId)->count();
        $completedBookings = Booking::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $upcomingBookings = Booking::where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->count();
        
        return [
            Stat::make('إجمالي الحجوزات', $totalBookings)
                ->description('جميع الحجوزات الخاصة بك')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('purple'),
            
            Stat::make('الجلسات المكتملة', $completedBookings)
                ->description('الجلسات التي تم إكمالها سابقاً')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('الحجوزات القادمة', $upcomingBookings)
                ->description('حجوزات معلقة أو مؤكدة')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
        ];
    }
}

class MyBookingsWidget extends \Filament\Widgets\TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        return Booking::query()
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_date', '>=', now()->format('Y-m-d'))
            ->latest('booking_date');
    }
    
    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('specialist.name')
                ->label('المختص')
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
        return 'حجوزاتي القادمة';
    }
}
