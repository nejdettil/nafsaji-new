@extends('layouts.app')

@section('styles')
<style>
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }
    .success-checkmark .check-icon {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4CAF50;
    }
    .success-checkmark .check-icon::before {
        top: 48px;
        left: 26px;
        width: 12px;
        transform: rotate(45deg);
        z-index: 5;
    }
    .success-checkmark .check-icon::after {
        top: 36px;
        left: 30px;
        width: 24px;
        transform: rotate(135deg);
    }
    .success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
        content: '';
        height: 4px;
        background-color: #4CAF50;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }
    .booking-detail {
        display: flex;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #edf2f7;
    }
    .booking-detail:last-child {
        border-bottom: none;
    }
    .booking-detail-icon {
        margin-right: 16px;
        color: #805ad5;
    }
    .booking-detail-label {
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    .booking-detail-value {
        color: #2d3748;
        font-weight: 600;
    }
    .reference-code {
        font-family: monospace;
        letter-spacing: 0.1em;
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        background-color: #edf2f7;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 md:p-8 text-center">
            <div class="success-checkmark mb-4">
                <div class="check-icon"></div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ __('messages.booking_confirmed') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('messages.booking_confirmation_message') }}</p>
            
            @if(session('guest_booking'))
                <div class="bg-gray-50 rounded-lg p-5 mb-6 text-center">
                    <div class="text-sm text-gray-600 mb-2">{{ __('messages.your_reference_code') }}</div>
                    <div class="reference-code">{{ session('guest_booking.reference_code') }}</div>
                    <div class="text-xs text-gray-500 mt-2">{{ __('messages.keep_reference_code') }}</div>
                </div>
                
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-6">
                    <div class="booking-detail">
                        <div class="booking-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="booking-detail-label">{{ __('messages.name') }}</div>
                            <div class="booking-detail-value">{{ session('guest_booking.guest_name') }}</div>
                        </div>
                    </div>
                    
                    <div class="booking-detail">
                        <div class="booking-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="booking-detail-label">{{ __('messages.specialist') }}</div>
                            <div class="booking-detail-value">{{ session('guest_booking.specialist_name') }}</div>
                        </div>
                    </div>
                    
                    <div class="booking-detail">
                        <div class="booking-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <div class="booking-detail-label">{{ __('messages.service') }}</div>
                            <div class="booking-detail-value">{{ session('guest_booking.service_name') }}</div>
                        </div>
                    </div>
                    
                    <div class="booking-detail">
                        <div class="booking-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="booking-detail-label">{{ __('messages.date') }}</div>
                            <div class="booking-detail-value">{{ \Carbon\Carbon::parse(session('guest_booking.booking_date'))->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="booking-detail">
                        <div class="booking-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="booking-detail-label">{{ __('messages.time') }}</div>
                            <div class="booking-detail-value">{{ session('guest_booking.booking_time') }}</div>
                        </div>
                    </div>
                    
                    <div class="booking-detail">
                        <div class="booking-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="booking-detail-label">{{ __('messages.email') }}</div>
                            <div class="booking-detail-value">{{ session('guest_booking.guest_email') }}</div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                @if(session('guest_booking'))
                    <form action="{{ route('payment.checkout') }}" method="GET" class="w-full sm:w-auto">
                        <input type="hidden" name="booking_id" value="{{ session('guest_booking.id') }}">
                        <input type="hidden" name="amount" value="{{ session('guest_booking.price') ?? '0' }}">
                        <input type="hidden" name="description" value="حجز جلسة: {{ session('guest_booking.service_name') }} مع {{ session('guest_booking.specialist_name') }}">
                        <input type="hidden" name="return_url" value="{{ route('booking.payment_success', ['booking_id' => session('guest_booking.id')]) }}">
                        <input type="hidden" name="cancel_url" value="{{ route('booking.payment_cancel', ['booking_id' => session('guest_booking.id')]) }}">
                        
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('المتابعة للدفع') }}
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    {{ __('messages.return_to_home') }}
                </a>
                
                <a href="{{ route('booking.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    {{ __('messages.book_another_session') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Animación del check mark
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const checkIcon = document.querySelector('.check-icon');
            checkIcon.classList.add('animate');
        }, 200);
    });
</script>
@endsection
