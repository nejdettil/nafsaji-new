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

class SpecialistPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('specialist')
            ->path('filament-specialist')
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
            ->brandName('نفسجي - لوحة تحكم المختص')
            ->brandLogo(fn () => view('filament.logo.specialist'))
            ->brandLogoHeight('2.5rem')
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/SpecialistResources'), for: 'App\\Filament\\SpecialistResources')
            ->discoverPages(in: app_path('Filament/SpecialistPages'), for: 'App\\Filament\\SpecialistPages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/SpecialistWidgets'), for: 'App\\Filament\\SpecialistWidgets')
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
