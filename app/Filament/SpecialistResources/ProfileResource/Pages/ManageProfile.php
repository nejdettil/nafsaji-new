<?php

namespace App\Filament\SpecialistResources\ProfileResource\Pages;

use App\Filament\SpecialistResources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageProfile extends ManageRecords
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('تعديل الملف الشخصي')
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['password'] = null;
                    $data['password_confirmation'] = null;

                    return $data;
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        return auth()->user();
    }
}
