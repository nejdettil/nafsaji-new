@extends('layouts.app')

@section('title', 'حجز موعد')

@section('styles')
<style>
    .booking-container {
        background-color: #f8f9fa;
        padding: 50px 0;
        min-height: 70vh;
    }
    
    .booking-header {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .booking-header h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .booking-header p {
        color: #6c757d;
        max-width: 700px;
        margin: 0 auto 30px;
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .booking-options {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin-top: 40px;
    }
    
    .booking-option {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 30px;
        width: 100%;
        max-width: 420px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .booking-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .booking-option:hover .option-icon {
        transform: scale(1.08);
    }
    
    .option-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 2.5rem;
        transition: all 0.4s ease;
    }
    
    .service-first .option-icon {
        background: linear-gradient(135deg, #20c997 0%, #0dcaf0 100%);
    }
    
    .booking-option h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .booking-option p {
        color: #6c757d;
        margin-bottom: 25px;
        line-height: 1.6;
    }
    
    .booking-option .btn {
        padding: 10px 25px;
        font-weight: 500;
        border-radius: 50px;
        transition: all 0.3s ease;
    }
    
    .booking-option .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
    
    .booking-option .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(32, 201, 151, 0.3);
    }
    
    /* للشاشات الصغيرة */
    @media (max-width: 767px) {
        .booking-options {
            flex-direction: column;
            align-items: center;
        }
        
        .booking-option {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-container">
    <div class="container">
        <!-- عنوان ووصف الصفحة -->
        <div class="booking-header">
            <h2>حجز موعد مع متخصص</h2>
            <p>اختر طريقة الحجز المناسبة لك، يمكنك البدء باختيار المتخصص أو اختيار الخدمة التي تبحث عنها. نحن هنا لمساعدتك في رحلتك نحو الصحة النفسية.</p>
        </div>
        
        <!-- خيارات الحجز -->
        <div class="booking-options">
            <!-- خيار اختيار المتخصص أولاً -->
            <div class="booking-option specialist-first">
                <div class="option-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>ابدأ باختيار المتخصص</h3>
                <p>ابحث عن المتخصص المناسب لك أولاً ثم تعرف على الخدمات التي يقدمها. مناسب إذا كان لديك متخصص مفضل أو توصية من شخص ما.</p>
                <a href="{{ route('booking.specialists') }}" class="btn btn-primary">اختيار المتخصص <i class="fas fa-arrow-left me-2"></i></a>
            </div>
            
            <!-- خيار اختيار الخدمة أولاً -->
            <div class="booking-option service-first">
                <div class="option-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>ابدأ باختيار الخدمة</h3>
                <p>حدد الخدمة التي تبحث عنها أولاً ثم اختر المتخصص المناسب. مناسب إذا كنت تعرف نوع الخدمة التي تحتاجها بالتحديد.</p>
                <a href="{{ route('booking.allservices') }}" class="btn btn-success">اختيار الخدمة <i class="fas fa-arrow-left me-2"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection
