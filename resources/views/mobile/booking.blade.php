@extends('layouts.app')

@section('title', __('messages.booking'))

@section('content')
<div class="mobile-booking-page pb-20">
    <!-- هيدر الصفحة -->
    <div class="app-header md:hidden bg-white shadow-sm">
        <a href="{{ route('mobile.home') }}" class="icon-btn">
            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} text-gray-700"></i>
        </a>
        
        <h1 class="app-header-title text-gradient">{{ app()->getLocale() == 'ar' ? 'حجز موعد' : 'Book Appointment' }}</h1>
        
        <div class="icon-btn">
            <i class="far fa-question-circle text-gray-700"></i>
        </div>
    </div>
    
    <!-- محتوى الصفحة -->
    <div class="container mx-auto px-4 py-6">
        <!-- شريط التقدم -->
        <div class="booking-progress mb-8">
            <div class="flex justify-between relative">
                <div class="progress-line absolute h-1 bg-gray-200 top-1/2 left-0 right-0 -mt-0.5 z-0"></div>
                <div class="progress-line-active absolute h-1 bg-primary-600 top-1/2 left-0 w-1/3 -mt-0.5 z-0"></div>
                
                <div class="step active z-10">
                    <div class="step-circle flex items-center justify-center w-8 h-8 rounded-full bg-primary-600 text-white text-sm border-2 border-white shadow-md">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="step-label text-xs mt-1 text-primary-700 font-medium">{{ app()->getLocale() == 'ar' ? 'المتخصص' : 'Specialist' }}</div>
                </div>
                
                <div class="step z-10">
                    <div class="step-circle flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 text-sm border-2 border-white shadow-sm">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <div class="step-label text-xs mt-1 text-gray-500">{{ app()->getLocale() == 'ar' ? 'الموعد' : 'Date & Time' }}</div>
                </div>
                
                <div class="step z-10">
                    <div class="step-circle flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 text-sm border-2 border-white shadow-sm">
                        <i class="far fa-credit-card"></i>
                    </div>
                    <div class="step-label text-xs mt-1 text-gray-500">{{ app()->getLocale() == 'ar' ? 'الدفع' : 'Payment' }}</div>
                </div>
            </div>
        </div>
        
        <!-- عنوان القسم -->
        <div class="section-title mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ app()->getLocale() == 'ar' ? 'اختر المتخصص' : 'Choose a Specialist' }}</h2>
            <p class="text-sm text-gray-500">{{ app()->getLocale() == 'ar' ? 'حدد المتخصص المناسب لاحتياجاتك' : 'Select the specialist that matches your needs' }}</p>
        </div>
        
        <!-- مربع البحث -->
        <div class="mb-6">
            <x-search-bar :placeholder="app()->getLocale() == 'ar' ? 'ابحث عن متخصص...' : 'Search for a specialist...'" />
        </div>
        
        <!-- فلتر التخصصات -->
        <div class="categories-filter mb-6 overflow-x-auto hide-scrollbar">
            <div class="flex gap-2">
                <button class="filter-chip active">{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</button>
                <button class="filter-chip">{{ app()->getLocale() == 'ar' ? 'الصحة النفسية' : 'Mental Health' }}</button>
                <button class="filter-chip">{{ app()->getLocale() == 'ar' ? 'الإرشاد الأسري' : 'Family Counseling' }}</button>
                <button class="filter-chip">{{ app()->getLocale() == 'ar' ? 'التنمية الذاتية' : 'Self Development' }}</button>
            </div>
        </div>
        
        <!-- قائمة المتخصصين -->
        <div class="specialists-list grid grid-cols-1 gap-4 mb-10">
            @for($i = 1; $i <= 5; $i++)
                <div class="specialist-item card bg-white p-4 rounded-xl flex items-center">
                    <div class="specialist-avatar w-16 h-16 rounded-full overflow-hidden mr-4">
                        <img src="{{ asset('images/specialists/avatar' . $i . '.jpg') }}" alt="Specialist" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="specialist-info flex-grow">
                        <h3 class="font-semibold text-gray-800">{{ $i == 1 ? (app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. Ahmed Mohamed') : (app()->getLocale() == 'ar' ? 'د. سارة علي' : 'Dr. Sara Ali') }}</h3>
                        <p class="text-sm text-gray-600">{{ $i % 2 == 0 ? (app()->getLocale() == 'ar' ? 'أخصائي نفسي' : 'Psychologist') : (app()->getLocale() == 'ar' ? 'معالج أسري' : 'Family Therapist') }}</p>
                        
                        <div class="flex items-center mt-1">
                            <x-star-rating :rating="4.5" :maxRating="5" size="sm" />
                            <span class="text-xs text-gray-500 ms-2">({{ rand(10, 100) }})</span>
                        </div>
                    </div>
                    
                    <div class="specialist-action">
                        <button class="btn-primary py-2 px-4 rounded-lg text-sm">{{ app()->getLocale() == 'ar' ? 'اختيار' : 'Select' }}</button>
                    </div>
                </div>
            @endfor
        </div>
        
        <!-- زر مشاهدة المزيد -->
        <div class="text-center mb-10">
            <button class="btn-outline py-2 px-6 rounded-lg text-sm">
                {{ app()->getLocale() == 'ar' ? 'عرض المزيد' : 'Show More' }}
            </button>
        </div>
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .categories-filter {
        padding-bottom: 4px;
    }
</style>
@endsection
