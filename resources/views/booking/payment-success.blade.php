@extends('layouts.app')

@section('title', 'تم الدفع بنجاح')

@section('styles')
<style>
    .payment-container {
        background-color: #f8f9fa;
        padding: 50px 0;
        min-height: 70vh;
    }
    
    .success-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
        background-color: #28a745;
        color: white;
        font-size: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .success-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .success-message {
        color: #6c757d;
        margin-bottom: 25px;
    }
    
    .booking-details {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        text-align: right;
    }
    
    .detail-group {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
    }
    
    .detail-group:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .detail-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .detail-title i {
        margin-left: 8px;
        color: #0d6efd;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    
    .detail-label {
        color: #6c757d;
    }
    
    .detail-value {
        font-weight: 500;
        color: #333;
    }
    
    .payment-status {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .status-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .next-steps {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 25px 0;
    }
    
    .next-steps h5 {
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .next-steps-list {
        text-align: right;
        padding-right: 20px;
    }
    
    .next-steps-list li {
        margin-bottom: 10px;
        color: #6c757d;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin-top: 25px;
    }
    
    .btn-action {
        flex: 1;
        min-width: 150px;
        max-width: 200px;
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
        transform: translateY(-3px);
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        transform: translateY(-3px);
    }
    
    .transaction-id {
        font-family: monospace;
        background-color: #f8f9fa;
        padding: 5px 10px;
        border-radius: 5px;
        margin: 10px 0;
        display: inline-block;
    }
    
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-action {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="payment-container">
    <div class="container">
        <div class="success-card">
            <!-- أيقونة النجاح -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <!-- رسالة النجاح -->
            <h2 class="success-title">تم الدفع والحجز بنجاح!</h2>
            <p class="success-message">شكراً لك! تم تأكيد حجزك ودفع الرسوم بنجاح. يمكنك الاطلاع على تفاصيل الحجز أدناه.</p>
            
            <!-- حالة الدفع -->
            <div class="payment-status status-success">
                <i class="fas fa-check-circle"></i>
                تم الدفع
            </div>
            
            <!-- معرف المعاملة -->
            <div>
                <small>معرف المعاملة:</small>
                <div class="transaction-id">{{ $booking->transaction_id }}</div>
            </div>
            
            <!-- تفاصيل الحجز -->
            <div class="booking-details">
                <div class="detail-group">
                    <div class="detail-title">
                        <i class="fas fa-user-md"></i>
                        تفاصيل المتخصص
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الاسم:</div>
                        <div class="detail-value">{{ $booking->specialist->name }}</div>
                    </div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-title">
                        <i class="fas fa-clipboard-list"></i>
                        تفاصيل الخدمة
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الخدمة:</div>
                        <div class="detail-value">{{ $booking->service->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">المدة:</div>
                        <div class="detail-value">{{ $booking->duration }} دقيقة</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">السعر:</div>
                        <div class="detail-value">{{ $booking->price }} ريال</div>
                    </div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-title">
                        <i class="fas fa-calendar-alt"></i>
                        تفاصيل الموعد
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">التاريخ:</div>
                        <div class="detail-value">{{ $booking->booking_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الوقت:</div>
                        <div class="detail-value">{{ $booking->booking_time }}</div>
                    </div>
                </div>
            </div>
            
            <!-- الخطوات التالية -->
            <div class="next-steps">
                <h5>الخطوات التالية</h5>
                <ul class="next-steps-list">
                    <li>تم إرسال تفاصيل الحجز إلى بريدك الإلكتروني.</li>
                    <li>يرجى الالتزام بموعد الجلسة والحضور قبل 10 دقائق من بدء الموعد.</li>
                    <li>يمكنك إلغاء الحجز أو تعديله من خلال حسابك الشخصي قبل 24 ساعة من الموعد.</li>
                    <li>في حالة وجود أي استفسارات، يرجى التواصل مع خدمة العملاء.</li>
                </ul>
            </div>
            
            <!-- أزرار الإجراءات -->
            <div class="action-buttons">
                <a href="{{ route('booking.start') }}" class="btn btn-outline-secondary btn-action">
                    <i class="fas fa-home"></i>
                    العودة للرئيسية
                </a>
                @if(Auth::check())
                <a href="{{ route('profile.bookings') }}" class="btn btn-primary btn-action">
                    <i class="fas fa-calendar-check"></i>
                    عرض حجوزاتي
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
