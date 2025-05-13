<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#9333ea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">
    <title>{{ config('app.name', 'نفسجي') }} - @yield('title', __('messages.welcome'))</title>
    
    <!-- Manifest for PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary: #9333ea;
            --primary-dark: #7928ca;
            --secondary: #6366f1;
            --accent: #f43f5e;
            --safe-top: env(safe-area-inset-top, 0px);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
            --safe-left: env(safe-area-inset-left, 0px);
            --safe-right: env(safe-area-inset-right, 0px);
        }
        
        /* Base Styles */
        [dir="rtl"] body { font-family: 'Tajawal', sans-serif; }
        [dir="ltr"] body { font-family: 'Nunito', sans-serif; }
        
        html {
            height: -webkit-fill-available;
            scroll-behavior: smooth;
        }
        
        body {
            min-height: 100vh;
            min-height: -webkit-fill-available;
            display: flex;
            flex-direction: column;
            background-color: #f5f5f5;
            /* App-like touch inertial scrolling */
            -webkit-overflow-scrolling: touch;
            /* Remove tap highlight on mobile devices */
            -webkit-tap-highlight-color: transparent;
            /* Ensure all content is within safe areas */
            padding-top: var(--safe-top);
            padding-bottom: calc(60px + var(--safe-bottom));
            padding-left: var(--safe-left);
            padding-right: var(--safe-right);
            /* Disable overscroll effects */
            overscroll-behavior: none;
        }
        
        main {
            flex: 1;
            padding-bottom: 70px; /* Space for bottom nav */
        }
        
        /* App-like Button Styles */
        .btn-primary {
            background-color: var(--primary);
            color: white;
            transition: all 0.2s;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .btn-primary:active {
            transform: scale(0.98);
            background-color: var(--primary-dark);
        }
        
        .btn-outline {
            border: 1.5px solid var(--primary);
            color: var(--primary);
            border-radius: 12px;
            transition: all 0.15s;
            font-weight: 600;
        }
        
        .btn-outline:active {
            transform: scale(0.98);
            background-color: var(--primary);
            color: white;
        }
        
        .text-gradient-purple {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* App UI Components */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        /* Extra styles for making main content have enough space at the bottom */
        main {
            padding-bottom: 90px;
        }
        
        /* App Header Style */
        .app-header {
            position: sticky;
            top: 0;
            z-index: 40;
            background: white;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .app-header-title {
            font-weight: bold;
            font-size: 1.25rem;
            margin-inline-start: 8px;
            flex-grow: 1;
            text-align: center;
        }
        
        /* App-like Icon Button */
        .icon-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: #f5f5f7;
            transition: background-color 0.2s;
        }
        
        .icon-btn:active {
            background-color: #e5e7eb;
        }
        
        /* Floating Action Button Navigation */
        .app-bottom-nav {
            position: fixed;
            bottom: var(--safe-bottom);
            right: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 50;
            pointer-events: none;
            padding-bottom: 20px;
        }
        
        /* Floating Action Button and Menu Styles */
        .floating-action-button {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(147, 51, 234, 0.4);
            position: relative;
            z-index: 55;
            cursor: pointer;
            pointer-events: auto;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .floating-action-button:active {
            transform: scale(0.95);
            box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
        }
        
        .floating-action-button i {
            font-size: 1.8rem;
            color: white;
        }
        
        .floating-action-label {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 15px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
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
        
        .mobile-app-view header {
            padding-top: env(safe-area-inset-top, 20px);
        }
        
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
            height: 60px;
        }
        
        .app-content {
            padding-top: 70px !important;
        }
        
        .bottom-nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            height: 100%;
            padding-bottom: 10px;
            position: relative;
            margin-top: -10px;
        }
        
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
        }
        
        /* Bottom Navigation Icons & Labels */
        .bottom-nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            transition: all 0.2s ease;
        }
        
        .bottom-nav-icon i {
            font-size: 1.1rem;
            color: #64748b;
        }
        
        .bottom-nav-label {
            font-size: 0.7rem;
            color: #64748b;
            font-weight: 500;
        }
        
        /* Main Booking Button */
        .main-booking-btn .bottom-nav-icon-main {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
            margin-bottom: 5px;
        }
        
        .main-booking-btn .bottom-nav-icon-main i {
            font-size: 1.5rem;
            color: white;
        }
        
        .main-booking-btn .bottom-nav-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        /* Extra Bottom Padding for Content */
        main {
            padding-bottom: 80px;
        }
        
        /* Language Switcher (iOS/Android Toggle Style) */
        .app-lang-switch {
            position: relative;
            display: inline-flex;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0.5rem 1rem;
        }
        
        .app-lang-option {
            padding: 5px 12px;
            border-radius: 14px;
            font-size: 0.8rem;
            line-height: 1.2;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .app-lang-option.active {
            background: var(--primary);
            color: white;
        }

        /* Desktop specific styles */
        .desktop-only {
            display: block;
        }
        @media (max-width: 767px) {
            .desktop-only {
                display: none !important;
            }
        }
        
        /* Hide mobile elements on desktop */
        @media (min-width: 768px) {
            .app-status-bar,
            .app-bottom-nav,
            .nav-curved-container,
            .app-header {
                display: none;
            }
            
            body {
                padding-top: 0;
                padding-bottom: 0;
            }
            
            main {
                padding-bottom: 0;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- MOBILE ELEMENTS -->
    
    <!-- Mobile App Header - Mobile Only - تم تثبيته عند التمرير -->
    <div class="app-header md:hidden" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; z-index: 99999 !important; background: rgba(255,255,255,0.95) !important; backdrop-filter: blur(10px) !important; -webkit-backdrop-filter: blur(10px) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;">
        <div x-data="{ open: false }" style="position: static !important;">
            <button class="icon-btn" @click="open = !open" x-init="$watch('open', value => document.body.classList.toggle('overflow-hidden', value))" style="position: relative !important; z-index: 99999 !important;">
                <i class="fas fa-bars text-gray-700"></i>
            </button>
            
            <!-- Mobile Side Menu -->
            <div x-show="open" @click.away="open = false" x-cloak style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; background-color: rgba(0, 0, 0, 0.5) !important; z-index: 99990 !important;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div style="position: fixed !important; top: 0 !important; {{ app()->getLocale() == 'ar' ? 'right: 0 !important;' : 'left: 0 !important;' }} height: 100vh !important; width: 80% !important; max-width: 350px !important; background-color: white !important; box-shadow: 0 0 20px rgba(0, 0, 0, 0.2) !important; z-index: 99995 !important; overflow-y: auto !important; padding: 20px !important;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }}" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }}">
                    <div class="flex justify-end mb-6">
                        <button @click="open = false" class="p-2 rounded-lg bg-gray-100">
                            <i class="fas fa-times text-gray-500"></i>
                        </button>
                    </div>
                    
                    <!-- User Section -->
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-md mx-auto">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-lg font-semibold">{{ __('messages.welcome') }}</p>
                            <p class="text-sm text-gray-500">{{ __('messages.sign_in_prompt') }}</p>
                            
                            <!-- أزرار تسجيل الدخول أو لوحة التحكم - Login/Admin Panel Buttons -->
                            <div class="flex items-center justify-center gap-2 mt-3">
                                @auth
                                    @if(Auth::user()->isAdmin() || Auth::user()->role === 'admin')
                                        <a href="/filament-admin" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white text-sm font-medium shadow-sm w-full text-center">
                                            {{ __('لوحة التحكم') }}
                                        </a>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-4 py-2 border border-purple-600 rounded-lg text-purple-600 text-sm font-medium w-full text-center">
                                            {{ __('تسجيل خروج') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @else
                                        <a href="/profile" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white text-sm font-medium shadow-sm w-full text-center">
                                            {{ __('الملف الشخصي') }}
                                        </a>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-4 py-2 border border-purple-600 rounded-lg text-purple-600 text-sm font-medium w-full text-center">
                                            {{ __('تسجيل خروج') }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('mobile.login') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white text-sm font-medium shadow-sm w-full text-center">
                                        {{ __('messages.login') }}
                                    </a>
                                    <a href="{{ route('mobile.register') }}" class="px-4 py-2 border border-purple-600 rounded-lg text-purple-600 text-sm font-medium w-full text-center">
                                        {{ __('messages.register') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="space-y-1 mb-6">
                        <a href="/" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ request()->is('/') ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-home w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('messages.home') }}
                        </a>
                        <a href="/specialists" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ request()->is('specialists*') ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-user-md w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('messages.specialists') }}
                        </a>
                        <a href="/services" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ request()->is('services*') ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-hands-helping w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('messages.services') }}
                        </a>
                        <a href="/booking" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ request()->is('bookings*') ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-calendar-check w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('messages.bookings') }}
                        </a>
                        <a href="/contact" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ request()->is('contact*') ? 'text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-envelope w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('messages.contact_us') }}
                        </a>
                    </div>
                    
                    <!-- تغيير اللغة - Language Switcher -->
                    <div class="border-t border-gray-200 pt-4 mt-4 mb-4">
                        <p class="text-xs text-gray-500 mb-3">{{ __('messages.app_settings') }}</p>
                        
                        <a href="{{ route('language.switch', ['lang' => 'ar']) }}" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ app()->getLocale() == 'ar' ? 'bg-purple-50 text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-language w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            العربية
                        </a>
                        
                        <a href="{{ route('language.switch', ['lang' => 'en']) }}" class="block py-2 px-4 rounded-lg hover:bg-gray-50 {{ app()->getLocale() == 'en' ? 'bg-purple-50 text-purple-600 font-medium' : 'text-gray-700' }}">
                            <i class="fas fa-language w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            English
                        </a>
                        
                        <a href="/settings" class="block py-2 px-4 rounded-lg hover:bg-gray-50 text-gray-700 mt-2">
                            <i class="fas fa-cog w-6 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                            {{ __('messages.settings') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- App Logo -->
        <a href="{{ route('home') }}" class="flex items-center">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg">
                ن
            </div>
            <span class="ms-2 text-lg font-bold text-gray-800">نفسجي</span>
        </a>
        
        <!-- Right Action Buttons -->
        <div class="flex items-center gap-2">
            <!-- أزرار تبديل اللغة المباشرة - Direct Language Switcher Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('language.switch', ['lang' => 'ar']) }}" class="text-sm font-medium {{ app()->getLocale() == 'ar' ? 'text-purple-600 font-bold' : 'text-gray-700' }}">
                    عربي
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('language.switch', ['lang' => 'en']) }}" class="text-sm font-medium {{ app()->getLocale() == 'en' ? 'text-purple-600 font-bold' : 'text-gray-700' }}">
                    EN
                </a>
            </div>
        </div>
    </div>
    
    <!-- App Language Switcher - Mobile Only - تم نقله إلى قائمة الهيدر -->
    <!-- سيتم وضع زر اللغة في الهيدر بدلاً من شريط ثابت -->
    
    <!-- DESKTOP ELEMENTS -->
    <!-- Desktop Header (visible only on desktop) -->
    <header class="bg-white shadow-sm hidden md:block mb-8">
        <div class="container mx-auto px-4">
            <div class="h-16 flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        ن
                    </div>
                    <span class="ms-2 text-xl font-bold text-gray-800">نفسجي</span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-6 rtl:space-x-reverse">
                    <a href="/" class="text-gray-700 hover:text-purple-600 px-2 py-1 font-medium">
                        {{ __('messages.home') }}
                    </a>
                    <a href="/specialists" class="text-gray-700 hover:text-purple-600 px-2 py-1 font-medium">
                        {{ __('messages.specialists') }}
                    </a>
                    <a href="/services" class="text-gray-700 hover:text-purple-600 px-2 py-1 font-medium">
                        {{ __('messages.services') }}
                    </a>
                    <a href="/booking" class="text-gray-700 hover:text-purple-600 px-2 py-1 font-medium">
                        {{ __('messages.bookings') }}
                    </a>
                    <a href="/contact" class="text-gray-700 hover:text-purple-600 px-2 py-1 font-medium">
                        {{ __('messages.contact_us') }}
                    </a>
                </nav>
                
                <!-- Authentication & Language Buttons for Desktop -->
                <div class="hidden md:flex items-center gap-3">
                    <!-- Language Switcher for Desktop (Simple Text) -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('language.switch', ['lang' => 'ar']) }}" class="text-sm font-medium {{ app()->getLocale() == 'ar' ? 'text-purple-600 font-bold' : 'text-gray-700 hover:text-purple-600' }}">
                            عربي
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('language.switch', ['lang' => 'en']) }}" class="text-sm font-medium {{ app()->getLocale() == 'en' ? 'text-purple-600 font-bold' : 'text-gray-700 hover:text-purple-600' }}">
                            EN
                        </a>
                    </div>
                    
                    <!-- أزرار تسجيل الدخول/لوحة التحكم - Login/Admin Panel Buttons -->
                    <div class="flex items-center gap-3 ml-5">
                        @auth
                            @if(Auth::user()->isAdmin() || Auth::user()->role === 'admin')
                                <a href="/filament-admin" class="px-5 py-2 bg-gradient-to-br from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                    {{ __('لوحة التحكم') }}
                                </a>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();" class="px-5 py-2 border border-purple-500 rounded-lg text-purple-600 hover:text-white hover:bg-purple-500 font-medium transition-all">
                                    {{ __('تسجيل خروج') }}
                                </a>
                                <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @else
                                <a href="/profile" class="px-5 py-2 bg-gradient-to-br from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                    {{ __('الملف الشخصي') }}
                                </a>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();" class="px-5 py-2 border border-purple-500 rounded-lg text-purple-600 hover:text-white hover:bg-purple-500 font-medium transition-all">
                                    {{ __('تسجيل خروج') }}
                                </a>
                                <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 border border-purple-500 rounded-lg text-purple-600 hover:text-white hover:bg-purple-500 font-medium transition-all">
                                {{ __('pages.auth.login.title') }}
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-br from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                {{ __('pages.auth.register.title') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 container mx-auto mt-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 container mx-auto mt-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Main Content Container -->
    <div class="container mx-auto px-4">
        <!-- Desktop Content -->
        <div class="hidden md:block">
            @yield('content')
        </div>
        
        <!-- Mobile App Content -->
        <div class="md:hidden">
            <main class="pb-20 pt-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Fixed Bottom Navigation Bar -->
    <style>
        /* شريط التنقل السفلي - يظهر فقط على الأجهزة المحمولة */
        .bottom-nav {
            position: fixed;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 450px;
            background: transparent;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 12px 0 10px;
            z-index: 9999;
            height: 75px;
            /* تأكيد إخفاء العنصر على الشاشات المتوسطة والكبيرة */
            display: flex;
        }
        
        /* إخفاء الشريط السفلي في وضع سطح المكتب */
        @media (min-width: 768px) {
            .bottom-nav {
                display: none !important;
            }
        }
        
        /* خلفية الشريط السفلي بشكل منحني */
        .bottom-nav::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(249, 250, 251, 0.95));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(147, 51, 234, 0.15);
            box-shadow: 0 5px 20px rgba(147, 51, 234, 0.15);
            border-radius: 25px;
            z-index: -1;
        }
        
        /* تم إزالة البروز البيضاوي من المنتصف */
        /* تصغير الشريط من الجوانب */
        .bottom-nav::before {
            transform: scaleX(0.9);
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            width: 33%;
            position: relative;
            transition: transform 0.2s ease;
            padding: 5px 0;
        }
        
        .nav-item:active {
            transform: scale(0.95);
        }
        
        .nav-item i {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 6px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-item i::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(147, 51, 234, 0.15);
            border-radius: 50%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .nav-item:active i::after {
            transform: translate(-50%, -50%) scale(1.5);
            opacity: 1;
        }
        
        .nav-item span {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .booking-item {
            transform: translateY(-15px);
        }
        
        .booking-item i {
            background: linear-gradient(135deg, #9333ea, #6366f1);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            box-shadow: 0 5px 15px rgba(147, 51, 234, 0.4);
            margin-bottom: 8px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .booking-item i::before {
            position: relative;
            z-index: 2;
        }
        
        .booking-item i::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(135deg, #f43f5e, #9333ea);
            border-radius: 50%;
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .booking-item:active i::after {
            opacity: 1;
        }
        
        .booking-item span {
            color: #9333ea;
            font-weight: 600;
            margin-top: 2px;
        }
        
        .nav-active i {
            color: #9333ea;
        }
        
        .nav-active span {
            color: #9333ea;
            font-weight: 600;
        }
        
        .nav-active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background-color: #9333ea;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(147, 51, 234, 0.5);
        }
    </style>
    
    <div class="bottom-nav md:hidden">

        <a href="{{ route('specialists.index') }}" class="nav-item {{ request()->is('specialists*') ? 'nav-active' : '' }}">
            <i class="fas fa-user-md"></i>
            <span>{{ __('messages.specialists') }}</span>
        </a>
        
        <a href="{{ route('booking.start') }}" class="nav-item booking-item {{ request()->is('booking*') ? 'nav-active' : '' }}">
            <i class="fas fa-calendar-plus"></i>
            <span>{{ __('messages.book_now') }}</span>
        </a>
        
        <a href="{{ route('contact.create') }}" class="nav-item {{ request()->is('contact*') ? 'nav-active' : '' }}">
            <i class="fas fa-envelope"></i>
            <span>{{ __('messages.contact_us') }}</span>
        </a>
    </div>
    
    <!-- تم حذف زر التبديل لوضع الجوال -->
    
    <!-- Mobile Notifications -->
    @if(session('success'))
    <div id="successAlert" class="fixed top-20 inset-x-0 flex justify-center items-start z-50 px-4 pt-6 pointer-events-none md:hidden">
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg pointer-events-auto max-w-md w-full flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 rtl:mr-3 rtl:ml-0">
                <p>{{ session('success') }}</p>
            </div>
            <div class="ml-auto rtl:mr-auto rtl:ml-0">
                <button onclick="document.getElementById('successAlert').remove()" class="text-green-500 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    @endif
    
    @if(session('error'))
    <div id="errorAlert" class="fixed top-20 inset-x-0 flex justify-center items-start z-50 px-4 pt-6 pointer-events-none md:hidden">
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg pointer-events-auto max-w-md w-full flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 rtl:mr-3 rtl:ml-0">
                <p>{{ session('error') }}</p>
            </div>
            <div class="ml-auto rtl:mr-auto rtl:ml-0">
                <button onclick="document.getElementById('errorAlert').remove()" class="text-red-500 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('errorAlert');
            if (alert) {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    @yield('scripts')
    
    <script>
        // Detect active page
        document.addEventListener('DOMContentLoaded', function() {
            const path = window.location.pathname;
            
            // Set Alpine.js activeTab value
            if (typeof Alpine !== 'undefined') {
                window.Alpine.store('nav', {
                    activeTab: path
                });
            }
        });
    </script>

    <!-- فوتر سطح المكتب - Desktop Footer -->
    <footer class="hidden md:block bg-gray-50 border-t border-gray-100 mt-16">
        <div class="container mx-auto px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- المعلومات الرئيسية - Main Info -->
                <div class="md:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md">
                            {{ app()->getLocale() == 'ar' ? 'ن' : 'N' }}
                        </div>
                        <span class="ms-2 text-xl font-bold text-gray-800">{{ app()->getLocale() == 'ar' ? 'نفسجي' : 'Nafsaji' }}</span>
                    </div>
                    <p class="text-gray-600 mb-4">
                        @if(app()->getLocale() == 'ar')
                            نفسجي هي منصة متخصصة تقدم خدمات نفسية واستشارية عن بعد لمساعدتك على تحسين جودة حياتك
                        @else
                            Nafsaji is a specialized platform offering remote psychological services and consultations to help you improve your quality of life
                        @endif
                    </p>
                    <div class="flex space-x-4 rtl:space-x-reverse">
                        <a href="#" class="text-gray-500 hover:text-purple-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-purple-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-purple-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <!-- روابط سريعة - Quick Links -->
                <div class="md:col-span-1">
                    <h3 class="font-bold text-gray-800 mb-4">
                        {{ app()->getLocale() == 'ar' ? 'روابط سريعة' : 'Quick Links' }}
                    </h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('specialists.index') }}" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'المتخصصون' : 'Specialists' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'الخدمات' : 'Services' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact.create') }}" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- الخدمات - Services -->
                <div class="md:col-span-1">
                    <h3 class="font-bold text-gray-800 mb-4">
                        {{ app()->getLocale() == 'ar' ? 'خدماتنا' : 'Our Services' }}
                    </h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/services#therapy" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'العلاج النفسي' : 'Psychological Therapy' }}
                            </a>
                        </li>
                        <li>
                            <a href="/services#consulting" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'الاستشارات النفسية' : 'Psychological Consulting' }}
                            </a>
                        </li>
                        <li>
                            <a href="/services#testing" class="text-gray-600 hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'الاختبارات النفسية' : 'Psychological Testing' }}
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- معلومات الاتصال - Contact Info -->
                <div class="md:col-span-1">
                    <h3 class="font-bold text-gray-800 mb-4">
                        {{ app()->getLocale() == 'ar' ? 'معلومات الاتصال' : 'Contact Info' }}
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 text-purple-600 me-3"></i>
                            <span class="text-gray-600">
                                {{ app()->getLocale() == 'ar' ? 'الرياض، المملكة العربية السعودية' : 'Riyadh, Saudi Arabia' }}
                            </span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt text-purple-600 me-3"></i>
                            <span class="text-gray-600">+966 123 456 789</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-purple-600 me-3"></i>
                            <span class="text-gray-600">info@nafsaji.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- حقوق النشر - Copyright -->
            <div class="border-t border-gray-200 mt-10 pt-6 text-center">
                <p class="text-gray-500">
                    &copy; {{ date('Y') }} {{ app()->getLocale() == 'ar' ? 'نفسجي. جميع الحقوق محفوظة' : 'Nafsaji. All rights reserved' }}
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
