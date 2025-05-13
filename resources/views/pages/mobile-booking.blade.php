@extends('layouts.app')

@section('title', __('pages.booking.title'))

@section('meta')
<!-- تهيئة التطبيق المحمول - Mobile app configuration -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
<meta name="theme-color" content="#6366f1">
<link rel="manifest" href="{{ asset('manifest.json') }}">
<link rel="apple-touch-icon" href="{{ asset('images/app-icon.png') }}">
@endsection

@section('styles')
<style>
    /* تصميم مخصص للجوال - Mobile App Style */
    body.mobile-app-view {
        margin: 0;
        padding: 0;
        background-color: #f7f8fc;
    }
    
    .app-section {
        margin-bottom: 24px;
        padding: 0 16px;
    }
    
    .app-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 16px;
        overflow: hidden;
    }
    
    .app-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #333;
    }
    
    .app-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 16px;
    }
    
    /* مؤشر التقدم */
    .booking-steps {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
    }
    
    .booking-steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 0;
    }
    
    .booking-steps .progress-line {
        position: absolute;
        top: 15px;
        left: 0;
        height: 2px;
        background-color: #6366f1;
        z-index: 1;
        width: 25%;
        transition: width 0.3s ease;
    }
    
    .step {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: white;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        position: relative;
        z-index: 2;
    }
    
    .step.active {
        background-color: #6366f1;
        border-color: #6366f1;
        color: white;
    }
    
    .step.completed {
        background-color: #6366f1;
        border-color: #6366f1;
        color: white;
    }
    
    /* أنماط قسم التقويم والأوقات */
    .calendar-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -16px;
        padding: 0 16px;
    }
    
    .calendar-strip {
        display: flex;
        padding: 10px 0;
    }
    
    .calendar-day {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 0;
        width: 60px;
        flex-shrink: 0;
    }
    
    .day-number {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .day-name {
        font-size: 12px;
        color: #6b7280;
    }
    
    .calendar-day.active .day-number {
        background-color: #6366f1;
        color: white;
    }
    
    .calendar-day.disabled {
        opacity: 0.4;
    }
    
    .time-slots {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 16px;
    }
    
    .time-slot {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .time-slot.active {
        border-color: #6366f1;
        background-color: #eff6ff;
    }
    
    .time-slot.disabled {
        color: #9ca3af;
        background-color: #f3f4f6;
        pointer-events: none;
    }
    
    .slot-time {
        font-weight: 600;
        color: #374151;
    }
    
    .slot-time-active {
        color: #6366f1;
    }
    
    .slot-availability {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    /* زر العمل الرئيسي */
    .app-main-button {
        display: block;
        width: 100%;
        padding: 14px;
        background: linear-gradient(to right, #6366f1, #8b5cf6);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
    }
    
    .app-main-button:active {
        transform: scale(0.98);
    }
    
    .app-secondary-button {
        display: block;
        width: 100%;
        padding: 14px;
        background: white;
        color: #6366f1;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<!-- عرض التطبيق المخصص للجوال (يظهر فقط على شاشات الجوال) - Mobile App View -->
<div class="block">
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content pb-20">
        <!-- قسم العنوان والمتخصص - Header & Specialist Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 pt-6 pb-6 px-4 text-white">
            <h1 class="text-2xl font-bold mb-2">{{ __('pages.booking.title') }}</h1>
            <p class="text-indigo-100 mb-6">{{ __('pages.booking.subtitle') }}</p>
            
            <!-- بطاقة معلومات المتخصص - Specialist Card -->
            <div class="bg-white rounded-xl p-4 text-gray-800 flex items-center shadow-lg">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                </div>
                <div class="ms-3 flex-1">
                    <h2 class="font-bold text-gray-900">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</h2>
                    <p class="text-sm text-gray-600">{{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}</p>
                    <div class="flex items-center mt-1">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-500 text-xs me-1"></i>
                            <span class="text-sm font-medium">4.8</span>
                        </div>
                        <span class="mx-2 text-gray-300">|</span>
                        <div class="text-sm text-gray-600">
                            {{ app()->getLocale() == 'ar' ? '10 سنوات خبرة' : '10 years experience' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- مؤشر خطوات الحجز - Booking Steps Indicator -->
        <div class="app-section mt-6">
            <div class="booking-steps">
                <div class="progress-line" style="width: 25%"></div>
                <div class="step active">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            <div class="flex justify-between text-sm mt-2 px-3">
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.date') }}</span>
                <span class="text-gray-500">{{ __('pages.booking.steps.details') }}</span>
                <span class="text-gray-500">{{ __('pages.booking.steps.payment') }}</span>
                <span class="text-gray-500">{{ __('pages.booking.steps.confirm') }}</span>
            </div>
        </div>
        
        <!-- اختيار التاريخ - Date Selection -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.select_date') }}</h2>
            
            <div class="app-card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'مايو 2025' : 'May 2025' }}</h3>
                    <div>
                        <button class="p-2 rounded-full bg-gray-100 text-gray-600 mr-2">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        <button class="p-2 rounded-full bg-gray-100 text-gray-600">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <div class="calendar-container">
                    <div class="calendar-strip">
                        <div class="calendar-day disabled">
                            <div class="day-number">10</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'سبت' : 'Sat' }}</div>
                        </div>
                        <div class="calendar-day disabled">
                            <div class="day-number">11</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'أحد' : 'Sun' }}</div>
                        </div>
                        <div class="calendar-day active">
                            <div class="day-number">12</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'اثن' : 'Mon' }}</div>
                        </div>
                        <div class="calendar-day">
                            <div class="day-number">13</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'ثلا' : 'Tue' }}</div>
                        </div>
                        <div class="calendar-day">
                            <div class="day-number">14</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'أرب' : 'Wed' }}</div>
                        </div>
                        <div class="calendar-day">
                            <div class="day-number">15</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'خمي' : 'Thu' }}</div>
                        </div>
                        <div class="calendar-day">
                            <div class="day-number">16</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'جمع' : 'Fri' }}</div>
                        </div>
                        <div class="calendar-day">
                            <div class="day-number">17</div>
                            <div class="day-name">{{ app()->getLocale() == 'ar' ? 'سبت' : 'Sat' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- اختيار الوقت - Time Selection -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.select_time') }}</h2>
            
            <div class="app-card">
                <h3 class="font-semibold text-gray-800 mb-2">{{ app()->getLocale() == 'ar' ? 'الأوقات المتاحة في 12 مايو' : 'Available Times on May 12' }}</h3>
                
                <!-- أوقات الصباح - Morning -->
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">{{ app()->getLocale() == 'ar' ? 'صباحاً' : 'Morning' }}</h4>
                    <div class="time-slots">
                        <div class="time-slot">
                            <div class="slot-time">09:00 AM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'متاح' : 'Available' }}</div>
                        </div>
                        <div class="time-slot active">
                            <div class="slot-time slot-time-active">10:00 AM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'متاح' : 'Available' }}</div>
                        </div>
                        <div class="time-slot">
                            <div class="slot-time">11:00 AM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'متاح' : 'Available' }}</div>
                        </div>
                        <div class="time-slot disabled">
                            <div class="slot-time">12:00 PM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'محجوز' : 'Booked' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- أوقات بعد الظهر - Afternoon -->
                <div>
                    <h4 class="text-sm font-medium text-gray-600 mb-2">{{ app()->getLocale() == 'ar' ? 'مساءً' : 'Afternoon' }}</h4>
                    <div class="time-slots">
                        <div class="time-slot">
                            <div class="slot-time">02:00 PM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'متاح' : 'Available' }}</div>
                        </div>
                        <div class="time-slot disabled">
                            <div class="slot-time">03:00 PM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'محجوز' : 'Booked' }}</div>
                        </div>
                        <div class="time-slot">
                            <div class="slot-time">04:00 PM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'متاح' : 'Available' }}</div>
                        </div>
                        <div class="time-slot">
                            <div class="slot-time">05:00 PM</div>
                            <div class="slot-availability">{{ app()->getLocale() == 'ar' ? 'متاح' : 'Available' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ملخص الحجز - Booking Summary -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.summary') }}</h2>
            
            <div class="app-card">
                <div class="flex items-start mb-4">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-indigo-600 text-sm"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'موعدك' : 'Your Appointment' }}</h3>
                        <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? '12 مايو 2025 • 10:00 صباحاً' : 'May 12, 2025 • 10:00 AM' }}</p>
                    </div>
                </div>
                <div class="flex items-start mb-4">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-md text-indigo-600 text-sm"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'المتخصص' : 'Specialist' }}</h3>
                        <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد • طبيب نفسي' : 'Dr. John Smith • Psychiatrist' }}</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hourglass-half text-indigo-600 text-sm"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'مدة الجلسة' : 'Session Duration' }}</h3>
                        <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? '60 دقيقة' : '60 minutes' }}</p>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 mt-4 pt-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}</span>
                        <span class="font-semibold">{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</span>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('mobile.booking.details') }}" class="app-main-button">
                {{ app()->getLocale() == 'ar' ? 'المتابعة إلى التفاصيل' : 'Continue to Details' }}
                <i class="fas fa-chevron-right ms-1 text-sm"></i>
            </a>
            
            <a href="{{ route('mobile.home') }}" class="app-secondary-button">
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل اختيار التاريخ
        const days = document.querySelectorAll('.calendar-day:not(.disabled)');
        days.forEach(day => {
            day.addEventListener('click', function() {
                days.forEach(d => d.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // تفعيل اختيار الوقت
        const timeSlots = document.querySelectorAll('.time-slot:not(.disabled)');
        timeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                timeSlots.forEach(s => {
                    s.classList.remove('active');
                    s.querySelector('.slot-time').classList.remove('slot-time-active');
                });
                this.classList.add('active');
                this.querySelector('.slot-time').classList.add('slot-time-active');
            });
        });
    });
</script>
@endsection
