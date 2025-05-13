@extends('layouts.app')

@section('title', __('pages.contact.title'))

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
        overscroll-behavior: none; /* منع سلوك التمرير الزائد - Prevent overscroll behavior */
        -webkit-tap-highlight-color: transparent; /* إزالة التظليل عند النقر - Remove tap highlight */
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
        touch-action: manipulation; /* تحسين الاستجابة للمس - Improve touch responsiveness */
    }
    
    /* إزالة مظهر الويب بالكامل - Remove all web appearance */
    .mobile-app-view #web-header,
    .mobile-app-view #web-footer,
    .mobile-app-view .web-element,
    .mobile-app-view .browser-ui {
        display: none !important;
    }
    
    /* محتوى التطبيق - App Content Container */
    .app-content {
        padding: 20px;
        padding-top: calc(65px + env(safe-area-inset-top, 20px)); /* إضافة مسافة للهيدر الثابت */
        margin-bottom: 80px;
        position: relative;
    }
    
    /* زخارف خلفية - Background Decorations */
    .app-bg-decoration {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(99, 102, 241, 0.1));
        z-index: 0;
        filter: blur(40px);
        pointer-events: none;
    }
    
    /* قسم التطبيق - App Section */
    .app-section {
        margin-bottom: 32px;
        position: relative;
        z-index: 1;
    }
    
    /* العنوان الرئيسي - Main Title */
    .app-main-title {
        font-size: 28px;
        font-weight: 800;
        color: #4F46E5;
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
    
    /* عنوان القسم - Section Title */
    .app-section-title {
        font-size: 22px;
        font-weight: 700;
        color: #4B5563;
        margin-bottom: 16px;
        position: relative;
        display: inline-block;
    }
    
    .app-section-title::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(135deg, #9333EA, #4F46E5);
        border-radius: 2px;
    }
    
    /* عنوان فرعي - Section Subtitle */
    .app-section-subtitle {
        font-size: 16px;
        color: #6B7280;
        margin-bottom: 24px;
        line-height: 1.5;
    }
    
    /* بطاقة معلومات - Info Card */
    .app-info-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(99, 102, 241, 0.1);
        transition: all 0.3s ease;
    }
    
    .app-info-card:active {
        transform: scale(0.98);
    }
    
    /* أيقونة المعلومات - Info Icon */
    .app-info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.12), rgba(99, 102, 241, 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        color: #6366f1;
        flex-shrink: 0;
        font-size: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .app-info-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.5), rgba(99, 102, 241, 0.5));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .app-info-card:active .app-info-icon::before {
        opacity: 0.2;
    }
    
    /* نموذج التواصل - Contact Form */
    .app-form {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(99, 102, 241, 0.1);
        position: relative;
        overflow: hidden;
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
    
    /* مجموعة الحقول - Form Field Group */
    .app-form-group {
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    /* عنوان الحقل - Field Label */
    .app-form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #4B5563;
        margin-bottom: 8px;
        transition: all 0.2s;
    }
    
    /* حقل المدخلات - Input Field */
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
    
    /* منطقة نصية - Textarea */
    .app-form-textarea {
        width: 100%;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid rgba(99, 102, 241, 0.2);
        background-color: #F9FAFB;
        font-size: 15px;
        min-height: 140px;
        resize: none;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    
    .app-form-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        background-color: #fff;
    }
    
    /* زر الإرسال - Submit Button */
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
    
    /* FAQ أقسام الأسئلة الشائعة */
    .app-faq-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-left: 3px solid #6366f1;
        transition: all 0.3s;
    }
    
    .app-faq-card.active {
        border-color: #9333EA;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .app-faq-question {
        font-weight: 600;
        color: #374151;
        transition: color 0.3s;
    }
    
    .app-faq-card.active .app-faq-question {
        color: #6366f1;
    }
    
    .app-faq-answer {
        padding-top: 16px;
        margin-top: 16px;
        border-top: 1px solid #E5E7EB;
        color: #6B7280;
        line-height: 1.6;
    }
    
    /* تأثيرات التحرك - Animations */
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
    
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(0px);
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* أزرار الاتصال السريع - Quick Contact Buttons */
    .quick-contact-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .quick-contact-btn i {
        font-size: 24px;
        margin-bottom: 8px;
    }
    
    .quick-contact-btn:active {
        transform: translateY(2px);
        box-shadow: 0 2px 3px rgba(0,0,0,0.1);
    }
    
    /* أيقونات التواصل الاجتماعي - Social Media Icons */
    .social-icon-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .social-icon-btn:active {
        transform: scale(0.95);
        box-shadow: 0 2px 3px rgba(0,0,0,0.1);
    }
    
    /* دعم RTL والاتجاهات - RTL & Direction Support */
    .dir-ltr {
        direction: ltr;
        text-align: left;
        unicode-bidi: embed;
    }
    
    .dir-rtl {
        direction: rtl;
        text-align: right;
        unicode-bidi: embed;
    }
    
    [dir="rtl"] .app-section-title::after {
        left: auto;
        right: 0;
    }
    
    [dir="rtl"] .app-info-icon {
        margin-right: 0;
        margin-left: 16px;
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .float-animation {
        animation: float 6s ease-in-out infinite;
    }
    
    .pulse-animation {
        animation: pulse 3s ease-in-out infinite;
    }
    
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }
</style>
@endsection

