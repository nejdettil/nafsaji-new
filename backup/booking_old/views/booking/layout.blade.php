@extends('layouts.app')

@section('styles')
<style>
    /* تخصيص الألوان الرئيسية بناءً على هوية نفسجي */
    :root {
        --primary-color: #7e22ce; /* أرجواني غامق */
        --primary-light: #a855f7; /* أرجواني فاتح */
        --primary-dark: #4c1d95; /* أرجواني داكن */
        --secondary-color: #e5e7eb; /* رمادي فاتح */
        --success-color: #059669; /* أخضر */
        --warning-color: #f59e0b; /* برتقالي */
        --danger-color: #dc2626; /* أحمر */
        --text-dark: #1f2937; /* نص داكن */
        --text-light: #6b7280; /* نص فاتح */
        --bg-light: #f9fafb; /* خلفية فاتحة */
        --bg-white: #ffffff; /* خلفية بيضاء */
    }

    /* تنسيق عام */
    .booking-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .page-subtitle {
        font-size: 1.2rem;
        color: var(--text-light);
        margin-bottom: 2rem;
        text-align: center;
    }

    /* تنسيق مؤشر خطوات الحجز */
    .booking-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
    }
    
    .booking-steps::before {
        content: "";
        position: absolute;
        top: 1.5rem;
        left: 0;
        right: 0;
        height: 2px;
        background-color: var(--secondary-color);
        z-index: 1;
    }
    
    .step {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        width: 20%;
    }
    
    .step-number {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background-color: var(--secondary-color);
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .step-text {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-light);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .step.active .step-number {
        background-color: var(--primary-color);
        color: white;
    }
    
    .step.active .step-text {
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .step.completed .step-number {
        background-color: var(--success-color);
        color: white;
    }
    
    /* أنماط البطاقات */
    .card {
        background-color: var(--bg-white);
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .card-text {
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    /* أنماط الأزرار */
    .btn {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border: none;
        outline: none;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark);
    }
    
    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-outline:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-success {
        background-color: var(--success-color);
        color: white;
    }
    
    .btn-success:hover {
        background-color: #047857;
    }
    
    /* أنماط النماذج */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.3);
        outline: none;
    }
    
    /* تنسيق رسائل التنبيه */
    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .alert-warning {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .alert-danger {
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    /* تنسيق خاص بالشبكة */
    .grid {
        display: grid;
        gap: 1.5rem;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    @media (min-width: 640px) {
        .sm\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    
    /* تنسيق خاص بقسم ملخص الحجز */
    .booking-summary {
        background-color: #f8f5ff;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 2rem;
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
    
    /* تنسيق شريط التقدم */
    .progress-container {
        margin: 1rem 0;
        background-color: var(--secondary-color);
        border-radius: 9999px;
        height: 0.5rem;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: 9999px;
        transition: width 0.5s ease;
    }
</style>

@yield('booking_styles')
@endsection

@section('content')
<div class="booking-container">
    <h1 class="page-title">{{ __('messages.book_new_session') }}</h1>
    <p class="page-subtitle">{{ __('messages.book_session_subtitle') }}</p>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif
    
    @if(isset($step) && isset($steps_count))
    <div class="booking-steps">
        <div class="step {{ $step >= 1 ? 'active' : '' }} {{ $step > 1 ? 'completed' : '' }}">
            <div class="step-number">1</div>
            <div class="step-text">{{ __('messages.select_specialist') }}</div>
        </div>
        <div class="step {{ $step >= 2 ? 'active' : '' }} {{ $step > 2 ? 'completed' : '' }}">
            <div class="step-number">2</div>
            <div class="step-text">{{ __('messages.select_service') }}</div>
        </div>
        <div class="step {{ $step >= 3 ? 'active' : '' }} {{ $step > 3 ? 'completed' : '' }}">
            <div class="step-number">3</div>
            <div class="step-text">{{ __('messages.select_date_time') }}</div>
        </div>
        <div class="step {{ $step >= 4 ? 'active' : '' }} {{ $step > 4 ? 'completed' : '' }}">
            <div class="step-number">4</div>
            <div class="step-text">{{ __('messages.confirm_details') }}</div>
        </div>
    </div>
    
    <div class="progress-container">
        <div class="progress-bar" style="width: {{ ($step / $steps_count) * 100 }}%"></div>
    </div>
    @endif
    
    @yield('booking_content')
</div>
@endsection

@section('scripts')
@yield('booking_scripts')
@endsection
