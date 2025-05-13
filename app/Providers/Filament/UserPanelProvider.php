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

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('filament-user')
            ->login()
            ->colors([
                'primary' => '#9333ea', // اللون البنفسجي الرئيسي من هوية الموقع
                'secondary' => '#6366f1', // اللون الثانوي من هوية الموقع
                'gray' => Color::Slate,
                'info' => '#0ea5e9',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#f43f5e',
            ])
            ->favicon('images/favicon.ico')
            ->brandName('نفسجي - لوحة تحكم المستخدم')
            ->brandLogo(fn () => view('filament.logo.user'))
            ->brandLogoHeight('2.5rem')
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/UserResources'), for: 'App\\Filament\\UserResources')
            ->discoverPages(in: app_path('Filament/UserPages'), for: 'App\\Filament\\UserPages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/UserWidgets'), for: 'App\\Filament\\UserWidgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
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
