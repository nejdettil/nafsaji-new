<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'speciality',
        'avatar',
        'is_featured',
        'is_active'
    ];

    /**
     * Get the bookings for the specialist.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the specializations for this specialist as an array.
     * This is needed for the show blade template.
     */
    public function getSpecializationsAttribute(): array
    {
        // This could be refactored to use a proper specializations table in the future
        // For now, we'll just split the speciality field by commas for display
        return array_map('trim', explode(',', $this->speciality));
    }
    
    /**
     * Scope a query to only include active specialists.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    
    /**
     * Get the services for the specialist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'specialist_service');
    }
    
    /**
     * Get the user that owns the specialist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the name attribute from user relation.
     *
     * @return string|null
     */
    public function getNameAttribute()
    {
        // الحصول على اسم المستخدم المرتبط إذا توفر، وإلا استخدام التخصص كبديل
        return $this->user ? $this->user->name : $this->specialization;
    }
    
    /**
     * Get the title/role of the specialist.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        // إذا كان الحقل specialization متوفراً استخدمه، وإلا استخدم نصاً افتراضياً
        return $this->specialization ?? __('messages.specialist');
    }
    
    /**
     * Get the bio of the specialist.
     *
     * @return string
     */
    public function getBioAttribute($value)
    {
        // إذا كان الوصف متوفراً استخدمه، وإلا استخدم نصاً افتراضياً
        return $value ?? $this->qualification ?? __('messages.specialist_bio_placeholder');
    }
}
