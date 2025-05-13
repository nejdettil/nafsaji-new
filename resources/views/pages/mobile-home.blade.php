@extends('layouts.app')

@section('title', __('pages.home.title'))

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
    
    /* إضافة مساحة لشريط الحالة - Add padding for status bar */
    .mobile-app-view header {
        padding-top: env(safe-area-inset-top, 20px);
    }
    
    /* شريط التطبيق العلوي - App Header Bar */
    .app-header {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        height: 60px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        padding: 0 16px;
        animation: fadeInDown 0.3s ease-out;
    }
    
    /* محتوى التطبيق - App Content */
    .app-content {
        padding: 20px 16px;
        padding-top: calc(65px + env(safe-area-inset-top, 20px)); /* إضافة مسافة للهيدر الثابت */
        padding-bottom: 80px; /* مساحة لشريط التنقل السفلي - Space for bottom nav */
        animation: fadeIn 0.4s ease-out;
    }
    
    /* بطاقات العناصر - Item Cards */
    .app-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 20px;
        padding: 20px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid rgba(0,0,0,0.03);
        overflow: hidden;
        position: relative;
    }
    
    .app-card:active {
        transform: scale(0.98);
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    
    /* شريط التنقل السفلي - Bottom Navigation Bar */
    .app-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 68px;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        display: flex;
        justify-content: space-around;
        align-items: center;
        box-shadow: 0 -1px 10px rgba(0,0,0,0.05);
        z-index: 100;
        padding-bottom: env(safe-area-inset-bottom, 0px);
    }
    
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #666;
        font-size: 11px;
        font-weight: 500;
        padding: 10px 0;
        transition: all 0.2s ease;
    }
    
    .nav-item.active {
        color: #6366f1; /* لون إنديغو - Indigo color */
    }
    
    .nav-item i {
        font-size: 22px;
        margin-bottom: 5px;
        transition: transform 0.2s ease;
    }
    
    .nav-item:active i {
        transform: scale(0.92);
    }
    
    /* قسم البطاقات المتحركة - Swipeable Cards Section */
    .swipe-cards {
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        display: flex;
        scroll-padding: 16px;
        gap: 14px;
        padding: 8px 16px;
        margin: 16px -16px;
        -webkit-overflow-scrolling: touch;
    }
    
    .swipe-cards::-webkit-scrollbar {
        display: none;
    }
    
    .swipe-card {
        scroll-snap-align: start;
        min-width: 280px;
        max-width: 280px;
        height: 160px;
        flex: 0 0 auto;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        transition: transform 0.3s ease;
    }
    
    .swipe-card:active {
        transform: scale(0.98);
    }
    
    /* أزرار الدعوة للعمل - CTA Buttons */
    .app-button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px 24px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }
    
    .app-button:active {
        transform: translateY(1px);
    }
    
    .app-button-primary {
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        color: white;
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
    }
    
    .app-button-primary:active {
        box-shadow: 0 3px 8px rgba(99, 102, 241, 0.2);
    }
    
    .app-button-outline {
        border: 1.5px solid #d1d5db;
        background: white;
        color: #374151;
    }
    
    .app-section-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 18px;
        margin-top: 32px;
        color: #1f2937;
        position: relative;
    }
    
    .app-section-title:after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(to right, #8b5cf6, #6366f1);
        border-radius: 6px;
    }
    
    [dir="rtl"] .app-section-title:after {
        left: auto;
        right: 0;
    }
    
    /* زخارف خلفية - Background decorations */
    .app-bg-decoration {
        position: absolute;
        border-radius: 50%;
        z-index: -1;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(99, 102, 241, 0.1));
        animation: float 6s ease-in-out infinite;
    }
    
    /* تخصيص حسب اللغة - RTL support */
    [dir="rtl"] .swipe-cards {
        scroll-padding: 16px 0 16px 16px;
    }
    
    /* الرسوم المتحركة - Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    /* مؤشر المقدار - Progress indicator */
    .progress-dots {
        display: flex;
        justify-content: center;
        margin: 16px 0;
        gap: 8px;
    }
    
    .progress-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .progress-dot.active {
        width: 24px;
        border-radius: 10px;
        background-color: #6366f1;
    }
    
    /* مقاطع المحتوى - Content sections */
    .app-section {
        animation: fadeInUp 0.5s ease-out;
        animation-fill-mode: both;
    }
    
    .app-section:nth-child(1) { animation-delay: 0.1s; }
    .app-section:nth-child(2) { animation-delay: 0.2s; }
    .app-section:nth-child(3) { animation-delay: 0.3s; }
    .app-section:nth-child(4) { animation-delay: 0.4s; }
    
    /* تأثيرات اللمعان - Shimmer effects */
    .shimmer-effect {
        position: relative;
        overflow: hidden;
    }
    
    .shimmer-effect:after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: rotate(30deg);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) rotate(30deg); }
        100% { transform: translateX(100%) rotate(30deg); }
    }
