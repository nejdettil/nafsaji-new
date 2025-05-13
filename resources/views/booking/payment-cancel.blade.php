@extends('layouts.app')

@section('title', 'تم إلغاء الدفع')

@section('styles')
<style>
    .payment-container {
        background-color: #f8f9fa;
        padding: 50px 0;
        min-height: 70vh;
    }
    
    .cancel-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
    }
    
    .cancel-icon {
        width: 80px;
        height: 80px;
        background-color: #dc3545;
        color: white;
        font-size: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .cancel-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .cancel-message {
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
    
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .retry-box {
        background-color: #fff3cd;
        color: #856404;
        border-radius: 10px;
        padding: 20px;
        margin: 25px 0;
    }
    
    .retry-box h5 {
        font-weight: 600;
        margin-bottom: 15px;
        color: #856404;
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
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    
    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        transform: translateY(-3px);
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
        <div class="cancel-card">
            <!-- أيقونة الإلغاء -->
            <div class="cancel-icon">
                <i class="fas fa-times"></i>
            </div>
            
            <!-- رسالة الإلغاء -->
            <h2 class="cancel-title">تم إلغاء عملية الدفع</h2>
            <p class="cancel-message">لقد قمت بإلغاء عملية الدفع. لا يزال الحجز الخاص بك موجوداً ولكنه غير مؤكد حتى يتم الدفع بنجاح.</p>
            
            <!-- حالة الدفع -->
            <div class="payment-status status-cancelled">
                <i class="fas fa-times-circle"></i>
                تم إلغاء الدفع
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
            
            <!-- مربع إعادة المحاولة -->
            <div class="retry-box">
                <h5>هل ترغب في إعادة محاولة الدفع؟</h5>
                <p>يمكنك إعادة محاولة الدفع في أي وقت خلال الـ 24 ساعة القادمة. بعد ذلك، سيتم إلغاء الحجز تلقائياً.</p>
                <a href="{{ route('booking.payment.retry', $booking->transaction_id) }}" class="btn btn-warning">
                    <i class="fas fa-redo"></i>
                    إعادة محاولة الدفع
                </a>
            </div>
            
            <!-- أزرار الإجراءات -->
            <div class="action-buttons">
                <a href="{{ route('booking.start') }}" class="btn btn-outline-secondary btn-action">
                    <i class="fas fa-home"></i>
                    العودة للرئيسية
                </a>
                <a href="{{ route('booking.confirmation', $booking->transaction_id) }}" class="btn btn-primary btn-action">
                    <i class="fas fa-info-circle"></i>
                    تفاصيل الحجز
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
