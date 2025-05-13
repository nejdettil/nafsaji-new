<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Contact;
use App\Models\Specialist;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';
    
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = -3;
    
    public function getGreeting(): string
    {
        $hour = Carbon::now()->format('H');
        
        if ($hour < 12) {
            return 'صباح الخير';
        } elseif ($hour < 17) {
            return 'مساء الخير';
        } else {
            return 'مساء الخير';
        }
    }
    
    public function getUserName(): string
    {
        return Auth::user()->name ?? 'مدير النظام';
    }
    
    public function getDate(): string
    {
        return Carbon::now()->locale('ar')->translatedFormat('l d F Y');
    }
    
    public function getAlerts(): array
    {
        $alerts = [];
        
        // التحقق من وجود جداول البيانات
        if (Schema::hasTable('bookings')) {
            $pendingBookings = Booking::where('status', 'pending')->count();
            if ($pendingBookings > 0) {
                $alerts[] = [
                    'message' => "لديك {$pendingBookings} حجز بانتظار التأكيد",
                    'icon' => 'heroicon-o-bell-alert',
                    'color' => 'warning',
                    'url' => '/filament-admin/bookings',
                ];
            }
        }
        
        if (Schema::hasTable('contacts')) {
            $unreadMessages = Contact::where('is_read', false)->count();
            if ($unreadMessages > 0) {
                $alerts[] = [
                    'message' => "لديك {$unreadMessages} رسالة جديدة",
                    'icon' => 'heroicon-o-envelope',
                    'color' => 'danger',
                    'url' => '/filament-admin/contacts',
                ];
            }
        }
        
        return $alerts;
    }
    
    public function getStats(): array
    {
        $stats = [];
        
        if (Schema::hasTable('users')) {
            $stats[] = [
                'label' => 'المستخدمين',
                'value' => User::where('role', 'user')->count(),
                'icon' => 'heroicon-m-user-group',
                'color' => 'primary',
            ];
        }
        
        if (Schema::hasTable('specialists')) {
            $stats[] = [
                'label' => 'المختصين',
                'value' => Specialist::count(),
                'icon' => 'heroicon-m-academic-cap',
                'color' => 'success',
            ];
        }
        
        if (Schema::hasTable('bookings')) {
            $stats[] = [
                'label' => 'الحجوزات',
                'value' => Booking::count(),
                'icon' => 'heroicon-m-calendar',
                'color' => 'warning',
            ];
        }
        
        return $stats;
    }
    
    public function getQuickLinks(): array
    {
        return [
            [
                'title' => 'إدارة المستخدمين',
                'icon' => 'heroicon-o-user-group',
                'url' => '/filament-admin/resources/users',
                'color' => 'primary',
            ],
            [
                'title' => 'إدارة المختصين',
                'icon' => 'heroicon-o-academic-cap',
                'url' => '/filament-admin/resources/specialists',
                'color' => 'success',
            ],
            [
                'title' => 'إدارة الحجوزات',
                'icon' => 'heroicon-o-calendar',
                'url' => '/filament-admin/bookings',
                'color' => 'warning',
            ],
            [
                'title' => 'إدارة المدفوعات',
                'icon' => 'heroicon-o-banknotes',
                'url' => '/filament-admin/payments',
                'color' => 'danger',
            ],
        ];
    }
    
    protected function getViewData(): array
    {
        return [
            'greeting' => $this->getGreeting(),
            'userName' => $this->getUserName(),
            'date' => $this->getDate(),
            'quickLinks' => $this->getQuickLinks(),
        ];
    }
}
