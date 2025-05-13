@extends('layouts.app')

@section('title', __('pages.specialists.title'))

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
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    /* أزرار الرأس - Header Buttons */
    .icon-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.03);
        border-radius: 50%;
        color: #333;
        border: none;
        transition: all 0.2s;
    }
    
    .icon-btn:active {
        transform: scale(0.94);
        background: rgba(0,0,0,0.08);
    }
    
    /* محتوى التطبيق - App Content Container */
    .app-content {
        padding: 20px;
        padding-top: calc(65px + env(safe-area-inset-top, 20px)); /* إضافة مسافة للهيدر الثابت */
        margin-bottom: 80px;
        position: relative;
    }
    
    /* زخارف خلفية - Background Decorations */
    .app-bg-decoration {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        filter: blur(70px);
        z-index: -1;
    }
    
    /* أقسام التطبيق - App Sections */
    .app-section {
        margin-bottom: 24px;
    }
    
    /* بطاقات المتخصصين - Specialists Cards */
    .specialist-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        position: relative;
        transform: translateZ(0);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .specialist-card:active {
        transform: scale(0.98);
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    
    .specialist-header {
        padding: 16px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }
    
    .specialist-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 16px;
        border: 3px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .specialist-info {
        flex: 1;
    }
    
    .specialist-content {
        padding: 16px;
    }
    
    .specialist-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    
    .specialist-stat {
        text-align: center;
        flex: 1;
        padding: 8px 4px;
        border-radius: 8px;
        background: rgba(0,0,0,0.02);
    }
    
    .specialist-footer {
        padding: 12px 16px;
        border-top: 1px solid rgba(0,0,0,0.04);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* شريط البحث - Search Bar */
    .app-search-bar {
        background: white;
        border-radius: 12px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .app-search-bar input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 4px 8px;
        font-size: 14px;
    }
    
    .app-search-bar input:focus {
        outline: none;
    }
    
    /* فلاتر التصفية - Filters */
    .filter-pills {
        display: flex;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 16px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .filter-pills::-webkit-scrollbar {
        display: none;
    }
    
    .filter-pill {
        white-space: nowrap;
        padding: 6px 12px;
        border-radius: 20px;
        margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 8px;
        background-color: #f2f2f2;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        transition: all 0.2s;
    }
    
    .filter-pill.active {
        background-color: #6366f1;
        color: white;
    }
    
    .filter-pill:active {
        transform: scale(0.96);
    }
    
    /* أزرار التطبيق - App Buttons */
    .app-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
    }
    
    .app-button:active {
        transform: scale(0.96);
    }
    
    .app-button-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }
    
    .app-button-secondary {
        background-color: #f5f5f5;
        color: #333;
    }
    
    .app-button-outline {
        background-color: transparent;
        border: 1px solid #6366f1;
        color: #6366f1;
    }
    
    /* قائمة الأقسام - Section Tabs */
    .section-tabs {
        display: flex;
        margin-bottom: 16px;
        border-radius: 10px;
        overflow: hidden;
        background-color: #f5f5f5;
        padding: 3px;
    }
    
    .section-tab {
        flex: 1;
        padding: 10px;
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .section-tab.active {
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: #6366f1;
    }
    
    /* تأثير تموج الإصبع - Ripple Effect */
    .ripple {
        position: relative;
        overflow: hidden;
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
</style>
@endsection

@section('content')
<!-- عرض التطبيق المخصص للجوال (يظهر فقط على شاشات الجوال) - Mobile App View -->
<div class="block" x-data="{ showMenu: false }"> 
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content">
        <!-- زخارف خلفية - Background decorations -->
        <div class="app-bg-decoration" style="width: 150px; height: 150px; top: 5%; right: -50px; opacity: 0.4;"></div>
        <div class="app-bg-decoration" style="width: 200px; height: 200px; bottom: 30%; left: -100px; opacity: 0.3;"></div>
        
        <!-- قسم العنوان مع خلفية متدرجة - Title Section with Gradient Background -->
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl opacity-90 shadow-lg"></div>
            <div class="relative z-10 p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">{{ __('pages.specialists.title') }}</h1>
                <p class="text-white/90">{{ __('pages.specialists.subtitle') }}</p>
                
                <!-- شريط البحث متكامل مع العنوان - Integrated Search Bar -->
                <div class="mt-4 bg-white/20 backdrop-blur-md rounded-xl p-3 flex items-center shadow-md border border-white/30">
                    <i class="fas fa-search text-white/70 mx-2"></i>
                    <input type="text" placeholder="{{ __('pages.specialists.search_placeholder') }}" class="bg-transparent w-full text-white placeholder-white/70 border-none focus:ring-0 focus:outline-none">
                </div>
            </div>
        </div>
        
        <!-- فلاتر التصفية المحسنة - Enhanced Filter Pills -->
        <div class="filter-pills p-1 bg-gray-50 rounded-xl shadow-sm mb-6">
            <div class="filter-pill active shadow-sm flex items-center justify-center"><i class="fas fa-star-of-life mr-1 text-xs"></i> {{ __('pages.specialists.all_specialties') }}</div>
            <div class="filter-pill flex items-center justify-center"><i class="fas fa-brain mr-1 text-xs"></i> {{ __('specialists.therapies.psychological') }}</div>
            <div class="filter-pill flex items-center justify-center"><i class="fas fa-wind mr-1 text-xs"></i> {{ __('specialists.therapies.anxiety') }}</div>
            <div class="filter-pill flex items-center justify-center"><i class="fas fa-cloud-rain mr-1 text-xs"></i> {{ __('specialists.therapies.depression') }}</div>
            <div class="filter-pill flex items-center justify-center"><i class="fas fa-users mr-1 text-xs"></i> {{ __('specialists.therapies.family') }}</div>
        </div>
        
        <!-- بطاقات المتخصصين المحسنة - Enhanced Specialists Cards -->
        <div class="app-section">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 p-2 rounded-full mr-2"><i class="fas fa-user-md"></i></span>
                    {{ __('pages.specialists.available_specialists') }}
                </h2>
                <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                    <span>{{ count($specialists ?? []) }}</span>
                    <span class="mx-1">{{ __('pages.specialists.available') }}</span>
                </div>
            </div>
            
            <!-- قائمة المتخصصين المحسنة - Enhanced Specialists List -->
            <div class="space-y-6">
                @foreach($specialists as $specialist)
                <div class="specialist-card ripple bg-white overflow-hidden rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                    <!-- هيدر بتصميم متدرج - Header with gradient design -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-90"></div>
                        <div class="relative p-4 flex items-center z-10">
                            <!-- صورة المتخصص المحسنة - Enhanced Specialist Avatar -->
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-xl font-bold border-4 border-white shadow-md mr-4 text-indigo-600">
                                {{ mb_substr($specialist['name'], 0, 1) }}
                            </div>
                            
                            <!-- معلومات المتخصص - Specialist Info -->
                            <div>
                                <h3 class="font-bold text-white text-lg">{{ $specialist['name'] }}</h3>
                                <div class="flex items-center">
                                    <span class="bg-white/20 backdrop-blur-sm text-white rounded-full px-3 py-1 text-xs font-medium border border-white/20">
                                        {{ $specialist['specialty'] }}
                                    </span>
                                </div>
                                <!-- تقييم النجوم - Star Rating -->
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-300 text-xs">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $specialist['rating'])
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-white/90 ml-1">({{ $specialist['reviews_count'] }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- المحتوى الرئيسي - Main Content -->
                    <div class="p-4">
                        <!-- إحصائيات المتخصص المحسنة - Enhanced Specialist Stats -->
                        <div class="flex justify-between p-2 bg-gray-50 rounded-xl mb-4 shadow-sm">
                            <div class="text-center px-3 border-r border-gray-200">
                                <div class="text-xs text-gray-600">{{ __('pages.specialists.experience') }}</div>
                                <div class="font-bold text-indigo-700 flex items-center justify-center">
                                    <i class="fas fa-award text-xs mr-1 opacity-60"></i> {{ $specialist['experience'] }}
                                </div>
                            </div>
                            <div class="text-center px-3 border-r border-gray-200">
                                <div class="text-xs text-gray-600">{{ __('pages.specialists.sessions') }}</div>
                                <div class="font-bold text-indigo-700 flex items-center justify-center">
                                    <i class="fas fa-video text-xs mr-1 opacity-60"></i> {{ $specialist['sessions_count'] }}+
                                </div>
                            </div>
                            <div class="text-center px-3">
                                <div class="text-xs text-gray-600">{{ __('pages.specialists.price') }}</div>
                                <div class="font-bold text-indigo-700 flex items-center justify-center">
                                    <i class="fas fa-tag text-xs mr-1 opacity-60"></i> {{ $specialist['price'] }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- نبذة قصيرة - Short Bio -->
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-2 mb-3 bg-gray-50 p-3 rounded-lg italic">
                            <i class="fas fa-quote-left text-indigo-200 mr-1"></i> {{ $specialist['bio'] }}
                        </p>
                        
                        <!-- تخصصات - Specialties -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach(explode(',', $specialist['expertise']) as $expertise)
                            <span class="inline-block bg-indigo-50 text-indigo-700 rounded-full px-3 py-1 text-xs font-medium border border-indigo-100">
                                <i class="fas fa-hashtag text-indigo-400 mr-1 text-xs"></i> {{ trim($expertise) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- أزرار الإجراءات المحسنة - Enhanced Action Buttons -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('specialists.show', $specialist['id']) }}" class="text-indigo-600 text-sm font-medium flex items-center">
                            <i class="fas fa-user-circle mr-1"></i> {{ __('pages.specialists.view_profile') }}
                        </a>
                        <a href="{{ route('booking.start') }}" 
                           class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm py-2 px-4 rounded-lg font-medium flex items-center shadow-md hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-calendar-check mr-2"></i> {{ __('pages.specialists.book_appointment') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- رابط المزيد المحسن - Enhanced More Link -->
            <div class="text-center mt-8">
                <a href="{{ route('specialists.index') }}" class="bg-white text-indigo-700 border-2 border-indigo-200 font-bold rounded-xl py-3 px-6 inline-flex items-center shadow-sm hover:shadow-md transition-all duration-300">
                    {{ __('pages.specialists.show_more') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }}"></i>
                </a>
            </div>
        </div>
        
        <!-- قسم المساعدة في الاختيار - Help Choosing Section -->
        <div class="app-section mt-8">
            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 text-white rounded-2xl p-5 shadow-lg relative overflow-hidden">
                <!-- زخارف خلفية - Background Decorations -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full transform translate-x-20 -translate-y-20"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-10 rounded-full transform -translate-x-20 translate-y-20"></div>
                
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-2">{{ __('pages.specialists.need_help_choosing') }}</h3>
                    <p class="text-white/80 text-sm mb-4">{{ __('pages.specialists.help_description') }}</p>
                    <a href="{{ route('contact.create') }}" class="app-button bg-white text-purple-700 shadow-md">
                        <i class="fas fa-headset mr-1"></i> {{ __('pages.specialists.get_assistance') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// كود جافاسكريبت لجعله يشبه التطبيق أكثر - JavaScript to make it feel more like an app
document.addEventListener('DOMContentLoaded', function() {
    // للأجهزة المحمولة فقط - Mobile devices only
    if (window.innerWidth <= 768) {
        // إضافة تأثيرات اللمس للفلاتر - Add touch effects to filters
        const filterPills = document.querySelectorAll('.filter-pill');
        filterPills.forEach(pill => {
            pill.addEventListener('click', function() {
                // إزالة الحالة النشطة من جميع الفلاتر - Remove active state from all filters
                filterPills.forEach(p => p.classList.remove('active'));
                // إضافة الحالة النشطة للفلتر المحدد - Add active state to selected filter
                this.classList.add('active');
            });
        });
        
        // تحسين التمرير للفلاتر - Smooth scrolling for filters
        const filterContainer = document.querySelector('.filter-pills');
        if (filterContainer) {
            let isDown = false;
            let startX;
            let scrollLeft;

            filterContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                filterContainer.classList.add('active');
                startX = e.pageX - filterContainer.offsetLeft;
                scrollLeft = filterContainer.scrollLeft;
            });
            
            filterContainer.addEventListener('mouseleave', () => {
                isDown = false;
                filterContainer.classList.remove('active');
            });
            
            filterContainer.addEventListener('mouseup', () => {
                isDown = false;
                filterContainer.classList.remove('active');
            });
            
            filterContainer.addEventListener('mousemove', (e) => {
                if(!isDown) return;
                e.preventDefault();
                const x = e.pageX - filterContainer.offsetLeft;
                const walk = (x - startX) * 2;
                filterContainer.scrollLeft = scrollLeft - walk;
            });
            
            // نفس الشيء للمس - Same for touch
            filterContainer.addEventListener('touchstart', (e) => {
                isDown = true;
                startX = e.touches[0].pageX - filterContainer.offsetLeft;
                scrollLeft = filterContainer.scrollLeft;
            }, { passive: true });
            
            filterContainer.addEventListener('touchend', () => {
                isDown = false;
            });
            
            filterContainer.addEventListener('touchmove', (e) => {
                if(!isDown) return;
                const x = e.touches[0].pageX - filterContainer.offsetLeft;
                const walk = (x - startX) * 2;
                filterContainer.scrollLeft = scrollLeft - walk;
            }, { passive: true });
        }
    }
});
</script>
@endsection
