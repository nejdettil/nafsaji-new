<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification
{
    use Queueable;

    /**
     * رسالة الاتصال التي تم إرسالها
     *
     * @var \App\Models\Contact
     */
    protected $contact;

    /**
     * إنشاء مثيل جديد للإشعار
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * الحصول على قنوات توصيل الإشعار
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * الحصول على تمثيل الإشعار عبر البريد الإلكتروني
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('admin.contacts.show', $this->contact->id);

        return (new MailMessage)
                    ->subject(__('messages.new_contact_message'))
                    ->greeting(__('messages.hello_admin'))
                    ->line(__('messages.new_contact_message_received'))
                    ->line(__('messages.message_details'))
                    ->line(__('messages.from') . ': ' . $this->contact->name . ' (' . $this->contact->email . ')')
                    ->line(__('messages.subject') . ': ' . $this->contact->subject)
                    ->line(__('messages.message_preview') . ': ' . $this->contact->getPreview())
                    ->action(__('messages.view_message_details'), $url)
                    ->line(__('messages.thank_you_for_using_platform'));
    }

    /**
     * الحصول على تمثيل الإشعار عبر قاعدة البيانات
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'contact_id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message_preview' => $this->contact->getPreview(),
            'message' => __('messages.new_contact_message_received'),
            'url' => route('admin.contacts.show', $this->contact->id),
        ];
    }

    /**
     * الحصول على تمثيل الإشعار عبر المصفوفة
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'contact_id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message_preview' => $this->contact->getPreview(),
        ];
    }
}
