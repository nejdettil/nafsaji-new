<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

    // إعدادات مخصصة للمشروع
    'roles' => [
        'user' => 'مستخدم',
        'specialist' => 'متخصص',
        'admin' => 'مسؤول',
    ],

    'permissions' => [
        'booking' => ['user', 'specialist', 'admin'],
        'consultation' => ['specialist', 'admin'],
        'management' => ['admin'],
    ],
];
