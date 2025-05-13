@extends('booking.layout')

@section('booking_styles')
<style>
    .booking-form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .booking-summary {
        background-color: #f8f5ff;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .booking-section {
        margin-bottom: 2.5rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-col {
        flex: 1;
        min-width: 250px;
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
    
    .price-summary {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        text-align: right;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    
    .required {
        color: var(--danger-color);
    }
    
    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    
    .terms-checkbox input {
        margin-right: 0.5rem;
        margin-top: 0.25rem;
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
    
    .payment-options {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1.5rem 0;
    }
    
    .payment-option {
        flex: 1;
        min-width: 160px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .payment-option:hover {
        border-color: var(--primary-light);
        background-color: #f8f5ff;
    }
    
    .payment-option.selected {
        border-color: var(--primary-color);
        background-color: #f8f5ff;
    }
    
    .payment-option-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .payment-option-label {
        font-weight: 500;
    }
    
    .errors {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('booking_content')
<div class="booking-form-container">
    <a href="{{ route('booking.schedule', ['specialist' => $specialist->id, 'service' => $service->id]) }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        {{ __('messages.back_to_schedule') }}
    </a>
    
    <div class="booking-section">
        <h3 class="section-title">{{ __('messages.booking_details') }}</h3>
        
        <div class="booking-summary">
            <div class="summary-item">
                <div class="summary-label">{{ __('messages.specialist') }}:</div>
                <div class="summary-value">{{ $specialist->name }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">{{ __('messages.service') }}:</div>
                <div class="summary-value">{{ $service->name }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">{{ __('messages.date') }}:</div>
                <div class="summary-value">{{ $booking_date->format('Y-m-d') }} ({{ $booking_date->translatedFormat('l') }})</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">{{ __('messages.time') }}:</div>
                <div class="summary-value">{{ $booking_time }}</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">{{ __('messages.duration') }}:</div>
                <div class="summary-value">{{ $service->duration }} {{ __('messages.minutes') }}</div>
            </div>
            
            <div class="price-summary">
                {{ __('messages.total') }}: {{ number_format($service->price, 2) }} {{ __('messages.currency_sar') }}
            </div>
        </div>
    </div>
    
    <form action="{{ route('booking.process') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
        <input type="hidden" name="service_id" value="{{ $service->id }}">
        <input type="hidden" name="booking_date" value="{{ $booking_date->format('Y-m-d') }}">
        <input type="hidden" name="booking_time" value="{{ $booking_time }}">
        <input type="hidden" name="price" value="{{ $service->price }}">
        
        <div class="booking-section">
            <h3 class="section-title">{{ __('messages.your_information') }}</h3>
            
            @if(!Auth::check())
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('messages.name') }} <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('messages.email') }} <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="phone" class="form-label">{{ __('messages.phone') }} <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="errors">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="form-group">
                        <label for="create_account" class="form-label">{{ __('messages.create_account') }}</label>
                        <div class="form-check">
                            <input type="checkbox" id="create_account" name="create_account" class="form-check-input" value="1" {{ old('create_account') ? 'checked' : '' }}>
                            <label for="create_account" class="form-check-label">{{ __('messages.create_account_for_me') }}</label>
                        </div>
                        <small class="text-muted">{{ __('messages.create_account_description') }}</small>
                    </div>
                </div>
            </div>
            
            <div id="passwordFields" style="display: none;">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="password" class="form-label">{{ __('messages.password') }} <span class="required">*</span></label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="errors">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-col">
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }} <span class="required">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            @else
            <p>{{ __('messages.logged_in_as') }} <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})</p>
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            @endif
            
            <div class="form-group">
                <label for="notes" class="form-label">{{ __('messages.special_requests') }}</label>
                <textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <div class="booking-section">
            <h3 class="section-title">{{ __('messages.payment_method') }}</h3>
            
            <div class="payment-options">
                <div class="payment-option selected" data-payment="stripe">
                    <div class="payment-option-icon">
                        <i class="fab fa-cc-stripe"></i>
                    </div>
                    <div class="payment-option-label">{{ __('messages.credit_card') }}</div>
                </div>
            </div>
            
            <input type="hidden" name="payment_method" id="paymentMethod" value="stripe">
        </div>
        
        <div class="terms-checkbox">
            <label class="checkbox-container">
                <input type="checkbox" name="terms" id="terms" required>
                <span class="checkmark"></span>
                <span class="checkbox-text">{{ __('messages.agree_to_terms') }} <a href="#" target="_blank">{{ __('messages.terms_and_conditions') }}</a></span>
            </label>
        </div>
        
        <div class="booking-actions">
            <a href="{{ route('booking.schedule', ['specialist' => $specialist->id, 'service' => $service->id]) }}" class="btn btn-outline">
                {{ __('messages.back') }}
            </a>
            
            <button type="submit" class="btn btn-primary">
                {{ __('messages.complete_booking') }}
            </button>
        </div>
    </form>
    
    <!-- زر بديل للدفع المباشر في حالة وجود مشكلة في تدفق الحجز الرئيسي -->
    <div class="booking-section mt-4">
        <h3 class="section-title">{{ __('messages.payment_options') }}</h3>
        <p class="mb-3">{{ __('messages.if_booking_issues') }}</p>
        <div class="direct-payment-btn">
            <a href="{{ route('booking.stripe.checkout', ['transaction_id' => 'WILL_BE_CREATED']) }}" 
               class="btn btn-primary btn-block direct-payment-link" 
               id="directPaymentBtn" 
               style="display: none; margin-top: 15px; background: linear-gradient(to right, #7e22ce, #4c1d95);">
                {{ __('messages.proceed_to_payment_directly') }} <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@section('booking_scripts')
