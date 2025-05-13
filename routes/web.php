<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LanguageController;

// طريق تغيير اللغة باستخدام وحدة التحكم الجديدة
Route::get('language/{lang}', [LanguageController::class, 'switchLang'])->name('language.switch');

// طريق للتحقق من حالة اللغة الحالية
Route::get('check-language', [LanguageController::class, 'checkLang'])->name('language.check');

// طريق لفرض اللغة الإنجليزية مباشرة
Route::get('force-english', function() {
    app()->setLocale('en');
    session()->put('locale', 'en');
    \Illuminate\Support\Facades\Cookie::queue('locale', 'en', 60*24*365);
    return redirect('/')->with('success', 'Forced English language: ' . app()->getLocale());
});

// طريق لصفحة تشخيص اللغة
Route::get('debug-language', function() {
    return view('debug-language');
});

// مسارات الصفحة الرئيسية - مع تعديلات لدعم واجهة تطبيق الجوال

// مسار الصفحة الرئيسية مع التحقق من نوع الجهاز
Route::get('/', function () {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    // التحقق من User Agent لاكتشاف نوع الجهاز
    $agent = new \Jenssegers\Agent\Agent();
    
    // إذا كان الجهاز جوال، قم بتوجيه المستخدم إلى صفحة تطبيق الجوال
    if($agent->isMobile() && !$agent->isTablet() && !request()->has('desktop_view')) {
        return view('pages.mobile-home');
    }
    
    // للأجهزة الأخرى (سطح المكتب، الأجهزة اللوحية) أو طلب المستخدم الصريح لعرض سطح المكتب
    return view('pages.responsive-home');
})->name('home');

// مسار خاص لعرض الجوال مباشرة (للاختبار والعرض)
Route::get('/mobile-app', function () {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    return view('pages.mobile-home');
})->name('mobile.app');

// مسار صفحة التواصل للجوال
Route::get('/mobile-contact', function() {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    return view('pages.mobile-contact');
})->name('mobile.contact');

// صفحات تسجيل الدخول وتسجيل حساب جديد للجوال
Route::get('/mobile-login', function() {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    return view('pages.mobile-login');
})->name('mobile.login');

Route::get('/mobile-register', function() {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    return view('pages.mobile-register');
})->name('mobile.register');

// مسار صفحة المتخصصين للجوال
Route::get('/mobile-specialists', function () {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    // هنا يمكن جلب بيانات المتخصصين من قاعدة البيانات
    // للاختبار فقط سنضيف بيانات وهمية
    $specialists = [
        [
            'id' => 1,
            'name' => app()->getLocale() == 'ar' ? 'د. أحمد السعدي' : 'Dr. Ahmed Al-Saadi',
            'specialty' => app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist',
            'rating' => 4,
            'reviews_count' => 28,
            'experience' => 12,
            'sessions_count' => 350,
            'price' => 350,
            'bio' => app()->getLocale() == 'ar' ? 'متخصص في علاج القلق والاكتئاب واضطرابات النوم. خبرة أكثر من 12 عاماً في المجال.' : 'Specialist in treating anxiety, depression, and sleep disorders. More than 12 years of experience in the field.',
            'expertise' => app()->getLocale() == 'ar' ? 'القلق, الاكتئاب, اضطرابات النوم' : 'Anxiety, Depression, Sleep Disorders'
        ],
        [
            'id' => 2,
            'name' => app()->getLocale() == 'ar' ? 'د. سارة العمري' : 'Dr. Sara Al-Omari',
            'specialty' => app()->getLocale() == 'ar' ? 'معالج نفسي' : 'Psychotherapist',
            'rating' => 5,
            'reviews_count' => 42,
            'experience' => 8,
            'sessions_count' => 220,
            'price' => 300,
            'bio' => app()->getLocale() == 'ar' ? 'متخصصة في العلاج المعرفي السلوكي والعلاقات الأسرية. خريجة جامعة هارفارد.' : 'Specialized in cognitive behavioral therapy and family relationships. Harvard University graduate.',
            'expertise' => app()->getLocale() == 'ar' ? 'العلاقات الأسرية, العلاج المعرفي, الصحة النفسية للمرأة' : 'Family Relationships, CBT, Women\'s Mental Health'
        ],
        [
            'id' => 3,
            'name' => app()->getLocale() == 'ar' ? 'د. خالد الراشد' : 'Dr. Khalid Al-Rashid',
            'specialty' => app()->getLocale() == 'ar' ? 'استشاري نفسي' : 'Psychological Consultant',
            'rating' => 4,
            'reviews_count' => 36,
            'experience' => 15,
            'sessions_count' => 450,
            'price' => 400,
            'bio' => app()->getLocale() == 'ar' ? 'خبير في علاج صدمات ما بعد الحرب والصدمات النفسية. عمل مع منظمات دولية لمدة 8 سنوات.' : 'Expert in treating PTSD and psychological trauma. Worked with international organizations for 8 years.',
            'expertise' => app()->getLocale() == 'ar' ? 'صدمات نفسية, اضطرابات ما بعد الصدمة, القلق الاجتماعي' : 'Trauma, PTSD, Social Anxiety'
        ]
    ];
    
    return view('pages.mobile-specialists', compact('specialists'));
})->name('mobile.specialists');

