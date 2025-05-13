<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الخصائص التي يمكن تعيينها بشكل جماعي.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'specialist_id',
        'service_id',
        'booking_date',
        'booking_time',
        'duration',
        'price',
        'status', // pending, confirmed, completed, cancelled
        'payment_status', // unpaid, paid, refunded
        'payment_method', // online, cash, etc.
        'transaction_id',
        'stripe_payment_id',
        'notes',
        'guest_details',
        'metadata',
    ];

    /**
     * الخصائص التي يجب تحويلها إلى أنواع محددة.
     *
     * @var array
     */
    protected $casts = [
        'booking_date' => 'date',
        'guest_details' => 'array',
        'metadata' => 'array',
    ];

    /**
     * علاقة مع المستخدم.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة مع المتخصص.
     */
    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    /**
     * علاقة مع الخدمة.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * علاقة مع المدفوعات.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * تحديد ما إذا كان الحجز قابلاً للإلغاء.
     * يمكن إلغاء الحجوزات فقط إذا كانت في حالة معلقة أو مؤكدة
     * وإذا كان تاريخ الحجز لم يمر بعد.
     *
     * @return bool
     */
    public function isCancellable()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->booking_date->isFuture();
    }

    /**
     * تحديد ما إذا كان الحجز قد تم دفعه.
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * تحديد ما إذا كان الحجز للضيوف (غير المسجلين).
     *
     * @return bool
     */
    public function isGuestBooking()
    {
        return is_null($this->user_id) && !is_null($this->guest_details);
    }

    /**
     * الحصول على اسم العميل (سواء كان مستخدم مسجل أو ضيف).
     *
     * @return string
     */
    public function getCustomerName()
    {
        if ($this->user) {
            return $this->user->name;
        }

        if ($this->isGuestBooking() && isset($this->guest_details['name'])) {
            return $this->guest_details['name'];
        }

        return 'غير معروف';
    }

    /**
     * الحصول على بريد العميل الإلكتروني (سواء كان مستخدم مسجل أو ضيف).
     *
     * @return string|null
     */
    public function getCustomerEmail()
    {
        if ($this->user) {
            return $this->user->email;
        }

        if ($this->isGuestBooking() && isset($this->guest_details['email'])) {
            return $this->guest_details['email'];
        }

        return null;
    }

    /**
     * الحصول على هاتف العميل (سواء كان مستخدم مسجل أو ضيف).
     *
     * @return string|null
     */
    public function getCustomerPhone()
    {
        if ($this->user) {
            return $this->user->phone;
        }

        if ($this->isGuestBooking() && isset($this->guest_details['phone'])) {
            return $this->guest_details['phone'];
        }

        return null;
    }
}
