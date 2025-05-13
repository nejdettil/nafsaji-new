<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switchLocale($locale)
    {
        // Validate locale
        if (in_array($locale, ['ar', 'en'])) {
            Session::put('locale', $locale);
        }
        
        return redirect()->back();
    }
}
