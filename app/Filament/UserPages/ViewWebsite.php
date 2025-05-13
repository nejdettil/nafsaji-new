<?php

namespace App\Filament\UserPages;

use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Redirect;

class ViewWebsite extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'العودة إلى الموقع';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.view-website';

    public function mount(): void
    {
        // توجيه المستخدم مباشرة إلى الصفحة الرئيسية للموقع
        redirect()->to(route('home'))->send();
    }
}
