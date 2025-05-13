<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string عنوان الإشعار
     */
    public $title;

    /**
     * @var string محتوى الإشعار
     */
    public $content;

    /**
     * @var array بيانات إضافية للإشعار
     */
    public $data;

    /**
     * إنشاء مثيل جديد للإشعار.
     */
    public function __construct(string $title, string $content, array $data = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->data = $data;
    }

    /**
     * الحصول على قنوات تسليم الإشعار.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * الحصول على محتوى رسالة البريد الإلكتروني.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting('مرحباً ' . ($notifiable->name ?? ''))
            ->line($this->content)
            ->action('عرض التفاصيل', url('/dashboard'))
            ->line('شكراً لاستخدامك منصة نفسجي!');
    }

    /**
     * الحصول على مصفوفة الإشعار التي سيتم تخزينها في قاعدة البيانات.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'data' => $this->data,
        ];
    }

    /**
     * الحصول على مصفوفة إشعار Firebase.
     *
     * @return array<string, mixed>
     */
    public function toFirebase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => strip_tags($this->content),
            'data' => $this->data,
        ];
    }
}
