@extends('booking.layout')

@section('booking_styles')
<style>
    .service-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .service-card:hover {
        border-color: var(--primary-light);
    }
    
    .service-card.selected {
        border-color: var(--primary-color);
        box-shadow: 0 10px 15px rgba(126, 34, 206, 0.2);
    }
    
    .service-info {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .service-footer {
        margin-top: auto;
        padding-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .duration {
        display: flex;
        align-items: center;
        color: var(--text-light);
        font-size: 0.875rem;
    }
    
    .duration svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.25rem;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .back-link:hover {
        color: var(--primary-dark);
    }
    
    .back-link svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
    }
    
    .specialist-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: #f8f5ff;
        border-radius: 0.5rem;
    }
    
    .specialist-image {
        width: 4rem;
        height: 4rem;
        border-radius: 9999px;
        object-fit: cover;
        margin-right: 1rem;
    }
    
    .specialist-details {
        flex: 1;
    }
    
    .specialist-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.25rem;
    }
    
    .specialist-title {
        color: var(--text-light);
        font-size: 0.875rem;
    }
</style>
@endsection

@section('booking_content')
<a href="{{ route('booking.specialists') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    {{ __('messages.back_to_specialists') }}
</a>

<div class="specialist-header">
    @if($specialist->avatar)
        <img src="{{ asset('storage/' . $specialist->avatar) }}" alt="{{ $specialist->name }}" class="specialist-image">
    @else
        <div class="specialist-image bg-gray-200 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
    @endif
    
    <div class="specialist-details">
        <h3 class="specialist-name">{{ $specialist->name }}</h3>
        <p class="specialist-title">{{ $specialist->title }}</p>
    </div>
</div>

<h2 class="text-xl font-semibold mb-4">{{ __('messages.available_services') }}</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    @foreach($services as $service)
    <div class="card service-card">
        <div class="card-body service-info">
            <h3 class="card-title">{{ $service->name }}</h3>
            <p class="card-text text-sm mt-2">{{ $service->description }}</p>
            
            <div class="service-footer">
                <div>
                    <div class="price">{{ number_format($service->price, 2) }} {{ __('messages.currency_sar') }}</div>
                    <div class="duration">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $service->duration }} {{ __('messages.minutes') }}
                    </div>
                </div>
                
                <a href="{{ route('booking.schedule', ['specialist' => $specialist->id, 'service' => $service->id]) }}" class="btn btn-primary">
                    {{ __('messages.select') }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($services->isEmpty())
<div class="text-center py-8">
    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('messages.no_services') }}</h3>
    <p class="mt-1 text-sm text-gray-500">{{ __('messages.no_services_description') }}</p>
</div>
@endif
@endsection
