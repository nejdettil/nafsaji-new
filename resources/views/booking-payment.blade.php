@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'الدفع' : 'Payment')

@section('styles')
<style>
    /* أنماط مخصصة لصفحة الدفع */
    .booking-progress-step {
        position: relative;
        z-index: 1;
    }
    
    .booking-progress-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: -1;
        transform: translateY(-50%);
    }
    
    .booking-progress-line-active {
        background-color: #8b5cf6;
    }
    
    .payment-method {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .payment-method:hover {
        border-color: #8b5cf6;
        background-color: #f5f3ff;
    }
    
    .payment-method.selected {
        border-color: #8b5cf6;
        background-color: #f5f3ff;
    }
    
    .payment-method.selected .check-icon {
        visibility: visible;
    }
    
    .check-icon {
        visibility: hidden;
    }
    
    .card-input {
        transition: all 0.2s ease;
    }
    
    .card-input:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Hero Section with Specialist Info -->
    <div class="bg-gradient-to-r from-purple-700 to-indigo-800 py-10 px-4 text-white">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="md:w-7/12">
                    <h1 class="text-3xl font-bold mb-2">{{ app()->getLocale() == 'ar' ? 'الدفع' : 'Payment' }}</h1>
                    <p class="text-purple-100 text-lg mb-4">{{ app()->getLocale() == 'ar' ? 'اكمل عملية الدفع لتأكيد حجزك' : 'Complete payment to confirm your booking' }}</p>
                    
                    <!-- Breadcrumbs -->
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <a href="/" class="inline-flex items-center text-sm font-medium text-purple-100 hover:text-white">
                                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    {{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="/specialists" class="ms-1 text-sm font-medium text-purple-100 hover:text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'المتخصصين' : 'Specialists' }}</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="/booking" class="ms-1 text-sm font-medium text-purple-100 hover:text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'حجز موعد' : 'Booking' }}</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-300 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-medium text-white md:ms-2">{{ app()->getLocale() == 'ar' ? 'الدفع' : 'Payment' }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <!-- Specialist Card - Summary -->
                <div class="md:w-5/12">
                    <div class="bg-white rounded-xl p-4 shadow-lg text-gray-800 flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                            {{ app()->getLocale() == 'ar' ? 'د' : 'D' }}
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold text-lg text-gray-900">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</h2>
                            <p class="text-sm text-gray-600">{{ app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist' }}</p>
                            <div class="flex items-center mt-1">
                                <div class="text-sm text-gray-600">
                                    {{ app()->getLocale() == 'ar' ? '12 مايو 2025 - 11:00 صباحًا' : 'May 12, 2025 - 11:00 AM' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Progress Indicator -->
    <div class="container mx-auto px-4 py-8">
        <div class="relative mb-12">
            <!-- Progress Line -->
            <div class="booking-progress-line"></div>
            <div class="booking-progress-line-active" style="width: 75%;"></div>
            
            <!-- Steps -->
            <div class="flex justify-between">
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'اختيار الموعد' : 'Select Date & Time' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'تفاصيل الجلسة' : 'Session Details' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="text-sm font-medium text-purple-600">{{ app()->getLocale() == 'ar' ? 'الدفع' : 'Payment' }}</div>
                </div>
                
                <div class="booking-progress-step text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="text-sm font-medium text-gray-500">{{ app()->getLocale() == 'ar' ? 'التأكيد' : 'Confirmation' }}</div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ app()->getLocale() == 'ar' ? 'معلومات الدفع' : 'Payment Details' }}</h2>
                    
                    <!-- Payment Methods -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ app()->getLocale() == 'ar' ? 'اختر طريقة الدفع' : 'Select Payment Method' }}</h3>
                        
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <!-- Credit Card Method -->
                            <div class="payment-method selected border-2 rounded-lg p-4 relative">
                                <div class="check-icon visible absolute top-3 right-3 w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div class="flex flex-col items-center justify-center py-2">
                                    <i class="far fa-credit-card text-3xl text-gray-600 mb-2"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ app()->getLocale() == 'ar' ? 'بطاقة ائتمان' : 'Credit Card' }}</span>
                                </div>
                            </div>
                            
                            <!-- Apple Pay Method -->
                            <div class="payment-method border-2 rounded-lg p-4 relative">
                                <div class="check-icon absolute top-3 right-3 w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div class="flex flex-col items-center justify-center py-2">
                                    <i class="fab fa-apple-pay text-3xl text-gray-600 mb-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Apple Pay</span>
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Method -->
                            <div class="payment-method border-2 rounded-lg p-4 relative">
                                <div class="check-icon absolute top-3 right-3 w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div class="flex flex-col items-center justify-center py-2">
                                    <i class="fas fa-university text-3xl text-gray-600 mb-2"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ app()->getLocale() == 'ar' ? 'تحويل بنكي' : 'Bank Transfer' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Credit Card Form -->
                        <div class="border-t border-gray-200 pt-6">
                            <form>
                                <!-- Cardholder Name -->
                                <div class="mb-4">
                                    <label for="card_name" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'اسم حامل البطاقة' : 'Cardholder Name' }} <span class="text-red-500">*</span></label>
                                    <input id="card_name" type="text" class="card-input w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none" placeholder="{{ app()->getLocale() == 'ar' ? 'الاسم كما يظهر على البطاقة' : 'Name as it appears on card' }}" />
                                </div>
                                
                                <!-- Card Number -->
                                <div class="mb-4">
                                    <label for="card_number" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'رقم البطاقة' : 'Card Number' }} <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input id="card_number" type="text" class="card-input w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none" placeholder="XXXX XXXX XXXX XXXX" />
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                            <i class="fab fa-cc-visa text-blue-700"></i>
                                            <i class="fab fa-cc-mastercard text-red-600"></i>
                                            <i class="fab fa-cc-amex text-blue-500"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Expiry Date and CVC -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label for="expiry_date" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'تاريخ الانتهاء' : 'Expiry Date' }} <span class="text-red-500">*</span></label>
                                        <input id="expiry_date" type="text" class="card-input w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none" placeholder="MM/YY" />
                                    </div>
                                    <div>
                                        <label for="cvc" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'رمز الأمان (CVC)' : 'CVC' }} <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input id="cvc" type="text" class="card-input w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none" placeholder="123" />
                                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                <i class="fas fa-question-circle text-gray-400" title="{{ app()->getLocale() == 'ar' ? '3 أرقام في ظهر البطاقة' : '3 digits on back of card' }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Save Card Information -->
                                <div class="mb-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600" checked>
                                        <span class="ml-2 text-gray-700">{{ app()->getLocale() == 'ar' ? 'حفظ معلومات البطاقة للدفعات المستقبلية' : 'Save card information for future payments' }}</span>
                                    </label>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-6">
                                    <p class="text-gray-500 text-sm mb-4">{{ app()->getLocale() == 'ar' ? 'بالضغط على "إتمام الدفع" أنت توافق على شروط وأحكام الخدمة.' : 'By clicking "Complete Payment" you agree to our terms and conditions.' }}</p>
                                    
                                    <button type="button" class="w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white font-semibold hover:opacity-90 transition-all">
                                        {{ app()->getLocale() == 'ar' ? 'إتمام الدفع' : 'Complete Payment' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 mb-8 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4">{{ app()->getLocale() == 'ar' ? 'ملخص الطلب' : 'Order Summary' }}</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'المتخصص' : 'Specialist' }}</span>
                            <span class="font-medium">{{ app()->getLocale() == 'ar' ? 'د. أحمد محمد' : 'Dr. John Smith' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</span>
                            <span class="font-medium">{{ app()->getLocale() == 'ar' ? '12 مايو 2025' : 'May 12, 2025' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'الوقت' : 'Time' }}</span>
                            <span class="font-medium">11:00 AM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'نوع الجلسة' : 'Session Type' }}</span>
                            <span class="font-medium">{{ app()->getLocale() == 'ar' ? 'جلسة عبر الفيديو' : 'Video Session' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'المدة' : 'Duration' }}</span>
                            <span class="font-medium">{{ app()->getLocale() == 'ar' ? '60 دقيقة' : '60 minutes' }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'سعر الجلسة' : 'Session Price' }}</span>
                            <span class="font-medium">{{ app()->getLocale() == 'ar' ? '250 ريال' : '250 SAR' }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">{{ app()->getLocale() == 'ar' ? 'الضريبة (15%)' : 'Tax (15%)' }}</span>
                            <span class="font-medium">{{ app()->getLocale() == 'ar' ? '37.5 ريال' : '37.5 SAR' }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-bold">{{ app()->getLocale() == 'ar' ? 'المجموع' : 'Total' }}</span>
                            <span class="text-purple-700 text-xl font-bold">{{ app()->getLocale() == 'ar' ? '287.5 ريال' : '287.5 SAR' }}</span>
                        </div>
                    </div>
                    
                    <!-- Promo Code -->
                    <div class="mb-6">
                        <label for="promo_code" class="block text-gray-700 font-medium mb-2">{{ app()->getLocale() == 'ar' ? 'هل لديك رمز ترويجي؟' : 'Have a promo code?' }}</label>
                        <div class="flex">
                            <input id="promo_code" type="text" class="card-input flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none" placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل الرمز' : 'Enter code' }}" />
                            <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-r-lg hover:bg-gray-300">
                                {{ app()->getLocale() == 'ar' ? 'تطبيق' : 'Apply' }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Secure Payment Note -->
                    <div class="text-center text-gray-500 text-sm">
                        <div class="flex justify-center items-center mb-2">
                            <i class="fas fa-lock mr-2 text-green-600"></i>
                            <span>{{ app()->getLocale() == 'ar' ? 'دفع آمن' : 'Secure Payment' }}</span>
                        </div>
                        <p>{{ app()->getLocale() == 'ar' ? 'معلومات بطاقتك مشفرة ومحمية' : 'Your card information is encrypted and secure' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('booking.details') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
            </a>
            <a href="{{ route('booking.confirmation') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg text-white font-medium hover:opacity-90">
                {{ app()->getLocale() == 'ar' ? 'إتمام الدفع' : 'Complete Payment' }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // سكريبت لتفعيل وظائف الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل اختيار طريقة الدفع
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                // إزالة الفئة النشطة من جميع طرق الدفع
                paymentMethods.forEach(m => m.classList.remove('selected'));
                // إضافة الفئة النشطة للطريقة المحددة
                this.classList.add('selected');
            });
        });
    });
</script>
@endsection
