<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use App\Models\User;
use App\Models\Specialist;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\GeneralNotification;
use Filament\Notifications\Notification;

class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('تعديل الإشعار'),
            Actions\Action::make('send_now')
                ->label('إرسال الآن')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->action(function () {
                    $notification = $this->record;
                    $recipients = $this->getRecipients($notification);
                    
                    if (empty($recipients)) {
                        Notification::make()
                            ->title('لا يوجد مستلمين')
                            ->warning()
                            ->send();
                        return;
                    }
                    
                    $this->sendNotification($notification, $recipients);
                    
                    $notification->update(['sent_at' => now()]);
                    
                    Notification::make()
                        ->title('تم إرسال الإشعار بنجاح')
                        ->success()
                        ->send();
                    
                    $this->redirect(NotificationResource::getUrl('index'));
                })
                ->requiresConfirmation()
                ->modalHeading('تأكيد إرسال الإشعار')
                ->modalDescription('هل أنت متأكد من إرسال هذا الإشعار الآن؟')
                ->modalSubmitActionLabel('نعم، أرسل الآن')
                ->hidden(fn () => $this->record->sent_at !== null),
                
            Actions\DeleteAction::make()
                ->label('حذف الإشعار'),
        ];
    }
    
    protected function getRecipients($notification)
    {
        switch ($notification->type) {
            case 'general':
                return User::where('role', '!=', 'admin')->get();
            case 'specialists':
                return User::whereHas('specialist')->get();
            case 'users':
                return User::where('role', 'user')->get();
            case 'selected':
                return User::whereIn('id', $notification->recipients ?? [])->get();
            default:
                return collect();
        }
    }
    
    protected function sendNotification($notificationRecord, $recipients)
    {
        // إرسال الإشعار داخل النظام
        NotificationFacade::send($recipients, new GeneralNotification(
            $notificationRecord->title,
            $notificationRecord->content
        ));
        
        // إذا كان الإشعار يجب إرساله عبر البريد
        if ($notificationRecord->is_email) {
            // أضف هنا منطق إرسال البريد الإلكتروني
        }
        
        // إذا كان الإشعار يجب إرساله عبر الرسائل القصيرة
        if ($notificationRecord->is_sms) {
            // أضف هنا منطق إرسال الرسائل القصيرة
        }
    }
}
