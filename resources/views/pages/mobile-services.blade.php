@extends('layouts.app')

@section('title', __('pages.services.title'))

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
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        overscroll-behavior: none; /* منع سلوك التمرير الزائد - Prevent overscroll behavior */
        -webkit-tap-highlight-color: transparent; /* إزالة التظليل عند النقر - Remove tap highlight */
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
        touch-action: manipulation; /* تحسين الاستجابة للمس - Improve touch responsiveness */
    }
    
    /* إزالة مظهر الويب بالكامل - Remove all web appearance */
    .mobile-app-view #web-header,
    .mobile-app-view #web-footer,
    .mobile-app-view .web-element,
    .mobile-app-view .browser-ui {
        display: none !important;
    }
    
    /* إضافة شريط حالة التطبيق - Add app status bar */
    .app-status-bar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: env(safe-area-inset-top, 20px);
        background-color: rgba(255,255,255,0.95);
        z-index: 10000;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    /* قائمة الزر العائم - Mobile menu */
    .mobile-nav-menu {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9000;
        background-color: white;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }
    
    .mobile-nav-menu.active {
        transform: translateY(0);
    }
    
    /* أنماط المحتوى - Content styles */
    .app-content {
        position: relative;
        padding: 16px;
        padding-top: calc(65px + env(safe-area-inset-top, 20px)); /* إضافة مسافة للهيدر الثابت */
        padding-bottom: calc(16px + env(safe-area-inset-bottom, 20px));
        min-height: 100vh;
        z-index: 1;
    }
    
    /* قسم التطبيق - App section */
    .app-section {
        margin-bottom: 24px;
        animation: fadeSlideUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(10px);
    }
    
    .app-section.animate-in {
        animation: fadeSlideUp 0.6s ease forwards;
    }
    
    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* عنوان قسم التطبيق - App section title */
    .app-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 16px;
        color: #374151;
        display: flex;
        align-items: center;
    }
    
    .app-section-title:before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 18px;
        background: linear-gradient(to bottom, #8B5CF6, #6366F1);
        margin-right: 8px;
        border-radius: 4px;
    }
    
    /* زخارف الخلفية - Background decorations */
    .app-bg-decoration {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(to bottom right, #a5b4fc, #818cf8);
        filter: blur(80px);
        z-index: -1;
    }
    
    /* بطاقة الخدمة - Service card */
    .service-card {
        background-color: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        margin-bottom: 16px;
    }
    
    .service-card:hover, .service-card:active {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    /* تأثير التموج عند النقر - Ripple effect */
    .ripple {
        position: relative;
        overflow: hidden;
        transform: translate3d(0, 0, 0);
    }
    
    .ripple:after {
        content: "";
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
        background-repeat: no-repeat;
        background-position: 50%;
        transform: scale(10, 10);
        opacity: 0;
        transition: transform .5s, opacity 1s;
    }
    
    .ripple:active:after {
        transform: scale(0, 0);
        opacity: .3;
        transition: 0s;
    }
    
    /* تأثير التلألؤ - Shimmer effect */
    .shimmer-effect {
        position: relative;
        overflow: hidden;
    }
    
    .shimmer-effect:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            to right,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.2) 50%,
            rgba(255,255,255,0) 100%
        );
        transform: translateX(-100%);
        animation: shimmer 2.5s infinite;
    }
    
    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }
</style>
@endsection

