<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('حذف')
                ->modalHeading('حذف الصلاحية')
                ->modalDescription('هل أنت متأكد من حذف هذه الصلاحية؟ سيتم إزالتها من جميع الأدوار المرتبطة بها.')
                ->modalSubmitActionLabel('نعم، حذف الصلاحية'),
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
            ->title('تم تحديث الصلاحية بنجاح')
            ->body('تم تحديث معلومات الصلاحية بنجاح.');
    }
}
