@extends('layouts.app')

@section('title', __('حالة الدفع') . ' | ' . config('app.name'))

@section('styles')
<style>
    .status-container {
        max-width: 700px;
        margin: 3rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .status-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .status-header h2 {
        font-weight: 600;
        color: #4c1d95;
        margin-bottom: 0.5rem;
    }
    
    .status-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .status-icon.success {
        background-color: #d1fae5;
        color: #047857;
    }
    
    .status-icon.pending {
        background-color: #fef3c7;
        color: #b45309;
    }
    
    .status-icon.failed {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .status-icon i {
        font-size: 2rem;
    }
    
    .status-details {
        background-color: #f8f5ff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .status-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed #e5e7eb;
    }
    
    .status-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .status-label {
        font-weight: 500;
        color: #4b5563;
    }
    
    .status-value {
        font-weight: 600;
        color: #1f2937;
    }
    
    .status-amount {
        font-size: 1.25rem;
        color: #4c1d95;
    }
    
    .status-buttons {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .status-button {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .primary-button {
        background: linear-gradient(to right, #7e22ce, #4c1d95);
        color: white;
    }
    
    .primary-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .secondary-button {
        background-color: #f3f4f6;
        color: #4b5563;
    }
    
    .secondary-button:hover {
        background-color: #e5e7eb;
    }
    
    .status-message {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .success-message {
        background-color: #d1fae5;
        color: #047857;
    }
    
    .pending-message {
        background-color: #fef3c7;
        color: #b45309;
    }
    
    .failed-message {
        background-color: #fee2e2;
        color: #b91c1c;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="status-container">
        <div class="status-header">
            @if($payment->status === \App\Models\Payment::STATUS_COMPLETED)
                <div class="status-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>{{ __('تمت عملية الدفع بنجاح') }}</h2>
                <div class="status-message success-message">
                    <p>{{ __('تم تأكيد الدفع وتسجيله بنجاح. شكراً لك!') }}</p>
                </div>
            @elseif($payment->status === \App\Models\Payment::STATUS_PENDING)
                <div class="status-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <h2>{{ __('عملية الدفع قيد المعالجة') }}</h2>
                <div class="status-message pending-message">
                    <p>{{ __('جاري معالجة الدفع. يرجى الانتظار.') }}</p>
                </div>
            @elseif($payment->status === \App\Models\Payment::STATUS_FAILED)
                <div class="status-icon failed">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2>{{ __('فشلت عملية الدفع') }}</h2>
                <div class="status-message failed-message">
                    <p>{{ __('حدث خطأ أثناء معالجة الدفع.') }}</p>
                    @if($payment->error_message)
                        <p class="mt-2">{{ $payment->error_message }}</p>
                    @endif
                </div>
            @elseif($payment->status === \App\Models\Payment::STATUS_CANCELLED)
                <div class="status-icon failed">
                    <i class="fas fa-ban"></i>
                </div>
                <h2>{{ __('تم إلغاء عملية الدفع') }}</h2>
                <div class="status-message failed-message">
                    <p>{{ __('تم إلغاء عملية الدفع.') }}</p>
                </div>
            @elseif($payment->status === \App\Models\Payment::STATUS_REFUNDED)
                <div class="status-icon success">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <h2>{{ __('تم استرداد المبلغ') }}</h2>
                <div class="status-message success-message">
                    <p>{{ __('تم استرداد المبلغ بنجاح إلى حسابك.') }}</p>
                </div>
            @endif
        </div>

        <div class="status-details">
            <div class="status-row">
                <span class="status-label">{{ __('رقم المعاملة') }}</span>
                <span class="status-value">#{{ $payment->id }}</span>
            </div>
            
            <div class="status-row">
                <span class="status-label">{{ __('التاريخ') }}</span>
                <span class="status-value">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
            </div>
            
            @if($payment->description)
                <div class="status-row">
                    <span class="status-label">{{ __('الوصف') }}</span>
                    <span class="status-value">{{ $payment->description }}</span>
                </div>
            @endif
            
            <div class="status-row">
                <span class="status-label">{{ __('طريقة الدفع') }}</span>
                <span class="status-value">{{ $payment->payment_method ?: 'بطاقة ائتمان' }}</span>
            </div>
            
            <div class="status-row">
                <span class="status-label">{{ __('المبلغ') }}</span>
                <span class="status-value status-amount">{{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</span>
            </div>
        </div>

        <div class="status-buttons">
            @if($payment->status === \App\Models\Payment::STATUS_COMPLETED && $payment->receipt_url)
                <a href="{{ $payment->receipt_url }}" target="_blank" class="status-button primary-button">
                    <i class="fas fa-receipt mr-2"></i> {{ __('عرض الإيصال') }}
                </a>
            @endif
            
            @if($payment->status === \App\Models\Payment::STATUS_FAILED)
                <a href="{{ route('payment.checkout', ['amount' => $payment->amount, 'currency' => $payment->currency, 'description' => $payment->description]) }}" class="status-button primary-button">
                    <i class="fas fa-redo mr-2"></i> {{ __('إعادة المحاولة') }}
                </a>
            @endif
            
            <a href="{{ route('home') }}" class="status-button secondary-button">
                <i class="fas fa-home mr-2"></i> {{ __('العودة للرئيسية') }}
            </a>
        </div>
    </div>
</div>
@endsection
