@extends('booking.layout')

@section('booking_styles')
<style>
    .success-container {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 2rem;
        background-color: #ecfdf5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .success-icon svg {
        width: 40px;
        height: 40px;
        color: #059669;
    }
    
    .success-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 1rem;
    }
    
    .success-message {
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 2rem;
    }
    
    .booking-details {
        background-color: #f8f5ff;
        border-radius: 0.5rem;
        padding: 2rem;
        margin: 2rem 0;
        text-align: left;
    }
    
    .details-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .details-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .details-item:last-child {
        border-bottom: none;
    }
    
    .details-label {
        width: 40%;
        font-weight: 500;
        color: var(--text-light);
    }
    
    .details-value {
        width: 60%;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .reference-number {
        background-color: #e0f2fe;
        border-radius: 0.375rem;
        padding: 1rem;
        margin: 2rem 0;
        font-weight: 600;
        color: #0369a1;
    }
    
    .contact-info {
        margin: 2rem 0;
        color: var(--text-light);
    }
    
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .reminder {
        margin-top: 3rem;
        font-size: 0.9rem;
        color: var(--text-light);
    }
    
    @media print {
        .no-print {
            display: none;
        }
        
        .page-title, .page-subtitle {
            text-align: center;
        }
        
        .booking-container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
    }
</style>
@endsection

@section('booking_content')
<div class="success-container">
    <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    
    <h2 class="success-title">{{ __('messages.booking_confirmed') }}</h2>
    
    <p class="success-message">
        {{ __('messages.booking_confirmed_message') }}
    </p>
    
    <div class="reference-number">
        {{ __('messages.booking_reference') }}: <strong>{{ $booking->reference }}</strong>
    </div>
    
    <div class="booking-details">
        <h3 class="details-title">{{ __('messages.booking_details') }}</h3>
        
        <div class="details-item">
            <div class="details-label">{{ __('messages.specialist') }}:</div>
            <div class="details-value">{{ $booking->specialist->name }}</div>
        </div>
        
        <div class="details-item">
            <div class="details-label">{{ __('messages.service') }}:</div>
            <div class="details-value">{{ $booking->service->name }}</div>
        </div>
        
        <div class="details-item">
            <div class="details-label">{{ __('messages.date') }}:</div>
            <div class="details-value">{{ $booking->booking_date->format('Y-m-d') }} ({{ $booking->booking_date->translatedFormat('l') }})</div>
        </div>
        
        <div class="details-item">
            <div class="details-label">{{ __('messages.time') }}:</div>
            <div class="details-value">{{ $booking->booking_time }}</div>
        </div>
        
        <div class="details-item">
            <div class="details-label">{{ __('messages.status') }}:</div>
            <div class="details-value">
                @if($booking->status == 'confirmed')
                    <span style="color: #059669;">{{ __('messages.status_confirmed') }}</span>
                @elseif($booking->status == 'pending')
                    <span style="color: #d97706;">{{ __('messages.status_pending') }}</span>
                @elseif($booking->status == 'cancelled')
                    <span style="color: #dc2626;">{{ __('messages.status_cancelled') }}</span>
                @elseif($booking->status == 'completed')
                    <span style="color: #1d4ed8;">{{ __('messages.status_completed') }}</span>
                @endif
            </div>
        </div>
        
        <div class="details-item">
            <div class="details-label">{{ __('messages.payment_status') }}:</div>
            <div class="details-value">
                @if($booking->payment_status == 'paid')
                    <span style="color: #059669;">{{ __('messages.payment_status_paid') }}</span>
                @elseif($booking->payment_status == 'pending')
                    <span style="color: #d97706;">{{ __('messages.payment_status_pending') }}</span>
                @elseif($booking->payment_status == 'failed')
                    <span style="color: #dc2626;">{{ __('messages.payment_status_failed') }}</span>
                @endif
            </div>
        </div>
        
        @if($booking->payment_status != 'paid')
        <div class="details-item">
            <div class="details-label">{{ __('messages.price') }}:</div>
            <div class="details-value">{{ number_format($booking->price, 2) }} {{ __('messages.currency_sar') }}</div>
        </div>
        @endif
        
        @if($booking->notes)
        <div class="details-item">
            <div class="details-label">{{ __('messages.notes') }}:</div>
            <div class="details-value">{{ $booking->notes }}</div>
        </div>
        @endif
    </div>
    
    <div class="contact-info">
        {{ __('messages.booking_contact_info') }}
    </div>
    
    <div class="action-buttons no-print">
        @if($booking->payment_status != 'paid')
        <a href="{{ route('booking.payment', $booking->reference) }}" class="btn btn-primary">
            {{ __('messages.proceed_to_payment') }}
        </a>
        @endif
        
        <button onclick="window.print()" class="btn btn-outline">
            {{ __('messages.print_details') }}
        </button>
        
        <a href="{{ route('home') }}" class="btn btn-outline">
            {{ __('messages.return_to_home') }}
        </a>
    </div>
    
    <div class="reminder">
        {{ __('messages.booking_email_sent') }}
    </div>
</div>
@endsection
