@extends('layouts.app')

@section('title', 'اختيار الخدمة')

@section('styles')
<style>
    .booking-container {
        background-color: #f8f9fa;
        padding: 30px 0;
        min-height: 70vh;
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
    
    /* تصميم أدوات البحث والتصفية */
    .services-filters {
        margin-bottom: 30px;
    }
    
    /* تصميم الخدمات */
    .services-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .service-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 0;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        border-color: #e6f0ff;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #20c997 0%, #0dcaf0 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 1.5rem;
    }
    
    .service-name {
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
        font-size: 1.2rem;
    }
    
    .service-price {
        display: inline-block;
        background-color: #e9f4ff;
        color: #0d6efd;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .service-description {
        color: #6c757d;
        margin-bottom: 20px;
        flex-grow: 1;
    }
    
    .service-duration {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }
    
    .service-duration i {
        margin-left: 5px;
        color: #20c997;
    }
    
    .service-specialists {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 15px;
    }
    
    .service-specialists strong {
        color: #0d6efd;
    }
    
    .service-card .btn {
        width: 100%;
    }
    
    .service-card .btn-select {
        background-color: #20c997;
        color: white;
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
        align-self: flex-end;
    }
    
    .service-card .btn-select:hover {
        background-color: #1ba37e;
        transform: scale(1.02);
    }
    
    .no-services {
        text-align: center;
        padding: 50px 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        grid-column: 1/-1;
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
        <!-- زر الرجوع للصفحة السابقة -->
        <a href="{{ route('booking.start') }}" class="back-button">
            <i class="fas fa-arrow-right"></i>
            الرجوع للخلف
        </a>
    
        <!-- عنوان ووصف الصفحة -->
        <div class="booking-header">
            <h2>اختيار الخدمة</h2>
            <p>اختر الخدمة المناسبة لاحتياجاتك ثم اختر المتخصص المناسب لتقديم هذه الخدمة</p>
        </div>
        
        <!-- خطوات الحجز - تسلسل مختلف عن المسار الأصلي -->
        <div class="booking-steps">
            <div class="step active">
                <div class="step-number"><i class="fas fa-clipboard-list"></i></div>
                <div class="step-title">اختيار الخدمة</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-user-md"></i></div>
                <div class="step-title">اختيار المتخصص</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-calendar-alt"></i></div>
                <div class="step-title">اختيار الموعد</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-check"></i></div>
                <div class="step-title">تأكيد الحجز</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-credit-card"></i></div>
                <div class="step-title">إتمام الدفع</div>
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
        
        <!-- أدوات البحث والتصفية -->
        <div class="services-filters">
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="service-search" class="form-control" placeholder="ابحث عن خدمة..." oninput="filterServices()">
                    </div>
                </div>
            </div>
        </div>
        
        @if(count($services) > 0)
        <!-- عرض الخدمات -->
        <div id="services-grid" class="services-container">
            @foreach($services as $service)
            <div class="service-item" data-name="{{ $service->name }}" data-description="{{ $service->description }}">
                <div class="service-card">
                    <div class="service-icon">
                        @if($service->type == 'consultation')
                        <i class="fas fa-comments"></i>
                        @elseif($service->type == 'therapy')
                        <i class="fas fa-brain"></i>
                        @elseif($service->type == 'assessment')
                        <i class="fas fa-clipboard-check"></i>
                        @else
                        <i class="fas fa-stethoscope"></i>
                        @endif
                    </div>
                    <h3 class="service-name">{{ $service->name }}</h3>
                    <div class="service-price">{{ $service->price }} ريال</div>
                    <div class="service-description">{{ Str::limit($service->description, 150) }}</div>
                    <div class="service-duration">
                        <i class="far fa-clock"></i>
                        {{ $service->duration ?? 60 }} دقيقة
                    </div>
                    <div class="service-specialists">
                        <strong>{{ count($service->specialists) }}</strong> متخصص متاح لتقديم هذه الخدمة
                    </div>
                    <a href="{{ route('booking.service.specialists', $service->id) }}" class="btn btn-select">اختيار الخدمة</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="no-services">
            <i class="fas fa-clipboard-list"></i>
            <h3>لا توجد خدمات متاحة حالياً</h3>
            <p>نأسف لعدم توفر خدمات في الوقت الحالي. يرجى المحاولة مرة أخرى لاحقاً.</p>
            <a href="{{ route('booking.start') }}" class="btn btn-primary">العودة للخلف</a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // وظيفة تصفية الخدمات بناءً على البحث
    function filterServices() {
        const searchInput = document.getElementById('service-search').value.toLowerCase();
        const services = document.querySelectorAll('.service-item');
        
        services.forEach(function(service) {
            const name = service.getAttribute('data-name').toLowerCase();
            const description = service.getAttribute('data-description').toLowerCase();
            
            // التحقق مما إذا كان اسم الخدمة أو وصفها يحتوي على نص البحث
            if (name.includes(searchInput) || description.includes(searchInput)) {
                service.style.display = '';
            } else {
                service.style.display = 'none';
            }
        });
        
        // عرض رسالة إذا لم يتم العثور على نتائج
        const visibleCount = document.querySelectorAll('.service-item[style="display: none;"]').length;
        const noResultsMessage = document.getElementById('no-results-message');
        
        if (visibleCount === services.length && searchInput !== '') {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.id = 'no-results-message';
                message.className = 'alert alert-info text-center my-4';
                message.innerHTML = '<i class="fas fa-info-circle me-2"></i> لا توجد نتائج مطابقة لبحثك';
                
                const container = document.getElementById('services-grid');
                container.parentNode.insertBefore(message, container.nextSibling);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }
</script>
@endsection
