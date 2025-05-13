<?php

namespace App\Filament\SpecialistResources\BookingResource\Pages;

use App\Filament\SpecialistResources\BookingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('confirm')
                ->label('تأكيد الحجز')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function () {
                    $this->record->update(['status' => 'confirmed']);
                    
                    Notification::make()
                        ->title('تم تأكيد الحجز بنجاح')
                        ->success()
                        ->send();
                        
                    $this->redirect(BookingResource::getUrl());
                })
                ->visible(fn (): bool => $this->record->status === 'pending'),

            Actions\Action::make('complete')
                ->label('اكتمال الجلسة')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    $this->record->update(['status' => 'completed']);
                    
                    Notification::make()
                        ->title('تم تحديث حالة الحجز إلى مكتمل')
                        ->success()
                        ->send();
                        
                    $this->redirect(BookingResource::getUrl());
                })
                ->visible(fn (): bool => $this->record->status === 'confirmed'),

            Actions\Action::make('cancel')
                ->label('إلغاء الحجز')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->action(function () {
                    $this->record->update(['status' => 'canceled']);
                    
                    Notification::make()
                        ->title('تم إلغاء الحجز')
                        ->warning()
                        ->send();
                        
                    $this->redirect(BookingResource::getUrl());
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => in_array($this->record->status, ['pending', 'confirmed'])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