// مسار صفحة الخدمات للجوال
Route::get('/mobile-services', function () {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    return view('pages.mobile-services');
})->name('mobile.services');

// مسارات المتخصصين
Route::get('/specialists', function() {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    // إنشاء بيانات وهمية للعرض والاختبار
    $specialists = collect();
    
    // إضافة 9 متخصصين وهميين
    for ($i = 1; $i <= 9; $i++) {
        $name = app()->getLocale() == 'ar' ? 'د. أحمد محمد ' . $i : 'Dr. John Smith ' . $i;
        $specialists->push([
            'id' => $i,
            'name' => $name,
            'specialty' => app()->getLocale() == 'ar' ? 'طبيب نفسي' : 'Psychiatrist',
            'rating' => rand(3, 5),
            'reviews_count' => rand(10, 50),
            'experience' => rand(3, 15),
            'sessions_count' => rand(100, 500),
            'image' => 'https://randomuser.me/api/portraits/' . (rand(0, 1) ? 'men' : 'women') . '/' . rand(1, 99) . '.jpg',
            'price' => rand(100, 300),
            'availability' => rand(0, 1) ? 'متاح الآن' : 'متاح غدًا',
            'is_online' => rand(0, 1),
            'is_featured' => rand(0, 1),
            'is_verified' => rand(0, 1),
            'description' => app()->getLocale() == 'ar' ? 'طبيب متخصص في علاج مشاكل الصحة النفسية، لديه خبرة في علاج الاكتئاب والقلق واضطرابات النوم.' : 'Specialized doctor in treating mental health issues with experience in treating depression, anxiety, and sleep disorders.',
        ]);
    }
    
    // عمل صفحات للبيانات
    $perPage = 12;
    $page = request('page', 1);
    $pagedData = $specialists->slice(($page - 1) * $perPage, $perPage)->all();
    $specialists = new \Illuminate\Pagination\LengthAwarePaginator(
        $pagedData, count($specialists), $perPage, $page, ['path' => request()->url()]
    );
    
    // عرض صفحة المتخصصين مباشرة
    return view('specialists-new', ['specialists' => $specialists]);
})->name('specialists.index');

Route::get('/specialists/{specialist}', [SpecialistController::class, 'show'])->name('specialists.show');

// مسارات الخدمات
Route::get('/services', function() {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    // استدعاء نفس المتحكم لعرض صفحة الخدمات
    return app()->make(ServiceController::class)->index();
})->name('services.index');

Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// مسارات المصادقة
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticationController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login.submit');
    
    Route::get('/register', [AuthenticationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthenticationController::class, 'register'])->name('register.submit');
    
    Route::get('/forgot-password', [AuthenticationController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthenticationController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/reset-password/{token}', [AuthenticationController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])->name('password.update');
});

// مسارات تسجيل الخروج
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('auth');

// مسارات تسجيل الخروج من لوحة تحكم Filament - يعيد توجيه المستخدم للصفحة الرئيسية
Route::get('/filament-admin/logout', function() {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
});

