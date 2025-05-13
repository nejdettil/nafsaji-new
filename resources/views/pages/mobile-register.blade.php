@extends('layouts.app')

@section('title', __('pages.auth.register.title'))

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
        background-color: #f5f7ff;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        overscroll-behavior: none; /* منع سلوك التمرير الزائد */
        -webkit-tap-highlight-color: transparent; /* إزالة التظليل عند النقر */
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
        touch-action: manipulation; /* تحسين الاستجابة للمس */
    }
    
    /* إزالة مظهر الويب بالكامل */
    .mobile-app-view #web-header,
    .mobile-app-view #web-footer,
    .mobile-app-view .web-element,
    .mobile-app-view .browser-ui {
        display: none !important;
    }
    
    /* محتوى التطبيق */
    .app-content {
        padding: 20px;
        padding-top: calc(65px + env(safe-area-inset-top, 20px)); /* إضافة مسافة للهيدر الثابت */
        margin-bottom: 80px;
        position: relative;
    }
    
    /* زخارف خلفية */
    .app-bg-decoration {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(99, 102, 241, 0.1));
        z-index: 0;
        filter: blur(40px);
        pointer-events: none;
    }
    
    /* قسم التطبيق */
    .app-section {
        margin-bottom: 32px;
        position: relative;
        z-index: 1;
    }
    
    /* العنوان الرئيسي */
    .app-main-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        text-align: center;
        background: linear-gradient(135deg, #9333EA, #4F46E5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        display: inline-block;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .app-main-title::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 25%;
        width: 50%;
        height: 3px;
        background: linear-gradient(135deg, #9333EA, #4F46E5);
        border-radius: 2px;
    }
    
    /* نموذج التسجيل */
    .app-form {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(99, 102, 241, 0.1);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }
    
    .app-form::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.08), rgba(99, 102, 241, 0.08));
        border-radius: 0 0 0 100%;
        z-index: 0;
    }
    
    .app-form::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.08), rgba(99, 102, 241, 0.08));
        border-radius: 0 100% 0 0;
        z-index: 0;
    }
    
    /* مجموعة الحقول */
    .app-form-group {
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    /* عنوان الحقل */
    .app-form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #4B5563;
        margin-bottom: 8px;
        transition: all 0.2s;
    }
    
    /* حقل المدخلات */
    .app-form-input {
        width: 100%;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid rgba(99, 102, 241, 0.2);
        background-color: #F9FAFB;
        font-size: 15px;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    
    .app-form-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        background-color: #fff;
    }
    
    /* زر الإرسال */
    .app-form-submit {
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        background: linear-gradient(135deg, #9333ea, #6366f1);
        color: white;
        font-weight: 600;
        border: none;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .app-form-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: all 0.4s;
    }
    
    .app-form-submit:active {
        transform: translateY(2px);
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.4);
    }
    
    .app-form-submit:active::before {
        left: 100%;
    }
    
    /* مؤشر حالة كلمة المرور */
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        cursor: pointer;
    }
    
    [dir="rtl"] .password-toggle {
        right: auto;
        left: 12px;
    }
    
    /* خط فاصل مع نص */
    .divider-with-text {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 24px 0;
    }
    
    .divider-with-text:before,
    .divider-with-text:after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #E5E7EB;
    }
    
    .divider-with-text:before {
        margin-right: 10px;
    }
    
    .divider-with-text:after {
        margin-left: 10px;
    }
    
    [dir="rtl"] .divider-with-text:before {
        margin-right: 0;
        margin-left: 10px;
    }
    
    [dir="rtl"] .divider-with-text:after {
        margin-left: 0;
        margin-right: 10px;
    }
    
    .divider-text {
        color: #9CA3AF;
        font-size: 14px;
        padding: 0 10px;
    }
    
    /* أزرار التسجيل بالخدمات الخارجية */
    .social-login-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        background: white;
        border: 1px solid #E5E7EB;
        margin-bottom: 12px;
        font-weight: 500;
        color: #4B5563;
        transition: all 0.3s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .social-login-btn i {
        margin-right: 10px;
        font-size: 18px;
    }
    
    [dir="rtl"] .social-login-btn i {
        margin-right: 0;
        margin-left: 10px;
    }
    
    .social-login-btn:active {
        transform: translateY(1px);
        box-shadow: none;
    }
    
    .btn-google {
        color: #EA4335;
        border-color: rgba(234, 67, 53, 0.2);
    }
    
    .btn-facebook {
        color: #1877F2;
        border-color: rgba(24, 119, 242, 0.2);
    }
    
    .btn-apple {
        color: #000000;
        border-color: rgba(0, 0, 0, 0.1);
    }
    
    /* تأثيرات الحركة */
    @keyframes fadeInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    
    /* دعم اللغة العربية */
    [dir="rtl"] .app-section-title::after {
        left: auto;
        right: 0;
    }
    
    .dir-ltr {
        direction: ltr !important;
        text-align: left !important;
        unicode-bidi: embed;
    }
    
    /* شعار التطبيق */
    .app-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #9333EA, #4F46E5);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 2.5rem;
        margin: 0 auto 20px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
    }
    
    /* بطاقة ميزات الخدمة */
    .feature-card {
        padding: 16px;
        border-radius: 12px;
        background: white;
        margin-bottom: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        display: flex;
        align-items: flex-start;
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(99, 102, 241, 0.1));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    [dir="rtl"] .feature-icon {
        margin-right: 0;
        margin-left: 12px;
    }
    
    .feature-content h4 {
        font-weight: 600;
        color: #4B5563;
        margin-bottom: 4px;
    }
    
    .feature-content p {
        color: #6B7280;
        font-size: 14px;
        line-height: 1.4;
    }
