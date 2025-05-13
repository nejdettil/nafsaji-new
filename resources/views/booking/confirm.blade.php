@extends('layouts.app')

@section('title', 'تأكيد الحجز')

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
    
    .confirm-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }
    
    .booking-details {
        flex: 1;
        min-width: 300px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .booking-form {
        flex: 1;
        min-width: 300px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .detail-section {
        margin-bottom: 25px;
    }
    
    .detail-section:last-child {
        margin-bottom: 0;
    }
    
    .section-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-left: 8px;
        color: #0d6efd;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 10px;
    }
    
    .detail-row:last-child {
        margin-bottom: 0;
    }
    
    .detail-label {
        min-width: 120px;
        color: #6c757d;
    }
    
    .detail-value {
        color: #333;
        font-weight: 500;
    }
    
    .price-summary {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .price-row:last-child {
        margin-bottom: 0;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
    }
    
    .price-label {
        color: #6c757d;
    }
    
    .price-value {
        font-weight: 600;
        color: #333;
    }
    
    .total-price {
        font-weight: 700;
        color: #0d6efd;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .account-creation {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    
    .account-creation .form-check {
        margin-bottom: 10px;
    }
    
    .password-fields {
        padding-top: 10px;
        border-top: 1px dashed #dee2e6;
        margin-top: 10px;
    }
    
    .btn-submit {
        background-color: #0d6efd;
        color: white;
        border-radius: 25px;
        padding: 10px 30px;
        transition: all 0.3s ease;
        font-weight: 600;
        width: 100%;
    }
    
    .btn-submit:hover {
        background-color: #0b5ed7;
        transform: scale(1.02);
    }
    
    @media (max-width: 767.98px) {
        .confirm-container {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-container">
    <div class="container">
        <!-- عنوان ووصف الصفحة -->
        <div class="booking-header">
            <h2>تأكيد الحجز</h2>
            <p>تحقق من تفاصيل الحجز وأكمل معلوماتك الشخصية</p>
        </div>
        
        <!-- خطوات الحجز -->
        <div class="booking-steps">
            <div class="step completed">
                <div class="step-number"><i class="fas fa-user-md"></i></div>
                <div class="step-title">اختيار المتخصص</div>
            </div>
            <div class="step completed">
                <div class="step-number"><i class="fas fa-clipboard-list"></i></div>
                <div class="step-title">اختيار الخدمة</div>
            </div>
            <div class="step completed">
                <div class="step-number"><i class="fas fa-calendar-alt"></i></div>
                <div class="step-title">اختيار الموعد</div>
            </div>
            <div class="step active">
                <div class="step-number"><i class="fas fa-check"></i></div>
                <div class="step-title">تأكيد الحجز</div>
            </div>
            <div class="step">
                <div class="step-number"><i class="fas fa-credit-card"></i></div>
                <div class="step-title">إتمام الدفع</div>
            </div>
        </div>
        
        <!-- رابط العودة -->
        <a href="{{ route('booking.timeslots', ['specialist' => $specialist->id, 'service' => $service->id]) }}" class="back-link">
            <i class="fas fa-chevron-right"></i>
            العودة إلى اختيار الموعد
        </a>
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="confirm-container">
            <!-- تفاصيل الحجز -->
            <div class="booking-details">
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-user-md"></i>
                        تفاصيل المتخصص
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الاسم:</div>
                        <div class="detail-value">{{ $specialist->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">التخصص:</div>
                        <div class="detail-value">{{ $specialist->specialization }}</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-clipboard-list"></i>
                        تفاصيل الخدمة
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الخدمة:</div>
                        <div class="detail-value">{{ $service->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">المدة:</div>
                        <div class="detail-value">{{ $service->duration ?? 60 }} دقيقة</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-calendar-alt"></i>
                        تفاصيل الموعد
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">التاريخ:</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">الوقت:</div>
                        <div class="detail-value">{{ $time }}</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <div class="section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        ملخص السعر
                    </div>
                    <div class="price-summary">
                        <div class="price-row">
                            <div class="price-label">سعر الخدمة</div>
                            <div class="price-value">{{ $service->price }} ريال</div>
                        </div>
                        <div class="price-row">
                            <div class="price-label">الإجمالي</div>
                            <div class="price-value total-price">{{ $service->price }} ريال</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- نموذج تأكيد الحجز -->
            <div class="booking-form">
                <form id="bookingForm" action="{{ route('booking.process') }}" method="POST">
                    @csrf
                    
                    <!-- المعلومات المخفية -->
                    <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <input type="hidden" name="booking_date" value="{{ $date }}">
                    <input type="hidden" name="booking_time" value="{{ $time }}">
                    
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        المعلومات الشخصية
                    </div>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name ?? old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? old('phone') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes" class="form-label">ملاحظات إضافية (اختياري)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                    
                    @if(!Auth::check())
                    <div class="account-creation">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="create_account" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }}>
                            <label class="form-check-label" for="create_account">
                                إنشاء حساب لتسهيل الحجوزات المستقبلية
                            </label>
                        </div>
                        
                        <div class="password-fields" id="passwordFields" style="display: none;">
                            <div class="form-group">
                                <label for="password" class="form-label">كلمة المرور</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms_agree" name="terms_agree" required>
                            <label class="form-check-label" for="terms_agree">
                                أوافق على <a href="#" target="_blank">الشروط والأحكام</a> وسياسة الخصوصية
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-submit">تأكيد الحجز والمتابعة للدفع</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // التحقق من حالة إنشاء الحساب
        const createAccountCheckbox = document.getElementById('create_account');
        const passwordFields = document.getElementById('passwordFields');
        
        if (createAccountCheckbox && passwordFields) {
            // تحديث حقول كلمة المرور عند تغيير حالة الخانة
            createAccountCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordFields.style.display = 'block';
                    document.getElementById('password').required = true;
                } else {
                    passwordFields.style.display = 'none';
                    document.getElementById('password').required = false;
                }
            });
            
            // تحديث الحالة الأولية
            if (createAccountCheckbox.checked) {
                passwordFields.style.display = 'block';
                document.getElementById('password').required = true;
            }
        }
    });
</script>
@endsection
