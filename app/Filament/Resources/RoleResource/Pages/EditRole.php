<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('عرض'),
            Actions\DeleteAction::make()
                ->label('حذف')
                ->modalHeading('حذف الدور')
                ->modalDescription('هل أنت متأكد من حذف هذا الدور؟ سيتم إزالة الصلاحيات من المستخدمين المرتبطين به.')
                ->modalSubmitActionLabel('نعم، حذف الدور'),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم تحديث الدور بنجاح')
            ->body('تم تحديث الدور وصلاحياته بنجاح.');
    }
}
