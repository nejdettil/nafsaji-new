<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <h1 class="text-4xl font-bold text-center text-purple-800 mb-6">
        {{ __('pages.specialists.title') }}
    </h1>
    
    <div class="max-w-3xl mx-auto bg-purple-50 rounded-lg p-6 mb-10">
        <p class="text-purple-800 text-center text-lg">
            {{ __('pages.specialists.subtitle') }}
        </p>
    </div>

    <!-- فلتر للتخصصات -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-10">
        <h2 class="text-2xl font-semibold text-purple-700 mb-4 text-center">
            {{ __('pages.specialists.filter_specialists') }}
        </h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-purple-700 mb-2">
                    {{ __('pages.specialists.search_by_specialty') }}
                </h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('specialists.index') }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        {{ __('pages.specialists.all_specialties') }}
                    </a>
                    
                    <a href="{{ route('specialists.index', ['specialty' => 'العلاج النفسي']) }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        {{ __('specialists.therapies.psychotherapy') }}
                    </a>
                    
                    <a href="{{ route('specialists.index', ['specialty' => 'العلاج السلوكي المعرفي']) }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        {{ __('specialists.therapies.cbt') }}
                    </a>
                    
                    <a href="{{ route('specialists.index', ['specialty' => 'علاج الإدمان']) }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        {{ __('specialists.therapies.addiction') }}
                    </a>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-purple-700 mb-2">
                    {{ __('pages.specialists.experience') }}
                </h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('specialists.index', ['experience' => '5+']) }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        5+ {{ __('pages.specialists.years_experience') }}
                    </a>
                    
                    <a href="{{ route('specialists.index', ['experience' => '10+']) }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        10+ {{ __('pages.specialists.years_experience') }}
                    </a>
                    
                    <a href="{{ route('specialists.index', ['experience' => '15+']) }}" class="bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 transition">
                        15+ {{ __('pages.specialists.years_experience') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- عرض المختصين -->
    <div class="grid md:grid-cols-3 gap-8">
        @foreach($specialists as $specialist)
        <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105">
            <div class="relative">
                @if($specialist->profile_image)
                    <img src="{{ asset('storage/'.$specialist->profile_image) }}" alt="{{ $specialist->name }}" class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-user-md text-purple-400 text-5xl"></i>
                    </div>
                @endif
                
                <div class="absolute top-4 right-4 bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                    {{ $specialist->specialty }}
                </div>
                
                @if($specialist->experience)
                <div class="absolute bottom-4 left-4 bg-white text-purple-800 px-3 py-1 rounded-full shadow">
                    {{ $specialist->experience }} {{ __('pages.specialists.years_experience') }}
                </div>
                @endif
            </div>
            
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-purple-700 mb-2">
                    {{ $specialist->name }}
                </h2>
                
                @if($specialist->rating)
                <div class="flex items-center mb-4">
                    <span class="text-yellow-500 mr-1">
                        <i class="fas fa-star"></i>
                    </span>
                    <span class="text-gray-700">{{ $specialist->rating }}/5 ({{ $specialist->reviews_count ?? 0 }} {{ __('pages.specialists.reviews') }})</span>
                </div>
                @endif
                
                <p class="text-gray-600 mb-6">
                    {{ Str::limit($specialist->bio, 120) }}
                </p>
                
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach(explode(',', $specialist->expertise ?? '') as $expertise)
                        @if(trim($expertise))
                        <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm">
                            {{ trim($expertise) }}
                        </span>
                        @endif
                    @endforeach
                </div>
                
                <div class="flex justify-between">
                    <a href="{{ route('specialists.show', $specialist->id) }}" 
                       class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition">
                        {{ __('pages.specialists.view_profile') }}
                    </a>
                    
                    <a href="{{ route('booking.create', ['specialist_id' => $specialist->id]) }}" 
                       class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        {{ __('pages.specialists.book_appointment') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-12 flex justify-center">
        {{ $specialists->links() }}
    </div>
    
    <div class="mt-16 max-w-4xl mx-auto bg-purple-50 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-purple-800 mb-4 text-center">
            {{ __('pages.specialists.need_help_choosing') }}
        </h2>
        
        <p class="text-purple-700 text-center mb-6">
            {{ __('pages.specialists.help_description') }}
        </p>
        
        <div class="flex justify-center">
            <a href="{{ route('contact.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-purple-700 transition">
                {{ __('pages.services.contact_us') }}
            </a>
        </div>
    </div>
</div>
@endsection
