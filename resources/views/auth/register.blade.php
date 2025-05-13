<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')

@section('title', __('pages.auth.register.title'))

@section('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
@endsection

@section('content')
<div class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-indigo-50">
    <div class="max-w-xl mx-auto">
        <!-- غرافيك العنوان -->
        <div class="text-center mb-10">
            <a href="/" class="inline-block">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        ن
                    </div>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">
                    {{ config('app.name', 'نفسجي') }}
                </h2>
            </a>
            <p class="mt-3 text-gray-600 text-lg">
                {{ __('pages.auth.register.welcome_message') }}
            </p>
        </div>

        <!-- بطاقة إنشاء الحساب -->
        <div class="bg-white shadow-xl rounded-xl overflow-hidden transform transition-all">
            <!-- عنوان البطاقة -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 py-6 px-6">
                <h3 class="text-xl font-bold text-white text-center">
                    {{ __('pages.auth.register.title') }}
                </h3>
            </div>

            <!-- رسائل الخطأ -->
            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 my-4 mx-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                        <ul class="list-disc space-y-1 pl-5 rtl:pr-5 rtl:pl-0">
                            @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- نموذج إنشاء الحساب -->
            <div class="py-8 px-6">
                <form class="space-y-6" method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf
                    
                    <!-- الاسم -->
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.auth.register.name') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                id="name" 
                                name="name" 
                                type="text" 
                                value="{{ old('name') }}"
                                required 
                                class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="{{ __('pages.auth.register.name_placeholder') }}"
                            >
                        </div>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.auth.register.email') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}"
                                required 
                                class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="{{ __('pages.auth.register.email_placeholder') }}"
                            >
                        </div>
                    </div>

                    <!-- رقم الهاتف (اختياري) -->
                    <div>
                        <label for="phone" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.auth.register.phone') }} <span class="text-gray-500 text-sm">{{ __('pages.auth.register.optional') }}</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input 
                                id="phone" 
                                name="phone" 
                                type="tel" 
                                value="{{ old('phone') }}"
                                class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="{{ __('pages.auth.register.phone_placeholder') }}"
                            >
                        </div>
                    </div>
                    
                    <!-- كلمة المرور -->
                    <div>
                        <label for="password" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.auth.register.password') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="{{ __('pages.auth.register.password_placeholder') }}"
                            >
                            <div class="absolute inset-y-0 right-0 rtl:left-0 rtl:right-auto pr-3 rtl:pl-3 rtl:pr-0 flex items-center">
                                <button type="button" id="toggle-password" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">{{ __('pages.auth.register.password_hint') }}</p>
                    </div>
                    
                    <!-- تأكيد كلمة المرور -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.auth.register.confirm_password') }}
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 rtl:right-0 rtl:left-auto pl-3 rtl:pr-3 rtl:pl-0 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                class="block w-full pl-10 rtl:pr-10 rtl:pl-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="{{ __('pages.auth.register.confirm_password_placeholder') }}"
                            >
                        </div>
                    </div>

                    <!-- الشروط والأحكام -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="terms" 
                                name="terms" 
                                type="checkbox" 
                                required
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                            >
                        </div>
                        <div class="ml-3 rtl:mr-3 rtl:ml-0 text-sm">
                            <label for="terms" class="text-gray-700">
                                {{ __('pages.auth.register.agree_terms_part1') }} 
                                <a href="#" class="text-purple-600 hover:text-purple-500">{{ __('pages.auth.register.terms') }}</a>
                                {{ __('pages.auth.register.and') }}
                                <a href="#" class="text-purple-600 hover:text-purple-500">{{ __('pages.auth.register.privacy_policy') }}</a>
                            </label>
                        </div>
                    </div>
                    
                    <!-- زر إنشاء الحساب -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform transition hover:scale-105"
                        >
                            <i class="fas fa-user-plus mr-2 rtl:ml-2 rtl:mr-0"></i>
                            {{ __('pages.auth.register.submit') }}
                        </button>
                    </div>
                </form>
                
                <!-- أو تسجيل باستخدام -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                {{ __('pages.auth.register.or_sign_up_with') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transform transition hover:scale-105">
                            <i class="fab fa-google text-red-500 mr-2 rtl:ml-2 rtl:mr-0"></i>
                            Google
                        </a>
                        <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transform transition hover:scale-105">
                            <i class="fab fa-facebook text-blue-600 mr-2 rtl:ml-2 rtl:mr-0"></i>
                            Facebook
                        </a>
                    </div>
                </div>
                
                <!-- لديك حساب بالفعل؟ -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        {{ __('pages.auth.register.already_have_account') }}
                        <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">
                            {{ __('pages.auth.login.submit') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- مزايا الانضمام -->
        <div class="mt-10 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-purple-700 mb-4 text-center">{{ __('pages.auth.register.benefits_title') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-calendar-check text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                        <h4 class="text-sm font-medium text-gray-800">{{ __('pages.auth.register.benefit1_title') }}</h4>
                        <p class="text-xs text-gray-600">{{ __('pages.auth.register.benefit1_desc') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-history text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                        <h4 class="text-sm font-medium text-gray-800">{{ __('pages.auth.register.benefit2_title') }}</h4>
                        <p class="text-xs text-gray-600">{{ __('pages.auth.register.benefit2_desc') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-bell text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                        <h4 class="text-sm font-medium text-gray-800">{{ __('pages.auth.register.benefit3_title') }}</h4>
                        <p class="text-xs text-gray-600">{{ __('pages.auth.register.benefit3_desc') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-lock text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-3 rtl:mr-3 rtl:ml-0">
                        <h4 class="text-sm font-medium text-gray-800">{{ __('pages.auth.register.benefit4_title') }}</h4>
                        <p class="text-xs text-gray-600">{{ __('pages.auth.register.benefit4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تبديل عرض كلمة المرور
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // تغيير الأيقونة
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // إضافة التحقق من صحة النموذج
    const form = document.getElementById('register-form');
    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const terms = document.getElementById('terms');
            
            // التحقق من الاسم
            if (!name.value || name.value.length < 3) {
                isValid = false;
                showError(name, 'الاسم يجب أن يحتوي على 3 أحرف على الأقل');
            } else {
                removeError(name);
            }
            
            // التحقق من البريد الإلكتروني
            if (!email.value || !email.value.includes('@')) {
                isValid = false;
                showError(email, 'الرجاء إدخال بريد إلكتروني صحيح');
            } else {
                removeError(email);
            }
            
            // التحقق من كلمة المرور
            if (!password.value || password.value.length < 8) {
                isValid = false;
                showError(password, 'كلمة المرور يجب أن تكون 8 أحرف على الأقل');
            } else {
                removeError(password);
            }
            
            // التحقق من تأكيد كلمة المرور
            if (password.value !== passwordConfirmation.value) {
                isValid = false;
                showError(passwordConfirmation, 'كلمات المرور غير متطابقة');
            } else {
                removeError(passwordConfirmation);
            }
            
            // التحقق من الموافقة على الشروط
            if (!terms.checked) {
                isValid = false;
                showError(terms, 'يجب الموافقة على الشروط والأحكام');
            } else {
                removeError(terms);
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
    
    // وظائف مساعدة للتحقق
    function showError(input, message) {
        const formControl = input.parentElement;
        formControl.classList.add('error');
        
        // إنشاء عنصر رسالة الخطأ إذا لم يكن موجودًا
        let errorElement = formControl.querySelector('.error-message');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.className = 'error-message text-sm text-red-600 mt-1';
            formControl.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        input.classList.add('border-red-500');
    }
    
    function removeError(input) {
        const formControl = input.parentElement;
        formControl.classList.remove('error');
        
        const errorElement = formControl.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
        
        input.classList.remove('border-red-500');
    }
});
</script>
@endsection
