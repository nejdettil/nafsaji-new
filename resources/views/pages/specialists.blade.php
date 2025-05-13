<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')

@section('styles')
<style>
    /* لإصلاح عرض الصفحة */
    #desktop-specialists-view, .desktop-view {
        display: block !important;
    }
    
    @media (max-width: 768px) {
        #mobile-specialists-view {
            display: block !important;
        }
        #desktop-specialists-view, .desktop-view {
            display: none !important;
        }
    }
</style>
@endsection

@section('title', __('pages.specialists.title'))

@section('content')
<!-- Mobile Optimized Specialists Page -->
<div id="mobile-specialists-view" class="md:hidden pb-20">
    <!-- Hero Section with Fixed Search Bar (Mobile) -->
    <section class="relative">
        <!-- Gradient Background with Pattern -->
        <div class="bg-gradient-to-r from-purple-700 to-indigo-800 h-44 relative overflow-hidden">
            <div class="absolute inset-0 bg-pattern opacity-10"></div>
            <div class="container mx-auto px-4 h-full flex flex-col items-center justify-center pt-4">
                <h1 class="text-2xl md:text-4xl font-bold text-white mb-1">
                    {{ __('pages.specialists.title') }}
                </h1>
                <p class="text-sm md:text-base text-white opacity-90 text-center">
                    {{ __('pages.specialists.subtitle') }}
                </p>
            </div>
        </div>
        
        <!-- Floating Search Bar -->
        <div class="container mx-auto px-4">
            <div class="relative -mt-6 bg-white rounded-xl shadow-lg p-4 mb-4">
                <div class="flex items-center border rounded-lg p-2 bg-gray-50">
                    <i class="fas fa-search text-gray-400 mx-2"></i>
                    <input 
                        type="text" 
                        placeholder="{{ __('pages.specialists.search_placeholder') }}" 
                        class="flex-grow bg-transparent border-none focus:ring-0 text-sm"
                    >
                </div>
                
                <!-- Filter Tags Scrollable -->
                <div class="mt-3 flex overflow-x-auto hide-scrollbar space-x-2 rtl:space-x-reverse pb-1">
                    <button class="whitespace-nowrap text-xs text-purple-700 border border-purple-200 bg-purple-50 rounded-full px-3 py-1 active:bg-purple-100">
                        <i class="fas fa-sliders-h mr-1"></i> {{ __('pages.specialists.all_filters') }}
                    </button>
                    <button class="whitespace-nowrap text-xs bg-gray-100 rounded-full px-3 py-1 active:bg-gray-200">
                        {{ __('specialists.therapies.psychological') }}
                    </button>
                    <button class="whitespace-nowrap text-xs bg-gray-100 rounded-full px-3 py-1 active:bg-gray-200">
                        {{ __('specialists.therapies.anxiety') }}
                    </button>
                    <button class="whitespace-nowrap text-xs bg-gray-100 rounded-full px-3 py-1 active:bg-gray-200">
                        {{ __('specialists.therapies.depression') }}
                    </button>
                    <button class="whitespace-nowrap text-xs bg-gray-100 rounded-full px-3 py-1 active:bg-gray-200">
                        {{ __('specialists.therapies.family') }}
                    </button>
                    <button class="whitespace-nowrap text-xs bg-gray-100 rounded-full px-3 py-1 active:bg-gray-200">
                        {{ __('specialists.therapies.online') }}
                    </button>
                </div>
            </div>
        </div>
    </section>

