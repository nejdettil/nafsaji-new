<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| مسارات الحجز
|--------------------------------------------------------------------------
|
| هنا يمكنك تسجيل مسارات الحجز والمدفوعات الخاصة بالتطبيق
|
*/

// مسارات عملية الحجز الأساسية
Route::prefix('booking')->name('booking.')->group(function () {
    // صفحة اختيار طريقة الحجز (الخطوة الصفر)
    Route::get('/', [BookingController::class, 'index'])->name('start');
    
    // مسار 1: اختيار المتخصص أولاً (المسار الأصلي)
    Route::get('/specialists', [BookingController::class, 'showSpecialists'])->name('specialists');
    
    // اختيار خدمات المتخصص (من مسار المتخصص أولاً)
    Route::get('/specialists/{specialist}/services', [BookingController::class, 'showServices'])->name('services');
    
    // اختيار المواعيد (من مسار المتخصص أولاً)
    Route::get('/specialists/{specialist}/services/{service}/timeslots', [BookingController::class, 'showTimeSlots'])->name('timeslots');
    
    // مسار 2: اختيار الخدمة أولاً (المسار الجديد)
    Route::get('/all-services', [BookingController::class, 'showAllServices'])->name('allservices');
    
    // اختيار المتخصص المناسب للخدمة (من مسار الخدمة أولاً)
    Route::get('/all-services/{service}/specialists', [BookingController::class, 'showServiceSpecialists'])->name('service.specialists');
    
    // اختيار المواعيد (من مسار الخدمة أولاً)
    Route::get('/all-services/{service}/specialists/{specialist}/timeslots', [BookingController::class, 'showTimeSlotsFromService'])->name('timeslots.from.service');
    
    // مسار للتعامل مع /booking/services مباشرة لتوجيه المستخدم
    Route::get('/services', function() {
        return redirect()->route('booking.specialists');
    });
    
    // واجهة برمجة التطبيق للمواعيد المتاحة
    Route::get('/api/time-slots', [BookingController::class, 'getTimeSlotsForDay']);
    
    // واجهة برمجة التطبيق للتحقق من توفر موعد محدد
    Route::get('/api/check-availability', [BookingController::class, 'checkTimeSlotAvailabilityApi']);
    
    // صفحة تأكيد الحجز
    Route::get('/confirm', [BookingController::class, 'showConfirmation'])->name('confirm');
    
    // معالجة نموذج الحجز
    Route::post('/process', [BookingController::class, 'processBooking'])->name('process');
    
    // استعراض معلومات الحجز بعد الإنشاء
    Route::get('/confirmation/{transaction_id}', [BookingController::class, 'showBookingConfirmation'])->name('confirmation');
    
    // الدفع مباشرة لحجز موجود
    Route::get('/pay-now/{transaction_id}', [BookingController::class, 'payNow'])->name('payment.now');
});

// مسارات الدفع
Route::prefix('booking/payment')->name('booking.payment.')->group(function () {
    // معالجة نجاح الدفع
    Route::get('/success/{transaction_id}', [BookingPaymentController::class, 'handleSuccess'])->name('success');
    
    // معالجة إلغاء الدفع
    Route::get('/cancel/{transaction_id}', [BookingPaymentController::class, 'handleCancel'])->name('cancel');
    
    // صفحة نجاح الدفع
    Route::get('/success-page/{transaction_id}', [BookingPaymentController::class, 'showSuccessPage'])->name('success.page');
    
    // صفحة إلغاء الدفع
    Route::get('/cancel-page/{transaction_id}', [BookingPaymentController::class, 'showCancelPage'])->name('cancel.page');
    
    // إعادة محاولة الدفع
    Route::get('/retry/{transaction_id}', [BookingPaymentController::class, 'retryPayment'])->name('retry');
});
