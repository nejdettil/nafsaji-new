@extends('layouts.app')

@section('title', __('pages.booking.details_title'))

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
        width: 50%;
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
    
    /* أنماط نوع الجلسة */
    .session-type-card {
        padding: 16px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
        margin-bottom: 12px;
        position: relative;
    }
    
    .session-type-card.selected {
        border-color: #6366f1;
        background-color: #f5f3ff;
    }
    
    .session-type-card .check-mark {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #6366f1;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .session-type-card.selected .check-mark {
        opacity: 1;
    }
    
    /* أنماط نموذج تفاصيل الجلسة */
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #374151;
    }
    
    .form-input, .form-select, .form-textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        padding: 12px;
        border-radius: 8px;
        background-color: white;
        transition: all 0.2s ease;
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }
    
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .radio-group {
        display: flex;
        gap: 16px;
    }
    
    .radio-item {
        display: flex;
        align-items: center;
    }
    
    .radio-item input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #6366f1;
    }
    
    .radio-item label {
        margin-left: 8px;
        font-size: 15px;
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
        <!-- قسم العنوان والمتخصص - Header & Specialist Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 pt-6 pb-6 px-4 text-white">
            <h1 class="text-2xl font-bold mb-2">{{ __('pages.booking.details_title') }}</h1>
            <p class="text-indigo-100 mb-6">{{ __('pages.booking.details_subtitle') }}</p>
            
            <!-- بطاقة معلومات المتخصص والموعد - Specialist & Appointment Card -->
            <div class="bg-white rounded-xl p-4 text-gray-800 flex flex-col shadow-lg">
                <div class="flex items-center mb-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                        {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                    </div>
                    <div class="ms-3 flex-1">
                        <h2 class="font-bold text-gray-900">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</h2>
                        <p class="text-sm text-gray-600">{{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}</p>
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
                <div class="progress-line" style="width: 50%"></div>
                <div class="step completed">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="step active">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            <div class="flex justify-between text-sm mt-2 px-3">
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.date') }}</span>
                <span class="text-indigo-600 font-medium">{{ __('pages.booking.steps.details') }}</span>
                <span class="text-gray-500">{{ __('pages.booking.steps.payment') }}</span>
                <span class="text-gray-500">{{ __('pages.booking.steps.confirm') }}</span>
            </div>
        </div>
        
        <!-- اختيار نوع الجلسة - Session Type Selection -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.session_type') }}</h2>
            
            <div class="session-type-card selected" data-type="video">
                <div class="check-mark">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 mr-3">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}</h3>
                        <p class="text-sm text-gray-600">{{ app()->getLocale() == 'ar' ? 'تواصل مباشر مع المتخصص عبر مكالمة فيديو' : 'Direct video communication with the specialist' }}</p>
                        <div class="text-indigo-600 font-bold mt-1">{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="session-type-card" data-type="voice">
                <div class="check-mark">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-3">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'جلسة صوتية' : 'Voice Session' }}</h3>
                        <p class="text-sm text-gray-600">{{ app()->getLocale() == 'ar' ? 'تواصل مباشر مع المتخصص عبر مكالمة صوتية' : 'Direct voice communication with the specialist' }}</p>
                        <div class="text-blue-600 font-bold mt-1">{{ app()->getLocale() == 'ar' ? '200 ريال' : '200 SAR' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- نموذج تفاصيل الجلسة - Session Details Form -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.session_details') }}</h2>
            
            <div class="app-card">
                <form>
                    <!-- سبب الجلسة - Reason for Session -->
                    <div class="form-group">
                        <label for="reason" class="form-label">
                            {{ app()->getLocale() == 'ar' ? 'سبب الجلسة' : 'Reason for Session' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="reason" class="form-select">
                            <option disabled selected>{{ app()->getLocale() == 'ar' ? 'اختر سبب الجلسة' : 'Select reason for session' }}</option>
                            <option>{{ app()->getLocale() == 'ar' ? 'القلق' : 'Anxiety' }}</option>
                            <option>{{ app()->getLocale() == 'ar' ? 'الاكتئاب' : 'Depression' }}</option>
                            <option>{{ app()->getLocale() == 'ar' ? 'الضغط النفسي' : 'Stress' }}</option>
                            <option>{{ app()->getLocale() == 'ar' ? 'اضطرابات النوم' : 'Sleep disorders' }}</option>
                            <option>{{ app()->getLocale() == 'ar' ? 'مشاكل العلاقات' : 'Relationship issues' }}</option>
                            <option>{{ app()->getLocale() == 'ar' ? 'أخرى' : 'Other' }}</option>
                        </select>
                    </div>
                    
                    <!-- وصف المشكلة - Problem Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            {{ app()->getLocale() == 'ar' ? 'وصف المشكلة' : 'Problem Description' }}
                        </label>
                        <textarea id="description" class="form-textarea" placeholder="{{ app()->getLocale() == 'ar' ? 'صف مشكلتك بإيجاز...' : 'Briefly describe your problem...' }}"></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ app()->getLocale() == 'ar' ? 'ستساعد هذه المعلومات المتخصص في التحضير لجلستك' : 'This information will help the specialist prepare for your session' }}
                        </p>
                    </div>
                    
                    <!-- الخبرة السابقة - Previous Experience -->
                    <div class="form-group">
                        <label class="form-label">
                            {{ app()->getLocale() == 'ar' ? 'هل سبق وتلقيت استشارات نفسية؟' : 'Have you received psychological counseling before?' }}
                        </label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="experience_yes" name="experience" checked>
                                <label for="experience_yes">{{ app()->getLocale() == 'ar' ? 'نعم' : 'Yes' }}</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="experience_no" name="experience">
                                <label for="experience_no">{{ app()->getLocale() == 'ar' ? 'لا' : 'No' }}</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- طلبات خاصة - Special Requests -->
                    <div class="form-group">
                        <label for="special_requests" class="form-label">
                            {{ app()->getLocale() == 'ar' ? 'طلبات خاصة' : 'Special Requests' }}
                        </label>
                        <textarea id="special_requests" class="form-textarea" placeholder="{{ app()->getLocale() == 'ar' ? 'أي طلبات أو ملاحظات إضافية...' : 'Any additional requests or notes...' }}"></textarea>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- ملخص الحجز - Booking Summary -->
        <div class="app-section">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('pages.booking.summary') }}</h2>
            
            <div class="app-card">
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'المتخصص' : 'Specialist' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'التاريخ والوقت' : 'Date & Time' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? '12 مايو 2025 • 10:00 ص' : 'May 12, 2025 • 10:00 AM' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'نوع الجلسة' : 'Session Type' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'المدة' : 'Duration' }}</span>
                    <span class="font-medium">{{ app()->getLocale() == 'ar' ? '60 دقيقة' : '60 minutes' }}</span>
                </div>
                <div class="flex justify-between py-2 border-t border-gray-100 mt-2">
                    <span class="font-semibold text-gray-800">{{ app()->getLocale() == 'ar' ? 'السعر' : 'Price' }}</span>
                    <span class="font-bold text-indigo-600">{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</span>
                </div>
            </div>
            
            <a href="{{ route('mobile.booking.payment') }}" class="app-main-button">
                {{ app()->getLocale() == 'ar' ? 'المتابعة إلى الدفع' : 'Continue to Payment' }}
                <i class="fas fa-chevron-right ms-1 text-sm"></i>
            </a>
            
            <a href="{{ route('mobile.booking') }}" class="app-secondary-button">
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل اختيار نوع الجلسة
        const sessionTypeCards = document.querySelectorAll('.session-type-card');
        sessionTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                sessionTypeCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                
                // تحديث السعر في ملخص الحجز حسب نوع الجلسة المحدد
                const sessionType = this.getAttribute('data-type');
                const priceElement = document.querySelector('.app-card .text-indigo-600');
                
                if (sessionType === 'video') {
                    priceElement.textContent = '{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}';
                    document.querySelector('.app-card .font-medium:last-of-type').textContent = 
                        '{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}';
                } else {
                    priceElement.textContent = '{{ app()->getLocale() == 'ar' ? '200 ريال' : '200 SAR' }}';
                    document.querySelector('.app-card .font-medium:last-of-type').textContent = 
                        '{{ app()->getLocale() == 'ar' ? 'جلسة صوتية' : 'Voice Session' }}';
                }
            });
        });
    });
</script>
@endsection
