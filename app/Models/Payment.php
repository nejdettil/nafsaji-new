<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'stripe_payment_id',
        'stripe_customer_id',
        'payable_type',
        'payable_id',
        'metadata',
        'description',
        'receipt_url',
        'error_message',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];
    
    /**
     * Payment statuses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the payable model (polymorphic relationship).
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Determine if the payment was successful.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    
    /**
     * Determine if the payment has failed.
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
    
    /**
     * Determine if the payment is still processing.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
