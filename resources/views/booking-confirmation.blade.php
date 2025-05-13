@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تأكيد الحجز' : 'Booking Confirmation')

@section('styles')
<style>
    /* أنماط مخصصة لصفحة تأكيد الحجز */
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
    
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4CAF50;
    }
    
    .success-checkmark .check-icon {
        width: 60px;
        height: 30px;
        display: block;
        margin: 0 auto;
        box-sizing: content-box;
        transform: rotate(45deg);
        margin-top: 25px;
    }
    
    .success-checkmark .check-icon::before {
        content: '';
        width: 4px;
        height: 30px;
        background-color: #4CAF50;
        position: absolute;
        left: 16px;
        top: 0px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    
    .success-checkmark .check-icon::after {
        content: '';
        width: 30px;
        height: 4px;
        background-color: #4CAF50;
        position: absolute;
        left: 0px;
        top: 26px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Hero Section with Success Message -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 py-10 px-4 text-white">
        <div class="container mx-auto text-center">
            <div class="success-checkmark">
                <div class="check-icon position-absolute"></div>
            </div>
            <h1 class="text-3xl font-bold mt-6 mb-2">{{ app()->getLocale() == 'ar' ? 'تم تأكيد الحجز بنجاح!' : 'Booking Confirmed Successfully!' }}</h1>
            <p class="text-green-100 text-lg mb-4 max-w-2xl mx-auto">
                {{ app()->getLocale() == 'ar' ? 'تم استلام حجزك وتم تأكيده. ستصلك رسالة بالتفاصيل عبر البريد الإلكتروني ورسالة نصية.' : 'Your booking has been received and confirmed. You will receive details via email and SMS.' }}
            </p>
            
            <!-- Breadcrumbs -->
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-green-100 hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            {{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-green-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="/specialists" class="ms-1 text-sm font-medium text-green-100 hover:text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'المتخصصين' : 'Specialists' }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-green-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'تأكيد الحجز' : 'Booking Confirmation' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Booking Progress Indicator -->
    <div class="container mx-auto px-4 py-8">
        <div class="relative mb-12">
            <!-- Progress Line -->
            <div class="booking-progress-line"></div>
            <div class="booking-progress-line-active" style="width: 100%;"></div>
            
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
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'الدفع' : 'Payment' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-green-600">{{ app()->getLocale() == 'ar' ? 'التأكيد' : 'Confirmation' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Booking Details Card -->
        <div class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">{{ app()->getLocale() == 'ar' ? 'تفاصيل الحجز' : 'Booking Details' }}</h2>
                <p class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'رقم الحجز:' : 'Booking Reference:' }} <span class="font-medium">NAFSAJI-25051210087</span></p>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between">
                    <!-- Specialist Info -->
                    <div class="mb-6 md:mb-0 md:w-1/3">
                        <h3 class="text-sm uppercase text-gray-500 mb-3">{{ app()->getLocale() == 'ar' ? 'معلومات المتخصص' : 'Specialist Information' }}</h3>
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg flex items-center justify-center text-white text-xl font-bold mr-3 flex-shrink-0">
                                {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</h4>
                                <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}</p>
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-star text-yellow-500 text-xs mr-1"></i>
                                    <span class="text-sm text-gray-600">4.8 (42 {{ app()->getLocale() == 'ar' ? 'تقييم' : 'reviews' }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Session Info -->
                    <div class="mb-6 md:mb-0 md:w-1/3">
                        <h3 class="text-sm uppercase text-gray-500 mb-3">{{ app()->getLocale() == 'ar' ? 'معلومات الجلسة' : 'Session Information' }}</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="far fa-calendar-alt text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ app()->getLocale() == 'ar' ? '12 مايو 2025' : 'May 12, 2025' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="far fa-clock text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">11:00 AM - 12:00 PM</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-video text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-tag text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ app()->getLocale() == 'ar' ? '287.5 ريال (شامل الضريبة)' : '287.5 SAR (Tax Included)' }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="md:w-1/3">
                        <h3 class="text-sm uppercase text-gray-500 mb-3">{{ app()->getLocale() == 'ar' ? 'معلومات إضافية' : 'Additional Information' }}</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-info-circle text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ app()->getLocale() == 'ar' ? 'ستتلقى رابط الجلسة قبل 30 دقيقة من الموعد' : 'You will receive the session link 30 minutes before the appointment' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-bell text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ app()->getLocale() == 'ar' ? 'سيتم إرسال تذكير قبل الموعد بـ 24 ساعة' : 'A reminder will be sent 24 hours before the appointment' }}</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-exchange-alt text-purple-600 mt-1 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ app()->getLocale() == 'ar' ? 'يمكن إلغاء أو إعادة جدولة الموعد قبل 24 ساعة' : 'Appointment can be cancelled or rescheduled 24 hours in advance' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Next Steps and Tips -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Next Steps -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ app()->getLocale() == 'ar' ? 'الخطوات التالية' : 'Next Steps' }}</h2>
                
                <ul class="space-y-4">
                    <li class="flex">
                        <div class="bg-green-100 text-green-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <span class="font-bold">1</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'تحقق من بريدك الإلكتروني' : 'Check Your Email' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'ستجد تفاصيل الحجز وتعليمات الاتصال في بريدك الإلكتروني' : 'You will find booking details and connection instructions in your email' }}</p>
                        </div>
                    </li>
                    <li class="flex">
                        <div class="bg-green-100 text-green-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <span class="font-bold">2</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'استعد للجلسة' : 'Prepare for the Session' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'جهز أي أسئلة أو نقاط تريد مناقشتها مع المتخصص' : 'Prepare any questions or points you want to discuss with the specialist' }}</p>
                        </div>
                    </li>
                    <li class="flex">
                        <div class="bg-green-100 text-green-600 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <span class="font-bold">3</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'انضم للجلسة' : 'Join the Session' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'انقر على الرابط في البريد الإلكتروني قبل 5 دقائق من موعد الجلسة' : 'Click the link in the email 5 minutes before your session time' }}</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Tips for Effective Session -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">{{ app()->getLocale() == 'ar' ? 'نصائح لجلسة فعالة' : 'Tips for an Effective Session' }}</h2>
                
                <ul class="space-y-4">
                    <li class="flex">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'ابحث عن مكان هادئ' : 'Find a Quiet Place' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'تأكد من أن تكون في مكان هادئ وخاص دون مقاطعات' : 'Make sure you are in a quiet and private place without interruptions' }}</p>
                        </div>
                    </li>
                    <li class="flex">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'تحقق من اتصالك بالإنترنت' : 'Check Your Internet Connection' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'تأكد من استقرار اتصالك بالإنترنت قبل الجلسة' : 'Ensure your internet connection is stable before the session' }}</p>
                        </div>
                    </li>
                    <li class="flex">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'كن صريحاً ومنفتحاً' : 'Be Open and Honest' }}</h3>
                            <p class="text-gray-600 text-sm">{{ app()->getLocale() == 'ar' ? 'لتحقيق أقصى استفادة من الجلسة، كن صريحاً مع المتخصص' : 'To get the most out of your session, be honest with your specialist' }}</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Call to Action Buttons -->
        <div class="text-center mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ app()->getLocale() == 'ar' ? 'تحتاج مساعدة؟' : 'Need Help?' }}</h2>
            <p class="text-gray-600 mb-6">{{ app()->getLocale() == 'ar' ? 'فريق الدعم متاح لمساعدتك في أي وقت' : 'Our support team is available to help you anytime' }}</p>
            
            <div class="flex flex-col md:flex-row justify-center space-y-3 md:space-y-0 md:space-x-4 rtl:space-x-reverse">
                <a href="#" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 flex items-center justify-center">
                    <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                    {{ app()->getLocale() == 'ar' ? 'إدارة مواعيدي' : 'Manage My Appointments' }}
                </a>
                <a href="#" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 flex items-center justify-center">
                    <i class="fas fa-headset mr-2 text-purple-600"></i>
                    {{ app()->getLocale() == 'ar' ? 'تواصل مع الدعم' : 'Contact Support' }}
                </a>
                <a href="/" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white font-medium hover:opacity-90 flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    {{ app()->getLocale() == 'ar' ? 'العودة للصفحة الرئيسية' : 'Return to Home Page' }}
                </a>
            </div>
        </div>
        
        <!-- Get the App Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl text-white p-6 text-center">
            <h2 class="text-xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'قم بتنزيل تطبيق نفسجي' : 'Download the Nafsaji App' }}</h2>
            <p class="mb-6 opacity-90">{{ app()->getLocale() == 'ar' ? 'احصل على تنبيهات حول مواعيدك القادمة وإدارة حجوزاتك بشكل أسهل' : 'Get notifications about your upcoming appointments and manage your bookings more easily' }}</p>
            
            <div class="flex flex-col md:flex-row justify-center space-y-3 md:space-y-0 md:space-x-4 rtl:space-x-reverse">
                <a href="#" class="px-6 py-3 bg-black rounded-lg text-white font-medium hover:bg-gray-900 flex items-center justify-center">
                    <i class="fab fa-apple text-2xl mr-2"></i>
                    <div class="text-left">
                        <div class="text-xs">{{ app()->getLocale() == 'ar' ? 'تحميل من' : 'Download on the' }}</div>
                        <div class="text-sm font-bold">App Store</div>
                    </div>
                </a>
                <a href="#" class="px-6 py-3 bg-black rounded-lg text-white font-medium hover:bg-gray-900 flex items-center justify-center">
                    <i class="fab fa-google-play text-2xl mr-2"></i>
                    <div class="text-left">
                        <div class="text-xs">{{ app()->getLocale() == 'ar' ? 'متوفر على' : 'Available on' }}</div>
                        <div class="text-sm font-bold">Google Play</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
