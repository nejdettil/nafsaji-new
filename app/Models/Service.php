<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'is_active'
    ];

    /**
     * Get the bookings for the service.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get service details as an array.
     * This is needed for the service details displayed on the show page.
     */
    public function getDetailsAttribute(): array
    {
        // Convert description to bullet points for display purposes
        // This is a simple implementation - in production, you might want to
        // store details separately or use a more sophisticated approach
        $points = explode("\n", $this->description);
        return array_filter($points, fn($point) => !empty(trim($point)));
    }
    
    /**
     * Get the specialists that are related to this service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specialists(): BelongsToMany
    {
        return $this->belongsToMany(Specialist::class, 'specialist_service');
    }
}