<!-- DESKTOP VERSION (NEW) -->
<div class="md:block hidden" id="desktop-specialists-view">
    <!-- Hero Section - Enhanced for Desktop -->
    <section class="bg-gradient-to-br from-purple-800 to-indigo-900 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-500 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
        
        <div class="container mx-auto px-6 py-16 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="md:w-1/2">
                    <h1 class="text-4xl font-bold text-white mb-4 leading-tight">
                        {{ __('pages.specialists.title') }}
                    </h1>
                    <p class="text-xl text-white opacity-90 mb-8 max-w-lg">
                        {{ __('pages.specialists.subtitle') }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#specialists-list" class="bg-white text-purple-700 hover:bg-gray-100 px-6 py-3 rounded-lg text-base font-semibold shadow-lg transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                            <i class="fas fa-user-md mr-2"></i> {{ __('pages.specialists.browse_all') }}
                        </a>
                        <a href="#filter-specialists" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow-lg transition-all duration-300 transform hover:-translate-y-1 inline-flex items-center">
                            <i class="fas fa-filter mr-2"></i> {{ __('pages.specialists.filter_results') }}
                        </a>
                    </div>
                </div>
                <div class="md:w-5/12">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg blur opacity-25"></div>
                        <div class="relative bg-white rounded-lg shadow-xl overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-search text-purple-600 mr-2"></i> {{ __('pages.specialists.find_specialist') }}
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.search_by_name') }}</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </div>
                                            <input type="text" class="pl-10 block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="{{ __('pages.specialists.search_placeholder') }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.specialty') }}</label>
                                        <select class="block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                            <option value="">{{ __('pages.specialists.any_specialty') }}</option>
                                            <option value="psychological">{{ __('specialists.therapies.psychological') }}</option>
                                            <option value="anxiety">{{ __('specialists.therapies.anxiety') }}</option>
                                            <option value="depression">{{ __('specialists.therapies.depression') }}</option>
                                            <option value="family">{{ __('specialists.therapies.family') }}</option>
                                        </select>
                                    </div>
                                    <div class="flex gap-4">
                                        <div class="w-1/2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.session_type') }}</label>
                                            <select class="block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                                <option value="">{{ __('pages.specialists.any_type') }}</option>
                                                <option value="online">{{ __('specialists.online') }}</option>
                                                <option value="in-person">{{ __('specialists.in_person') }}</option>
                                            </select>
                                        </div>
                                        <div class="w-1/2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.availability') }}</label>
                                            <select class="block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                                <option value="">{{ __('pages.specialists.any_time') }}</option>
                                                <option value="today">{{ __('pages.specialists.today') }}</option>
                                                <option value="this-week">{{ __('pages.specialists.this_week') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                                        {{ __('pages.specialists.search_now') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Specialists Cards (Optimized for Mobile) -->
    <section class="md:hidden container mx-auto px-4 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">
                <i class="fas fa-user-md text-purple-600 mr-2"></i> {{ __('pages.specialists.our_specialists') }}
            </h2>
            <div class="text-sm text-purple-600">
                <span class="font-medium">{{ count($specialists ?? []) }}</span> {{ __('pages.specialists.available') }}
            </div>
        </div>
        
        <!-- Specialists Cards (Mobile) -->
        <div class="space-y-4">
            @foreach($specialists as $specialist)
            <div class="specialist-card bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="flex flex-col">
                    <!-- Card Header with Background and Avatar -->
                    <div class="relative pt-3 px-3">
                        <div class="flex items-center">
                            <!-- Avatar with Gradient Circle -->
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white text-xl font-bold border-2 border-white shadow-md mr-3 rtl:ml-3 rtl:mr-0">
                                {{ mb_substr($specialist['name'], 0, 1) }}
                            </div>
                            
                            <!-- Name and Specialty -->
                            <div class="flex-grow">
                                <h3 class="text-base font-bold text-gray-800 leading-tight">
                                    {{ $specialist['name'] }}
                                </h3>
                                <div class="flex items-center text-xs">
                                    <span class="bg-purple-100 text-purple-800 rounded-full px-2 py-0.5 inline-block">
                                        {{ $specialist['specialty'] }}
                                    </span>
                                </div>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <div class="flex text-yellow-400">
                                        @for($j = 0; $j < 5; $j++)
                                            <i class="fas fa-star {{ $j < 4 ? '' : 'text-gray-300' }} text-xs"></i>
                                        @endfor
                                    </div>
                                    <span class="ms-1">4.0 ({{ 10 + $loop->index * 3 }})</span>
                                </div>
                            </div>
                            
                            <!-- Quick Book Button -->
                            <a href="{{ route('booking.start') }}" class="bg-purple-600 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-sm hover:bg-purple-700 transition">
                                <i class="fas fa-calendar-plus"></i>
                            </a>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-sm text-gray-600 mt-3 pb-3 border-b">
                            {{ Str::limit($specialist['description'], 120) }}
                        </p>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="px-3 py-2 mt-1 flex items-center justify-between">
                        <div class="flex items-center space-x-1 rtl:space-x-reverse text-sm">
                            <div class="flex items-center text-gray-500 px-1">
                                <i class="fas fa-video mr-1 text-purple-400"></i>
                                <span class="text-xs">{{ __('specialists.online') }}</span>
                            </div>
                            <div class="flex items-center text-gray-500 px-1">
                                <i class="fas fa-clock mr-1 text-purple-400"></i>
                                <span class="text-xs">{{ 30 + ($loop->index % 3) * 15 }} {{ __('specialists.min') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('specialists.show', $specialist['id'] ?? 1) }}" class="text-purple-600 text-sm font-medium border border-purple-200 rounded-lg px-3 py-1 hover:bg-purple-50 transition">
                            {{ __('pages.specialists.view_profile') }} <i class="fas fa-chevron-right ms-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    
    <!-- Specialists Listing - Desktop Version -->
    <section id="specialists-list" class="desktop-view container mx-auto py-16">
        <!-- Specialists Filters - Desktop -->
        <div class="mb-12 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-5 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-filter text-purple-600 mr-3"></i> {{ __('pages.specialists.filter_specialists') }}
                    </h2>
                    <div class="text-purple-600 font-medium">
                        <span class="font-bold text-xl">{{ count($specialists ?? []) }}</span> {{ __('pages.specialists.available') }}
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-5 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.keyword') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" class="pl-10 block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="{{ __('pages.specialists.search_placeholder') }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.specialty') }}</label>
                        <select class="block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">{{ __('pages.specialists.any_specialty') }}</option>
                            <option value="psychological">{{ __('specialists.therapies.psychological') }}</option>
                            <option value="anxiety">{{ __('specialists.therapies.anxiety') }}</option>
                            <option value="depression">{{ __('specialists.therapies.depression') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.session_type') }}</label>
                        <select class="block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">{{ __('pages.specialists.any_type') }}</option>
                            <option value="online">{{ __('specialists.online') }}</option>
                            <option value="in-person">{{ __('specialists.in_person') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('pages.specialists.rating') }}</label>
                        <select class="block w-full border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <option value="">{{ __('pages.specialists.any_rating') }}</option>
                            <option value="4">4+ {{ __('pages.specialists.stars') }}</option>
                            <option value="4.5">4.5+ {{ __('pages.specialists.stars') }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-5 flex justify-between items-center">
                    <div class="flex flex-wrap gap-2">
                        <button class="inline-flex items-center text-sm bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                            {{ __('specialists.therapies.depression') }} <i class="fas fa-times ml-2"></i>
                        </button>
                        <button class="inline-flex items-center text-sm bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                            {{ __('specialists.online') }} <i class="fas fa-times ml-2"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="text-sm text-gray-500 hover:text-purple-600 font-medium">
                            {{ __('pages.specialists.clear_all') }}
                        </button>
                        <button class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-5 py-2 rounded-lg shadow-md hover:shadow-lg transition duration-200 font-medium flex items-center">
                            <i class="fas fa-search mr-2"></i> {{ __('pages.specialists.apply_filters') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Specialists Grid - Desktop -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($specialists as $specialist)
            <div class="specialist-card bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1 border border-gray-100">
                <!-- Card Header with Cover Image -->
                <div class="h-32 bg-gradient-to-r from-purple-500 to-indigo-600 relative">
                    <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-black/60 to-transparent"></div>
                    
                    <!-- Tags positioned on top -->
                    <div class="absolute top-3 right-3 flex flex-wrap gap-2 justify-end">
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded-full bg-opacity-90">
                            {{ $specialist['specialty'] }}
                        </span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full bg-opacity-90">
                            {{ __('specialists.online') }}
                        </span>
                    </div>
                    
                    <!-- Avatar positioned to overlap the border -->
                    <div class="absolute -bottom-10 left-6 w-20 h-20 rounded-xl bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-md">
                        {{ mb_substr($specialist['name'], 0, 1) }}
                    </div>
                </div>
                
                <!-- Card Content -->
                <div class="pt-12 px-6 pb-6">
                    <!-- Name and Rating -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                {{ $specialist['name'] }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $specialist['specialty'] }}
                            </p>
                        </div>
                        <div class="flex items-center text-sm bg-yellow-50 px-2 py-1 rounded-md">
                            <div class="text-yellow-500 mr-1">
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="font-medium">4.{{ $loop->index % 9 + 1 }}</span>
                            <span class="text-gray-500 ml-1">({{ 10 + $loop->index * 7 }})</span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-5">
                        {{ app()->getLocale() == 'ar' ? 'طبيب متخصص في علاج مشاكل الصحة النفسية، لديه خبرة في علاج الاكتئاب والقلق واضطرابات النوم.' : 'Specialized doctor in treating mental health issues with experience in treating depression, anxiety, and sleep disorders.' }}
                    </p>
                    
                    <!-- Specializations -->
                    <div class="mb-5">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __('pages.specialists.areas_of_expertise') }}</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-md">{{ __('specialists.therapies.' . strtolower(explode(' ', $specialist['specialty'])[0])) }}</span>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-md">{{ __('specialists.therapies.anxiety') }}</span>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-md">{{ __('specialists.therapies.depression') }}</span>
                            @if($loop->index % 2 == 0)
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-md">{{ __('specialists.therapies.family') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Session Info -->
                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-video mr-2 text-purple-500"></i>
                                <span class="text-sm">{{ __('specialists.online') }}</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-clock mr-2 text-purple-500"></i>
                                <span class="text-sm">{{ 30 + ($loop->index % 3) * 15 }} {{ __('specialists.min') }}</span>
                            </div>
                        </div>
                        <div class="text-purple-700 text-sm font-medium">
                            {{ $loop->index % 2 == 0 ? __('pages.specialists.available_today') : __('pages.specialists.next_week') }}
                        </div>
                    </div>
                </div>
                
                <!-- Card Actions -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <a href="{{ route('specialists.show', $specialist['id'] ?? 1) }}" class="text-purple-600 font-medium hover:text-purple-800 transition-colors">
                        {{ __('pages.specialists.view_profile') }}
                    </a>
                    <a href="{{ route('booking.start') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all inline-flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i> {{ __('pages.specialists.book_session') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Mobile-friendly Pagination -->
        <div class="mt-8 flex justify-center md:hidden">
            <div class="inline-flex space-x-1 rtl:space-x-reverse text-sm">
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-600">
                    <i class="fas fa-chevron-left rtl:hidden"></i>
                    <i class="fas fa-chevron-right ltr:hidden"></i>
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-purple-600 text-white border border-purple-600 rounded-md">
                    1
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-800">
                    2
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-600">
                    <i class="fas fa-chevron-right rtl:hidden"></i>
                    <i class="fas fa-chevron-left ltr:hidden"></i>
                </a>
            </div>
        </div>
        
        <!-- Desktop Pagination -->
        <div class="hidden md:flex justify-center mt-12">
            <div class="inline-flex space-x-2 rtl:space-x-reverse">
                <a href="#" class="flex items-center justify-center h-10 px-4 bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                    <i class="fas fa-chevron-left mr-2 rtl:hidden"></i>
                    <i class="fas fa-chevron-right ml-2 ltr:hidden"></i>
                    <span>{{ __('pages.specialists.previous') }}</span>
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium rounded-md shadow-sm">
                    1
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-800 font-medium transition-colors">
                    2
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-800 font-medium transition-colors">
                    3
                </a>
                <span class="w-10 h-10 flex items-center justify-center text-gray-500">
                    ...
                </span>
                <a href="#" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-800 font-medium transition-colors">
                    8
                </a>
                <a href="#" class="flex items-center justify-center h-10 px-4 bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                    <span>{{ __('pages.specialists.next') }}</span>
                    <i class="fas fa-chevron-right ml-2 rtl:hidden"></i>
                    <i class="fas fa-chevron-left mr-2 ltr:hidden"></i>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Quick Help Card (Mobile) -->
    <section class="md:hidden container mx-auto px-4 mb-10">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-5 text-white shadow-md">
            <div class="flex items-start">
                <div class="flex-grow">
                    <h2 class="text-lg font-bold mb-2">{{ __('pages.specialists.need_help_choosing') }}</h2>
                    <p class="text-sm opacity-90 mb-4">{{ __('pages.specialists.help_description') }}</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('contact.create') }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center shadow-sm">
                            <i class="fas fa-envelope mr-2"></i> {{ __('messages.contact_now') }}
                        </a>
                        <a href="tel:+9661234567890" class="bg-purple-500 bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center border border-white border-opacity-30">
                            <i class="fas fa-phone-alt mr-2"></i> {{ __('messages.call_us') }}
                        </a>
                    </div>
                </div>
                <div class="hidden sm:block w-24">
                    <div class="w-20 h-20 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <i class="fas fa-headset text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Enhanced Help Card (Desktop) -->
    <section class="hidden desktop-view container mx-auto my-16">
        <div class="bg-gradient-to-br from-purple-600 via-indigo-600 to-indigo-800 rounded-2xl overflow-hidden shadow-xl relative">
            <!-- Background Pattern Elements -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-pattern"></div>
            <div class="absolute -right-5 -bottom-5 w-40 h-40 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute -left-10 top-10 w-32 h-32 rounded-full bg-purple-300 opacity-20 blur-xl"></div>
            
            <div class="relative z-10 p-10 flex items-center">
                <div class="w-3/5 pr-10">
                    <h2 class="text-3xl font-bold text-white mb-4">{{ __('pages.specialists.need_help_choosing') }}</h2>
                    <p class="text-lg text-white opacity-90 mb-8 max-w-2xl">{{ __('pages.specialists.help_description_extended') }}</p>
                    <div class="flex gap-4">
                        <a href="{{ route('contact.create') }}" class="bg-white hover:bg-gray-100 text-purple-700 px-6 py-3 rounded-lg text-base font-semibold inline-flex items-center shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <i class="fas fa-envelope mr-2"></i> {{ __('messages.contact_now') }}
                        </a>
                        <a href="tel:+9661234567890" class="bg-purple-500 hover:bg-purple-600 bg-opacity-30 hover:bg-opacity-40 text-white px-6 py-3 rounded-lg text-base font-semibold inline-flex items-center border border-white border-opacity-30 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <i class="fas fa-phone-alt mr-2"></i> {{ __('messages.call_us') }}
                        </a>
                    </div>
                </div>
                <div class="w-2/5 flex justify-end">
                    <div class="w-72 h-72 relative">
                        <div class="absolute inset-0 bg-white bg-opacity-10 rounded-full animate-pulse-slow"></div>
                        <div class="absolute inset-4 bg-white bg-opacity-15 rounded-full"></div>
                        <div class="absolute inset-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-white text-6xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section - Desktop Only -->
    <section class="desktop-view container mx-auto py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">{{ __('pages.specialists.faq_title') }}</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">{{ __('pages.specialists.faq_subtitle') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3 text-sm">1</span>
                    {{ __('pages.specialists.faq1_title') }}
                </h3>
                <p class="text-gray-600">{{ __('pages.specialists.faq1_content') }}</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3 text-sm">2</span>
                    {{ __('pages.specialists.faq2_title') }}
                </h3>
                <p class="text-gray-600">{{ __('pages.specialists.faq2_content') }}</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3 text-sm">3</span>
                    {{ __('pages.specialists.faq3_title') }}
                </h3>
                <p class="text-gray-600">{{ __('pages.specialists.faq3_content') }}</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3 text-sm">4</span>
                    {{ __('pages.specialists.faq4_title') }}
                </h3>
                <p class="text-gray-600">{{ __('pages.specialists.faq4_content') }}</p>
            </div>
        </div>
    </section>
    
    <!-- Custom CSS -->
    <style>
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.384 7.9l-7.9-7.9h2.83zM16.686 0L10.2 6.485 11.616 7.9l7.9-7.9h-2.83zm20.97 0l9.315 9.314-1.414 1.414L34.828 0h2.83zM22.344 0L13.03 9.314l1.414 1.414L25.172 0h-2.83zM32 0l12.142 12.142-1.414 1.414L30 2.828 17.272 15.556l-1.414-1.414L28 0h4zM.284 0l28 28-1.414 1.414L0 2.828 0 0h.284zM0 5.657l25.456 25.456-1.414 1.414L0 8.485V5.657z' fill='%23fff' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% {
                opacity: 0.1;
            }
            50% {
                opacity: 0.2;
            }
        }
        
        /* Media query for desktop view */
        @media (min-width: 768px) {
            .desktop-view {
                display: block !important;
            }
            #mobile-specialists-view {
                display: none !important;
            }
        }
    </style>
    
    <!-- JavaScript for responsive view switching -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function handleScreenSizeChange() {
                var desktopView = document.getElementById('desktop-specialists-view');
                var mobileView = document.getElementById('mobile-specialists-view');
                var desktopElements = document.querySelectorAll('.desktop-view');
                
                if (window.innerWidth >= 768) {
                    // Desktop view
                    if (desktopView) desktopView.classList.remove('hidden');
                    if (mobileView) mobileView.classList.add('hidden');
                    desktopElements.forEach(function(element) {
                        element.classList.remove('hidden');
                    });
                } else {
                    // Mobile view
                    if (desktopView) desktopView.classList.add('hidden');
                    if (mobileView) mobileView.classList.remove('hidden');
                    desktopElements.forEach(function(element) {
                        element.classList.add('hidden');
                    });
                }
            }
            
            // Run on page load
            handleScreenSizeChange();
            
            // Run on window resize
            window.addEventListener('resize', handleScreenSizeChange);
        });
    </script>
</div>
@endsection
