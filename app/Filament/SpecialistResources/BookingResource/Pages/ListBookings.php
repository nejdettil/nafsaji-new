<?php

namespace App\Filament\SpecialistResources\BookingResource\Pages;

use App\Filament\SpecialistResources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // غير مسموح للمختص بإنشاء حجوزات جديدة
        ];
    }
}
