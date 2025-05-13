@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'حجز موعد' : 'Book Appointment')

@section('styles')
<style>
    /* أنماط مخصصة لصفحة الحجز */
    .booking-progress-step {
        position: relative;
        z-index: 1;
    }
    
    .booking-progress-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: -1;
        transform: translateY(-50%);
    }
    
    .booking-progress-line-active {
        background-color: #8b5cf6;
    }
    
    .time-slot {
        transition: all 0.2s ease;
    }
    
    .time-slot:hover {
        border-color: #8b5cf6;
        background-color: #f5f3ff;
    }
    
    .time-slot.active {
        background-color: #8b5cf6;
        color: white;
        border-color: #8b5cf6;
    }
    
    /* للتقويم */
    .calendar-day {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .calendar-day:hover {
        background-color: #f5f3ff;
    }
    
    .calendar-day.active {
        background-color: #8b5cf6;
        color: white;
    }
    
    .calendar-day.disabled {
        color: #d1d5db;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Hero Section with Specialist Info -->
    <div class="bg-gradient-to-r from-purple-700 to-indigo-800 py-10 px-4 text-white">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="md:w-7/12">
                    <h1 class="text-3xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'حجز موعد' : 'Book Appointment' }}</h1>
                    <p class="text-purple-100 text-lg mb-4">{{ app()->getLocale() == 'ar' ? 'احجز جلستك مع المتخصص المختار' : 'Schedule your session with your chosen specialist' }}</p>
                    
                    <!-- Breadcrumbs -->
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <a href="/" class="inline-flex items-center text-sm font-medium text-purple-100 hover:text-white">
                                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="/specialists" class="ms-1 text-sm font-medium text-purple-100 hover:text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'المتخصصين' : 'Specialists' }}</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'حجز موعد' : 'Booking' }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <!-- Specialist Card - Summary -->
                <div class="md:w-5/12">
                    <div class="bg-white rounded-xl p-4 shadow-lg text-gray-800 flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                            {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold text-lg text-gray-900">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</h2>
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
            </div>
        </div>
    </div>
    
    <!-- Booking Progress Indicator -->
    <div class="container mx-auto px-4 py-8">
        <div class="relative mb-12">
            <!-- Progress Line -->
            <div class="booking-progress-line"></div>
            <div class="booking-progress-line-active" style="width: 25%;"></div>
            
            <!-- Steps -->
            <div class="flex justify-between">
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'اختيار الموعد' : 'Select Date & Time' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="text-sm font-medium text-gray-500">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="text-sm font-medium text-gray-500">{{ app()->getLocale() == 'ar' ? 'الدفع' : 'Payment' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-gray-500">{{ app()->getLocale() == 'ar' ? 'التأكيد' : 'Confirmation' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Booking Form - Step 1: Date & Time Selection -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ app()->getLocale() == 'ar' ? 'اختر التاريخ والوقت' : 'Select Date & Time' }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Calendar Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</h3>
                    
                    <!-- Month Navigation -->
                    <div class="flex justify-between items-center mb-4">
                        <button class="text-gray-500 hover:text-purple-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h4 class="text-lg font-medium">{{ app()->getLocale() == 'ar' ? 'مايو 2025' : 'May 2025' }}</h4>
                        <button class="text-gray-500 hover:text-purple-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- Days of Week -->
                    <div class="grid grid-cols-7 gap-1 mb-2 text-center">
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'أحد' : 'Su' }}</div>
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'إثن' : 'Mo' }}</div>
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'ثلا' : 'Tu' }}</div>
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'أرب' : 'We' }}</div>
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'خمي' : 'Th' }}</div>
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'جمع' : 'Fr' }}</div>
                        <div class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'سبت' : 'Sa' }}</div>
                    </div>
                    
                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-1">
                        <!-- Previous Month (Disabled) -->
                        <div class="calendar-day disabled">28</div>
                        <div class="calendar-day disabled">29</div>
                        <div class="calendar-day disabled">30</div>
                        
                        <!-- Current Month -->
                        <div class="calendar-day">1</div>
                        <div class="calendar-day">2</div>
                        <div class="calendar-day">3</div>
                        <div class="calendar-day">4</div>
                        <div class="calendar-day">5</div>
                        <div class="calendar-day">6</div>
                        <div class="calendar-day">7</div>
                        <div class="calendar-day">8</div>
                        <div class="calendar-day">9</div>
                        <div class="calendar-day">10</div>
                        <div class="calendar-day">11</div>
                        <div class="calendar-day active">12</div>
                        <div class="calendar-day">13</div>
                        <div class="calendar-day">14</div>
                        <div class="calendar-day">15</div>
                        <div class="calendar-day">16</div>
                        <div class="calendar-day">17</div>
                        <div class="calendar-day">18</div>
                        <div class="calendar-day">19</div>
                        <div class="calendar-day">20</div>
                        <div class="calendar-day">21</div>
                        <div class="calendar-day">22</div>
                        <div class="calendar-day">23</div>
                        <div class="calendar-day">24</div>
                        <div class="calendar-day">25</div>
                        <div class="calendar-day">26</div>
                        <div class="calendar-day">27</div>
                        <div class="calendar-day">28</div>
                        <div class="calendar-day">29</div>
                        <div class="calendar-day">30</div>
                        <div class="calendar-day">31</div>
                        
                        <!-- Next Month (Disabled) -->
                        <div class="calendar-day disabled">1</div>
                        <div class="calendar-day disabled">2</div>
                        <div class="calendar-day disabled">3</div>
                        <div class="calendar-day disabled">4</div>
                    </div>
                </div>
                
                <!-- Time Slots Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ app()->getLocale() == 'ar' ? 'الوقت المتاح' : 'Available Time' }}</h3>
                    <p class="text-gray-600 mb-4">{{ app()->getLocale() == 'ar' ? 'اختر الوقت المناسب لك يوم 12 مايو 2025' : 'Select your preferred time on May 12, 2025' }}</p>
                    
                    <!-- Morning Slots -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'صباحاً' : 'Morning' }}</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">09:00 AM</span>
                            </div>
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">10:00 AM</span>
                            </div>
                            <div class="time-slot active border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">11:00 AM</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Afternoon Slots -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'بعد الظهر' : 'Afternoon' }}</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">01:00 PM</span>
                            </div>
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">02:00 PM</span>
                            </div>
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">03:00 PM</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Evening Slots -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ app()->getLocale() == 'ar' ? 'مساءً' : 'Evening' }}</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">05:00 PM</span>
                            </div>
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center cursor-pointer">
                                <span class="text-sm">06:00 PM</span>
                            </div>
                            <div class="time-slot border border-gray-200 rounded-lg py-2 px-3 text-center text-gray-400 cursor-not-allowed">
                                <span class="text-sm">07:00 PM</span>
                                <span class="block text-xs text-red-500">{{ app()->getLocale() == 'ar' ? 'محجوز' : 'Booked' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking Summary -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ app()->getLocale() == 'ar' ? 'ملخص الحجز' : 'Booking Summary' }}</h2>
            
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'المتخصص' : 'Specialist' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? '12 مايو 2025' : 'May 12, 2025' }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'الوقت' : 'Time' }}</span>
                    <span class="font-medium">11:00 AM</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'المدة' : 'Duration' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? '60 دقيقة' : '60 minutes' }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-3 mt-3">
                    <span class="text-gray-800 font-semibold">{{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}</span>
                    <span class="font-bold text-lg">{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('specialists') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
            <a href="{{ route('booking.details') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white font-medium hover:opacity-90">
                {{ app()->getLocale() == 'ar' ? 'المتابعة إلى تفاصيل الجلسة' : 'Continue to Session Details' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // سكريبت لتفعيل وظائف الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل اختيار التاريخ
        const calendarDays = document.querySelectorAll('.calendar-day:not(.disabled)');
        calendarDays.forEach(day => {
            day.addEventListener('click', function() {
                // إزالة الفئة النشطة من جميع الأيام
                calendarDays.forEach(d => d.classList.remove('active'));
                // إضافة الفئة النشطة للعنصر المحدد
                this.classList.add('active');
            });
        });
        
        // تفعيل اختيار الوقت
        const timeSlots = document.querySelectorAll('.time-slot:not(.cursor-not-allowed)');
        timeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                // إزالة الفئة النشطة من جميع الفترات الزمنية
                timeSlots.forEach(s => s.classList.remove('active'));
                // إضافة الفئة النشطة للعنصر المحدد
                this.classList.add('active');
            });
        });
    });
</script>
@endsection