<script>
    // يمكن للمستخدم الضغط على زر الدفع المباشر بعد إنشاء الحجز
    document.addEventListener('DOMContentLoaded', function() {
        const bookingForm = document.getElementById('bookingForm');
        const directPaymentBtn = document.getElementById('directPaymentBtn');
        
        if (bookingForm && directPaymentBtn) {
            // عند تقديم نموذج الحجز
            bookingForm.addEventListener('submit', function(e) {
                // إذا تم التحقق من صحة النموذج، أظهر زر الدفع المباشر بعد 3 ثوانٍ
                if (bookingForm.checkValidity()) {
                    // إظهار زر الدفع المباشر بعد مهلة قصيرة
                    setTimeout(function() {
                        directPaymentBtn.style.display = 'inline-block';
                        
                        // تحديث رابط الدفع المباشر باستخدام رمز المعاملة الجديد
                        fetch('/booking/api/latest-transaction-id')
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.transaction_id) {
                                    // تحديث رابط زر الدفع المباشر
                                    const currentHref = directPaymentBtn.getAttribute('href');
                                    const newHref = currentHref.replace('WILL_BE_CREATED', data.transaction_id);
                                    directPaymentBtn.setAttribute('href', newHref);
                                }
                            })
                            .catch(error => console.error('Error fetching transaction ID:', error));
                    }, 3000);
                }
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password fields visibility based on create account checkbox
        const createAccountCheckbox = document.getElementById('create_account');
        const passwordFields = document.getElementById('passwordFields');
        
        if (createAccountCheckbox) {
            createAccountCheckbox.addEventListener('change', function() {
                passwordFields.style.display = this.checked ? 'block' : 'none';
                
                // Toggle required attribute on password fields
                const passwordInput = document.getElementById('password');
                const passwordConfirmInput = document.getElementById('password_confirmation');
                
                if (this.checked) {
                    passwordInput.setAttribute('required', '');
                    passwordConfirmInput.setAttribute('required', '');
                } else {
                    passwordInput.removeAttribute('required');
                    passwordConfirmInput.removeAttribute('required');
                }
            });
            
            // Initialize on page load based on checkbox state
            if (createAccountCheckbox.checked) {
                passwordFields.style.display = 'block';
                document.getElementById('password').setAttribute('required', '');
                document.getElementById('password_confirmation').setAttribute('required', '');
            }
        }
        
        // Payment method selection
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentMethodInput = document.getElementById('paymentMethod');
        
        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                // Update hidden input value
                paymentMethodInput.value = this.getAttribute('data-payment');
            });
        });
    });
</script>
@endsection
