<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'Nafsaji'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),

    'timezone' => 'UTC',
    'locale' => env('DEFAULT_LANGUAGE', 'ar'),
    'fallback_locale' => 'en',
    'faker_locale' => 'ar_SA',
    'supported_languages' => ['ar', 'en'],

    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\LanguageServiceProvider::class,
        App\Providers\SettingsServiceProvider::class,
        // مزود خدمة Stripe
        App\Providers\StripeServiceProvider::class,
        // مزود خدمة الحجز الجديد
        App\Providers\BookingServiceProvider::class,
        // مزودي خدمة Filament
        Filament\FilamentServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\Filament\SpecialistPanelProvider::class,
        App\Providers\Filament\UserPanelProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
    ])->toArray(),

    // إعدادات مخصصة للمشروع
    'supported_languages' => ['ar', 'en'],
    'mental_health' => [
        'assessment_types' => ['depression', 'anxiety', 'stress'],
        'consultation_duration' => 60, // دقيقة
    ],
];
