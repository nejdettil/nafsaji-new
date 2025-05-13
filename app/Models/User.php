<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Booking;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the bookings for the user.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * تحديد إذا كان المستخدم يمكنه الوصول إلى Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // الوصول حسب الأدوار ونوع لوحة التحكم
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }
        
        if ($panel->getId() === 'specialist') {
            return $this->role === 'specialist';
        }
        
        if ($panel->getId() === 'user') {
            return $this->role === 'user' || $this->role === 'admin' || $this->role === 'specialist';
        }
        
        return false;
    }
    
    /**
     * التحقق إذا كان المستخدم مدير نظام
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * التحقق إذا كان المستخدم مختص
     */
    public function isSpecialist(): bool
    {
        return $this->role === 'specialist';
    }
    
    /**
     * التحقق إذا كان المستخدم عادي
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
