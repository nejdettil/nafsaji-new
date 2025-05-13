@extends('layouts.app')

@section('title', __('pages.booking.payment_title'))

@section('meta')
<!-- تهيئة التطبيق المحمول - Mobile app configuration -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
<meta name="theme-color" content="#6366f1">
<link rel="manifest" href="{{ asset('manifest.json') }}">
<link rel="apple-touch-icon" href="{{ asset('images/app-icon.png') }}">
@endsection

@section('styles')
<style>
    /* تصميم مخصص للجوال - Mobile App Style */
    body.mobile-app-view {
        margin: 0;
        padding: 0;
        background-color: #f7f8fc;
    }
    
    .app-section {
        margin-bottom: 24px;
        padding: 0 16px;
    }
    
    .app-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 16px;
        overflow: hidden;
    }
    
    .app-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #333;
    }
    
    .app-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 16px;
    }
    
    /* مؤشر التقدم */
    .booking-steps {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
    }
    
    .booking-steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 0;
    }
    
    .booking-steps .progress-line {
        position: absolute;
        top: 15px;
        left: 0;
        height: 2px;
        background-color: #6366f1;
        z-index: 1;
        width: 75%;
        transition: width 0.3s ease;
    }
    
    .step {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: white;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        position: relative;
        z-index: 2;
    }
    
    .step.active {
        background-color: #6366f1;
        border-color: #6366f1;
        color: white;
    }
    
    .step.completed {
        background-color: #6366f1;
        border-color: #6366f1;
        color: white;
    }
    
    /* أنماط طرق الدفع */
    .payment-method {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .payment-method.selected {
        border-color: #6366f1;
        background-color: #f5f3ff;
    }
    
    .payment-method-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    
    .payment-method-icon i {
        font-size: 18px;
        color: #6b7280;
    }
    
    .payment-method-icon img {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }
    
    .payment-method-details {
        flex: 1;
    }
    
    .payment-method-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 2px;
    }
    
    .payment-method-description {
        font-size: 12px;
        color: #6b7280;
    }
    
    .payment-radio {
        width: 22px;
        height: 22px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .payment-method.selected .payment-radio {
        border-color: #6366f1;
    }
    
    .payment-method.selected .payment-radio::after {
        content: '';
        position: absolute;
        width: 12px;
        height: 12px;
        background-color: #6366f1;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    /* أنماط بطاقة الائتمان */
    .credit-card-form {
        border-top: 1px solid #e5e7eb;
        margin-top: 16px;
        padding-top: 16px;
    }
    
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #374151;
    }
    
    .form-input {
        width: 100%;
        border: 1px solid #d1d5db;
        padding: 12px;
        border-radius: 8px;
        background-color: white;
        transition: all 0.2s ease;
    }
    
    .form-input:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }
    
    .form-row {
        display: flex;
        gap: 12px;
    }
    
    .form-col {
        flex: 1;
    }
    
    /* زر العمل الرئيسي */
    .app-main-button {
        display: block;
        width: 100%;
        padding: 14px;
        background: linear-gradient(to right, #6366f1, #8b5cf6);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
    }
    
    .app-main-button:active {
        transform: scale(0.98);
    }
    
    .app-secondary-button {
        display: block;
        width: 100%;
        padding: 14px;
        background: white;
        color: #6366f1;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        margin-top: 10px;
    }
    
    /* ملخص الطلب */
    .order-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: #6b7280;
    }
    
    .order-summary-row.total {
        font-weight: 600;
        color: #111827;
        padding-top: 12px;
        margin-top: 8px;
        border-top: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<!-- عرض التطبيق المخصص للجوال (يظهر فقط على شاشات الجوال) - Mobile App View -->
<div class="block">
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content pb-20">
        <!-- قسم العنوان والمتخصص - Header & Specialist Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 pt-6 pb-6 px-4 text-white">
            <h1 class="text-2xl font-bold mb-2">{{ __('pages.booking.payment_title') }}</h1>
            <p class="text-indigo-100 mb-6">{{ __('pages.booking.payment_subtitle') }}</p>
            
            <!-- بطاقة معلومات المتخصص والموعد - Specialist & Appointment Card -->
            <div class="bg-white rounded-xl p-4 text-gray-800 flex flex-col shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                        {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                    </div>
                    <div class="ms-3 flex-1">
                        <h2 class="font-bold text-gray-900">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</h2>
                        <p class="text-sm text-gray-600">{{ app()->getLocale() == 'ar' ? 'طبيب نفسي • جلسة عبر الفيديو' : 'Psychiatrist • Video Session' }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 py-2 px-3 rounded-lg flex items-center">
                    <i class="far fa-calendar-check text-indigo-600 me-2"></i>
                    <span class="text-sm text-gray-700">
                        {{ app()->getLocale() == 'ar' ? '12 مايو 2025 • 10:00 صباحاً' : 'May 12, 2025 • 10:00 AM' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- مؤشر خطوات الحجز - Booking Steps Indicator -->
        <div class="app-section mt-6">
            <div class="booking-steps">
                <div class="progress-line" style="width: 75%"></div>
                <div class="step completed">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="step completed">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="step active">3</div>
                <div class="step">4</div>
            </div>
            <div class="flex justify-between text-sm mt-2 px-3">
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.date') }}</span>
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.details') }}</span>
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.payment') }}</span>
                <span class="text-gray-500">{{ __('pages.booking.steps.confirm') }}</span>
            </div>
        </div>
        
        <!-- طرق الدفع - Payment Methods -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.payment_method') }}</h2>
            
            <div class="app-card">
                <!-- طريقة الدفع: بطاقة الائتمان - Credit Card -->
                <div class="payment-method selected" data-method="credit-card">
                    <div class="payment-method-icon">
                        <i class="far fa-credit-card"></i>
                    </div>
                    <div class="payment-method-details">
                        <div class="payment-method-title">{{ app()->getLocale() == 'ar' ? 'بطاقة ائتمان أو مدين' : 'Credit or Debit Card' }}</div>
                        <div class="payment-method-description">{{ app()->getLocale() == 'ar' ? 'فيزا، ماستركارد، مدى' : 'Visa, Mastercard, mada' }}</div>
                    </div>
                    <div class="payment-radio"></div>
                </div>
                
                <!-- طريقة الدفع: Apple Pay -->
                <div class="payment-method" data-method="apple-pay">
                    <div class="payment-method-icon">
                        <i class="fab fa-apple"></i>
                    </div>
                    <div class="payment-method-details">
                        <div class="payment-method-title">Apple Pay</div>
                        <div class="payment-method-description">{{ app()->getLocale() == 'ar' ? 'ادفع بسرعة وأمان' : 'Pay quickly and securely' }}</div>
                    </div>
                    <div class="payment-radio"></div>
                </div>
                
                <!-- طريقة الدفع: التحويل البنكي - Bank Transfer -->
                <div class="payment-method" data-method="bank-transfer">
                    <div class="payment-method-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="payment-method-details">
                        <div class="payment-method-title">{{ app()->getLocale() == 'ar' ? 'تحويل بنكي' : 'Bank Transfer' }}</div>
                        <div class="payment-method-description">{{ app()->getLocale() == 'ar' ? 'حوّل إلى حسابنا البنكي' : 'Transfer to our bank account' }}</div>
                    </div>
                    <div class="payment-radio"></div>
                </div>
                
                <!-- نموذج بطاقة الائتمان - Credit Card Form -->
                <div id="credit-card-form" class="credit-card-form">
                    <form>
                        <!-- رقم البطاقة - Card Number -->
                        <div class="form-group">
                            <label for="card_number" class="form-label">
                                {{ app()->getLocale() == 'ar' ? 'رقم البطاقة' : 'Card Number' }}
                            </label>
                            <input type="text" id="card_number" class="form-input" placeholder="0000 0000 0000 0000" inputmode="numeric">
                        </div>
                        
                        <!-- اسم حامل البطاقة - Cardholder Name -->
                        <div class="form-group">
                            <label for="cardholder_name" class="form-label">
                                {{ app()->getLocale() == 'ar' ? 'اسم حامل البطاقة' : 'Cardholder Name' }}
                            </label>
                            <input type="text" id="cardholder_name" class="form-input" placeholder="{{ app()->getLocale() == 'ar' ? 'الاسم كما يظهر على البطاقة' : 'Name as appears on card' }}">
                        </div>
                        
                        <!-- تاريخ الانتهاء ورمز الأمان - Expiry & CVV -->
                        <div class="form-row">
                            <div class="form-col">
                                <label for="expiry_date" class="form-label">
                                    {{ app()->getLocale() == 'ar' ? 'تاريخ الانتهاء' : 'Expiry Date' }}
                                </label>
                                <input type="text" id="expiry_date" class="form-input" placeholder="MM/YY" inputmode="numeric">
                            </div>
                            <div class="form-col">
                                <label for="cvv" class="form-label">
                                    {{ app()->getLocale() == 'ar' ? 'رمز الأمان' : 'CVV' }}
                                </label>
                                <input type="text" id="cvv" class="form-input" placeholder="123" inputmode="numeric">
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- نموذج Apple Pay (مخفي افتراضياً) - Apple Pay Form (hidden by default) -->
                <div id="apple-pay-form" class="credit-card-form" style="display: none;">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-gray-600 mb-3">
                            {{ app()->getLocale() == 'ar' ? 'سيتم توجيهك لإتمام الدفع باستخدام Apple Pay' : 'You will be redirected to complete payment using Apple Pay' }}
                        </p>
                        <button class="inline-flex items-center justify-center bg-black text-white rounded-lg py-3 px-4">
                            <i class="fab fa-apple me-2"></i>
                            <span>{{ app()->getLocale() == 'ar' ? 'الدفع باستخدام Apple Pay' : 'Pay with Apple Pay' }}</span>
                        </button>
                    </div>
                </div>
                
                <!-- نموذج التحويل البنكي (مخفي افتراضياً) - Bank Transfer Form (hidden by default) -->
                <div id="bank-transfer-form" class="credit-card-form" style="display: none;">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            {{ app()->getLocale() == 'ar' ? 'معلومات الحساب البنكي' : 'Bank Account Information' }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-1">
                            {{ app()->getLocale() == 'ar' ? 'اسم المستفيد: شركة نفسجي للصحة النفسية' : 'Beneficiary Name: Nafsaji Psychological Health Co.' }}
                        </p>
                        <p class="text-gray-600 text-sm mb-1">
                            {{ app()->getLocale() == 'ar' ? 'رقم الحساب: 1234567890' : 'Account Number: 1234567890' }}
                        </p>
                        <p class="text-gray-600 text-sm mb-1">
                            {{ app()->getLocale() == 'ar' ? 'آيبان: SA0380000000001234567890' : 'IBAN: SA0380000000001234567890' }}
                        </p>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ app()->getLocale() == 'ar' ? 'البنك: البنك الأهلي السعودي' : 'Bank: Saudi National Bank' }}
                        </p>
                        <p class="text-gray-700 text-sm bg-yellow-50 p-3 rounded-lg">
                            <i class="fas fa-info-circle text-yellow-500 me-1"></i>
                            {{ app()->getLocale() == 'ar' ? 'يرجى استخدام رقم الحجز كمرجع عند إجراء التحويل' : 'Please use the booking number as a reference when making the transfer' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ملخص الطلب - Order Summary -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.order_summary') }}</h2>
            
            <div class="app-card">
                <div class="order-summary-row">
                    <span>{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو (60 دقيقة)' : 'Video Session (60 minutes)' }}</span>
                    <span>{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</span>
                </div>
                <div class="order-summary-row">
                    <span>{{ app()->getLocale() == 'ar' ? 'ضريبة القيمة المضافة (15٪)' : 'VAT (15%)' }}</span>
                    <span>{{ app()->getLocale() == 'ar' ? '37.5 ريال' : '37.5 SAR' }}</span>
                </div>
                <div class="order-summary-row total">
                    <span>{{ app()->getLocale() == 'ar' ? 'الإجمالي' : 'Total' }}</span>
                    <span>{{ app()->getLocale() == 'ar' ? '287.5 ريال' : '287.5 SAR' }}</span>
                </div>
            </div>
            
            <a href="{{ route('mobile.booking.confirmation') }}" class="app-main-button">
                {{ app()->getLocale() == 'ar' ? 'إتمام الدفع' : 'Complete Payment' }}
                <i class="fas fa-chevron-right ms-1 text-sm"></i>
            </a>
            
            <a href="{{ route('mobile.booking.details') }}" class="app-secondary-button">
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل اختيار طريقة الدفع
        const paymentMethods = document.querySelectorAll('.payment-method');
        const creditCardForm = document.getElementById('credit-card-form');
        const applePayForm = document.getElementById('apple-pay-form');
        const bankTransferForm = document.getElementById('bank-transfer-form');
        
        function showPaymentForm(method) {
            // إخفاء جميع النماذج أولاً
            creditCardForm.style.display = 'none';
            applePayForm.style.display = 'none';
            bankTransferForm.style.display = 'none';
            
            // إظهار النموذج المناسب
            if (method === 'credit-card') {
                creditCardForm.style.display = 'block';
            } else if (method === 'apple-pay') {
                applePayForm.style.display = 'block';
            } else if (method === 'bank-transfer') {
                bankTransferForm.style.display = 'block';
            }
        }
        
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                // إزالة الفئة المحددة من جميع طرق الدفع
                paymentMethods.forEach(m => m.classList.remove('selected'));
                
                // إضافة الفئة المحددة للطريقة المختارة
                this.classList.add('selected');
                
                // إظهار النموذج المناسب
                const paymentMethod = this.getAttribute('data-method');
                showPaymentForm(paymentMethod);
            });
        });
        
        // عرض نموذج بطاقة الائتمان افتراضياً
        showPaymentForm('credit-card');
    });
</script>
@endsection