@section('body-class', 'mobile-app-view')

@section('header')
@endsection

@section('content')
<div class="block" x-data="{ showMenu: false }"> 
    <!-- محتوى التطبيق - App Content -->
    <div class="app-content">
        <!-- زخارف خلفية - Background decorations -->
        <div class="app-bg-decoration" style="width: 180px; height: 180px; top: 5%; right: -60px; opacity: 0.5;"></div>
        <div class="app-bg-decoration" style="width: 230px; height: 230px; bottom: 30%; left: -100px; opacity: 0.4;"></div>
        <div class="app-bg-decoration" style="width: 120px; height: 120px; top: 50%; left: 60%; opacity: 0.3;"></div>
        
        <!-- قسم العنوان - Title Section -->
        <div class="app-section text-center fade-in-up">
            <h1 class="app-main-title">
                {{ app()->getLocale() == 'ar' ? 'اتصل بنا' : 'Contact Us' }}
            </h1>
            <p class="app-section-subtitle mx-auto max-w-md">
                {{ app()->getLocale() == 'ar' ? 'نحن هنا لمساعدتك. تواصل معنا للإجابة على استفساراتك ودعمك بأفضل الطرق.' : 'We are here to help. Reach out to us for any questions or support needs.' }}
            </p>
        </div>
        
        <!-- قسم الاتصال السريع - Quick Contact Section -->
        <div class="app-section fade-in-up delay-1">
            <h2 class="app-section-title text-center w-full">
                {{ app()->getLocale() == 'ar' ? 'تواصل معنا بسرعة' : 'Get In Touch Quickly' }}
            </h2>
            
            <!-- أزرار الاتصال السريع - Quick Contact Buttons -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                <!-- واتساب - WhatsApp -->
                <a href="https://wa.me/966123456789" class="quick-contact-btn bg-green-500 hover:bg-green-600">
                    <i class="fab fa-whatsapp text-xl"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'واتساب' : 'WhatsApp' }}</span>
                </a>
                
                <!-- اتصال - Call -->
                <a href="tel:+966123456789" class="quick-contact-btn bg-blue-500 hover:bg-blue-600">
                    <i class="fas fa-phone-alt"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'اتصال' : 'Call Now' }}</span>
                </a>
                
                <!-- البريد الإلكتروني - Email -->
                <a href="mailto:info@nafsaji.com" class="quick-contact-btn bg-red-500 hover:bg-red-600">
                    <i class="fas fa-envelope"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'إيميل' : 'Email' }}</span>
                </a>
                
                <!-- الموقع - Location -->
                <a href="https://maps.google.com/?q=Riyadh+Saudi+Arabia" class="quick-contact-btn bg-yellow-500 hover:bg-yellow-600">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</span>
                </a>
            </div>
            
            <!-- وسائل التواصل الاجتماعي - Social Media -->
            <h3 class="font-semibold text-center mb-4 text-gray-700">
                {{ app()->getLocale() == 'ar' ? 'تابعنا على وسائل التواصل' : 'Follow Us On Social Media' }}
            </h3>
            
            <div class="flex justify-center items-center space-x-4 rtl:space-x-reverse mb-8">
                <a href="#" class="social-icon-btn bg-blue-600 hover:bg-blue-700">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon-btn bg-pink-600 hover:bg-pink-700">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-icon-btn bg-blue-400 hover:bg-blue-500">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon-btn bg-red-600 hover:bg-red-700">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
            
            <!-- معلومات الاتصال - Contact Information -->
            <div class="app-info-card">
                <div class="flex items-center">
                    <div class="app-info-icon pulse-animation">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ app()->getLocale() == 'ar' ? 'العنوان' : 'Address' }}
                        </h3>
                        <p class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'الرياض، المملكة العربية السعودية' : 'Riyadh, Saudi Arabia' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- البريد الإلكتروني - Email -->
            <div class="app-info-card">
                <div class="flex items-center">
                    <div class="app-info-icon pulse-animation">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                        </h3>
                        <p class="text-gray-600 dir-ltr">info@nafsaji.com</p>
                    </div>
                </div>
            </div>
            
            <!-- الهاتف - Phone -->
            <div class="app-info-card">
                <div class="flex items-center">
                    <div class="app-info-icon pulse-animation">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ app()->getLocale() == 'ar' ? 'الهاتف' : 'Phone' }}
                        </h3>
                        <p class="text-gray-600 dir-ltr">+966 123 456 789</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- نموذج الاتصال - Contact Form -->
        <div class="app-section fade-in-up delay-2">
            <h2 class="app-section-title">
                {{ app()->getLocale() == 'ar' ? 'أرسل رسالة' : 'Send a Message' }}
            </h2>
            
            <div class="app-form">
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    
                    <div class="app-form-group">
                        <label for="name" class="app-form-label">
                            {{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}
                        </label>
                        <input type="text" id="name" name="name" class="app-form-input" required 
                               placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل اسمك هنا' : 'Enter your name' }}">
                    </div>
                    
                    <div class="app-form-group">
                        <label for="email" class="app-form-label">
                            {{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                        </label>
                        <input type="email" id="email" name="email" class="app-form-input" required
                               placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email' }}">
                    </div>
                    
                    <div class="app-form-group">
                        <label for="phone" class="app-form-label">
                            {{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}
                        </label>
                        <input type="tel" id="phone" name="phone" class="app-form-input"
                               placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل رقم هاتفك (اختياري)' : 'Enter your phone number (optional)' }}">
                    </div>
                    
                    <div class="app-form-group">
                        <label for="subject" class="app-form-label">
                            {{ app()->getLocale() == 'ar' ? 'الموضوع' : 'Subject' }}
                        </label>
                        <input type="text" id="subject" name="subject" class="app-form-input" required
                               placeholder="{{ app()->getLocale() == 'ar' ? 'موضوع رسالتك' : 'Subject of your message' }}">
                    </div>
                    
                    <div class="app-form-group">
                        <label for="message" class="app-form-label">
                            {{ app()->getLocale() == 'ar' ? 'الرسالة' : 'Message' }}
                        </label>
                        <textarea id="message" name="message" class="app-form-textarea" required
                                  placeholder="{{ app()->getLocale() == 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...' }}"></textarea>
                    </div>
                    
                    <div class="app-form-group">
                        <button type="submit" class="app-form-submit">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                {{ app()->getLocale() == 'ar' ? 'إرسال الرسالة' : 'Send Message' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- قسم الأسئلة الشائعة - FAQ Section -->
        <div class="app-section fade-in-up delay-3">
            <h2 class="app-section-title">
                {{ app()->getLocale() == 'ar' ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}
            </h2>
            
            <div id="faq-container">
                <div class="app-faq-card" x-data="{ open: false }" @click="open = !open" :class="{ 'active': open }">
                    <div class="app-faq-question flex justify-between items-center cursor-pointer">
                        <span>{{ app()->getLocale() == 'ar' ? 'كيف يمكنني حجز موعد؟' : 'How can I book an appointment?' }}</span>
                        <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>
                    <div class="app-faq-answer" x-show="open" x-collapse>
                        {{ app()->getLocale() == 'ar' ? 'يمكنك حجز موعد عبر صفحة المختصين باختيار المختص المناسب لك ثم الضغط على زر "حجز موعد"' : 'You can book an appointment through the specialists page by selecting the appropriate specialist and then clicking the "Book an appointment" button.' }}
                    </div>
                </div>
                <div class="app-faq-card" x-data="{ open: false }" @click="open = !open" :class="{ 'active': open }">
                    <div class="app-faq-question flex justify-between items-center cursor-pointer">
                        <span>{{ app()->getLocale() == 'ar' ? 'كم تستغرق الاستشارة النفسية؟' : 'How long does a psychological consultation take?' }}</span>
                        <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>
                    <div class="app-faq-answer" x-show="open" x-collapse>
                        {{ app()->getLocale() == 'ar' ? 'تستغرق الجلسة الاستشارية عادة من 45 إلى 60 دقيقة حسب نوع الخدمة والحالة' : 'The consultation session usually takes from 45 to 60 minutes depending on the type of service and condition.' }}
                    </div>
                </div>
                <div class="app-faq-card" x-data="{ open: false }" @click="open = !open" :class="{ 'active': open }">
                    <div class="app-faq-question flex justify-between items-center cursor-pointer">
                        <span>{{ app()->getLocale() == 'ar' ? 'هل الجلسات مجانية؟' : 'Are the sessions free?' }}</span>
                        <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </div>
                    <div class="app-faq-answer" x-show="open" x-collapse>
                        {{ app()->getLocale() == 'ar' ? 'لا، تختلف رسوم الجلسات حسب نوع الخدمة والمختص، يمكنك الاطلاع على التفاصيل في صفحة الخدمات' : 'No, session fees vary according to the type of service and specialist. You can see the details on the services page.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
