<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;

/*
|--------------------------------------------------------------------------
| Booking Routes
|--------------------------------------------------------------------------
|
| This file contains all routes related to the booking system
| including public booking flow and authenticated user dashboard
|
*/

// Public booking routes - do not require authentication
Route::prefix('booking')->name('booking.')->group(function () {
    // Index page showing recent bookings or booking status
    Route::get('/index', [BookingController::class, 'index'])->name('index');
    
    // Step 1: Choose a specialist
    Route::get('/', [BookingController::class, 'showSpecialists'])->name('specialists');
    
    // Route for creating a new booking (accessed from homepage)
    Route::get('/create', [BookingController::class, 'create'])->name('create');
    
    // Step 2: Choose a service from the selected specialist
    Route::get('/{specialist}/services', [BookingController::class, 'showServices'])->name('services');
    
    // Step 3: Choose date and time
    Route::get('/{specialist}/{service}/schedule', [BookingController::class, 'showSchedule'])->name('schedule');
    
    // Step 4: Fill in details and confirm booking
    Route::post('/confirm', [BookingController::class, 'showConfirmation'])->name('confirm');
    // إضافة مسار GET أيضاً لصفحة التأكيد لدعم الوصول المباشر إليها
    Route::get('/confirm', [BookingController::class, 'showConfirmationForm'])->name('confirm.form');
    
    // Process booking form submission
    Route::post('/process', [BookingController::class, 'processBooking'])->name('process');
    
    // Booking confirmation page (after successful booking)
    Route::get('/success/{transaction_id}', [BookingController::class, 'showSuccess'])->name('success');
    
    // Payment routes for bookings
    Route::get('/payment/{transaction_id}', [BookingPaymentController::class, 'showPayment'])->name('payment');
    Route::match(['get', 'post'], '/payment/{transaction_id}/process', [BookingPaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{transaction_id}/success', [BookingPaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/{transaction_id}/cancel', [BookingPaymentController::class, 'paymentCancel'])->name('payment.cancel');
    
    // مسار مباشر لخدمة Stripe - سنستخدمه للتجاوز المباشر في حالة فشل المسارات الأخرى
    Route::get('/stripe-checkout/{transaction_id}', [BookingPaymentController::class, 'stripeCheckout'])->name('stripe.checkout');
});

// Authenticated user booking management
Route::middleware(['auth'])->prefix('bookings')->name('bookings.')->group(function () {
    // User's booking dashboard
    Route::get('/', [BookingController::class, 'userDashboard'])->name('dashboard');
    
    // View single booking details
    Route::get('/{booking}', [BookingController::class, 'viewBooking'])->name('view');
    
    // Cancel a booking
    Route::post('/{booking}/cancel', [BookingController::class, 'cancelBooking'])->name('cancel');
    
    // Reschedule a booking (form)
    Route::get('/{booking}/reschedule', [BookingController::class, 'showReschedule'])->name('reschedule');
    
    // Process reschedule
    Route::post('/{booking}/reschedule', [BookingController::class, 'processReschedule'])->name('reschedule.process');
});
