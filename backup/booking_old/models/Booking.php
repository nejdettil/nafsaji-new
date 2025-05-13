<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'specialist_id',
        'user_id',
        'service_id',
        'booking_date',
        'booking_time',
        'date',
        'time',
        'status',
        'notes',
        'price',
        'duration',
        'admin_notes',
        'payment_status',
        'payment_method',
        'transaction_id',
        'guest_details' // حقل لتخزين بيانات الزائرين (الاسم، البريد، رقم الهاتف)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime',
        'guest_details' => 'json',
    ];

    /**
     * Get the specialist that owns the booking.
     */
    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service associated with this booking.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the formatted booking date and time.
     * 
     * @return string
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->booking_date->format('Y-m-d') . ' ' . 
               date('H:i', strtotime($this->booking_time));
    }

    /**
     * Get the status badge class based on the booking status.
     * 
     * @return string
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }
    
    /**
     * Get the payments for this booking.
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
