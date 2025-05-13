<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotification extends EditRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('حذف الإشعار'),
            Actions\Action::make('send')
                ->label('إرسال الآن')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->action(function () {
                    // منطق إرسال الإشعارات هنا
                    // مثال: Notification::send($users, new \App\Notifications\GeneralNotification($this->record));
                    
                    // تحديث سجل الإشعار
                    $this->record->update(['sent_at' => now()]);
                    
                    $this->notify('success', 'تم إرسال الإشعار بنجاح');
                })
                ->requiresConfirmation()
                ->modalHeading('تأكيد إرسال الإشعار')
                ->modalDescription('هل أنت متأكد من إرسال هذا الإشعار؟ لا يمكن التراجع عن هذا الإجراء.')
                ->modalSubmitActionLabel('نعم، إرسال الآن')
                ->hidden(fn () => $this->record->sent_at !== null),
        ];
    }
}
