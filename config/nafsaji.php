<?php

return [
    /*
    |--------------------------------------------------------------------------
    | إعدادات موقع نفسجي
    |--------------------------------------------------------------------------
    |
    | هذا الملف يحتوي على الإعدادات الأساسية لموقع نفسجي للصحة النفسية
    |
    */

    'site' => [
        'name' => [
            'ar' => 'نفسجي',
            'en' => 'Nafsaji',
        ],
        'description' => [
            'ar' => 'منصة للدعم النفسي والتواصل مع المختصين النفسيين',
            'en' => 'Platform for mental health support and connecting with psychological specialists',
        ],
        'logo' => 'images/icons/icon-192x192.png',
        'contact_email' => 'info@nafsaji.com',
        'social_media' => [
            'twitter' => 'https://twitter.com/nafsaji',
            'facebook' => 'https://facebook.com/nafsaji',
            'instagram' => 'https://instagram.com/nafsaji',
        ],
    ],

    // إعدادات تخزين الصور والملفات
    'storage' => [
        'specialists_avatars' => 'specialists/avatars',
        'service_images' => 'services/images',
        'cache_time' => 60, // وقت التخزين المؤقت بالدقائق
    ],

    // إعدادات المتخصصين
    'specialists' => [
        'default_image' => 'images/default-specialist.png',
        'ratings_enabled' => true,
        'verification_enabled' => true,
        'min_price' => 50,
        'max_price' => 1000,
    ],

    // إعدادات الحجز
    'booking' => [
        'available_hours' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
        'booking_lead_time' => 2, // ساعات قبل موعد الحجز
        'cancellation_policy_hours' => 24, // ساعات قبل موعد الحجز للإلغاء بدون رسوم
        'default_session_duration' => 50, // المدة الافتراضية للجلسة بالدقائق
    ],

    // إعدادات الأمان
    'security' => [
        'password_min_length' => 8,
        'password_requires_special_chars' => true,
        'account_lockout_attempts' => 5,
        'account_lockout_minutes' => 15,
    ],
];
