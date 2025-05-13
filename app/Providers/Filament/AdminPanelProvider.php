<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('filament-admin')
            ->login()
            ->authGuard('web')
            ->brandName('نفسجي - الإدارة')
            ->favicon(asset('images/favicon.ico'))
            ->colors([
                'primary' => Color::Purple,
                'secondary' => Color::Indigo,
                'gray' => Color::Slate,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'info' => Color::Sky,
            ])
            ->darkMode()
            // تفعيل الاتجاه من اليمين لليسار للغة العربية
            ->renderHook(
                'panels::head.end',
                fn () => '<style>
                    /* اتجاه RTL للعناصر الرئيسية */
                    body { direction: rtl; font-family: "Cairo", system-ui, sans-serif; }
                    .fi-sidebar { direction: rtl; text-align: right; }
                    .fi-main { direction: rtl; text-align: right; }
                    
                    /* إصلاح أزرار الإجراءات */
                    .fi-ac-btn-action { display: flex; flex-direction: row-reverse; }
                    .fi-ac-btn-action svg { margin-left: 0.5rem; margin-right: 0; }
                    
                    /* إصلاح الجداول */
                    .fi-ta-header-cell, .fi-ta-cell { text-align: right; }
                    
                    /* تحسين النماذج */
                    .fi-fo-field-wrp { text-align: right; }
                    .fi-input-wrp { text-align: right; }
                    
                    /* إصلاح مسار التنقل */
                    .fi-breadcrumbs { flex-direction: row-reverse; }
                    .fi-breadcrumbs-separator { transform: rotate(180deg); }
                    
                    /* إصلاح القوائم المنسدلة */
                    .fi-dropdown-panel { text-align: right; }
                </style>
                <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">'
            )
            // تنظيم مجموعات التنقل
            ->navigationGroups([
                'نظرة عامة',
                'إدارة المستخدمين',
                'إدارة المختصين',
                'إدارة الخدمات',
                'إدارة الحجوزات',
                'إدارة المدفوعات',
                'الإعدادات',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