</style>
@endsection

@section('body-class', 'mobile-app-view')

@section('content')
<div class="block" x-data="{ showPassword: false, showPasswordConfirmation: false }"> 
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content">
        <!-- زخارف خلفية - Background decorations -->
        <div class="app-bg-decoration" style="width: 180px; height: 180px; top: 5%; right: -60px; opacity: 0.5;"></div>
        <div class="app-bg-decoration" style="width: 200px; height: 200px; bottom: 40%; left: -70px; opacity: 0.3;"></div>
        <div class="app-bg-decoration" style="width: 150px; height: 150px; top: 40%; left: 60%; opacity: 0.2;"></div>
        
        <!-- قسم العنوان - Title Section -->
        <div class="app-section text-center fade-in-up">
            <h1 class="app-main-title">
                {{ __('pages.auth.register.title') }}
            </h1>
            <p class="text-gray-600 mt-4">
                {{ __('pages.auth.register.welcome_message') }}
            </p>
        </div>
        
        <!-- نموذج إنشاء الحساب - Register Form -->
        <div class="app-section fade-in-up delay-1">
            <div class="app-form">
                <form action="{{ route('register') }}" method="POST" id="register-form">
                    @csrf
                    
                    <!-- رسائل الخطأ -->
                    @if($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                        @foreach ($errors->all() as $error)
                            <p><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- الاسم -->
                    <div class="app-form-group">
                        <label for="name" class="app-form-label">
                            {{ __('pages.auth.register.name') }}
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="app-form-input" 
                            required 
                            autocomplete="name"
                            value="{{ old('name') }}"
                            placeholder="{{ __('pages.auth.register.name_placeholder') }}"
                        >
                    </div>
                    
                    <!-- البريد الإلكتروني -->
                    <div class="app-form-group">
                        <label for="email" class="app-form-label">
                            {{ __('pages.auth.register.email') }}
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="app-form-input dir-ltr" 
                            required 
                            autocomplete="email"
                            value="{{ old('email') }}"
                            placeholder="{{ __('pages.auth.register.email_placeholder') }}"
                        >
                    </div>
                    
                    <!-- كلمة المرور -->
                    <div class="app-form-group">
                        <label for="password" class="app-form-label">
                            {{ __('pages.auth.register.password') }}
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password" 
                                name="password" 
                                class="app-form-input dir-ltr" 
                                required 
                                autocomplete="new-password"
                                placeholder="{{ __('pages.auth.register.password_placeholder') }}"
                            >
                            <span @click="showPassword = !showPassword" class="password-toggle">
                                <i :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ __('pages.auth.register.password_hint') }}
                        </div>
                    </div>
                    
                    <!-- تأكيد كلمة المرور -->
                    <div class="app-form-group">
                        <label for="password_confirmation" class="app-form-label">
                            {{ __('pages.auth.register.confirm_password') }}
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPasswordConfirmation ? 'text' : 'password'" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="app-form-input dir-ltr" 
                                required 
                                autocomplete="new-password"
                                placeholder="{{ __('pages.auth.register.confirm_password_placeholder') }}"
                            >
                            <span @click="showPasswordConfirmation = !showPasswordConfirmation" class="password-toggle">
                                <i :class="showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                            </span>
                        </div>
                    </div>
                    
                    <!-- الشروط والاحكام -->
                    <div class="app-form-group">
                        <div class="flex items-center">
                            <input type="checkbox" id="terms" name="terms" class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500" required>
                            <label for="terms" class="ml-2 rtl:mr-2 rtl:ml-0 text-sm text-gray-700">
                                {{ __('pages.auth.register.agree_terms') }}
                            </label>
                        </div>
                    </div>
                    
                    <!-- زر التسجيل -->
                    <div class="app-form-group">
                        <button type="submit" class="app-form-submit">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2 rtl:ml-2 rtl:mr-0"></i>
                                {{ __('pages.auth.register.submit') }}
                            </span>
                        </button>
                    </div>
                </form>
                
                <!-- التسجيل باستخدام الشبكات الاجتماعية -->
                <div class="divider-with-text">
                    <span class="divider-text">{{ __('pages.auth.register.or_sign_up_with') }}</span>
                </div>
                
                <div>
                    <button type="button" class="social-login-btn btn-google">
                        <i class="fab fa-google"></i>
                        {{ __('pages.auth.login.google') }}
                    </button>
                    
                    <button type="button" class="social-login-btn btn-facebook">
                        <i class="fab fa-facebook"></i>
                        {{ __('pages.auth.login.facebook') }}
                    </button>
                    
                    <button type="button" class="social-login-btn btn-apple">
                        <i class="fab fa-apple"></i>
                        {{ __('pages.auth.login.apple') }}
                    </button>
                </div>
            </div>
            
            <!-- تسجيل الدخول للحسابات الموجودة -->
            <div class="text-center mt-6">
                <p class="text-gray-600 mb-2">{{ __('pages.auth.register.already_have_account') }}</p>
                <a href="{{ route('mobile.login') }}" class="text-purple-600 font-semibold text-lg">
                    {{ __('pages.auth.login.title') }}
                </a>
            </div>
        </div>
        
        <!-- مزايا إنشاء حساب -->
        <div class="app-section fade-in-up delay-2">
            <h3 class="text-xl font-bold text-center text-gray-800 mb-4">
                {{ __('pages.auth.register.benefits_title') }}
            </h3>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="feature-content">
                    <h4>{{ __('pages.auth.register.benefit1_title') }}</h4>
                    <p>{{ __('pages.auth.register.benefit1_desc') }}</p>
                </div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="feature-content">
                    <h4>{{ __('pages.auth.register.benefit3_title') }}</h4>
                    <p>{{ __('pages.auth.register.benefit3_desc') }}</p>
                </div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="feature-content">
                    <h4>{{ __('pages.auth.register.benefit4_title') }}</h4>
                    <p>{{ __('pages.auth.register.benefit4_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // التعامل مع الأخطاء وتنبيهات التحقق
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('register-form');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const termsCheckbox = document.getElementById('terms');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // التحقق من الاسم
            if (!nameInput.value.trim()) {
                showError(nameInput, '{{ __('validation.required', ['attribute' => __('pages.auth.register.name')]) }}');
                isValid = false;
            } else {
                removeError(nameInput);
            }
            
            // التحقق من البريد الإلكتروني
            if (!emailInput.value.trim()) {
                showError(emailInput, '{{ __('validation.required', ['attribute' => __('pages.auth.register.email')]) }}');
                isValid = false;
            } else if (!isValidEmail(emailInput.value)) {
                showError(emailInput, '{{ __('validation.email', ['attribute' => __('pages.auth.register.email')]) }}');
                isValid = false;
            } else {
                removeError(emailInput);
            }
            
            // التحقق من كلمة المرور
            if (!passwordInput.value) {
                showError(passwordInput, '{{ __('validation.required', ['attribute' => __('pages.auth.register.password')]) }}');
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                showError(passwordInput, '{{ __('validation.min.string', ['attribute' => __('pages.auth.register.password'), 'min' => 8]) }}');
                isValid = false;
            } else {
                removeError(passwordInput);
            }
            
            // التحقق من تأكيد كلمة المرور
            if (!passwordConfirmInput.value) {
                showError(passwordConfirmInput, '{{ __('validation.required', ['attribute' => __('pages.auth.register.confirm_password')]) }}');
                isValid = false;
            } else if (passwordInput.value !== passwordConfirmInput.value) {
                showError(passwordConfirmInput, '{{ __('validation.confirmed', ['attribute' => __('pages.auth.register.password')]) }}');
                isValid = false;
            } else {
                removeError(passwordConfirmInput);
            }
            
            // التحقق من الموافقة على الشروط
            if (!termsCheckbox.checked) {
                showError(termsCheckbox, '{{ __('validation.accepted', ['attribute' => __('pages.auth.register.terms')]) }}');
                isValid = false;
            } else {
                removeError(termsCheckbox);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // التحقق من صحة البريد الإلكتروني
        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        
        // إظهار رسالة خطأ
        function showError(input, message) {
            const formGroup = input.closest('.app-form-group');
            removeError(input);
            
            const errorElement = document.createElement('div');
            errorElement.className = 'text-red-500 text-sm mt-1 error-message';
            errorElement.innerText = message;
            
            if (input.type === 'checkbox') {
                input.parentElement.insertAdjacentElement('afterend', errorElement);
            } else {
                input.classList.add('border-red-500');
                formGroup.appendChild(errorElement);
            }
        }
        
        // إزالة رسالة الخطأ
        function removeError(input) {
            const formGroup = input.closest('.app-form-group');
            const existingError = formGroup.querySelector('.error-message');
            
            if (existingError) {
                existingError.remove();
            }
            
            if (input.type !== 'checkbox') {
                input.classList.remove('border-red-500');
            }
        }
    });
</script>
@endsection
