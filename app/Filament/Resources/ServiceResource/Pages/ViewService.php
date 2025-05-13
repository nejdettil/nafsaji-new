<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use App\Models\Service;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('تعديل الخدمة'),
            Actions\Action::make('view_bookings')
                ->label('عرض الحجوزات')
                ->icon('heroicon-o-calendar')
                ->url(fn (): string => route('filament.admin.resources.bookings.index', [
                    'tableFilters[service][value]' => $this->record->id,
                ])),
            Actions\Action::make('toggle_featured')
                ->label(fn (): string => $this->record->is_featured ? 'إلغاء التمييز' : 'تمييز الخدمة')
                ->icon('heroicon-o-star')
                ->color(fn (): string => $this->record->is_featured ? 'gray' : 'warning')
                ->action(function (): void {
                    $this->record->update(['is_featured' => !$this->record->is_featured]);
                    $this->notification()->success('تم تحديث الخدمة بنجاح');
                }),
            Actions\Action::make('toggle_status')
                ->label(fn (): string => $this->record->is_active ? 'إيقاف الخدمة' : 'تنشيط الخدمة')
                ->icon(fn (): string => $this->record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->action(function (): void {
                    $this->record->update(['is_active' => !$this->record->is_active]);
                    $this->notification()->success('تم تحديث حالة الخدمة بنجاح');
                }),
            Actions\DeleteAction::make()
                ->label('حذف الخدمة'),
        ];
    }
}
