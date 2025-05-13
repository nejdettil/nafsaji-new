<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markAllAsRead')
                ->label('تحديد الكل كمقروء')
                ->icon('heroicon-o-check')
                ->action(function () {
                    $this->getResource()::getModel()::query()
                        ->where('is_read', false)
                        ->update(['is_read' => true]);
                        
                    $this->notify('success', 'تم تحديث جميع الرسائل كمقروءة بنجاح');
                }),
        ];
    }
}
