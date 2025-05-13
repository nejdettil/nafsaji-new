<?php

namespace App\Filament\UserResources\BookingResource\Pages;

use App\Filament\UserResources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إنشاء حجز جديد'),
        ];
    }
}
