<?php
// فرض اللغة المختارة من الجلسة
$locale = session('locale', config('app.locale'));
app()->setLocale($locale);
?>
@extends('layouts.app')

@section('title', __('pages.contact.title'))

@section('styles')
<style>
    /* تصميم صفحة التواصل الجديد */
    
    /* العنوان الرئيسي والنص الفرعي */
    .contact-hero {
        background: linear-gradient(rgba(147, 51, 234, 0.03), rgba(79, 70, 229, 0.03));
        border-radius: 16px;
        margin-bottom: 3rem;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .contact-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239333ea' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .gradient-heading {
        background: linear-gradient(135deg, #9333EA, #4F46E5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
        display: inline-block;
    }
    
    /* أزرار التواصل السريع */
    .contact-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .contact-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-top: 4px solid #9333EA;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    
    .contact-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #9333EA, #6366F1);
        transform: scaleX(0);
    .contact-btn {
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: var(--white);
        border-radius: var(--radius);
        padding: 1rem;
        text-align: center;
        transition: var(--transition);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .contact-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ===== FORM SECTION ===== */
    .contact-form-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto 3rem;
    }

    @media (min-width: 768px) {
        .contact-form-section {
            grid-template-columns: 1fr 1fr;
        }
    }

    .contact-form-card {
        background-color: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2rem;
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .contact-form-card:hover {
        box-shadow: var(--shadow-md);
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #9333EA, #6366F1);
    }
    
    [dir="rtl"] .contact-form-card::before {
        left: auto;
        right: 0;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        background-color: #F9FAFB;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .form-input:focus {
        border-color: #9333EA;
        box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
        outline: none;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4B5563;
    }
    
    .submit-btn {
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #9333EA, #6366F1);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
    }
    
    /* معلومات التواصل */
    .contact-info-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .contact-info-item {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .info-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(99, 102, 241, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    [dir="rtl"] .info-icon-wrapper {
        margin-right: 0;
        margin-left: 1rem;
    }
    
    .info-icon {
        color: #9333EA;
        font-size: 18px;
    }
    
    .info-text h4 {
        color: #1F2937;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .info-text p {
        color: #6B7280;
    }
    
    /* وسائل التواصل الاجتماعي */
    .social-media-section {
        margin-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
        padding-top: 1.5rem;
    }
    
    .social-media-icons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(99, 102, 241, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9333EA;
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        transform: translateY(-3px);
        background: linear-gradient(135deg, #9333EA, #6366F1);
        color: white;
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
    }
    
    /* ساعات العمل */
    .working-hours-section {
        margin-top: 2rem;
    }
    
    .working-hours-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .hours-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .hours-row:last-child {
        border-bottom: none;
    }
    
    .day {
        color: #4B5563;
    }
    
    .time {
        font-weight: 500;
        color: #1F2937;
    }
    
    /* خريطة الموقع */
    .map-section {
        margin-top: 3rem;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .map-container {
        height: 400px;
        background-color: #F3F4F6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6B7280;
    }
    
    /* RTL Support */
    .dir-ltr {
        direction: ltr !important;
        text-align: left !important;
        unicode-bidi: embed;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- قسم العنوان الرئيسي - Hero Section -->
    <div class="contact-hero">
        <h1 class="text-4xl font-bold gradient-heading mb-4">
            {{ __('pages.contact.title') }}
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            {{ __('pages.contact.subtitle') }}
        </p>
    </div>

    <!-- بطاقات الاتصال السريع - Quick Contact Cards -->
    <div class="contact-cards-grid">
        <!-- بطاقة واتساب - WhatsApp Card -->
        <div class="contact-card">
            <div class="contact-icon-wrapper">
                <i class="fab fa-whatsapp contact-icon"></i>
            </div>
            <h3>{{ app()->getLocale() == 'ar' ? 'واتساب' : 'WhatsApp' }}</h3>
            <p>{{ app()->getLocale() == 'ar' ? 'تواصل معنا عبر واتساب للحصول على رد سريع' : 'Chat with us on WhatsApp for quick response' }}</p>
            <a href="https://wa.me/966123456789" class="contact-btn">
                <i class="fas fa-arrow-right"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'مراسلة' : 'Chat Now' }}</span>
            </a>
        </div>
        
        <!-- بطاقة الهاتف - Phone Card -->
        <div class="contact-card">
            <div class="contact-icon-wrapper">
                <i class="fas fa-phone-alt contact-icon"></i>
            </div>
            <h3>{{ app()->getLocale() == 'ar' ? 'اتصل بنا' : 'Call Us' }}</h3>
            <p>{{ app()->getLocale() == 'ar' ? 'يمكنك الاتصال بنا مباشرة خلال ساعات العمل' : 'Call us directly during work hours' }}</p>
            <a href="tel:+966123456789" class="contact-btn">
                <i class="fas fa-phone"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'اتصل الآن' : 'Call Now' }}</span>
            </a>
        </div>
        
        <!-- بطاقة البريد الإلكتروني - Email Card -->
        <div class="contact-card">
            <div class="contact-icon-wrapper">
                <i class="fas fa-envelope contact-icon"></i>
            </div>
            <h3>{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</h3>
            <p>{{ app()->getLocale() == 'ar' ? 'أرسل لنا بريداً إلكترونياً وسنرد عليك خلال 24 ساعة' : 'Send us an email and we\'ll respond within 24 hours' }}</p>
            <a href="mailto:info@nafsaji.com" class="contact-btn">
                <i class="fas fa-envelope"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'مراسلة' : 'Email Us' }}</span>
            </a>
        </div>
        
        <!-- بطاقة الموقع - Location Card -->
        <div class="contact-card">
            <div class="contact-icon-wrapper">
                <i class="fas fa-map-marker-alt contact-icon"></i>
            </div>
            <h3>{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</h3>
            <p>{{ app()->getLocale() == 'ar' ? 'زر مقرنا في مدينة الرياض، المملكة العربية السعودية' : 'Visit our office in Riyadh, Saudi Arabia' }}</p>
            <a href="https://maps.google.com/?q=Riyadh+Saudi+Arabia" class="contact-btn" target="_blank">
                <i class="fas fa-map-marked-alt"></i>
                <span>{{ app()->getLocale() == 'ar' ? 'عرض الخريطة' : 'View Map' }}</span>
            </a>
        </div>
    </div>

    <!-- قسم النموذج ومعلومات الاتصال -->
    <div class="contact-form-section">
        <!-- نموذج الاتصال - Contact Form -->
        <div class="contact-form-card">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('pages.contact.send') }} {{ __('pages.contact.message') }}</h2>
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div>
                    <label class="form-label">{{ __('pages.contact.name') }}</label>
                    <input type="text" name="name" class="form-input" required
                           placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}">
                </div>

                <div>
                    <label class="form-label">{{ __('pages.contact.email') }}</label>
                    <input type="email" name="email" class="form-input" required
                           placeholder="{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Your email address' }}">
                </div>
                
                <div>
                    <label class="form-label">{{ __('pages.contact.phone') }}</label>
                    <input type="tel" name="phone" class="form-input dir-ltr"
                           placeholder="{{ app()->getLocale() == 'ar' ? 'رقم الهاتف (اختياري)' : 'Phone number (optional)' }}">
                </div>
                
                <div>
                    <label class="form-label">{{ __('pages.contact.subject') }}</label>
                    <input type="text" name="subject" class="form-input" required
                           placeholder="{{ app()->getLocale() == 'ar' ? 'موضوع الرسالة' : 'Message subject' }}">
                </div>

                <div>
                    <label class="form-label">{{ __('pages.contact.message') }}</label>
                    <textarea name="message" rows="5" class="form-input" required
                              placeholder="{{ app()->getLocale() == 'ar' ? 'نص الرسالة...' : 'Your message here...' }}"></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('pages.contact.send') }}
                </button>
            </form>
        </div>

        <!-- معلومات الاتصال - Contact Information -->
        <div>
            <!-- بطاقة معلومات الاتصال -->
            <div class="contact-info-card">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('pages.contact.contact_info') }}</h2>
                
                <!-- العنوان -->
                <div class="contact-info-item">
                    <div class="info-icon-wrapper">
                        <i class="fas fa-map-marker-alt info-icon"></i>
                    </div>
                    <div class="info-text">
                        <h4>{{ __('pages.contact.address') }}</h4>
                        <p>{{ app()->getLocale() == 'ar' ? 'الرياض، المملكة العربية السعودية' : 'Riyadh, Saudi Arabia' }}</p>
                    </div>
                </div>
                
                <!-- رقم الهاتف -->
                <div class="contact-info-item">
                    <div class="info-icon-wrapper">
                        <i class="fas fa-phone-alt info-icon"></i>
                    </div>
                    <div class="info-text">
                        <h4>{{ __('pages.contact.phone_number') }}</h4>
                        <p class="dir-ltr">+966 55 123 4567</p>
                    </div>
                </div>
                
                <!-- البريد الإلكتروني -->
                <div class="contact-info-item">
                    <div class="info-icon-wrapper">
                        <i class="fas fa-envelope info-icon"></i>
                    </div>
                    <div class="info-text">
                        <h4>{{ __('pages.contact.email') }}</h4>
                        <p class="dir-ltr">info@nafsaji.com</p>
                    </div>
                </div>
                
                <!-- بريد الدعم الفني -->
                <div class="contact-info-item">
                    <div class="info-icon-wrapper">
                        <i class="fas fa-headset info-icon"></i>
                    </div>
                    <div class="info-text">
                        <h4>{{ __('pages.contact.support') }}</h4>
                        <p class="dir-ltr">support@nafsaji.com</p>
                    </div>
                </div>
                
                <!-- وسائل التواصل الاجتماعي -->
                <div class="social-media-section">
                    <h3 class="text-lg font-medium text-gray-800">{{ app()->getLocale() == 'ar' ? 'تابعنا على وسائل التواصل' : 'Follow Us On Social Media' }}</h3>
                    
                    <div class="social-media-icons">
                        <a href="#" class="social-icon" aria-label="فيسبوك">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="انستجرام">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="تويتر">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="يوتيوب">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="واتساب">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- ساعات العمل -->
            <div class="working-hours-section">
                <div class="working-hours-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('pages.contact.working_hours') }}</h2>
                    
                    <div class="hours-row">
                        <span class="day">{{ app()->getLocale() == 'ar' ? 'الأحد - الخميس' : 'Sunday - Thursday' }}</span>
                        <span class="time">{{ app()->getLocale() == 'ar' ? '9:00 صباحاً - 5:00 مساءً' : '9:00 AM - 5:00 PM' }}</span>
                    </div>
                    
                    <div class="hours-row">
                        <span class="day">{{ app()->getLocale() == 'ar' ? 'الجمعة' : 'Friday' }}</span>
                        <span class="time">{{ app()->getLocale() == 'ar' ? 'مغلق' : 'Closed' }}</span>
                    </div>
                    
                    <div class="hours-row">
                        <span class="day">{{ app()->getLocale() == 'ar' ? 'السبت' : 'Saturday' }}</span>
                        <span class="time">{{ app()->getLocale() == 'ar' ? '10:00 صباحاً - 2:00 مساءً' : '10:00 AM - 2:00 PM' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- خريطة الموقع -->
    <div class="map-section">
        <h2 class="text-2xl font-semibold text-gray-800 p-6 border-b border-gray-100">
            {{ app()->getLocale() == 'ar' ? 'موقعنا على الخريطة' : 'Our Location' }}
        </h2>
        <div class="map-container">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-map-marked-alt text-2xl"></i>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    {{ app()->getLocale() == 'ar' ? 'قريباً سيتم إضافة خريطة تفاعلية هنا' : 'An interactive map will be added here soon' }}
                </p>
                <a href="https://maps.google.com/?q=Riyadh+Saudi+Arabia" target="_blank" class="contact-btn inline-flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i>
                    <span>{{ app()->getLocale() == 'ar' ? 'فتح في خرائط جوجل' : 'Open in Google Maps' }}</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- قسم الختام والتشجيع -->
    <div class="contact-hero mt-12 mb-0 text-center">
        <h2 class="text-2xl font-bold mb-4">{{ app()->getLocale() == 'ar' ? 'نتطلع لسماع صوتك!' : 'We look forward to hearing from you!' }}</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            {{ app()->getLocale() == 'ar' ? 'سيقوم فريقنا بالرد عليك في أسرع وقت ممكن. نحن هنا لمساعدتك والإجابة على جميع استفساراتك.' : 'Our team will get back to you as soon as possible. We are here to help and answer any questions you may have.' }}
        </p>
    </div>
</div>
@endsection