</style>
@endsection

@section('content')
<!-- تحقق من أجهزة الجوال - Check for mobile devices and add body class -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة فئة العرض المحمول
    document.body.classList.add('mobile-app-view');
    
    // تحديد اللغة والإتجاه
    const isRTL = document.documentElement.lang === 'ar';
    
    // إخفاء شريط عنوان المتصفح عند التمرير للأسفل
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop && scrollTop > 60) {
            // التمرير للأسفل - إخفاء الشريط العلوي
            document.querySelector('header').classList.add('header-hidden');
        } else {
            // التمرير للأعلى - إظهار الشريط العلوي
            document.querySelector('header').classList.remove('header-hidden');
        }
        lastScrollTop = scrollTop;
    });
    
    // إضافة تأثيرات النقر/اللمس للعناصر
    const touchElements = document.querySelectorAll('.app-card, .swipe-card, .app-button');
    touchElements.forEach(function(element) {
        element.addEventListener('touchstart', function() {
            this.classList.add('touch-active');
        });
        element.addEventListener('touchend', function() {
            this.classList.remove('touch-active');
            // إضافة تأثير تموج عند النقر
            const ripple = document.createElement('span');
            ripple.classList.add('touch-ripple');
            this.appendChild(ripple);
            setTimeout(function() {
                ripple.remove();
            }, 600);
        });
    });
    
    // تمرير البطاقات بشكل تلقائي (محاكاة التطبيقات)
    const cardSwipe = document.querySelector('.swipe-cards');
    if (cardSwipe) {
        let autoScrollInterval;
        const startAutoScroll = function() {
            autoScrollInterval = setInterval(function() {
                const scrollAmount = isRTL ? -150 : 150;
                cardSwipe.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                // إعادة التمرير للبداية عند الوصول للنهاية
                if ((isRTL && cardSwipe.scrollLeft <= 0) || 
                    (!isRTL && cardSwipe.scrollLeft + cardSwipe.clientWidth >= cardSwipe.scrollWidth)) {
                    cardSwipe.scrollTo({ left: isRTL ? cardSwipe.scrollWidth : 0, behavior: 'smooth' });
                }
            }, 4000);
        };
        
        // بدء التمرير التلقائي بعد 2 ثانية
        setTimeout(startAutoScroll, 2000);
        
        // إيقاف التمرير التلقائي عند لمس المستخدم للبطاقات
        cardSwipe.addEventListener('touchstart', function() {
            clearInterval(autoScrollInterval);
        });
        
        // إعادة تشغيل التمرير التلقائي بعد انتهاء اللمس
        cardSwipe.addEventListener('touchend', function() {
            setTimeout(startAutoScroll, 5000);
        });
    }
    
    // تشغيل الرسوم المتحركة للصفحة
    document.querySelectorAll('.app-section').forEach((section, index) => {
        setTimeout(() => {
            section.classList.add('animate-in');
        }, 100 * index);
    });
    
    // محاكاة حالة الإتصال بالإنترنت (مثل التطبيقات)
    const networkStatus = document.getElementById('network-status');
    if (networkStatus) {
        window.addEventListener('online', function() {
            networkStatus.classList.remove('offline');
            networkStatus.textContent = isRTL ? 'متصل' : 'Online';
            setTimeout(() => networkStatus.classList.add('fade-out'), 2000);
        });
        
        window.addEventListener('offline', function() {
            networkStatus.classList.add('offline');
            networkStatus.classList.remove('fade-out');
            networkStatus.textContent = isRTL ? 'غير متصل' : 'Offline';
        });
    }
});
</script>