// المسار المحدد الذي تتوقعه لوحة تحكم Filament بالاسم الصحيح
Route::any('/filament-admin/auth/logout', function() {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('filament.admin.auth.logout');

// للتأكد من التعامل مع جميع مسارات تسجيل الخروج المحتملة
Route::any('admin/logout', function() {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
});

// مسارات بوابة الدفع Stripe
Route::prefix('payment')->name('payment.')->group(function () {
    // مسارات عمليات الدفع العامة
    Route::get('/checkout', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('checkout');
    Route::get('/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/status/{payment}', [App\Http\Controllers\PaymentController::class, 'paymentStatus'])->name('status');
    
    // مسار webhook لاستقبال الإشعارات من Stripe
    Route::post('/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhook');
});

// تضمين مسارات نظام الحجز الجديد
require __DIR__.'/booking.php';

// ملاحظة: تم نقل مسارات الحجز إلى الملف routes/booking.php

// مسارات الحجز للجوال
Route::group(['prefix' => 'mobile'], function () {
    Route::get('/booking', function () {
        // فرض اللغة المختارة من الجلسة
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        
        return view('pages.mobile-booking');
    })->name('mobile.booking');
    
    Route::get('/booking/details', function () {
        // فرض اللغة المختارة من الجلسة
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        
        return view('pages.mobile-booking-details');
    })->name('mobile.booking.details');
    
    Route::get('/mobile/booking-payment', function () {
        // فرض اللغة المختارة من الجلسة
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        
        return view('pages.mobile-booking-payment');
    })->name('mobile.booking.payment');
    
    Route::get('/booking/confirmation', function () {
        // فرض اللغة المختارة من الجلسة
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        
        return view('pages.mobile-booking-confirmation');
    })->name('mobile.booking.confirmation');
    
    // مسارات الملف الشخصي للمستخدم (للربط في صفحة التأكيد)
    Route::get('/user/appointments', function () {
        // فرض اللغة المختارة من الجلسة
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        
        // هذه صفحة مستقبلية، نعيد توجيه المستخدم مؤقتًا للصفحة الرئيسية
        return redirect()->route('mobile.booking');
    })->name('mobile.user.appointments');
    
    Route::get('/', function () {
        // فرض اللغة المختارة من الجلسة
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
        
        // إعادة توجيه للصفحة الرئيسية المستقبلية للجوال
        return redirect()->route('mobile.booking');
    })->name('mobile.home');
});

// مسارات التواصل
Route::get('/contact', function() {
    // فرض اللغة المختارة من الجلسة
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
    
    // عرض صفحة التواصل مباشرة من القالب
    return view('pages.contact');
})->name('contact.create');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// مسارات لوحة تحكم الإدارة
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // إدارة الحجوزات
    Route::get('/bookings', [\App\Http\Controllers\Admin\AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [\App\Http\Controllers\Admin\AdminBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [\App\Http\Controllers\Admin\AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [\App\Http\Controllers\Admin\AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/confirm', [\App\Http\Controllers\Admin\AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/cancel', [\App\Http\Controllers\Admin\AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    
    // إدارة رسائل الاتصال
    Route::get('/contacts', [\App\Http\Controllers\Admin\AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [\App\Http\Controllers\Admin\AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/mark-as-read', [\App\Http\Controllers\Admin\AdminContactController::class, 'markAsRead'])->name('contacts.mark-as-read');
    Route::patch('/contacts/{contact}/mark-as-unread', [\App\Http\Controllers\Admin\AdminContactController::class, 'markAsUnread'])->name('contacts.mark-as-unread');
    Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\AdminContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/mark-all-as-read', [\App\Http\Controllers\Admin\AdminContactController::class, 'markAllAsRead'])->name('contacts.mark-all-as-read');
});

// مسارات لوحة تحكم المتخصصين
Route::prefix('specialist')->name('specialist.')->middleware(['auth', 'specialist'])->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'dashboard'])->name('dashboard');
    
    // إدارة الحجوزات
    Route::get('/bookings', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'showBooking'])->name('bookings.show');
    Route::patch('/bookings/{booking}/confirm', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/cancel', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'cancelBooking'])->name('bookings.cancel');
    
    // الملف الشخصي
    Route::get('/profile', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Specialist\SpecialistDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| مسارات الحجز
|--------------------------------------------------------------------------
|
| تضمين ملف مسارات الحجز الجديد
|
*/
require __DIR__.'/booking.php';
