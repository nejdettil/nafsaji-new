<?php

namespace App\Filament\UserResources\BookingResource\Pages;

use App\Filament\UserResources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cancel')
                ->label('إلغاء الحجز')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->action(function () {
                    $this->record->update(['status' => 'canceled']);
                    $this->redirect(BookingResource::getUrl());
                })
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'pending'),
        ];
    }
}