@section('content')
<!-- عرض التطبيق المخصص للجوال - Mobile App View -->
<div class="block" x-data="{ showMenu: false }"> 
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content">
        <!-- زخارف خلفية - Background decorations -->
        <div class="app-bg-decoration" style="width: 150px; height: 150px; top: 5%; right: -50px; opacity: 0.4;"></div>
        <div class="app-bg-decoration" style="width: 200px; height: 200px; bottom: 30%; left: -100px; opacity: 0.3;"></div>
        
        <!-- قسم الخدمات الرئيسية - Main Services Section -->
        <div class="app-section">
            <h2 class="app-section-title">{{ __('messages.our_services') }}</h2>
            
            <!-- بطاقات الخدمة - Service Cards -->
            <div class="space-y-5">
            
                <!-- الاستشارات - Consultations -->
                <div class="service-card ripple">
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-2 w-full"></div>
                    <div class="p-5">
                        <div class="flex items-start mb-4">
                            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mr-4 rtl:mr-0 rtl:ml-4 flex-shrink-0">
                                <i class="fas fa-comments text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">{{ __('messages.consultations') }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ __('messages.consultations_description') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('services.show', 1) }}" class="flex-1 bg-purple-50 text-purple-700 py-2 px-3 rounded-lg text-center text-sm font-medium hover:bg-purple-100 transition">
                                <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('messages.view_profile') }}
                            </a>
                            <a href="{{ route('booking.start') }}" class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-2 px-3 rounded-lg text-center text-sm font-medium hover:from-purple-700 hover:to-indigo-700 transition shadow-sm">
                                <i class="fas fa-calendar-check {{ app()->getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('messages.book_now') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- جلسات العلاج - Therapy Sessions -->
                <div class="service-card ripple">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-2 w-full"></div>
                    <div class="p-5">
                        <div class="flex items-start mb-4">
                            <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mr-4 rtl:mr-0 rtl:ml-4 flex-shrink-0">
                                <i class="fas fa-brain text-indigo-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">{{ __('messages.therapy_sessions') }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ __('messages.therapy_sessions_description') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('services.show', 2) }}" class="flex-1 bg-indigo-50 text-indigo-700 py-2 px-3 rounded-lg text-center text-sm font-medium hover:bg-indigo-100 transition">
                                <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('messages.view_profile') }}
                            </a>
                            <a href="{{ route('booking.start') }}" class="flex-1 bg-gradient-to-r from-indigo-500 to-blue-500 text-white py-2 px-3 rounded-lg text-center text-sm font-medium hover:from-indigo-600 hover:to-blue-600 transition shadow-sm">
                                <i class="fas fa-calendar-check {{ app()->getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('messages.book_now') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- الجلسات الجماعية - Group Sessions -->
                <div class="service-card ripple">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 w-full"></div>
                    <div class="p-5">
                        <div class="flex items-start mb-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mr-4 rtl:mr-0 rtl:ml-4 flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">{{ __('messages.group_sessions') }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ __('messages.group_sessions_description') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('services.show', 3) }}" class="flex-1 bg-blue-50 text-blue-700 py-2 px-3 rounded-lg text-center text-sm font-medium hover:bg-blue-100 transition">
                                <i class="fas fa-info-circle {{ app()->getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('messages.view_profile') }}
                            </a>
                            <a href="{{ route('booking.start') }}" class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-2 px-3 rounded-lg text-center text-sm font-medium hover:from-blue-600 hover:to-cyan-600 transition shadow-sm">
                                <i class="fas fa-calendar-check {{ app()->getLocale() == 'ar' ? 'ml-1' : 'mr-1' }}"></i> {{ __('messages.book_now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- قسم لماذا تختارنا - Why Choose Us Section -->
        <div class="app-section">
            <h2 class="app-section-title">{{ __('messages.how_it_works') }}</h2>
            
            <div class="space-y-4">
                <!-- ميزة 1: الرعاية المهنية - Professional Care -->
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-full mr-4 rtl:mr-0 rtl:ml-4 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-certificate text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">{{ __('messages.choose_specialist') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.choose_specialist_description') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- ميزة 2: السرية - Confidentiality -->
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full mr-4 rtl:mr-0 rtl:ml-4 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lock text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">{{ __('messages.book_appointment') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.book_appointment_description') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- ميزة 3: المرونة - Flexibility -->
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-full mr-4 rtl:mr-0 rtl:ml-4 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">{{ __('messages.attend_session') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('messages.attend_session_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- قسم دعوة للعمل - Call to Action -->
        <div class="app-section mb-10">
            <div class="relative bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-700 p-6 rounded-2xl text-white text-center shadow-xl overflow-hidden shimmer-effect">
                <!-- زخارف خلفية للـ CTA - CTA Background decorations -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-2xl transform -translate-x-20 -translate-y-20"></div>
                    <div class="absolute bottom-0 right-0 w-40 h-40 bg-white rounded-full blur-2xl transform translate-x-20 translate-y-20"></div>
                </div>
                
                <div class="relative z-10">
                    <h2 class="text-xl font-bold mb-3">{{ __('messages.contact_us') }}</h2>
                    <p class="text-white/90 mb-5 text-sm">{{ __('messages.contact_description') }}</p>
                    
                    <a href="{{ route('contact.create') }}" class="inline-block bg-white text-indigo-700 font-bold rounded-xl py-3 px-6 shadow-lg hover:bg-gray-50 transition-colors duration-300">
                        <i class="fas fa-paper-plane {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('messages.contact_now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تحديد لغة الواجهة للإتجاه - Set UI direction based on language
        const isRTL = {{ app()->getLocale() === 'ar' ? 'true' : 'false' }};
        
        // تطبيق الإتجاه الصحيح للمستند - Apply correct direction to document
        document.documentElement.dir = isRTL ? 'rtl' : 'ltr';
        document.body.classList.add('mobile-app-view');
        
        // تشغيل الرسوم المتحركة للصفحة - Initialize page animations
        document.querySelectorAll('.app-section').forEach((section, index) => {
            setTimeout(() => {
                section.classList.add('animate-in');
            }, 100 * index);
        });
    });
</script>
@endsection
