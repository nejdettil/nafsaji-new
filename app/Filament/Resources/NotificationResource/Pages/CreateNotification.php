<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function afterCreate(): void
    {
        $notification = $this->record;
        
        // تنفيذ منطق الإرسال هنا إذا كان الإشعار للإرسال الفوري
        if (!$notification->scheduled_at) {
            // منطق إرسال الإشعارات حسب نوع الإشعار وطريقة الإرسال
            $notification->update(['sent_at' => now()]);
        }
    }
}