<!-- عرض التطبيق المخصص للجوال (يظهر فقط على شاشات الجوال) - Mobile App View (visible on all screens for testing) -->
<div class="block" x-data="{ showMenu: false }"> <!-- يظهر على جميع الشاشات للاختبار - Visible on all screens for testing -->
    <!-- تم إزالة الهيدر حيث تم تعريفه في ملف الـ layout -->
    
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content">
        <!-- زخارف خلفية - Background decorations -->
        <div class="app-bg-decoration" style="width: 150px; height: 150px; top: 5%; right: -50px; opacity: 0.4;"></div>
        <div class="app-bg-decoration" style="width: 200px; height: 200px; bottom: 30%; left: -100px; opacity: 0.3;"></div>
        
        <!-- قسم الترحيب - Welcome Section -->
        <div class="app-section mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('pages.mobile.welcome') }} <span class="wave-emoji">👋</span></h1>
            <p class="text-gray-600">{{ __('pages.mobile.start_journey') }}</p>
        </div>
        
        <!-- مؤشر سريع للخدمات - Quick services indicator -->
        <div class="progress-dots mb-2">
            <div class="progress-dot active"></div>
            <div class="progress-dot"></div>
            <div class="progress-dot"></div>
        </div>
        
        <!-- بطاقات مستخدمي الخدمة الرئيسية - Main Service Cards -->
        <div class="swipe-cards mb-8">
            <div class="swipe-card bg-gradient-to-br from-purple-600 to-indigo-700 text-white shimmer-effect">
                <div class="text-lg font-bold mb-2">{{ __('pages.mobile.browse_specialists') }}</div>
                <p class="text-white/80 text-sm mb-4">{{ __('pages.mobile.specialists_description') }}</p>
                <a href="{{ route('mobile.specialists') }}" class="flex items-center text-white mt-auto">
                    <span>{{ __('pages.mobile.explore_now') }}</span>
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }}"></i>
                </a>
            </div>
            
            <div class="swipe-card bg-gradient-to-br from-blue-600 to-cyan-600 text-white shimmer-effect">
                <div class="text-lg font-bold mb-2">{{ __('pages.mobile.our_services') }}</div>
                <p class="text-white/80 text-sm mb-4">{{ __('pages.mobile.services_description') }}</p>
                <a href="{{ route('services.index') }}" class="flex items-center text-white mt-auto">
                    <span>{{ __('pages.mobile.learn_more') }}</span>
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }}"></i>
                </a>
            </div>
            
            <div class="swipe-card bg-gradient-to-br from-pink-600 to-rose-600 text-white shimmer-effect">
                <div class="text-lg font-bold mb-2">{{ __('pages.mobile.contact_us') }}</div>
                <p class="text-white/80 text-sm mb-4">{{ __('pages.mobile.contact_description') }}</p>
                <a href="{{ route('contact.create') }}" class="flex items-center text-white mt-auto">
                    <span>{{ __('pages.mobile.contact_now') }}</span>
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }}"></i>
                </a>
            </div>
        </div>
        
        <!-- كيف يعمل نفسجي - How it Works -->
        <div class="app-section">
            <h2 class="app-section-title">{{ __('pages.mobile.how_it_works') }}</h2>
            
            <div class="flex flex-col gap-5">
                <!-- الخطوة 1 - Step 1 -->
                <div class="app-card relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-purple-100 to-transparent rounded-bl-full opacity-70"></div>
                    <div class="flex items-start relative z-10">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="{{ app()->getLocale() == 'ar' ? 'mr-4' : 'ml-4' }}">
                            <h3 class="font-bold text-gray-800 mb-2 text-lg">{{ __('pages.mobile.step1_title') }}</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ __('pages.mobile.step1_description') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- الخطوة 2 - Step 2 -->
                <div class="app-card relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-blue-100 to-transparent rounded-bl-full opacity-70"></div>
                    <div class="flex items-start relative z-10">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-cyan-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="{{ app()->getLocale() == 'ar' ? 'mr-4' : 'ml-4' }}">
                            <h3 class="font-bold text-gray-800 mb-2 text-lg">{{ __('pages.mobile.step2_title') }}</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ __('pages.mobile.step2_description') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- الخطوة 3 - Step 3 -->
                <div class="app-card relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-rose-100 to-transparent rounded-bl-full opacity-70"></div>
                    <div class="flex items-start relative z-10">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-600 to-pink-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="{{ app()->getLocale() == 'ar' ? 'mr-4' : 'ml-4' }}">
                            <h3 class="font-bold text-gray-800 mb-2 text-lg">{{ __('pages.mobile.step3_title') }}</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ __('pages.mobile.step3_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- آراء المستخدمين - Testimonials -->
        <div class="app-section-title">{{ app()->getLocale() == 'ar' ? 'آراء المستخدمين' : 'User Testimonials' }}</div>
        
        <div class="swipe-cards">
            <!-- شهادة 1 - Testimonial 1 -->
            <div class="swipe-card bg-white p-4">
                <div class="flex text-yellow-400 mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 text-sm mb-4">{{ app()->getLocale() == 'ar' ? '"كانت تجربتي مع نفسجي ممتازة. المختص النفسي كان متفهماً ومحترفاً. أشعر بتحسن كبير بعد الجلسات."' : '"My experience with Nafsaji was excellent. The specialist was understanding and professional. I feel much better after the sessions."' }}</p>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xs">{{ app()->getLocale() == 'ar' ? 'س' : 'S' }}</div>
                    <div class="mr-2">
                        <div class="font-medium text-sm">{{ app()->getLocale() == 'ar' ? 'سارة م.' : 'Sarah M.' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- شهادة 2 - Testimonial 2 -->
            <div class="swipe-card bg-white p-4">
                <div class="flex text-yellow-400 mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-600 text-sm mb-4">{{ app()->getLocale() == 'ar' ? '"ما يميز نفسجي هو سهولة استخدام المنصة والمرونة في المواعيد. ساعدتني الجلسات على التغلب على القلق."' : '"What makes Nafsaji special is the ease of platform use and flexibility in appointments. Sessions helped me overcome anxiety."' }}</p>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-xs">{{ app()->getLocale() == 'ar' ? 'م' : 'M' }}</div>
                    <div class="mr-2">
                        <div class="font-medium text-sm">{{ app()->getLocale() == 'ar' ? 'محمد ع.' : 'Mohammed A.' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- قسم الدعوة للعمل - CTA Section -->
        <div class="app-section mt-10">
            <div class="relative bg-gradient-to-br from-purple-600 via-indigo-600 to-violet-700 p-6 rounded-2xl text-white text-center shadow-xl overflow-hidden shimmer-effect">
                <!-- زخارف خلفية للـ CTA - CTA Background decorations -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full blur-2xl transform -translate-x-20 -translate-y-20"></div>
                    <div class="absolute bottom-0 right-0 w-40 h-40 bg-white rounded-full blur-2xl transform translate-x-20 translate-y-20"></div>
                </div>
                
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-3">{{ __('pages.cta.start_journey') }}</h2>
                    <p class="text-white/90 mb-6 text-sm">{{ __('pages.cta.take_first_step') }}</p>
                    
                    <a href="{{ route('specialists.index') }}" class="app-button app-button-primary block w-full mb-4 shadow-xl">
                        <i class="fas fa-user-md {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('pages.mobile.browse_specialists') }}
                    </a>
                    
                    <a href="{{ route('contact.create') }}" class="app-button app-button-outline block w-full bg-white/10 backdrop-blur-sm border-white/30">
                        <i class="fas fa-headset {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('pages.mobile.need_help') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- عرض سطح المكتب الحالي (يظهر فقط على الشاشات الكبيرة) - Desktop View (hidden for testing) -->
<div class="hidden"></div>
@endsection

@section('scripts')
<script>
// كود جافاسكريبت لجعله يشبه التطبيق أكثر - JavaScript to make it feel more like an app
document.addEventListener('DOMContentLoaded', function() {
    // للأجهزة المحمولة فقط - Mobile devices only
    if (window.innerWidth <= 768) {
        // منع القفز عند النقر - Prevent jump on tap
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                const href = e.target.getAttribute('href');
                if (href && href !== '#') {
                    // تأثير النقر - Click effect
                    e.target.style.opacity = '0.7';
                    setTimeout(function() {
                        window.location.href = href;
                    }, 150);
                }
            }
        });
        
        // تأثير السحب للتحديث - Pull to refresh effect
        let touchStartY = 0;
        document.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        document.addEventListener('touchmove', function(e) {
            const touchY = e.touches[0].clientY;
            const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            
            // إذا كان المستخدم في أعلى الصفحة ويسحب للأسفل - If at top and pulling down
            if (scrollTop === 0 && touchY > touchStartY + 50) {
                document.body.style.paddingTop = '40px';
                document.body.style.transition = 'padding-top 0.2s';
            }
        }, { passive: true });
        
        document.addEventListener('touchend', function() {
            document.body.style.paddingTop = '0px';
        }, { passive: true });
    }
});
</script>
@endsection
