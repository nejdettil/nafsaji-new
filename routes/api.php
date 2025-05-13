<?php

use App\Http\Controllers\Api\AvailabilityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// مسار خاص بالحجز لاسترجاع آخر معرف معاملة
Route::prefix('booking')->group(function() {
    Route::get('/api/latest-transaction-id', function () {
        // البحث عن آخر حجز تم إنشاؤه في الجلسة أو باستخدام ملفات تعريف الارتباط
        $lastTransactionId = session('last_booking_transaction_id', '');
        
        return response()->json([
            'transaction_id' => $lastTransactionId
        ]);
    });
});

// مسارات التقويم والمواعيد للحجز
Route::get('/availability', [AvailabilityController::class, 'getAvailability']);
