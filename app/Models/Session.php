<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nafsaji_sessions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'status',
        'therapist_notes',
        'patient_feedback',
        'duration'
    ];
    
    /**
     * Get the booking that the session belongs to.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
