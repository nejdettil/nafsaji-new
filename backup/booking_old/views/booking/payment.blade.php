@extends('booking.layout')

@section('booking_styles')
<style>
    .payment-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .booking-summary {
        background-color: #f8f5ff;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .summary-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        color: var(--primary-dark);
    }
    
    .summary-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .summary-item:last-child {
        border-bottom: none;
    }
    
    .summary-label {
        width: 40%;
        font-weight: 500;
        color: var(--text-light);
    }
    
    .summary-value {
        width: 60%;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .price-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        text-align: right;
        margin-top: 1rem;
    }
    
    .payment-section {
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
    }
    
    .payment-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
    }
    
    .credit-card-icon {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .credit-card-icon i {
        font-size: 1.75rem;
        color: var(--text-light);
    }
    
    #card-element {
        background-color: white;
        padding: 1rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
    }
    
    #card-errors {
        color: var(--danger-color);
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
    
    .payment-note {
        font-size: 0.875rem;
        color: var(--text-light);
        margin-top: 1rem;
    }
    
    .payment-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
    
    .secure-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2rem;
        color: var(--text-light);
        font-size: 0.875rem;
    }
    
    .secure-badge svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
    }
    
    /* Overlay for loading state */
    .payment-processing-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }
    
    .payment-processing-content {
        background-color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        text-align: center;
        max-width: 400px;
        width: 100%;
    }
    
    .payment-processing-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('booking_content')
<div class="payment-container">
    <h3 class="text-xl font-semibold mb-6">{{ __('messages.complete_your_payment') }}</h3>
    
    <div class="booking-summary">
        <h3 class="summary-title">{{ __('messages.booking_summary') }}</h3>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.booking_reference') }}:</div>
            <div class="summary-value">{{ $booking->reference }}</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.specialist') }}:</div>
            <div class="summary-value">{{ $booking->specialist->name }}</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.service') }}:</div>
            <div class="summary-value">{{ $booking->service->name }}</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">{{ __('messages.date_and_time') }}:</div>
            <div class="summary-value">
                {{ $booking->booking_date->format('Y-m-d') }} {{ __('messages.at') }} {{ $booking->booking_time }}
            </div>
        </div>
        
        <div class="price-total">
            {{ __('messages.total') }}: {{ number_format($booking->price, 2) }} {{ __('messages.currency_sar') }}
        </div>
    </div>
    
    <div class="payment-section">
        <h3 class="payment-title">{{ __('messages.payment_information') }}</h3>
        
        <div class="credit-card-icon">
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-cc-mastercard"></i>
            <i class="fab fa-cc-amex"></i>
        </div>
        
        <form id="payment-form">
            <div class="form-group">
                <div id="card-element">
                    <!-- Stripe Card Element will be inserted here -->
                </div>
                <div id="card-errors" role="alert"></div>
            </div>
            
            <div class="payment-note">
                {{ __('messages.card_not_charged_until_confirm') }}
            </div>
            
            <div class="payment-buttons">
                <a href="{{ route('booking.success', $booking->reference) }}" class="btn btn-outline">
                    {{ __('messages.back_to_booking') }}
                </a>
                
                <button type="submit" id="submit-button" class="btn btn-primary">
                    {{ __('messages.pay_now') }} ({{ number_format($booking->price, 2) }} {{ __('messages.currency_sar') }})
                </button>
            </div>
        </form>
    </div>
    
    <div class="secure-badge">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        {{ __('messages.secure_payment') }}
    </div>
</div>

<div class="payment-processing-overlay" id="payment-processing-overlay">
    <div class="payment-processing-content">
        <div class="payment-processing-spinner"></div>
        <h3 class="font-semibold mb-2">{{ __('messages.processing_payment') }}</h3>
        <p>{{ __('messages.please_do_not_close') }}</p>
    </div>
</div>
@endsection

@section('booking_scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create a Stripe client
        const stripe = Stripe('{{ $stripeKey }}');
        
        // Create an instance of Elements
        const elements = stripe.elements();
        
        // Custom styling can be passed to options when creating an Element
        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        
        // Create a card Element and mount it to the div with id 'card-element'
        const card = elements.create('card', {style: style});
        card.mount('#card-element');
        
        // Handle real-time validation errors from the card Element
        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        
        // Handle form submission
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Show loading overlay
            document.getElementById('payment-processing-overlay').style.display = 'flex';
            
            // Disable the submit button to prevent repeated clicks
            document.getElementById('submit-button').disabled = true;
            
            // Create a PaymentMethod and confirm the PaymentIntent
            createPaymentMethod();
        });
        
        // Create a payment method and then create a PaymentIntent on the server
        function createPaymentMethod() {
            stripe.createPaymentMethod({
                type: 'card',
                card: card
            }).then(function(result) {
                if (result.error) {
                    // Show error in the form
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    
                    // Hide loading overlay
                    document.getElementById('payment-processing-overlay').style.display = 'none';
                    
                    // Re-enable the submit button
                    document.getElementById('submit-button').disabled = false;
                } else {
                    // Send the PaymentMethod ID to the server
                    fetch('{{ route("booking.payment.process", $booking->reference) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method_id: result.paymentMethod.id
                        })
                    }).then(function(response) {
                        return response.json();
                    }).then(function(responseJson) {
                        if (responseJson.success) {
                            // The payment requires confirmation, confirm it on the client
                            handlePaymentIntent(responseJson.clientSecret);
                        } else {
                            // Display error message
                            const errorElement = document.getElementById('card-errors');
                            errorElement.textContent = responseJson.message || '{{ __("messages.payment_processing_error") }}';
                            
                            // Hide loading overlay
                            document.getElementById('payment-processing-overlay').style.display = 'none';
                            
                            // Re-enable the submit button
                            document.getElementById('submit-button').disabled = false;
                            
                            // Redirect if needed
                            if (responseJson.redirect) {
                                window.location.href = responseJson.redirect;
                            }
                        }
                    }).catch(function(error) {
                        console.error('Error:', error);
                        
                        // Display error message
                        const errorElement = document.getElementById('card-errors');
                        errorElement.textContent = '{{ __("messages.payment_processing_error") }}';
                        
                        // Hide loading overlay
                        document.getElementById('payment-processing-overlay').style.display = 'none';
                        
                        // Re-enable the submit button
                        document.getElementById('submit-button').disabled = false;
                    });
                }
            });
        }
        
        // Handle the PaymentIntent confirmation
        function handlePaymentIntent(clientSecret) {
            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card
                }
            }).then(function(result) {
                if (result.error) {
                    // Show error in the form
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    
                    // Hide loading overlay
                    document.getElementById('payment-processing-overlay').style.display = 'none';
                    
                    // Re-enable the submit button
                    document.getElementById('submit-button').disabled = false;
                } else {
                    // The payment was successful, redirect to success page
                    window.location.href = '{{ route("booking.payment.success", $booking->reference) }}?payment_intent=' + result.paymentIntent.id;
                }
            });
        }
    });
</script>
@endsection
