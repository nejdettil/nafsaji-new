<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Schema;

class ImportantAlertsWidget extends Widget
{
    protected static string $view = 'filament.widgets.important-alerts';
    
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    
    protected function getViewData(): array
    {
        $alerts = [];
        
        // التحقق من وجود الجداول
        $hasBookingTable = Schema::hasTable('bookings');
        $hasContactTable = Schema::hasTable('contacts');
        
        // تنبيهات الحجوزات القادمة
        if ($hasBookingTable) {
            $upcomingBookings = Booking::where('booking_date', '>=', now()->toDateString())
                ->where('booking_date', '<=', now()->addDays(3)->toDateString())
                ->where('status', 'confirmed')
                ->count();
                
            if ($upcomingBookings > 0) {
                $alerts[] = [
                    'title' => 'حجوزات قادمة',
                    'message' => "لديك $upcomingBookings حجز مؤكد خلال الثلاثة أيام القادمة",
                    'icon' => 'heroicon-o-calendar',
                    'color' => 'warning',
                    'url' => route('filament.admin.resources.bookings.index'),
                ];
            }
            
            // تنبيهات الحجوزات المنتظرة التأكيد
            $pendingBookings = Booking::where('status', 'pending')->count();
            if ($pendingBookings > 0) {
                $alerts[] = [
                    'title' => 'حجوزات تنتظر التأكيد',
                    'message' => "يوجد $pendingBookings حجز بانتظار الموافقة",
                    'icon' => 'heroicon-o-clock',
                    'color' => 'primary',
                    'url' => route('filament.admin.resources.bookings.index'),
                ];
            }
        }
        
        // تنبيهات رسائل الاتصال غير المقروءة
        if ($hasContactTable) {
            $unreadContacts = Contact::where('is_read', false)->count();
            if ($unreadContacts > 0) {
                $alerts[] = [
                    'title' => 'رسائل غير مقروءة',
                    'message' => "لديك $unreadContacts رسالة جديدة لم تقرأها بعد",
                    'icon' => 'heroicon-o-envelope',
                    'color' => 'danger',
                    'url' => route('filament.admin.resources.contacts.index'),
                ];
            }
        }
        
        // تنبيهات المستخدمين الجدد
        if (Schema::hasTable('users')) {
            $newUsers = User::where('created_at', '>=', now()->subDays(7))
                ->where('role', 'user')
                ->count();
                
            if ($newUsers > 0) {
                $alerts[] = [
                    'title' => 'مستخدمين جدد',
                    'message' => "انضم $newUsers مستخدم جديد خلال الأسبوع الماضي",
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'success',
                    'url' => route('filament.admin.resources.users.index'),
                ];
            }
        }
        
        // إضافة تنبيه عام (رسالة ترحيبية إذا لم توجد تنبيهات)
        if (empty($alerts)) {
            $alerts[] = [
                'title' => 'مرحباً بك في نفسجي',
                'message' => 'لوحة التحكم جاهزة. لم يتم العثور على أي تنبيهات مهمة اليوم.',
                'icon' => 'heroicon-o-check-badge',
                'color' => 'success',
                'url' => null,
            ];
        }
        
        return [
            'date' => Carbon::now()->locale('ar')->translatedFormat('l d F Y'),
            'alerts' => $alerts,
        ];
    }
}
