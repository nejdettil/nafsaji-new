@extends('layouts.app')

@section('title', 'اختيار المتخصص')

@section('styles')
<style>
    .booking-container {
        background-color: #f8f9fa;
        padding: 30px 0;
        min-height: 70vh;
    }
    
    /* تصميم أدوات التصفية والبحث */
    .specialists-filters {
        margin-bottom: 30px;
    }
    
    .specialists-view-toggle .btn {
        padding: 8px 16px;
    }
    
    /* حاويات العرض */
    .specialists-container {
        display: grid;
        gap: 20px;
        transition: all 0.3s ease;
    }
    
    .specialists-container.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    }
    
    .specialists-container.list-view {
        grid-template-columns: 1fr;
    }
    
    .list-view .specialist-card {
        display: flex;
        flex-direction: row;
        max-width: 100%;
        align-items: center;
    }
    
    .list-view .card-header {
        width: 120px;
        flex-shrink: 0;
        padding: 0;
        border: none;
        background: none;
    }
    
    .list-view .card-body {
        flex-grow: 1;
    }
    
    .list-view .card-footer {
        flex-shrink: 0;
        margin-right: 15px;
        background: none;
        border: none;
        padding: 0;
    }
    
    /* تصميم البطاقات */
    .specialist-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.06);
        border: none;
        height: 100%;
        background-color: #ffffff;
    }
    
    .specialist-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .specialist-card .card-header {
        padding: 0;
        position: relative;
        border-bottom: none;
        background-color: transparent;
        text-align: center;
    }
    
    .specialist-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .specialist-card .card-body {
        padding: 15px;
    }
    
    .specialist-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 3px;
        color: #333;
    }
    
    .specialist-specialization {
        color: #0d6efd;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    
    .specialist-card .card-text {
        color: #6c757d;
        margin: 10px 0;
        font-size: 0.85rem;
        line-height: 1.5;
        max-height: 50px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .specialist-card .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 12px 15px;
        text-align: center;
    }
    
    .specialist-card .service-count {
        display: inline-block;
        background-color: #e9f4ff;
        color: #0d6efd;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-bottom: 15px;
    }
    
    .specialist-card .btn-select {
        background-color: #0d6efd;
        color: white;
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }
    
    .specialist-card .btn-select:hover {
        background-color: #0b5ed7;
        transform: scale(1.05);
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
    
    .no-specialists {
        text-align: center;
        padding: 50px 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .no-specialists i {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    .no-specialists h3 {
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .no-specialists p {
        color: #6c757d;
        max-width: 500px;
        margin: 0 auto 20px;
    }
    
    /* تصميم زر الرجوع */
    .back-button {
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 8px 16px;
        background-color: #f0f2f5;
        color: #4b5563;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .back-button:hover {
        background-color: #e5e7eb;
        color: #374151;
        text-decoration: none;
    }
    
    .back-button i {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="booking-container">
    <div class="container">
        <!-- Back button -->
        <a href="{{ route('booking.start') }}" class="back-button">
            <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i>
            {{ __('booking.back_button') }}
        </a>
    
        <!-- Page title and description -->
        <div class="booking-header">
            <h2>{{ __('booking.specialists.title') }}</h2>
            <p>{{ __('booking.specialists.subtitle') }}</p>
        </div>
        
        <!-- Booking Steps -->
        <div class="booking-steps">
            <div class="step active">
                <div class="step-number"><i class="fas fa-user-md"></i></div>
                <div class="step-title">{{ __('booking.steps.select_specialist') }}</div>
            </div>
            <div class="step">
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
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if(count($specialists) > 0)
        
        <!-- Search and Filter Tools -->
        <div class="specialists-filters">
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="specialist-search" class="form-control" placeholder="{{ __('booking.specialists.search_placeholder') }}" oninput="filterSpecialists()">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group specialists-view-toggle" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-view="grid"><i class="fas fa-th"></i></button>
                        <button type="button" class="btn btn-outline-primary" data-view="list"><i class="fas fa-list"></i></button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Specialists Display -->
        <div id="specialists-grid" class="specialists-container grid-view">
            @foreach($specialists as $specialist)
            <div class="specialist-item" data-name="{{ $specialist->name }}" data-specialization="{{ $specialist->specialization }}">
                <div class="card specialist-card">
                    <div class="card-header">
                        @if($specialist->profile_image)
                        <img src="{{ asset('storage/' . $specialist->profile_image) }}" class="specialist-image" alt="{{ $specialist->name }}">
                        @else
                        <img src="{{ asset('images/default-specialist.jpg') }}" class="specialist-image" alt="{{ $specialist->name }}">
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $specialist->name }}</h5>
                        <div class="specialist-specialization">{{ $specialist->specialization }}</div>
                        <div class="service-count">{{ count($specialist->services) }} {{ __('booking.specialists.available_services') }}</div>
                        <p class="card-text">
                            {{ Str::limit($specialist->bio, 100) }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('booking.services', $specialist->id) }}" class="btn btn-select">{{ __('booking.specialists.select_specialist') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="no-specialists">
            <i class="fas fa-user-md"></i>
            <h3>{{ __('booking.specialists.no_specialists') }}</h3>
            <p>{{ __('booking.specialists.no_specialists_message') }}</p>
            <a href="{{ route('booking.start') }}" class="btn btn-primary">{{ __('booking.specialists.go_back') }}</a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // تبديل عرض المتخصصين (شبكة/قائمة)
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.specialists-view-toggle .btn');
        const specialistsContainer = document.getElementById('specialists-grid');
        
        viewButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const viewMode = this.getAttribute('data-view');
                
                // إزالة الفئة النشطة من جميع الأزرار
                viewButtons.forEach(btn => btn.classList.remove('active'));
                
                // إضافة الفئة النشطة للزر المختار
                this.classList.add('active');
                
                // تحديث نمط العرض
                if (viewMode === 'grid') {
                    specialistsContainer.classList.remove('list-view');
                    specialistsContainer.classList.add('grid-view');
                } else {
                    specialistsContainer.classList.remove('grid-view');
                    specialistsContainer.classList.add('list-view');
                }
            });
        });
    });
    
    // وظيفة تصفية المتخصصين بناءً على البحث
    function filterSpecialists() {
        const searchInput = document.getElementById('specialist-search').value.toLowerCase();
        const specialists = document.querySelectorAll('.specialist-item');
        
        specialists.forEach(function(specialist) {
            const name = specialist.getAttribute('data-name').toLowerCase();
            const specialization = specialist.getAttribute('data-specialization').toLowerCase();
            
            // التحقق مما إذا كان اسم المتخصص أو تخصصه يحتوي على نص البحث
            if (name.includes(searchInput) || specialization.includes(searchInput)) {
                specialist.style.display = '';
            } else {
                specialist.style.display = 'none';
            }
        });
        
        // عرض رسالة إذا لم يتم العثور على نتائج
        const visibleCount = document.querySelectorAll('.specialist-item[style="display: none;"]').length;
        const noResultsMessage = document.getElementById('no-results-message');
        
        if (visibleCount === specialists.length && searchInput !== '') {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.id = 'no-results-message';
                message.className = 'alert alert-info text-center my-4';
                message.innerHTML = '<i class="fas fa-info-circle me-2"></i> لا توجد نتائج مطابقة لبحثك';
                
                const container = document.getElementById('specialists-grid');
                container.parentNode.insertBefore(message, container.nextSibling);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }
</script>
@endsection
