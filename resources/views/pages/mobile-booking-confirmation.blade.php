@extends('layouts.app')

@section('title', __('pages.booking.confirmation_title'))

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
        width: 100%;
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
    
    /* أنماط تأكيد الحجز */
    .confirmation-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #ecfdf5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .confirmation-icon i {
        font-size: 40px;
        color: #10b981;
    }
    
    .confirmation-title {
        font-size: 22px;
        font-weight: 700;
        text-align: center;
        color: #111827;
        margin-bottom: 12px;
    }
    
    .confirmation-subtitle {
        font-size: 16px;
        text-align: center;
        color: #6b7280;
        margin-bottom: 24px;
    }
    
    .booking-details-list {
        background-color: #f9fafb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .booking-detail-item {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .booking-detail-item:last-child {
        border-bottom: none;
    }
    
    .booking-detail-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    
    .booking-detail-icon i {
        font-size: 14px;
        color: #6366f1;
    }
    
    .booking-detail-content {
        flex: 1;
    }
    
    .booking-detail-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    
    .booking-detail-value {
        font-weight: 600;
        color: #374151;
    }
    
    /* العداد التنازلي */
    .countdown {
        display: flex;
        justify-content: center;
        margin: 24px 0;
    }
    
    .countdown-item {
        width: 60px;
        text-align: center;
        margin: 0 6px;
    }
    
    .countdown-value {
        background-color: #6366f1;
        color: white;
        border-radius: 8px;
        font-size: 24px;
        font-weight: 700;
        padding: 10px 0;
        box-shadow: 0 4px 6px rgba(99, 102, 241, 0.2);
    }
    
    .countdown-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    /* نصائح وإرشادات */
    .tips-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
    }
    
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tips-list li {
        position: relative;
        padding-left: 24px;
        padding-bottom: 10px;
        color: #4b5563;
        font-size: 14px;
    }
    
    .tips-list li::before {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 1px;
        color: #10b981;
        font-size: 12px;
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
</style>
@endsection

@section('content')
<!-- عرض التطبيق المخصص للجوال (يظهر فقط على شاشات الجوال) - Mobile App View -->
<div class="block">
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content pb-20">
        <!-- قسم العنوان والتأكيد - Header & Confirmation Section -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 pt-6 pb-6 px-4 text-white">
            <div class="confirmation-icon bg-white mx-auto mb-4">
                <i class="fas fa-check-circle text-emerald-500"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2 text-center">{{ __('pages.booking.confirmation_title') }}</h1>
            <p class="text-emerald-100 mb-6 text-center">{{ __('pages.booking.confirmation_subtitle') }}</p>
            
            <!-- بطاقة معلومات الحجز - Booking Information Card -->
            <div class="bg-white rounded-xl p-4 text-gray-800 shadow-lg">
                <div class="text-center mb-2">
                    <h3 class="font-bold text-gray-900">{{ app()->getLocale() == 'ar' ? 'رقم الحجز' : 'Booking Number' }}</h3>
                    <div class="text-xl font-extrabold text-indigo-600 tracking-wider">BK{{ rand(100000, 999999) }}</div>
                </div>
                <div class="bg-indigo-50 py-3 px-4 rounded-lg flex items-center justify-center">
                    <i class="far fa-calendar-check text-indigo-600 me-2"></i>
                    <span class="text-sm font-medium text-gray-700">
                        {{ app()->getLocale() == 'ar' ? 'تم تأكيد موعدك بنجاح!' : 'Your appointment has been confirmed!' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- مؤشر خطوات الحجز - Booking Steps Indicator -->
        <div class="app-section mt-6">
            <div class="booking-steps">
                <div class="progress-line" style="width: 100%"></div>
                <div class="step completed">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="step completed">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="step completed">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="step active">4</div>
            </div>
            <div class="flex justify-between text-sm mt-2 px-3">
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.date') }}</span>
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.details') }}</span>
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.payment') }}</span>
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.confirm') }}</span>
            </div>
        </div>
        
        <!-- تفاصيل الحجز - Booking Details -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.details') }}</h2>
            
            <div class="app-card">
                <div class="booking-details-list">
                    <!-- المتخصص - Specialist -->
                    <div class="booking-detail-item">
                        <div class="booking-detail-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">{{ app()->getLocale() == 'ar' ? 'المتخصص' : 'Specialist' }}</div>
                            <div class="booking-detail-value">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد • طبيب نفسي' : 'Dr. John Smith • Psychiatrist' }}</div>
                        </div>
                    </div>
                    
                    <!-- التاريخ والوقت - Date & Time -->
                    <div class="booking-detail-item">
                        <div class="booking-detail-icon">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">{{ app()->getLocale() == 'ar' ? 'التاريخ والوقت' : 'Date & Time' }}</div>
                            <div class="booking-detail-value">{{ app()->getLocale() == 'ar' ? '12 مايو 2025 • 10:00 صباحاً' : 'May 12, 2025 • 10:00 AM' }}</div>
                        </div>
                    </div>
                    
                    <!-- نوع الجلسة - Session Type -->
                    <div class="booking-detail-item">
                        <div class="booking-detail-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">{{ app()->getLocale() == 'ar' ? 'نوع الجلسة' : 'Session Type' }}</div>
                            <div class="booking-detail-value">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو • 60 دقيقة' : 'Video Session • 60 minutes' }}</div>
                        </div>
                    </div>
                    
                    <!-- طريقة الدفع - Payment Method -->
                    <div class="booking-detail-item">
                        <div class="booking-detail-icon">
                            <i class="far fa-credit-card"></i>
                        </div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">{{ app()->getLocale() == 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</div>
                            <div class="booking-detail-value">{{ app()->getLocale() == 'ar' ? 'بطاقة ائتمان' : 'Credit Card' }}</div>
                        </div>
                    </div>
                    
                    <!-- المبلغ - Amount -->
                    <div class="booking-detail-item">
                        <div class="booking-detail-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="booking-detail-content">
                            <div class="booking-detail-label">{{ app()->getLocale() == 'ar' ? 'المبلغ' : 'Amount' }}</div>
                            <div class="booking-detail-value">{{ app()->getLocale() == 'ar' ? '287.5 ريال (شامل الضريبة)' : '287.5 SAR (inc. VAT)' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- العداد التنازلي - Countdown -->
                <div class="text-center mb-3">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ app()->getLocale() == 'ar' ? 'موعدك بعد' : 'Your appointment in' }}</h3>
                    <div class="countdown">
                        <div class="countdown-item">
                            <div class="countdown-value">29</div>
                            <div class="countdown-label">{{ app()->getLocale() == 'ar' ? 'يوم' : 'Days' }}</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value">14</div>
                            <div class="countdown-label">{{ app()->getLocale() == 'ar' ? 'ساعة' : 'Hours' }}</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-value">45</div>
                            <div class="countdown-label">{{ app()->getLocale() == 'ar' ? 'دقيقة' : 'Minutes' }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-indigo-50 p-4 rounded-lg text-indigo-900 text-sm">
                    <p>
                        <i class="fas fa-info-circle me-2 text-indigo-600"></i>
                        {{ app()->getLocale() == 'ar' ? 'تم إرسال تأكيد الحجز إلى بريدك الإلكتروني. سنرسل لك تذكيراً قبل موعدك بيوم واحد.' : 'A booking confirmation has been sent to your email. We will send you a reminder one day before your appointment.' }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- نصائح للجلسة - Session Tips -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.session_tips') }}</h2>
            
            <div class="app-card">
                <h3 class="tips-title">{{ app()->getLocale() == 'ar' ? 'نصائح لجلسة ناجحة' : 'Tips for a successful session' }}</h3>
                <ul class="tips-list">
                    <li>{{ app()->getLocale() == 'ar' ? 'تأكد من وجود اتصال إنترنت مستقر قبل الجلسة.' : 'Ensure you have a stable internet connection before the session.' }}</li>
                    <li>{{ app()->getLocale() == 'ar' ? 'اختر مكاناً هادئاً وخاصاً للجلسة.' : 'Choose a quiet and private place for the session.' }}</li>
                    <li>{{ app()->getLocale() == 'ar' ? 'قم بتحضير أي أسئلة أو نقاط تود مناقشتها.' : 'Prepare any questions or points you would like to discuss.' }}</li>
                    <li>{{ app()->getLocale() == 'ar' ? 'ادخل إلى المنصة قبل 5 دقائق من بدء الجلسة.' : 'Log in to the platform 5 minutes before the session starts.' }}</li>
                </ul>
                
                <h3 class="tips-title mt-4">{{ app()->getLocale() == 'ar' ? 'الخطوات التالية' : 'Next Steps' }}</h3>
                <ul class="tips-list">
                    <li>{{ app()->getLocale() == 'ar' ? 'ستتلقى رابط الجلسة على بريدك الإلكتروني قبل 15 دقيقة من الموعد.' : 'You will receive the session link to your email 15 minutes before the appointment.' }}</li>
                    <li>{{ app()->getLocale() == 'ar' ? 'يمكنك إلغاء أو إعادة جدولة موعدك قبل 24 ساعة من الموعد المحدد.' : 'You can cancel or reschedule your appointment up to 24 hours before the scheduled time.' }}</li>
                    <li>{{ app()->getLocale() == 'ar' ? 'إذا كانت لديك أي استفسارات، يمكنك التواصل مع فريق الدعم على 920001234.' : 'If you have any questions, you can contact our support team at 920001234.' }}</li>
                </ul>
            </div>
            
            <a href="{{ route('mobile.user.appointments') }}" class="app-main-button">
                {{ app()->getLocale() == 'ar' ? 'عرض مواعيدي' : 'View My Appointments' }}
                <i class="fas fa-chevron-right ms-1 text-sm"></i>
            </a>
            
            <a href="{{ route('mobile.home') }}" class="app-secondary-button">
                {{ app()->getLocale() == 'ar' ? 'العودة للصفحة الرئيسية' : 'Back to Home' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // هذا النص البرمجي يمكن استخدامه لتنفيذ العداد التنازلي الفعلي
        // This script can be used to implement the actual countdown timer
        console.log('Booking confirmation page loaded');
    });
</script>
@endsection
