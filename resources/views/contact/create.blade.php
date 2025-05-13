<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
@endsection

@section('content')
<div class="container mx-auto py-12">
    <!-- العنوان والوصف -->
    <div class="text-center max-w-4xl mx-auto mb-16">
        <h1 class="text-4xl font-bold text-purple-800 mb-4">
            {{ __('pages.contact.title') }}
        </h1>
        <div class="bg-purple-50 rounded-lg p-6 mt-6">
            <p class="text-purple-700 text-lg">
                {{ __('pages.contact.subtitle') }}
            </p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-12">
        <!-- معلومات الاتصال والخريطة -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 transform transition hover:shadow-lg">
                <h2 class="text-2xl font-bold text-purple-800 mb-6 border-b pb-4">
                    {{ __('pages.contact.contact_info') }}
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-full mr-4 rtl:ml-4 rtl:mr-0">
                            <i class="fas fa-map-marker-alt text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">{{ __('pages.contact.our_address') }}</h3>
                            <p class="text-gray-600">{{ __('messages.office_address') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-full mr-4 rtl:ml-4 rtl:mr-0">
                            <i class="fas fa-phone-alt text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">{{ __('pages.contact.call_us') }}</h3>
                            <a href="tel:+9661234567890" class="text-purple-600 hover:underline">+966 123 456 7890</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-full mr-4 rtl:ml-4 rtl:mr-0">
                            <i class="fas fa-envelope text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">{{ __('pages.contact.email_us') }}</h3>
                            <a href="mailto:info@nafsaji.com" class="text-purple-600 hover:underline">info@nafsaji.com</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-full mr-4 rtl:ml-4 rtl:mr-0">
                            <i class="fas fa-clock text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">{{ __('pages.contact.working_hours') }}</h3>
                            <p class="text-gray-600">{{ __('pages.contact.working_days') }}</p>
                            <p class="text-gray-600">{{ __('pages.contact.weekend') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="font-semibold text-gray-800 mb-3">{{ __('pages.contact.follow_us') }}</h3>
                    <div class="flex space-x-4 rtl:space-x-reverse">
                        <a href="#" class="bg-purple-100 hover:bg-purple-200 p-3 rounded-full text-purple-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="bg-purple-100 hover:bg-purple-200 p-3 rounded-full text-purple-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-purple-100 hover:bg-purple-200 p-3 rounded-full text-purple-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-purple-100 hover:bg-purple-200 p-3 rounded-full text-purple-600 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- خريطة الموقع -->
            <div class="bg-white rounded-lg shadow-md p-6 transform transition hover:shadow-lg">
                <h2 class="text-2xl font-bold text-purple-800 mb-6 border-b pb-4">
                    {{ __('pages.contact.our_location') }}
                </h2>
                <div id="map" class="w-full h-64 rounded-lg"></div>
            </div>
        </div>
        
        <!-- نموذج الاتصال -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8 transform transition hover:shadow-lg">
                <h2 class="text-2xl font-bold text-purple-800 mb-6 border-b pb-4">
                    {{ __('pages.contact.send_message') }}
                </h2>
                
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ml-3 rtl:mr-3 rtl:ml-0">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3 rtl:mr-3 rtl:ml-0">
                            <ul class="list-disc space-y-1 pl-5 rtl:pr-5 rtl:pl-0">
                                @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">
                                {{ __('pages.contact.name') }} *
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">
                                {{ __('pages.contact.email') }} *
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.contact.subject') }} *
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                   class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.contact.message') }} *
                        </label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="6" required
                                   class="block w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 rtl:mr-2 rtl:ml-0 block text-sm text-gray-700">
                            {{ __('pages.contact.agree_terms') }}
                        </label>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-md transform transition hover:scale-105">
                            <i class="fas fa-paper-plane mr-2 rtl:ml-2 rtl:mr-0"></i> {{ __('pages.contact.send_message') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- الأسئلة الشائعة -->
    <div class="mt-16 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-purple-800 mb-8">
            {{ __('pages.contact.faq_title') }}
        </h2>
        
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-lg shadow-md transform transition hover:shadow-lg">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.contact.faq_q1') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.contact.faq_a1') }}
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md transform transition hover:shadow-lg">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.contact.faq_q2') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.contact.faq_a2') }}
                </p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md transform transition hover:shadow-lg">
                <h3 class="text-xl font-semibold text-purple-700 mb-3">
                    {{ __('pages.contact.faq_q3') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('pages.contact.faq_a3') }}
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // إعداد الخريطة
    mapboxgl.accessToken = 'pk.eyJ1IjoibmFmc2FqaSIsImEiOiJjbG16bzBqNXQwNnk1MnJtcHQyZ3l6ZzZqIn0.z8T0uouWQjPdhTptXGSsSQ';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [46.675296, 24.713552], // الرياض
        zoom: 12
    });
    
    // إضافة علامة على الخريطة
    new mapboxgl.Marker({
        color: "#9333ea"
    })
    .setLngLat([46.675296, 24.713552])
    .addTo(map);
    
    // التحكم في التكبير والتصغير
    map.addControl(new mapboxgl.NavigationControl());
});
</script>
@endsection
