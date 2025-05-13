<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')
@section('title', __('pages.home.title'))
@section('styles')
<style>
    /* تأثيرات التمرير للعناصر - Scroll Effects */
    .fade-in-up {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    
    .fade-in-right {
        opacity: 0;
        transform: translateX(-20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    
    .fade-in-left {
        opacity: 0;
        transform: translateX(20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    
    .visible {
        opacity: 1;
        transform: translate(0);
    }
    
    /* تحسينات عامة - General Enhancements */
    .service-card {
        transition: all 0.3s ease;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    /* تأثيرات للأزرار - Button Animations */
    .pulse-btn {
        position: relative;
        overflow: hidden;
    }
    
    .pulse-btn:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 120%;
        height: 120%;
        background: rgba(255,255,255,0.2);
        transform: translate(-50%, -50%) scale(0);
        border-radius: 50%;
        z-index: 0;
        transition: transform 0.5s;
    }
    
    .pulse-btn:active:after {
        transform: translate(-50%, -50%) scale(1);
        transition: transform 0s;
    }
    
    /* تأثيرات الخلفية - Background Effects */
    .bg-pattern {
        background-color: #f8f9fa;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>

<!-- Intersection Observer Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.fade-in-up, .fade-in-right, .fade-in-left').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endsection

@section('content')
<!-- الصفحة الرئيسية المتجاوبة - Responsive Home Page -->
<div class="min-h-screen">
    <!-- قسم البداية - Hero Section -->
    <section class="relative overflow-hidden min-h-[70vh] py-8">
        <!-- خلفية متدرجة - Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600 to-indigo-600"></div>
        
        <!-- زخارف خلفية - Background Decorations -->
        <div class="absolute top-10 left-10 w-24 h-24 bg-purple-500 rounded-full opacity-20 blur-xl"></div>
        <div class="absolute bottom-40 right-10 w-32 h-32 bg-indigo-400 rounded-full opacity-20 blur-2xl"></div>
        
        <!-- شكل موجي أسفل القسم - Wave Shape -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full h-auto fill-white">
                <path d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,224C672,213,768,171,864,144C960,117,1056,107,1152,112C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
        
        <!-- المحتوى الرئيسي - Hero Content -->
        <div class="container mx-auto px-4 relative z-10 pt-6 md:pt-8 flex flex-col-reverse md:flex-row items-center gap-6">
            <!-- النص - Text Content -->
            <div class="text-center md:text-start mb-4 md:mb-0 w-full md:w-1/2 fade-in-right">
                <h1 class="text-white text-3xl md:text-5xl font-bold mb-3 leading-tight">
                    {{ app()->getLocale() == 'ar' ? 'الدعم النفسي في متناول يديك' : 'Mental Support at Your Fingertips' }}
                </h1>
                <p class="text-white/90 text-lg mb-4 max-w-lg mx-auto md:mx-0">
                    {{ app()->getLocale() == 'ar' ? 'مع نفسجي، تواصل مع أفضل المختصين النفسيين من أي مكان وفي أي وقت.' : 'With Nafsaji, connect with the best mental health specialists from anywhere, anytime.' }}
                </p>
                
                <!-- أزرار الدعوة للعمل - CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                    <a href="{{ route('specialists.index') }}" class="py-2.5 px-5 bg-white text-purple-600 rounded-xl font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 fade-in-up" style="transition-delay: 200ms;">
                        <i class="fas fa-user-md me-2"></i> {{ app()->getLocale() == 'ar' ? 'المختصون النفسيون' : 'Our Specialists' }}
                    </a>
                    <a href="{{ route('services.index') }}" class="py-2.5 px-5 bg-transparent border-2 border-white text-white rounded-xl font-semibold hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-1 fade-in-up" style="transition-delay: 300ms;">
                        <i class="fas fa-info-circle me-2"></i> {{ app()->getLocale() == 'ar' ? 'خدماتنا' : 'Our Services' }}
                    </a>
                </div>
            </div>
            
            <!-- الصورة / الرسم التوضيحي - Illustration -->
            <div class="w-full md:w-1/2 mb-4 md:mb-0 fade-in-left">
                <div class="max-w-sm mx-auto transform rotate-1 hover:rotate-0 transition-transform duration-700">
                    <div class="bg-white rounded-xl p-2 shadow-lg">
                        <!-- شريط عنوان المتصفح - Browser Bar -->
                        <div class="flex items-center space-x-1 rtl:space-x-reverse mb-2">
                            <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                            <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                            <div class="h-3 bg-gray-200 rounded ml-auto w-16"></div>
                        </div>
                        
                        <!-- محتوى محاكاة واجهة التطبيق - App UI Mockup Content -->
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <div class="w-16 h-2 bg-gray-300 rounded"></div>
                                <div class="w-8 h-4 bg-purple-500 rounded"></div>
                            </div>
                            
                            <!-- صورة ملف المختص - Specialist Profile -->
                            <div class="bg-white p-3 rounded-lg mt-3">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <div class="w-12 h-12 bg-purple-400 rounded-full flex items-center justify-center">
                                        <div class="w-6 h-6 bg-white rotate-45 rounded"></div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="w-16 h-2 bg-gray-300 rounded mb-1"></div>
                                        <div class="w-24 h-2 bg-gray-200 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-3">
                                    <div class="w-16 h-6 bg-gray-200 rounded"></div>
                                    <div class="w-24 h-6 bg-purple-500 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- قسم الخدمات - Services Section -->

    <!-- قسم الخدمات - Services Section -->
    <section id="services" class="py-16 px-4 bg-pattern relative overflow-hidden">
        <!-- زخارف خلفية - Background Decorations -->
        <div class="absolute top-0 left-0 w-40 h-40 bg-purple-100 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-40"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-indigo-100 rounded-full translate-x-1/3 translate-y-1/3 opacity-40"></div>
        <div class="container mx-auto">
            <!-- عنوان القسم - Section Title -->
            <div class="text-center mb-12 fade-in-up relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">
                    {{ app()->getLocale() == 'ar' ? 'خدماتنا المتميزة' : 'Our Premium Services' }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ app()->getLocale() == 'ar' ? 'نقدم مجموعة متكاملة من الخدمات النفسية لمساعدتك في رحلتك نحو الصحة النفسية' : 'We offer a comprehensive range of mental health services to help you on your journey to mental wellness' }}
                </p>
            </div>
            
            <!-- بطاقات الخدمات (تصميم تطبيق للجوال وموقع للكمبيوتر) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                <!-- بطاقة الخدمة الأولى - First Service Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group hover:shadow-lg transition-all service-card fade-in-up" style="transition-delay: 100ms;">
                    <div class="h-36 bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center relative overflow-hidden group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-comments text-white text-4xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3 text-gray-800 group-hover:text-purple-600 transition-colors">
                            {{ __('messages.consultations') }}
                        </h3>
                        <p class="text-gray-600 mb-4 text-sm md:text-base">
                            {{ __('pages.services.consultation_description') }}
                        </p>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('services.show', 1) }}" class="text-purple-600 font-medium text-sm hover:text-purple-700 transition flex items-center">
                                {{ __('pages.services.learn_more') }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <div class="border-r border-gray-300 mx-2"></div>
                            <a href="{{ route('booking.start') }}" class="text-purple-600 font-medium text-sm hover:text-purple-700 transition flex items-center">
                                {{ __('pages.booking.book_now') }} <i class="fas fa-calendar-plus ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- بطاقة الخدمة الثانية - Second Service Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group hover:shadow-lg transition-all service-card fade-in-up" style="transition-delay: 200ms;">
                    <div class="h-36 bg-gradient-to-r from-indigo-500 to-blue-600 flex items-center justify-center relative overflow-hidden group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-brain text-white text-4xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3 text-gray-800 group-hover:text-indigo-600 transition-colors">
                            {{ __('messages.therapy_sessions') }}
                        </h3>
                        <p class="text-gray-600 mb-4 text-sm md:text-base">
                            {{ __('pages.services.therapy_description') }}
                        </p>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('services.show', 2) }}" class="text-indigo-600 font-medium text-sm hover:text-indigo-700 transition flex items-center">
                                {{ __('pages.services.learn_more') }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <div class="border-r border-gray-300 mx-2"></div>
                            <a href="{{ route('booking.start') }}" class="text-indigo-600 font-medium text-sm hover:text-indigo-700 transition flex items-center">
                                {{ __('pages.booking.book_now') }} <i class="fas fa-calendar-plus ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- بطاقة الخدمة الثالثة - Third Service Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group hover:shadow-lg transition-all service-card fade-in-up" style="transition-delay: 300ms;">
                    <div class="h-36 bg-gradient-to-r from-blue-500 to-teal-600 flex items-center justify-center relative overflow-hidden group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-users text-white text-4xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3 text-gray-800 group-hover:text-blue-600 transition-colors">
                            {{ __('messages.group_sessions') }}
                        </h3>
                        <p class="text-gray-600 mb-4 text-sm md:text-base">
                            {{ __('pages.services.group_description') }}
                        </p>
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <a href="{{ route('services.show', 3) }}" class="text-blue-600 font-medium text-sm hover:text-blue-700 transition flex items-center">
                                {{ __('pages.services.learn_more') }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <div class="border-r border-gray-300 mx-2"></div>
                            <a href="{{ route('booking.start') }}" class="text-blue-600 font-medium text-sm hover:text-blue-700 transition flex items-center">
                                {{ __('pages.booking.book_now') }} <i class="fas fa-calendar-plus ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <!-- قسم كيفية عمل المنصة - How It Works Section -->
    <section class="py-16 px-4 bg-gray-50 relative overflow-hidden">
        <!-- زخارف خلفية - Background Decorations -->
        <div class="absolute top-0 right-10 w-32 h-32 bg-indigo-100 rounded-full opacity-50 hidden md:block"></div>
        <div class="absolute bottom-0 left-10 w-48 h-48 bg-purple-100 rounded-full opacity-50 hidden md:block"></div>
        <div class="container mx-auto">
            <!-- عنوان القسم - Section Title -->
            <div class="text-center mb-12 fade-in-up relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">
                    {{ __('pages.howItWorks.title') }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ __('pages.howItWorks.process_description') }}
                </p>
            </div>
            
            <!-- خطوات العمل مع خطوط اتصال أفضل - Process Steps with Better Connection Lines -->
            <div class="relative max-w-5xl mx-auto z-10">
                <!-- خط اتصال أفقي (يظهر فقط على الشاشات المتوسطة والكبيرة) - Horizontal Connection Line -->
                <div class="hidden md:block absolute top-1/2 left-0 right-0 w-full border-t-2 border-dashed border-gray-300 transform -translate-y-20 z-0"></div>
                
                <!-- الخطوات - Steps -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 relative z-10">
                    <!-- الخطوة الأولى - Step 1 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm text-center relative fade-in-up" style="transition-delay: 100ms;">
                        <!-- رقم الخطوة - Step Number -->
                        <div class="w-14 h-14 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xl mb-4 mx-auto border-4 border-white shadow-md">
                            1
                        </div>
                        <!-- رمز الخطوة - Step Icon -->
                        <div class="text-purple-600 text-4xl mb-4">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <!-- عنوان ووصف الخطوة - Step Title & Description -->
                        <h3 class="text-xl font-bold mb-3 text-gray-800">
                            {{ __('pages.howItWorks.step1_title') }}
                        </h3>
                        <p class="text-gray-600">
                            {{ __('pages.howItWorks.step1_description') }}
                        </p>
                    </div>
                    
                    <!-- الخطوة الثانية - Step 2 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm text-center relative fade-in-up" style="transition-delay: 250ms;">
                        <!-- رقم الخطوة - Step Number -->
                        <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl mb-4 mx-auto border-4 border-white shadow-md">
                            2
                        </div>
                        <!-- رمز الخطوة - Step Icon -->
                        <div class="text-indigo-600 text-4xl mb-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <!-- عنوان ووصف الخطوة - Step Title & Description -->
                        <h3 class="text-xl font-bold mb-3 text-gray-800">
                            {{ __('pages.howItWorks.step2_title') }}
                        </h3>
                        <p class="text-gray-600">
                            {{ __('pages.howItWorks.step2_description') }}
                        </p>
                    </div>
                    
                    <!-- الخطوة الثالثة - Step 3 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm text-center relative fade-in-up" style="transition-delay: 400ms;">
                        <!-- رقم الخطوة - Step Number -->
                        <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xl mb-4 mx-auto border-4 border-white shadow-md">
                            3
                        </div>
                        <!-- رمز الخطوة - Step Icon -->
                        <div class="text-blue-600 text-4xl mb-4">
                            <i class="fas fa-video"></i>
                        </div>
                        <!-- عنوان ووصف الخطوة - Step Title & Description -->
                        <h3 class="text-xl font-bold mb-3 text-gray-800">
                            {{ __('pages.howItWorks.step3_title') }}
                        </h3>
                        <p class="text-gray-600">
                            {{ __('pages.howItWorks.step3_description') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- زر المختصين محسن - Enhanced Specialists Button -->
            <div class="text-center mt-12 fade-in-up" style="transition-delay: 500ms;">
                <a href="{{ route('specialists.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all text-lg">
                    @if(app()->getLocale() == 'ar')
                        <i class="fas fa-arrow-left ml-1"></i>
                        تصفح المختصين
                    @else
                        Browse Specialists
                        <i class="fas fa-arrow-right ml-1"></i>
                    @endif
                </a>
            </div>
        </div>
    </section>
    
    <!-- قسم شهادات العملاء - Testimonials Section -->
    <section class="py-16 px-4 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <!-- زخارف خلفية - Background Decorations -->
        <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-purple-100 opacity-70"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 rounded-full bg-indigo-100 opacity-70"></div>
        
        <div class="container mx-auto relative z-10">
            <!-- عنوان القسم - Section Header -->
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">
                    {{ app()->getLocale() == 'ar' ? 'ماذا يقول عملاؤنا' : 'What Our Clients Say' }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ app()->getLocale() == 'ar' ? 'آراء حقيقية من أشخاص تلقوا خدماتنا وشاركوا تجربتهم معنا' : 'Genuine reviews from individuals who have received our services and shared their experience with us' }}
                </p>
            </div>
            
            <!-- شريط شهادات العملاء (قابل للتمرير على الجوال) - Testimonial Cards (Scrollable on Mobile) -->
            <div class="flex overflow-x-auto pb-4 snap-x hide-scrollbar space-x-4 rtl:space-x-reverse md:grid md:grid-cols-3 md:gap-6 md:space-x-0">
                <!-- شهادة 1 - Testimonial 1 -->
                <div class="min-w-[300px] md:min-w-0 snap-start bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-col fade-in-up" style="transition-delay: 100ms;">
                    <!-- نجوم التقييم - Rating Stars -->
                    <div class="flex text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    
                    <!-- نص الشهادة - Testimonial Content -->
                    <p class="text-gray-600 mb-6 flex-grow text-sm md:text-base">
                        {{ app()->getLocale() == 'ar' ? '"كانت تجربتي مع نفسجي ممتازة. المختص النفسي الذي تواصلت معه كان متفهماً ومحترفاً. أشعر بتحسن كبير بعد الجلسات التي حضرتها."' : '"My experience with Nafsaji was excellent. The specialist I connected with was understanding and professional. I feel much better after the sessions I attended."' }}
                    </p>
                    
                    <!-- معلومات العميل - Client Info -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ app()->getLocale() == 'ar' ? 'س' : 'S' }}
                        </div>
                        <div class="ms-3">
                            <div class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'سارة م.' : 'Sarah M.' }}</div>
                            <div class="text-gray-500 text-sm">{{ app()->getLocale() == 'ar' ? 'عميلة منذ أبريل 2024' : 'Client since April 2024' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- شهادة 2 - Testimonial 2 -->
                <div class="min-w-[300px] md:min-w-0 snap-start bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-col fade-in-up" style="transition-delay: 200ms;">
                    <!-- نجوم التقييم - Rating Stars -->
                    <div class="flex text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    
                    <!-- نص الشهادة - Testimonial Content -->
                    <p class="text-gray-600 mb-6 flex-grow text-sm md:text-base">
                        {{ app()->getLocale() == 'ar' ? '"ما يميز نفسجي هو سهولة استخدام المنصة والمرونة في المواعيد. لقد ساعدتني الجلسات عبر الإنترنت على التغلب على القلق وإدارة التوتر بشكل أفضل."' : '"What sets Nafsaji apart is the ease of using the platform and the flexibility of appointments. The online sessions have helped me overcome anxiety and manage stress better."' }}
                    </p>
                    
                    <!-- معلومات العميل - Client Info -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ app()->getLocale() == 'ar' ? 'م' : 'M' }}
                        </div>
                        <div class="ms-3">
                            <div class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'محمد ع.' : 'Mohammed A.' }}</div>
                            <div class="text-gray-500 text-sm">{{ app()->getLocale() == 'ar' ? 'عميل منذ يناير 2024' : 'Client since January 2024' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- شهادة 3 - Testimonial 3 -->
                <div class="min-w-[300px] md:min-w-0 snap-start bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-col fade-in-up" style="transition-delay: 300ms;">
                    <!-- نجوم التقييم - Rating Stars -->
                    <div class="flex text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    
                    <!-- نص الشهادة - Testimonial Content -->
                    <p class="text-gray-600 mb-6 flex-grow text-sm md:text-base">
                        {{ app()->getLocale() == 'ar' ? '"أقدر كثيراً السرية والخصوصية التي توفرها نفسجي. لقد كانت جلسات العلاج الجماعي مفيدة للغاية وساعدتني على التواصل مع أشخاص يمرون بتجارب مشابهة."' : '"I greatly appreciate the confidentiality and privacy that Nafsaji provides. The group therapy sessions have been extremely helpful and helped me connect with people going through similar experiences."' }}
                    </p>
                    
                    <!-- معلومات العميل - Client Info -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ app()->getLocale() == 'ar' ? 'ن' : 'N' }}
                        </div>
                        <div class="ms-3">
                            <div class="font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'نورة ح.' : 'Noura H.' }}</div>
                            <div class="text-gray-500 text-sm">{{ app()->getLocale() == 'ar' ? 'عميلة منذ مارس 2024' : 'Client since March 2024' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- مؤشر التمرير للجوال - Mobile Scroll Indicator -->
            <div class="flex justify-center space-x-1 mt-4 md:hidden">
                <div class="w-12 h-1 bg-purple-600 rounded-full"></div>
                <div class="w-3 h-1 bg-gray-300 rounded-full"></div>
                <div class="w-3 h-1 bg-gray-300 rounded-full"></div>
            </div>
            

        </div>
    </section>
    
    <!-- قسم لماذا نفسجي - Why Choose Us Section -->
    <section class="py-16 px-4 relative overflow-hidden">
        <!-- زخارف خلفية - Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full bg-pattern opacity-30"></div>
        <div class="container mx-auto">
            <!-- عنوان القسم - Section Title -->
            <div class="text-center mb-10 fade-in-up relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">
                    {{ __('pages.services.why_choose_us') }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ app()->getLocale() == 'ar' ? 'ما يميزنا ويجعلنا الخيار الأمثل للعناية بصحتك النفسية' : 'What makes us unique and the ideal choice for your mental health care' }}
                </p>
            </div>
            
            <!-- مميزات نفسجي - Features -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                <!-- الميزة الأولى - Feature 1 -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all fade-in-up" style="transition-delay: 100ms;">
                    <div class="w-14 h-14 bg-purple-100 rounded-full mb-4 flex items-center justify-center">
                        <i class="fas fa-certificate text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ __('pages.services.expert_therapists') }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                        {{ __('pages.services.expert_therapists_desc') }}
                    </p>
                </div>
                
                <!-- الميزة الثانية - Feature 2 -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all fade-in-up" style="transition-delay: 200ms;">
                    <div class="w-14 h-14 bg-indigo-100 rounded-full mb-4 flex items-center justify-center">
                        <i class="fas fa-fingerprint text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ __('pages.services.personalized_approach') }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                        {{ __('pages.services.personalized_approach_desc') }}
                    </p>
                </div>
                
                <!-- الميزة الثالثة - Feature 3 -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all fade-in-up" style="transition-delay: 300ms;">
                    <div class="w-14 h-14 bg-blue-100 rounded-full mb-4 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ __('pages.services.confidential_care') }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                        {{ __('pages.services.confidential_care_desc') }}
                    </p>
                </div>
                
                <!-- الميزة الرابعة - Feature 4 -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all fade-in-up" style="transition-delay: 400ms;">
                    <div class="w-14 h-14 bg-teal-100 rounded-full mb-4 flex items-center justify-center">
                        <i class="fas fa-clock text-teal-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ __('pages.services.flexible_options') }}
                    </h3>
                    <p class="text-gray-600 text-sm">
                        {{ __('pages.services.flexible_options_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- قسم الأسئلة الشائعة - FAQ Section -->
    <section class="py-16 px-4 bg-gray-50 relative overflow-hidden">
        <!-- زخارف خلفية - Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full bg-pattern opacity-20"></div>
        
        <div class="container mx-auto relative z-10">
            <!-- عنوان القسم - Section Title -->
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">
                    {{ app()->getLocale() == 'ar' ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ app()->getLocale() == 'ar' ? 'إجابات على الأسئلة الأكثر شيوعاً حول خدماتنا وطريقة عمل منصة نفسجي' : 'Answers to the most common questions about our services and how Nafsaji platform works' }}
                </p>
            </div>
            
            <!-- قائمة الأسئلة والأجوبة - FAQ List -->
            <div class="max-w-3xl mx-auto divide-y divide-gray-200">
                <!-- سؤال 1 - Question 1 -->
                <div x-data="{open: false}" class="py-4 fade-in-up" style="transition-delay: 100ms;">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-800">
                            {{ __('pages.contact.faq_q1') }}
                        </h3>
                        <span class="ml-4 flex-shrink-0">
                            <i :class="open ? 'fa-minus' : 'fa-plus'" class="fas text-purple-600"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 text-gray-600">
                        <p>{{ __('pages.contact.faq_a1') }}</p>
                    </div>
                </div>
                
                <!-- سؤال 2 - Question 2 -->
                <div x-data="{open: false}" class="py-4 fade-in-up" style="transition-delay: 200ms;">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-800">
                            {{ __('pages.contact.faq_q2') }}
                        </h3>
                        <span class="ml-4 flex-shrink-0">
                            <i :class="open ? 'fa-minus' : 'fa-plus'" class="fas text-purple-600"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 text-gray-600">
                        <p>{{ __('pages.contact.faq_a2') }}</p>
                    </div>
                </div>
                
                <!-- سؤال 3 - Question 3 -->
                <div x-data="{open: false}" class="py-4 fade-in-up" style="transition-delay: 300ms;">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-800">
                            {{ __('pages.contact.faq_q3') }}
                        </h3>
                        <span class="ml-4 flex-shrink-0">
                            <i :class="open ? 'fa-minus' : 'fa-plus'" class="fas text-purple-600"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 text-gray-600">
                        <p>{{ __('pages.contact.faq_a3') }}</p>
                    </div>
                </div>
                
                <!-- سؤال 4 - Question 4 -->
                <div x-data="{open: false}" class="py-4 fade-in-up" style="transition-delay: 400ms;">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-800">
                            {{ app()->getLocale() == 'ar' ? 'كيف أعرف أي مختص نفسي يناسبني؟' : 'How do I know which specialist is right for me?' }}
                        </h3>
                        <span class="ml-4 flex-shrink-0">
                            <i :class="open ? 'fa-minus' : 'fa-plus'" class="fas text-purple-600"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 text-gray-600">
                        <p>{{ app()->getLocale() == 'ar' ? 'يمكنك الاطلاع على ملفات المختصين في صفحة المختصين لمعرفة تخصصاتهم وخبراتهم. يمكنك أيضًا التواصل معنا من خلال صفحة الاتصال للحصول على توجيه مجاني لاختيار المختص المناسب.' : 'You can view specialist profiles on the specialists page to learn about their specialties and experience. You can also contact us through the contact page for free guidance in choosing the appropriate specialist.' }}</p>
                    </div>
                </div>
                
                <!-- سؤال 5 - Question 5 -->
                <div x-data="{open: false}" class="py-4 fade-in-up" style="transition-delay: 500ms;">
                    <button @click="open = !open" class="flex justify-between items-center w-full text-left">
                        <h3 class="text-lg font-medium text-gray-800">
                            {{ app()->getLocale() == 'ar' ? 'هل تقدمون خدمات مخصصة للأطفال والمراهقين؟' : 'Do you offer specialized services for children and adolescents?' }}
                        </h3>
                        <span class="ml-4 flex-shrink-0">
                            <i :class="open ? 'fa-minus' : 'fa-plus'" class="fas text-purple-600"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="mt-2 text-gray-600">
                        <p>{{ app()->getLocale() == 'ar' ? 'نعم، لدينا مختصون متمرسون في علم النفس للأطفال والمراهقين، يقدمون خدمات مصممة خصيصًا لمساعدة الشباب على التعامل مع قضايا الصحة النفسية والنمو المختلفة.' : 'Yes, we have specialists experienced in child and adolescent psychology, offering services specifically designed to help young people deal with various mental health and developmental issues.' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- زر لمزيد من الأسئلة - More Questions Button -->
            <div class="text-center mt-8 fade-in-up" style="transition-delay: 600ms;">
                <a href="{{ route('contact.create') }}" class="inline-flex items-center text-purple-600 font-medium hover:text-purple-700 transition-colors">
                    {{ app()->getLocale() == 'ar' ? 'لديك سؤال آخر؟ تواصل معنا' : 'Have another question? Contact us' }}
                    <i class="fas fa-arrow-right ms-2 text-sm"></i>
                </a>
            </div>
        </div>
    </section>
    
    <!-- قسم الدعوة للعمل - CTA Section -->
    <section class="py-12 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-center relative">
        <div class="container mx-auto relative z-10 max-w-3xl">
            <!-- العنوان والنص - Title & Text -->
            <h2 class="text-2xl md:text-3xl font-bold mb-4" style="direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};">
                {{ app()->getLocale() == 'ar' ? 'ابدأ رحلتك نحو حياة نفسية أفضل اليوم' : 'Start your journey to better mental health today' }}
            </h2>
            <p class="mb-8 opacity-90" style="direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};">
                {{ app()->getLocale() == 'ar' ? 'لا تنتظر، خُذ الخطوة الأولى نحو الدعم النفسي المتخصص الذي تحتاج إليه' : 'Don\'t wait, take the first step towards the specialized psychological support you need' }}
            </p>
            
            <!-- الأزرار - Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('specialists.index') }}" class="px-6 py-3 bg-white text-indigo-700 rounded-full font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center">
                    <i class="fas fa-user-md {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i> 
                    {{ app()->getLocale() == 'ar' ? 'تصفح المختصين' : 'Browse Specialists' }}
                </a>
                <a href="{{ route('services.index') }}" class="px-6 py-3 bg-transparent border border-white text-white rounded-full font-semibold hover:bg-white/10 transition-all flex items-center justify-center">
                    <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i> 
                    {{ app()->getLocale() == 'ar' ? 'خدماتنا' : 'Our Services' }}
                </a>
            </div>
        </div>
    </section>
</div>

    <!-- زر المساعدة العائم - Floating Help Button -->
    <div class="fixed bottom-6 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} z-50">
        <a href="{{ route('contact.create') }}" class="flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full shadow-lg text-white hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <i class="fas fa-headset text-2xl"></i>
        </a>
        <div class="absolute {{ app()->getLocale() == 'ar' ? 'right-full pl-4 pr-3' : 'left-full pr-4 pl-3' }} top-1/2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-gray-800 py-2 px-4 text-sm text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            {{ app()->getLocale() == 'ar' ? 'بحاجة لمساعدة؟' : 'Need help?' }}
        </div>
    </div>
@endsection