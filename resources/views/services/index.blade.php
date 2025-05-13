<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}

// تعريف بيانات الخدمات باللغتين
$servicesData = [
    [
        'id' => 1, 
        'name_ar' => 'استشارة نفسية فردية',
        'name_en' => 'Individual Psychological Consultation',
        'description_ar' => 'جلسة استشارة نفسية فردية لمناقشة التحديات النفسية وإيجاد حلول مناسبة',
        'description_en' => 'Individual counseling session to discuss psychological challenges and find appropriate solutions',
        'icon' => 'fa-user'
    ],
    [
        'id' => 2, 
        'name_ar' => 'استشارة نفسية للأزواج',
        'name_en' => 'Couples Psychological Consultation',
        'description_ar' => 'جلسة استشارة نفسية للأزواج لتحسين التواصل وحل المشكلات الزوجية',
        'description_en' => 'Couples counseling session to improve communication and solve marital problems',
        'icon' => 'fa-users'
    ],
    [
        'id' => 3, 
        'name_ar' => 'العلاج المعرفي السلوكي',
        'name_en' => 'Cognitive Behavioral Therapy',
        'description_ar' => 'جلسة علاج معرفي سلوكي لعلاج الاضطرابات النفسية مثل القلق والاكتئاب',
        'description_en' => 'Cognitive behavioral therapy session to treat psychological disorders such as anxiety and depression',
        'icon' => 'fa-brain'
    ],
    [
        'id' => 4, 
        'name_ar' => 'ورشة إدارة الضغوط',
        'name_en' => 'Stress Management Workshop',
        'description_ar' => 'ورشة عمل لتعلم مهارات إدارة الضغوط والتعامل مع التحديات اليومية',
        'description_en' => 'Workshop to learn stress management skills and deal with daily challenges',
        'icon' => 'fa-hand-holding-heart'
    ],
    [
        'id' => 5, 
        'name_ar' => 'علاج اضطرابات النوم',
        'name_en' => 'Sleep Disorders Treatment',
        'description_ar' => 'برنامج علاجي متخصص لمعالجة مشاكل النوم وتحسين جودة الراحة',
        'description_en' => 'Specialized treatment program to address sleep problems and improve rest quality',
        'icon' => 'fa-moon'
    ],
    [
        'id' => 6, 
        'name_ar' => 'علاج إدمان الإنترنت',
        'name_en' => 'Internet Addiction Treatment',
        'description_ar' => 'برنامج متخصص لمعالجة إدمان الإنترنت ووسائل التواصل الاجتماعي',
        'description_en' => 'Specialized program to treat internet and social media addiction',
        'icon' => 'fa-laptop'
    ]
];

// تحديد اللغة الحالية
$locale = app()->getLocale();
?>
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <h1 class="text-4xl font-bold text-center text-purple-800 mb-12">
        {{ __('pages.services.title') }}
    </h1>
    
    <div class="max-w-3xl mx-auto bg-purple-50 rounded-lg p-6 mb-10">
        <p class="text-purple-800 text-center text-lg">
            {{ __('pages.services.subtitle') }}
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        @foreach($servicesData as $service)
        <div class="bg-white rounded-lg shadow-md p-6 transform transition-transform hover:scale-105">
            <div class="mb-4 flex justify-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas {{ $service['icon'] }} text-purple-600 text-2xl"></i>
                </div>
            </div>
            
            <h2 class="text-2xl font-semibold text-purple-700 mb-4 text-center">
                {{ $locale == 'ar' ? $service['name_ar'] : $service['name_en'] }}
            </h2>
            
            <p class="text-gray-600 mb-6 text-center">
                {{ $locale == 'ar' ? $service['description_ar'] : $service['description_en'] }}
            </p>
            
            <div class="flex justify-center mt-auto">
                <a href="{{ route('services.show', $service['id']) }}" 
                   class="bg-purple-500 text-white px-5 py-2 rounded-lg hover:bg-purple-600 transition">
                    {{ __('pages.services.learn_more') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-16 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-purple-800 mb-8">
            {{ __('pages.services.why_choose_us') }}
        </h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.services.expert_therapists') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.services.expert_therapists_desc') }}
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.services.personalized_approach') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.services.personalized_approach_desc') }}
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.services.confidential_care') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.services.confidential_care_desc') }}
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.services.flexible_options') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.services.flexible_options_desc') }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-16 text-center">
        <a href="{{ route('contact.create') }}" class="bg-purple-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-purple-700 transition">
            {{ __('pages.services.contact_us') }}
        </a>
    </div>
</div>
@endsection
