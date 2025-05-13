@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تم إنشاء الحجز' : 'Booking Created')

@section('styles')
<style>
    .booking-container {
        background-color: #f8f9fa;
        padding: 50px 0;
        min-height: 70vh;
    }
    
    .confirmation-card {
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
    
    .confirmation-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .confirmation-message {
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
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin-top: 10px;
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
    
    .payment-notice {
        background-color: #d1ecf1;
        color: #0c5460;
        border-radius: 10px;
        padding: 15px;
        margin: 25px 0;
        text-align: center;
    }
    
    .payment-notice i {
        margin-left: 8px;
    }
    
    .transaction-id {
        font-family: monospace;
        background-color: #f8f9fa;
        padding: 5px 10px;
        border-radius: 5px;
        margin: 0 5px;
    }
    
    .alternative-payment {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px dashed #dee2e6;
    }
    
    .alternative-payment h5 {
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .alternative-payment p {
        color: #6c757d;
        margin-bottom: 15px;
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
<div class="booking-container">
    <div class="container">
        <div class="confirmation-card">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <!-- Confirmation Message -->
            <h2 class="confirmation-title">{{ app()->getLocale() == 'ar' ? 'تم إنشاء الحجز بنجاح!' : 'Booking Created Successfully!' }}</h2>
            <p class="confirmation-message">{{ app()->getLocale() == 'ar' ? 'لقد قمت بإنشاء حجز جديد بنجاح. يرجى مراجعة التفاصيل أدناه ومتابعة الدفع لتأكيد الحجز بشكل نهائي.' : 'You have successfully created a new booking. Please review the details below and proceed with payment to finalize your booking.' }}</p>
            
            <!-- Payment Status Notice -->
            <div class="payment-notice">
                <i class="fas fa-info-circle"></i>
                {{ app()->getLocale() == 'ar' ? 'حالة الدفع:' : 'Payment Status:' }} <strong>{{ app()->getLocale() == 'ar' ? 'في انتظار الدفع' : 'Pending Payment' }}</strong>
                 <br>
                 {{ app()->getLocale() == 'ar' ? 'معرف المعاملة:' : 'Transaction ID:' }} <span class="transaction-id">{{ $booking->transaction_id }}</span>
            </div>
            
            <!-- Booking Details -->
            <div class="booking-details">
                <!-- Specialist Details -->
                <div class="detail-group">
                    <h5 class="detail-title">
                        <i class="fas fa-user-md"></i>{{ app()->getLocale() == 'ar' ? 'تفاصيل الأخصائي' : 'Specialist Details' }}
                    </h5>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'الاسم:' : 'Name:' }}</span>
                        <span class="detail-value">{{ $booking->specialist->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'التخصص:' : 'Specialty:' }}</span>
                        <span class="detail-value">{{ $booking->specialist->specialization ?? (app()->getLocale() == 'ar' ? 'غير محدد' : 'N/A') }}</span>
                    </div>
                </div>
                
                <!-- Service Details -->
                <div class="detail-group">
                    <h5 class="detail-title">
                        <i class="fas fa-concierge-bell"></i>{{ app()->getLocale() == 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}
                    </h5>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'الاسم:' : 'Name:' }}</span>
                        <span class="detail-value">{{ $booking->service->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'المدة:' : 'Duration:' }}</span>
                        <span class="detail-value">{{ $booking->service->duration ?? 60 }} {{ app()->getLocale() == 'ar' ? 'دقيقة' : 'minutes' }}</span>
                    </div>
                </div>
                
                <!-- Appointment Details -->
                <div class="detail-group">
                    <h5 class="detail-title">
                        <i class="fas fa-calendar-alt"></i>{{ app()->getLocale() == 'ar' ? 'تفاصيل الموعد' : 'Appointment Details' }}
                    </h5>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'التاريخ:' : 'Date:' }}</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($booking->date)->translatedFormat(app()->getLocale() == 'ar' ? 'd F Y' : 'F d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'الوقت:' : 'Time:' }}</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($booking->time)->translatedFormat(app()->getLocale() == 'ar' ? 'h:i A' : 'h:i A') }}</span>
                    </div>
                </div>
                
                <!-- Payment Details -->
                <div class="detail-group">
                    <h5 class="detail-title">
                        <i class="fas fa-credit-card"></i>{{ app()->getLocale() == 'ar' ? 'تفاصيل الفاتورة' : 'Billing Details' }}
                    </h5>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'السعر:' : 'Price:' }}</span>
                        <span class="detail-value">{{ $booking->service->price }} {{ app()->getLocale() == 'ar' ? 'ريال' : 'SAR' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() == 'ar' ? 'معرف المعاملة:' : 'Transaction ID:' }}</span>
                        <span class="detail-value transaction-id">{{ $booking->transaction_id ?? (app()->getLocale() == 'ar' ? 'لم يتم بعد' : 'Not yet available') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('booking.payment.now', $booking->transaction_id) }}" class="btn btn-primary btn-action">
                    <i class="fas fa-credit-card"></i>
                    {{ app()->getLocale() == 'ar' ? 'متابعة للدفع' : 'Proceed to Payment' }}
                </a>
                <a href="{{ route('booking.start') }}" class="btn btn-outline-secondary btn-action">
                    <i class="fas fa-home"></i>
                    {{ app()->getLocale() == 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
            </div>
            
            <!-- Alternative Payment Options -->
            <div class="alternative-payment">
                <h5>{{ app()->getLocale() == 'ar' ? 'هل تواجه مشكلة في الدفع؟' : 'Having trouble with payment?' }}</h5>
                <p>{{ app()->getLocale() == 'ar' ? 'إذا كنت تفضل الدفع لاحقًا أو استخدام طريقة دفع أخرى، يمكنك دائمًا العودة إلى هذه الصفحة من خلال قسم حجوزاتي في حسابك.' : 'If you prefer to pay later or use another payment method, you can always return to this page through the My Bookings section in your account.' }}</p>
                <a href="{{ route('booking.payment.now', $booking->transaction_id) }}" id="directPaymentBtn" class="btn btn-success">
                    <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-alt-circle-left' : 'fa-arrow-alt-circle-right' }}"></i>
                    {{ app()->getLocale() == 'ar' ? 'الدفع المباشر الآن' : 'Pay Directly Now' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
