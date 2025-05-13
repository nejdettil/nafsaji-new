<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    /**
     * الحجز الذي تم إنشاؤه
     *
     * @var \App\Models\Booking
     */
    protected $booking;

    /**
     * إنشاء مثيل جديد للإشعار
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
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
        $url = route('admin.bookings.show', $this->booking->id);

        return (new MailMessage)
                    ->subject(__('messages.new_booking_notification'))
                    ->greeting(__('messages.hello_admin'))
                    ->line(__('messages.new_booking_received'))
                    ->line(__('messages.booking_details'))
                    ->line(__('messages.from') . ': ' . $this->getUserName())
                    ->line(__('messages.specialist') . ': ' . $this->booking->specialist->name)
                    ->line(__('messages.service') . ': ' . $this->booking->service->name)
                    ->line(__('messages.date_time') . ': ' . date('Y-m-d', strtotime($this->booking->booking_date)) . ' ' . $this->booking->booking_time)
                    ->action(__('messages.view_booking_details'), $url)
                    ->line(__('messages.thank_you_for_using_platform'));
    }

    /**
     * الحصول على اسم المستخدم من الحجز
     * إما من المستخدم المرتبط أو من بيانات الضيف
     * 
     * @return string
     */
    protected function getUserName()
    {
        // إذا كان الحجز مرتبط بمستخدم مسجل
        if ($this->booking->user_id && $this->booking->user) {
            return $this->booking->user->name;
        }
        
        // إذا كان الحجز لضيف (غير مسجل)
        if ($this->booking->guest_details) {
            $guestDetails = is_string($this->booking->guest_details) 
                ? json_decode($this->booking->guest_details, true) 
                : $this->booking->guest_details;
            
            if (is_array($guestDetails) && isset($guestDetails['name'])) {
                return $guestDetails['name'];
            }
        }
        
        // إذا لم يتم العثور على اسم، إرجاع نص افتراضي
        return __('messages.guest');
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
            'booking_id' => $this->booking->id,
            'user_name' => $this->booking->user->name,
            'specialist_name' => $this->booking->specialist->name,
            'service_name' => $this->booking->service->name,
            'booking_date' => $this->booking->booking_date,
            'booking_time' => $this->booking->booking_time,
            'message' => __('messages.new_booking_received'),
            'url' => route('admin.bookings.show', $this->booking->id),
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
            'booking_id' => $this->booking->id,
            'user_name' => $this->booking->user->name,
            'specialist_name' => $this->booking->specialist->name,
            'service_name' => $this->booking->service->name,
            'booking_date' => $this->booking->booking_date,
            'booking_time' => $this->booking->booking_time,
        ];
    }
}
