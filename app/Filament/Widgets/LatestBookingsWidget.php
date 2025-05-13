<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestBookingsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'أحدث الحجوزات';
    protected static ?string $pollingInterval = '30s';
    
    protected function getTableContentFooter(): ?\Illuminate\Contracts\View\View
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('bookings')) {
            return null;
        }
        
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $viewAllUrl = route('filament.admin.resources.bookings.index');
        
        return view('filament.widgets.latest-bookings-footer', [
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            'viewAllUrl' => $viewAllUrl,
        ]);
    }

    protected function getTableQuery(): Builder
    {
        // التحقق من وجود جدول الحجوزات
        if (!\Illuminate\Support\Facades\Schema::hasTable('bookings')) {
            return Booking::query();
        }
        
        // إظهار الحجوزات المعلقة والمؤكدة أولاً
        return Booking::query()
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->limit(7);
    }

    protected function getTableColumns(): array
    {
        return [
            BadgeColumn::make('status')
                ->label('')
                ->colors([
                    'danger' => 'canceled',
                    'warning' => 'pending',
                    'success' => fn ($state) => in_array($state, ['confirmed', 'completed']),
                ])
                ->icons([
                    'heroicon-m-clock' => 'pending',
                    'heroicon-m-check-circle' => 'confirmed',
                    'heroicon-m-x-circle' => 'canceled',
                    'heroicon-m-check-badge' => 'completed',
                ])
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'قيد الانتظار',
                    'confirmed' => 'مؤكد',
                    'canceled' => 'ملغي',
                    'completed' => 'مكتمل',
                    default => $state,
                }),
                
            TextColumn::make('user.name')
                ->label('العميل')
                ->weight('bold')
                ->searchable(),

            TextColumn::make('specialist.name')
                ->label('المختص')
                ->searchable()
                ->weight('medium')
                ->color('primary'),
                
            TextColumn::make('date')
                ->label('التاريخ')
                ->date('d/m/Y')
                ->sortable()
                ->color(fn (Booking $record): string => $record->date < now()->toDateString() ? 'danger' : 'gray'),
                
            TextColumn::make('time')
                ->label('الوقت')
                ->time('h:i A')
                ->sortable()
                ->badge()
                ->color('info'),
                
            TextColumn::make('session_type')
                ->label('نوع الجلسة')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'video' => 'فيديو',
                    'audio' => 'صوتية',
                    'chat' => 'دردشة',
                    default => $state,
                })
                ->icon(fn (string $state): string => match ($state) {
                    'video' => 'heroicon-m-video-camera',
                    'audio' => 'heroicon-m-speaker-wave',
                    'chat' => 'heroicon-m-chat-bubble-left-right',
                    default => 'heroicon-m-chat-bubble-left-right',
                })
                ->iconPosition('before'),
                
            TextColumn::make('created_at')
                ->label('منذ')
                ->date('d/m/Y')
                ->sortable()
                ->color('gray')
                ->size('sm'),
                
            TextColumn::make('price')
                ->label('السعر')
                ->money('SAR')
                ->sortable()
                ->color('success')
                ->alignEnd(),
                
            TextColumn::make('payment_status')
                ->label('الدفع')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'paid' => 'مدفوع',
                    'unpaid' => 'غير مدفوع',
                    'partial' => 'مدفوع جزئياً',
                    default => $state,
                })
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'paid' => 'success',
                    'unpaid' => 'danger',
                    'partial' => 'warning',
                    default => 'gray',
                }),
                
            
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->icon('heroicon-o-eye')
                ->iconButton()
                ->tooltip('عرض الحجز')
                ->url(fn (Booking $record): string => route('filament.admin.resources.bookings.edit', ['record' => $record]))
                ->openUrlInNewTab()
                ->extraAttributes([
                    'class' => 'text-primary-600 hover:text-primary-500',
                ]),
                
            Action::make('confirm')
                ->icon('heroicon-o-check-circle')
                ->iconButton()
                ->tooltip('تأكيد الحجز')
                ->action(function (Booking $record) {
                    $record->update(['status' => 'confirmed']);
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'تم تأكيد الحجز بنجاح',
                    ]);
                })
                ->hidden(fn (Booking $record) => $record->status !== 'pending')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('تأكيد الحجز')
                ->modalDescription('هل أنت متأكد من تأكيد هذا الحجز؟')
                ->modalSubmitActionLabel('تأكيد')
                ->extraAttributes([
                    'class' => 'hover:scale-110 transition-transform',
                ]),
                
            Action::make('cancel')
                ->icon('heroicon-o-x-circle')
                ->iconButton()
                ->tooltip('إلغاء الحجز')
                ->action(function (Booking $record) {
                    $record->update(['status' => 'canceled']);
                    $this->dispatch('notify', [
                        'type' => 'warning',
                        'message' => 'تم إلغاء الحجز',
                    ]);
                })
                ->hidden(fn (Booking $record) => !in_array($record->status, ['pending', 'confirmed']))
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('إلغاء الحجز')
                ->modalDescription('هل أنت متأكد من إلغاء هذا الحجز؟')
                ->modalSubmitActionLabel('إلغاء')
                ->extraAttributes([
                    'class' => 'hover:scale-110 transition-transform',
                ]),
                
            Action::make('complete')
                ->icon('heroicon-o-check-badge')
                ->iconButton()
                ->tooltip('إكمال الجلسة')
                ->action(function (Booking $record) {
                    $record->update(['status' => 'completed']);
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'تم إكمال الجلسة بنجاح',
                    ]);
                })
                ->hidden(fn (Booking $record) => $record->status !== 'confirmed')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('إكمال الجلسة')
                ->modalDescription('هل تريد تأكيد إكمال هذه الجلسة؟')
                ->modalSubmitActionLabel('تأكيد')
                ->extraAttributes([
                    'class' => 'hover:scale-110 transition-transform',
                ]),
        ];
    }
    
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
    
    protected function isTableSearchable(): bool
    {
        return false;
    }
    
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [7, 10, 25, 50];
    }
}
