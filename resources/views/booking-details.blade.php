@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details')

@section('styles')
<style>
    /* أنماط مخصصة لصفحة تفاصيل الحجز */
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
    
    .session-type-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .session-type-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .session-type-card.selected {
        border-color: #8b5cf6;
        background-color: #f5f3ff;
    }
    
    .session-type-card.selected .check-icon {
        visibility: visible;
    }
    
    .check-icon {
        visibility: hidden;
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
                    <h1 class="text-3xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</h1>
                    <p class="text-purple-100 text-lg mb-4">{{ app()->getLocale() == 'ar' ? 'أخبرنا المزيد عن احتياجاتك' : 'Tell us more about your needs' }}</p>
                    
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
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="/booking" class="ms-1 text-sm font-medium text-purple-100 hover:text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'حجز موعد' : 'Booking' }}</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</span>
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
                                <div class="text-sm text-gray-600">
                                    {{ app()->getLocale() == 'ar' ? '12 مايو 2025 - 11:00 صباحًا' : 'May 12, 2025 - 11:00 AM' }}
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
            <div class="booking-progress-line-active" style="width: 50%;"></div>
            
            <!-- Steps -->
            <div class="flex justify-between">
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'اختيار الموعد' : 'Select Date & Time' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</div>
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
        
        <!-- Session Type Selection -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ app()->getLocale() == 'ar' ? 'اختر نوع الجلسة' : 'Choose Session Type' }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Video Session Card -->
                <div class="session-type-card selected border-2 rounded-xl p-5 relative">
                    <div class="check-icon visible absolute top-4 right-4 w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-4">
                            <i class="fas fa-video"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'تواصل مباشر مع المتخصص عبر مكالمة فيديو' : 'Direct communication with specialist via video call' }}</p>
                        </div>
                    </div>
                    
                    <ul class="space-y-2 mb-4">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700 text-sm">{{ app()->getLocale() == 'ar' ? 'جلسة خاصة مع متخصص مؤهل' : 'Private session with qualified specialist' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700 text-sm">{{ app()->getLocale() == 'ar' ? 'حجز بسيط وسريع' : 'Simple and quick booking' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700 text-sm">{{ app()->getLocale() == 'ar' ? 'مدة الجلسة: 60 دقيقة' : 'Session duration: 60 minutes' }}</span>
                        </li>
                    </ul>
                    
                    <div class="text-purple-700 font-bold text-lg">{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</div>
                </div>
                
                <!-- Voice Session Card -->
                <div class="session-type-card border-2 rounded-xl p-5 relative">
                    <div class="check-icon absolute top-4 right-4 w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-4">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">{{ app()->getLocale() == 'ar' ? 'جلسة صوتية' : 'Voice Session' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'تواصل مباشر مع المتخصص عبر مكالمة صوتية' : 'Direct communication with specialist via voice call' }}</p>
                        </div>
                    </div>
                    
                    <ul class="space-y-2 mb-4">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700 text-sm">{{ app()->getLocale() == 'ar' ? 'مناسبة للأشخاص الذين يفضلون الخصوصية' : 'Suitable for people who prefer privacy' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700 text-sm">{{ app()->getLocale() == 'ar' ? 'أقل استهلاكًا للبيانات' : 'Less data consumption' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700 text-sm">{{ app()->getLocale() == 'ar' ? 'مدة الجلسة: 60 دقيقة' : 'Session duration: 60 minutes' }}</span>
                        </li>
                    </ul>
                    
                    <div class="text-blue-700 font-bold text-lg">{{ app()->getLocale() == 'ar' ? '200 ريال' : '200 SAR' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Session Details Form -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</h2>
            
            <form>
                <!-- Reason for Session -->
                <div class="mb-6">
                    <label for="reason" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'سبب الجلسة' : 'Reason for Session' }} <span class="text-red-500">*</span></label>
                    <select id="reason" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option selected disabled>{{ app()->getLocale() == 'ar' ? 'اختر سبب الجلسة' : 'Select reason for session' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'القلق' : 'Anxiety' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'الاكتئاب' : 'Depression' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'الضغط النفسي' : 'Stress' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'اضطرابات النوم' : 'Sleep disorders' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'مشاكل العلاقات' : 'Relationship issues' }}</option>
                        <option>{{ app()->getLocale() == 'ar' ? 'أخرى' : 'Other' }}</option>
                    </select>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'وصف المشكلة' : 'Problem Description' }}</label>
                    <textarea id="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="{{ app()->getLocale() == 'ar' ? 'صف مشكلتك بإيجاز...' : 'Briefly describe your problem...' }}"></textarea>
                    <p class="text-gray-500 text-sm mt-1">{{ app()->getLocale() == 'ar' ? 'هذه المعلومات ستساعد المتخصص في التحضير لجلستك.' : 'This information will help the specialist prepare for your session.' }}</p>
                </div>
                
                <!-- Previous Experience -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'هل سبق وتلقيت استشارات نفسية؟' : 'Have you received psychological counseling before?' }}</label>
                    <div class="flex space-x-4 rtl:space-x-reverse">
                        <label class="flex items-center">
                            <input type="radio" name="previous_experience" class="form-radio h-5 w-5 text-purple-600" checked>
                            <span class="ml-2 text-gray-700">{{ app()->getLocale() == 'ar' ? 'نعم' : 'Yes' }}</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="previous_experience" class="form-radio h-5 w-5 text-purple-600">
                            <span class="ml-2 text-gray-700">{{ app()->getLocale() == 'ar' ? 'لا' : 'No' }}</span>
                        </label>
                    </div>
                </div>
                
                <!-- Special Requests -->
                <div class="mb-6">
                    <label for="special_requests" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'طلبات خاصة' : 'Special Requests' }}</label>
                    <textarea id="special_requests" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="{{ app()->getLocale() == 'ar' ? 'أي طلبات أو ملاحظات إضافية...' : 'Any additional requests or notes...' }}"></textarea>
                </div>
            </form>
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
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'نوع الجلسة' : 'Session Type' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}</span>
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
            <a href="{{ route('booking.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
            <a href="{{ route('booking.payment') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white font-medium hover:opacity-90">
                {{ app()->getLocale() == 'ar' ? 'المتابعة إلى الدفع' : 'Continue to Payment' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // سكريبت لتفعيل وظائف الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل اختيار نوع الجلسة
        const sessionTypeCards = document.querySelectorAll('.session-type-card');
        sessionTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                // إزالة الفئة النشطة من جميع البطاقات
                sessionTypeCards.forEach(c => c.classList.remove('selected'));
                // إضافة الفئة النشطة للبطاقة المحددة
                this.classList.add('selected');
            });
        });
    });
</script>
@endsection
