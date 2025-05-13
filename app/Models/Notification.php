<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_templates';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'type',
        'recipients',
        'is_email',
        'is_push',
        'is_sms',
        'scheduled_at',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recipients' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_email' => 'boolean',
        'is_push' => 'boolean',
        'is_sms' => 'boolean',
    ];

    /**
     * Scope a query to only include notifications that haven't been sent yet.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->whereNull('sent_at');
    }

    /**
     * Scope a query to only include notifications that should be sent now.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('scheduled_at')
                ->orWhere('scheduled_at', '<=', now());
        })->whereNull('sent_at');
    }
    
    /**
     * الحصول على المستلمين المستهدفين حسب نوع الإشعار
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTargetedRecipients()
    {
        switch ($this->type) {
            case 'general':
                return User::all();
            case 'specialists':
                return User::where('role', 'specialist')->get();
            case 'users':
                return User::where('role', 'user')->get();
            case 'selected':
                return User::whereIn('id', $this->recipients ?? [])->get();
            default:
                return collect();
        }
    }
}
