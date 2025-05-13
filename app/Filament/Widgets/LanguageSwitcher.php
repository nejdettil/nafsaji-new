<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher extends Widget
{
    protected static string $view = 'filament.widgets.language-switcher';

    public function switchLanguage($locale)
    {
        Session::put('locale', $locale);
        App::setLocale($locale);
        return redirect()->back();
    }

    public function getCurrentLocale()
    {
        return App::getLocale();
    }
}
