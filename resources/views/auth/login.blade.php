<?php
// فرض اللغة الإنجليزية إذا كانت موجودة في الجلسة
if (session('locale') === 'en') {
    app()->setLocale('en');
}
?>
@extends('layouts.app')

@section('title', __('pages.auth.login.title'))

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
                {{ __('pages.auth.login.welcome_back') }}
            </p>
        </div>

        <!-- بطاقة تسجيل الدخول -->
        <div class="bg-white shadow-xl rounded-xl overflow-hidden transform transition-all">
            <!-- عنوان البطاقة -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 py-6 px-6">
                <h3 class="text-xl font-bold text-white text-center">
                    {{ __('pages.auth.login.title') }}
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

            <!-- نموذج تسجيل الدخول -->
            <div class="py-8 px-6">
                <form class="space-y-6" method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf
                    
                    <!-- البريد الإلكتروني -->
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">
                            {{ __('pages.auth.login.email') }}
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
                                placeholder="{{ __('pages.auth.login.email_placeholder') }}"
                            >
                        </div>
                    </div>
                    
                    <!-- كلمة المرور -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-gray-700 font-medium">
                                {{ __('pages.auth.login.password') }}
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500">
                                {{ __('pages.auth.login.forgot_password') }}
                            </a>
                        </div>
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
                                placeholder="{{ __('pages.auth.login.password_placeholder') }}"
                            >
                            <div class="absolute inset-y-0 right-0 rtl:left-0 rtl:right-auto pr-3 rtl:pl-3 rtl:pr-0 flex items-center">
                                <button type="button" id="toggle-password" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- تذكرني -->
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                        >
                        <label for="remember_me" class="ml-2 rtl:mr-2 rtl:ml-0 block text-sm text-gray-700">
                            {{ __('pages.auth.login.remember_me') }}
                        </label>
                    </div>
                    
                    <!-- زر تسجيل الدخول -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform transition hover:scale-105"
                        >
                            <i class="fas fa-sign-in-alt mr-2 rtl:ml-2 rtl:mr-0"></i>
                            {{ __('pages.auth.login.submit') }}
                        </button>
                    </div>
                </form>
                
                <!-- أو تسجيل دخول باستخدام -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                {{ __('pages.auth.login.or_continue_with') }}
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
                
                <!-- تسجيل حساب جديد -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        {{ __('pages.auth.login.no_account') }}
                        <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-500">
                            {{ __('pages.auth.register.submit') }}
                        </a>
                    </p>
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
    const form = document.getElementById('login-form');
    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            // التحقق من البريد الإلكتروني
            if (!email.value || !email.value.includes('@')) {
                isValid = false;
                showError(email, 'الرجاء إدخال بريد إلكتروني صحيح');
            } else {
                removeError(email);
            }
            
            // التحقق من كلمة المرور
            if (!password.value || password.value.length < 6) {
                isValid = false;
                showError(password, 'كلمة المرور يجب أن تكون 6 أحرف على الأقل');
            } else {
                removeError(password);
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
