<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')

@section('title', __('pages.services.title'))

@section('content')
<!-- Mobile Optimized Services Page -->
<div class="container mx-auto px-4 py-6">
    <!-- Hero Section with Background -->
    <section class="relative rounded-xl overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-44 md:h-56 w-full absolute"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-44 md:h-56 text-white px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                {{ __('pages.services.title') }}
            </h1>
            <p class="text-base md:text-xl text-center max-w-lg opacity-90">
                {{ __('pages.services.subtitle') }}
            </p>
        </div>
    </section>

    <!-- Services Scrollable Cards -->
    <section class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 px-1">
            <i class="fas fa-clipboard-list mr-2 text-purple-600"></i> {{ __('pages.services.our_services') }}
        </h2>
        
        <!-- Horizontal Scrollable Service Cards for Mobile -->
        <div class="flex overflow-x-auto pb-4 snap-x hide-scrollbar space-x-4 rtl:space-x-reverse">
            <!-- Consultation Service Card -->
            <div class="min-w-[280px] snap-start bg-white rounded-lg shadow-md p-5 flex flex-col">
                <div class="w-16 h-16 bg-purple-100 rounded-full mb-4 flex items-center justify-center">
                    <i class="fas fa-comments text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ __('messages.consultations') }}
                </h3>
                <p class="text-gray-600 mb-4 flex-grow">
                    {{ __('pages.services.consultation_description') }}
                </p>
                <div class="flex space-x-2 rtl:space-x-reverse mt-auto">
                    <a href="{{ route('services.show', 1) }}" class="flex-1 px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-center text-sm font-medium hover:bg-purple-200 transition">
                        <i class="fas fa-info-circle mr-1"></i> {{ __('pages.services.learn_more') }}
                    </a>
                    <a href="{{ route('booking.start') }}" class="flex-1 px-3 py-2 bg-purple-600 text-white rounded-lg text-center text-sm font-medium hover:bg-purple-700 transition">
                        <i class="fas fa-calendar-plus mr-1"></i> {{ __('pages.booking.book_now') }}
                    </a>
                </div>
            </div>
            
            <!-- Therapy Sessions Card -->
            <div class="min-w-[280px] snap-start bg-white rounded-lg shadow-md p-5 flex flex-col">
                <div class="w-16 h-16 bg-purple-100 rounded-full mb-4 flex items-center justify-center">
                    <i class="fas fa-brain text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ __('messages.therapy_sessions') }}
                </h3>
                <p class="text-gray-600 mb-4 flex-grow">
                    {{ __('pages.services.therapy_description') }}
                </p>
                <div class="flex space-x-2 rtl:space-x-reverse mt-auto">
                    <a href="{{ route('services.show', 2) }}" class="flex-1 px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-center text-sm font-medium hover:bg-purple-200 transition">
                        <i class="fas fa-info-circle mr-1"></i> {{ __('pages.services.learn_more') }}
                    </a>
                    <a href="{{ route('booking.start') }}" class="flex-1 px-3 py-2 bg-purple-600 text-white rounded-lg text-center text-sm font-medium hover:bg-purple-700 transition">
                        <i class="fas fa-calendar-plus mr-1"></i> {{ __('pages.booking.book_now') }}
                    </a>
                </div>
            </div>
            
            <!-- Group Sessions Card -->
            <div class="min-w-[280px] snap-start bg-white rounded-lg shadow-md p-5 flex flex-col">
                <div class="w-16 h-16 bg-purple-100 rounded-full mb-4 flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                    {{ __('messages.group_sessions') }}
                </h3>
                <p class="text-gray-600 mb-4 flex-grow">
                    {{ __('pages.services.group_description') }}
                </p>
                <div class="flex space-x-2 rtl:space-x-reverse mt-auto">
                    <a href="{{ route('services.show', 3) }}" class="flex-1 px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-center text-sm font-medium hover:bg-purple-200 transition">
                        <i class="fas fa-info-circle mr-1"></i> {{ __('pages.services.learn_more') }}
                    </a>
                    <a href="{{ route('booking.start') }}" class="flex-1 px-3 py-2 bg-purple-600 text-white rounded-lg text-center text-sm font-medium hover:bg-purple-700 transition">
                        <i class="fas fa-calendar-plus mr-1"></i> {{ __('pages.booking.book_now') }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="flex justify-center space-x-1 mt-2 md:hidden">
            <div class="w-10 h-1 bg-purple-600 rounded-full"></div>
            <div class="w-2 h-1 bg-gray-300 rounded-full"></div>
            <div class="w-2 h-1 bg-gray-300 rounded-full"></div>
        </div>
    </section>

    <!-- Why Choose Us Cards -->
    <section class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-check-circle mr-2 text-purple-600"></i> {{ __('pages.services.why_choose_us') }}
            </h2>
        </div>
        
        <!-- Feature Cards -->
        <div class="space-y-4">
            <!-- Card 1: Professional Care -->
            <div class="bg-white rounded-lg shadow-sm p-4 transition-all">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-full mr-4 rtl:ml-4 rtl:mr-0 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ __('pages.services.professional_care') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ __('pages.services.professional_care_description') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Confidentiality -->
            <div class="bg-white rounded-lg shadow-sm p-4 transition-all">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-full mr-4 rtl:ml-4 rtl:mr-0 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ __('pages.services.confidential') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ __('pages.services.confidential_description') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Card 3: Flexibility -->
            <div class="bg-white rounded-lg shadow-sm p-4 transition-all">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-full mr-4 rtl:ml-4 rtl:mr-0 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ __('pages.services.flexible') }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ __('pages.services.flexible_description') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="mb-6">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 text-white text-center">
            <h2 class="text-xl font-bold mb-3">{{ __('pages.services.need_help_choosing') }}</h2>
            <p class="mb-4 opacity-90">{{ __('pages.services.help_choosing_description') }}</p>
            <a href="{{ route('contact') }}" class="inline-block bg-white text-purple-600 px-5 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i> {{ __('pages.services.contact_us_btn') }}
            </a>
        </div>
    </section>
    
    <!-- Custom Mobile CSS -->
    <style>
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        
        /* Snap scrolling for iOS */
        .snap-x {
            scroll-snap-type: x mandatory;
        }
        .snap-start {
            scroll-snap-align: start;
        }
    </style>
</div>
@endsection
