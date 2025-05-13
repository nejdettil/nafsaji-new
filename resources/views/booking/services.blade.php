@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'اختيار الخدمة' : 'Select Service')

@section('styles')
<style>
    .booking-container {
        background-color: #f8f9fa;
        padding: 30px 0;
        min-height: 70vh;
        font-family: 'Tajawal', sans-serif;
    }
    
    .booking-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .booking-header h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    
    .booking-header p {
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .booking-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 50px;
        padding: 20px 0;
        position: relative;
        z-index: 1;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 15px;
        position: relative;
        width: 110px;
        transition: all 0.3s ease;
    }
    
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 22px;
        right: -20px;
        width: 40px;
        height: 4px;
        background: linear-gradient(90deg, #0d6efd 0%, #28a745 100%);
        z-index: 0;
        border-radius: 4px;
    }
    
    .step-number {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e9ecef;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 12px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .step.active .step-number {
        background-color: #0d6efd;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    }
    
    .step.completed .step-number {
        background-color: #28a745;
        color: white;
        border: 2px solid #e9ecef;
    }
    
    .step-title {
        font-size: 0.95rem;
        color: #6c757d;
        text-align: center;
        font-weight: 500;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .step.active .step-title {
        color: #0d6efd;
        font-weight: 700;
    }
    
    .step.completed .step-title {
        color: #28a745;
        font-weight: 600;
    }
    
    .step:hover .step-number {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    .step:hover .step-title {
        color: #0d6efd;
    }
    
    @media (max-width: 767.98px) {
        .booking-steps {
            flex-wrap: wrap;
        }
        
        .step {
            margin-bottom: 15px;
        }
        
        .step:not(:last-child)::after {
            display: none;
        }
    }
    
    .specialist-info {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 30px;
        border-right: 4px solid #9333ea;
        position: relative;
        overflow: hidden;
    }
    
    .specialist-info::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 100%;
        background: linear-gradient(to left, rgba(147, 51, 234, 0.05), transparent);
        z-index: 1;
    }
    
    .specialist-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: 20px;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(147, 51, 234, 0.2);
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .specialist-info:hover .specialist-image {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(147, 51, 234, 0.3);
    }
    
    .specialist-details h4 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
        position: relative;
        display: inline-block;
        z-index: 2;
    }
    
    .specialist-details h4::after {
        content: '';
        position: absolute;
        bottom: -4px;
        right: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #9333ea, #6366f1);
        border-radius: 3px;
    }
    
    .specialist-details p {
        color: #666;
        font-size: 1rem;
        margin: 0;
        padding: 4px 12px;
        background-color: rgba(147, 51, 234, 0.08);
        border-radius: 20px;
        display: inline-block;
        z-index: 2;
    }
    
    .service-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        padding: 30px;
        border: 2px solid transparent;
    }
    
    .service-card:hover {
        box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
        transform: translateY(-7px);
        border-color: rgba(13, 110, 253, 0.2);
    }
    
    .service-card.selected {
        border-color: #0d6efd;
        background-color: #f0f7ff;
        box-shadow: 0 15px 30px rgba(13, 110, 253, 0.25);
        transform: translateY(-7px);
    }
    
    .service-card.selected::after {
        content: '✓';
        position: absolute;
        top: 10px;
        left: 10px;
        width: 25px;
        height: 25px;
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    
    .service-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.7rem;
        position: relative;
        display: inline-block;
    }
    
    .service-name::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #0d6efd, #20c997);
        border-radius: 3px;
    }
    
    .service-price {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #e9f4ff;
        color: #0d6efd;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .service-card.selected .service-price {
        background-color: #0d6efd;
        color: white;
    }
    
    .service-description {
        color: #6c757d;
        margin-bottom: 20px;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    
    .service-duration {
        color: #6c757d;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        margin-bottom: 1.2rem;
        padding: 5px 10px;
        background-color: rgba(13, 110, 253, 0.05);
        border-radius: 20px;
        display: inline-flex;
        gap: 5px;
    }
    
    .service-duration i {
        margin-left: 5px;
        color: #0d6efd;
    }
    
    .btn-continue {
        background-color: #0d6efd;
        color: white;
        padding: 15px 35px;
        border-radius: 50px;
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 40px auto 0;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
    
    .btn-continue:hover {
        background-color: #0b5ed7;
        transform: scale(1.05) translateY(-2px);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
    }
    
    .btn-continue:disabled {
        background-color: #adb5bd;
        transform: none;
        cursor: not-allowed;
        box-shadow: none;
    }
    
    .no-services {
        text-align: center;
        padding: 50px 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .no-services i {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    .no-services h3 {
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .no-services p {
        color: #6c757d;
        max-width: 500px;
        margin: 0 auto 20px;
    }
    
    .back-link {
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .back-link i {
        margin-left: 5px;
    }
    
    .back-link:hover {
        color: #0d6efd;
    }
    /* تحسين عرض معلومات المتخصص في الشريط السفلي */
    .specialist-footer-card {
        position: fixed;
        bottom: 75px;
        right: 15px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        padding: 12px 15px;
        max-width: 250px;
        display: flex;
        align-items: center;
        z-index: 20;
        border: 2px solid rgba(147, 51, 234, 0.1);
    }
    
    .specialist-footer-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #9333ea;
        margin-left: 10px;
        box-shadow: 0 3px 10px rgba(147, 51, 234, 0.2);
    }
    
    .specialist-footer-name {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 3px;
    }
    
    .specialist-footer-specialty {
        font-size: 0.75rem;
        color: #666;
        background-color: rgba(147, 51, 234, 0.08);
        padding: 2px 8px;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="booking-container">
    <div class="container">
        <!-- Page title and description -->
        <div class="booking-header">
            <h2>{{ __('booking.services.title') }}</h2>
            <p>{{ __('booking.services.subtitle') }}</p>
        </div>
        
        <!-- Booking Steps -->
        <div class="booking-steps">
            <div class="step completed">
                <div class="step-number"><i class="fas fa-user-md"></i></div>
                <div class="step-title">{{ __('booking.steps.select_specialist') }}</div>
            </div>
            <div class="step active">
                <div class="step-number"><i class="fas fa-clipboard-list"></i></div>
                <div class="step-title">{{ __('booking.steps.select_service') }}</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-calendar-alt"></i></div>
                <div class="step-title">{{ __('booking.steps.select_time') }}</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-check"></i></div>
                <div class="step-title">{{ __('booking.steps.confirm_booking') }}</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-credit-card"></i></div>
                <div class="step-title">{{ __('booking.steps.payment') }}</div>
            </div>
        </div>
        
        <!-- Back Link -->
        <a href="{{ route('booking.specialists') }}" class="back-link">
            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}"></i>
            {{ app()->getLocale() == 'ar' ? 'العودة إلى قائمة المتخصصين' : 'Back to Specialists List' }}
        </a>
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Selected Specialist Info -->
        <div class="specialist-info">
            @if($specialist->profile_image)
            <img src="{{ asset('storage/' . $specialist->profile_image) }}" class="specialist-image" alt="{{ $specialist->name }}">
            @else
            <img src="{{ asset('images/default-specialist.jpg') }}" class="specialist-image" alt="{{ $specialist->name }}">
            @endif
            <div class="specialist-details">
                <h4>{{ $specialist->name }}</h4>
                <p>{{ $specialist->specialization }}</p>
            </div>
        </div>
        
        <!-- Services List -->
        @if(count($specialist->services) > 0)
        <form id="serviceForm" action="{{ route('booking.timeslots', ['specialist' => $specialist->id, 'service' => ':service_id']) }}" method="GET">
            <div class="row">
                @foreach($specialist->services as $service)
                <div class="col-md-6 col-lg-4">
                    <div class="service-card" onclick="selectService(this, {{ $service->id }})">
                        <div class="service-price">{{ $service->price }} {{ app()->getLocale() == 'ar' ? 'ريال' : 'SAR' }}</div>
                        <h5 class="service-name">{{ $service->name }}</h5>
                        <div class="service-description">{{ $service->description }}</div>
                        <div class="service-duration">
                            <i class="far fa-clock"></i>
                            {{ $service->duration ?? 60 }} {{ app()->getLocale() == 'ar' ? 'دقيقة' : 'minutes' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button type="button" id="continueBtn" class="btn btn-continue" disabled>{{ app()->getLocale() == 'ar' ? 'متابعة' : 'Continue' }}</button>
        </form>
        @else
        <div class="no-services">
            <i class="fas fa-info-circle"></i>
            <h3>{{ app()->getLocale() == 'ar' ? 'لا توجد خدمات متاحة' : 'No Services Available' }}</h3>
            <p>{{ app()->getLocale() == 'ar' ? 'عذراً، لا يوجد خدمات متاحة لهذا المتخصص حالياً. يرجى اختيار متخصص آخر.' : 'Sorry, there are no services available for this specialist at the moment. Please choose another specialist.' }}</p>
            <a href="{{ route('booking.specialists') }}" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'العودة لاختيار متخصص آخر' : 'Return to choose another specialist' }}</a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedServiceId = null;
    
    function selectService(element, serviceId) {
        // Remove selected class from all cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selected class to the chosen card
        element.classList.add('selected');
        
        // Update selected service ID
        selectedServiceId = serviceId;
        
        // Enable continue button
        document.getElementById('continueBtn').disabled = false;
    }
    
    document.getElementById('continueBtn').addEventListener('click', function() {
        if (selectedServiceId) {
            // Replace service ID in the URL and submit form
            const url = document.getElementById('serviceForm').action;
            const newUrl = url.replace(':service_id', selectedServiceId);
            window.location.href = newUrl;
        }
    });
    
    // Improve specialist info display in the bottom bar
    document.addEventListener('DOMContentLoaded', function() {
        // Wait to ensure all elements are loaded
        setTimeout(function() {
            // Search for text elements at the bottom that might contain specialist text
            const footerElements = document.querySelectorAll('body > *:not(.booking-container):not(.bottom-nav):not(script):not(style)');
            let specialistElement = null;
            
            // Search for elements containing specialist info based on language
            const searchText = '{{ app()->getLocale() == "ar" ? "مختص تجريبي" : "Demo Specialist" }}';
            footerElements.forEach(el => {
                if (el.textContent.includes(searchText) && 
                    (getComputedStyle(el).position === 'fixed' || 
                     getComputedStyle(el).position === 'absolute' || 
                     el.style.position === 'fixed' || 
                     el.style.position === 'absolute')) {
                    specialistElement = el;
                }
            });
            
            if (specialistElement) {
                // Hide original element
                specialistElement.style.display = 'none';
                
                // Get specialist image from services page
                const specialistImg = document.querySelector('.specialist-image');
                const imgSrc = specialistImg ? specialistImg.src : '{{ asset("images/default-specialist.jpg") }}';
                
                // Create new element for better display of specialist info
                const specialistCard = document.createElement('div');
                specialistCard.className = 'specialist-footer-card';
                specialistCard.innerHTML = `
                    <img src="${imgSrc}" class="specialist-footer-image" alt="{{ app()->getLocale() == 'ar' ? 'صورة المتخصص' : 'Specialist Image' }}">
                    <div>
                        <div class="specialist-footer-name">{{ $specialist->name ?: (app()->getLocale() == 'ar' ? 'مختص تجريبي' : 'Demo Specialist') }}</div>
                        <div class="specialist-footer-specialty">{{ $specialist->specialization ?: (app()->getLocale() == 'ar' ? 'استشارات نفسية' : 'Psychological Consultations') }}</div>
                    </div>
                `;
                
                // Add new element to the page
                document.body.appendChild(specialistCard);
            }
        }, 500);
    });
</script>
@endsection
