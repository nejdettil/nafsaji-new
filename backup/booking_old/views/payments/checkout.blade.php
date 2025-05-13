@extends('layouts.app')

@section('title', __('الدفع') . ' | ' . config('app.name'))

@section('styles')
<style>
    .payment-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .payment-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .payment-header h2 {
        font-weight: 600;
        color: #4c1d95;
        margin-bottom: 0.5rem;
    }

    .payment-details {
        background-color: #f8f5ff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed #e5e7eb;
    }

    .payment-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .payment-label {
        font-weight: 500;
        color: #4b5563;
    }

    .payment-value {
        font-weight: 600;
        color: #1f2937;
    }

    .amount-value {
        font-size: 1.25rem;
        color: #4c1d95;
    }

    #payment-form {
        margin-top: 2rem;
    }

    #payment-element {
        margin-bottom: 1.5rem;
    }

    #payment-submit {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(to right, #7e22ce, #4c1d95);
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #payment-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    #payment-submit:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .payment-message {
        text-align: center;
        margin-top: 1rem;
        color: #4b5563;
    }

    #payment-error {
        color: #ef4444;
        text-align: center;
        margin-top: 1rem;
        padding: 0.75rem;
        background-color: #fee2e2;
        border-radius: 0.5rem;
        display: none;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="payment-container">
        <div class="payment-header">
            <h2>{{ __('إتمام عملية الدفع') }}</h2>
            <p>{{ __('يرجى إكمال المعلومات أدناه لإتمام عملية الدفع') }}</p>
        </div>

        <div class="payment-details">
            @if(isset($description) && $description)
            <div class="payment-row">
                <span class="payment-label">{{ __('الوصف') }}</span>
                <span class="payment-value">{{ $description }}</span>
            </div>
            @endif

            <div class="payment-row">
                <span class="payment-label">{{ __('العملة') }}</span>
                <span class="payment-value">{{ strtoupper($currency) }}</span>
            </div>

            <div class="payment-row">
                <span class="payment-label">{{ __('المبلغ') }}</span>
                <span class="payment-value amount-value">{{ number_format($amount, 2) }} {{ strtoupper($currency) }}</span>
            </div>
        </div>

        <form id="payment-form">
            <div id="payment-element">
                <!-- سيتم إضافة عناصر نموذج الدفع من Stripe هنا -->
            </div>

            <button id="payment-submit" type="submit">
                <span id="button-text">{{ __('إتمام الدفع الآن') }}</span>
                <span id="spinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('جاري المعالجة...') }}
                </span>
            </button>

            <div id="payment-error" class="hidden"></div>

            <div class="payment-message mt-4 text-sm text-gray-500">
                <p>{{ __('المعاملات المالية آمنة ومشفرة.') }}</p>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('{{ $stripeKey }}');
        const clientSecret = '{{ $clientSecret }}';
        
        const returnUrl = '{{ $returnUrl }}';
        const cancelUrl = '{{ $cancelUrl }}';

        // إعداد عناصر Stripe
        const elements = stripe.elements({
            clientSecret: clientSecret,
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#7e22ce',
                    colorBackground: '#ffffff',
                    colorText: '#1f2937',
                    colorDanger: '#ef4444',
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                    borderRadius: '8px',
                },
            },
            locale: 'ar' // تعيين اللغة العربية
        });

        // إنشاء عنصر الدفع وإضافته إلى الصفحة
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        // معالجة التقديم
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('payment-submit');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        const paymentError = document.getElementById('payment-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // تعطيل الزر ومنع إعادة الإرسال
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            // إخفاء أي أخطاء سابقة
            paymentError.textContent = '';
            paymentError.classList.add('hidden');

            // تنفيذ الدفع
            try {
                const { error } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: returnUrl,
                    },
                    redirect: 'if_required'
                });

                if (error) {
                    // إظهار رسالة الخطأ للمستخدم
                    showError(error.message);
                } else {
                    // تم التأكيد بنجاح - التوجيه إلى صفحة النجاح
                    window.location.href = returnUrl;
                }
            } catch (e) {
                showError("حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.");
            }

            // إعادة تفعيل الزر
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        });

        // إظهار رسالة خطأ
        function showError(message) {
            paymentError.textContent = message;
            paymentError.classList.remove('hidden');
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });
</script>
@endsection
