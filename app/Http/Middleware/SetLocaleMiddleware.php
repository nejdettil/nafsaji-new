<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if locale is in session, otherwise default to 'ar'
        $locale = Session::get('locale', 'ar');
        
        // Validate locale
        $locale = in_array($locale, ['ar', 'en']) ? $locale : 'ar';
        
        // Set application locale
        App::setLocale($locale);
        
        return $next($request);
    }
}
