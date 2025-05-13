@extends('booking.layout')

@section('booking_styles')
<style>
    .specialist-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .specialist-card:hover {
        border-color: var(--primary-light);
    }
    
    .specialist-card.selected {
        border-color: var(--primary-color);
        box-shadow: 0 10px 15px rgba(126, 34, 206, 0.2);
    }
    
    .specialist-image {
        height: 200px;
        width: 100%;
        object-fit: cover;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    
    .specialist-info {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .specialist-footer {
        margin-top: auto;
        padding-top: 1rem;
    }
    
    .specialist-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 0.5rem 0;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .badge-primary {
        background-color: #e9d5ff;
        color: var(--primary-dark);
    }
</style>
@endsection

@section('booking_content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($specialists as $specialist)
    <div class="card specialist-card">
        @if($specialist->avatar)
            <img src="{{ asset('storage/' . $specialist->avatar) }}" alt="{{ $specialist->name }}" class="specialist-image">
        @else
            <div class="specialist-image" style="background-color: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        @endif
        
        <div class="card-body specialist-info">
            <h3 class="card-title">{{ $specialist->name }}</h3>
            <p class="card-text text-sm">{{ $specialist->title }}</p>
            
            <div class="specialist-badges">
                @if($specialist->services && $specialist->services->count() > 0)
                    @foreach($specialist->services->take(3) as $service)
                        <span class="badge badge-primary">{{ $service->name }}</span>
                    @endforeach
                    
                    @if($specialist->services->count() > 3)
                        <span class="badge badge-primary">+{{ $specialist->services->count() - 3 }}</span>
                    @endif
                @else
                    <span class="badge badge-primary">{{ $specialist->speciality ?? __('messages.no_services') }}</span>
                @endif
            </div>
            
            <p class="card-text mt-4">
                {{ Str::limit($specialist->bio, 100) }}
            </p>
            
            <div class="specialist-footer">
                <a href="{{ route('booking.services', $specialist->id) }}" class="btn btn-primary w-full">
                    {{ __('messages.choose_specialist') }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($specialists->isEmpty())
<div class="text-center py-8">
    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('messages.no_specialists') }}</h3>
    <p class="mt-1 text-sm text-gray-500">{{ __('messages.no_specialists_description') }}</p>
</div>
@endif
@endsection
