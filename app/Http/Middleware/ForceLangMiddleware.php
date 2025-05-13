<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\URL;

class ForceLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // إيقاف ذاكرة التخزين المؤقت للغة
        URL::forceScheme('https');
        
        $currentLang = 'ar'; // اللغة الافتراضية هي العربية
        
        // التحقق من وجود اللغة في الجلسة
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if (in_array($locale, ['ar', 'en'])) {
                $currentLang = $locale;
                Log::info('FORCE LANG: Using session locale = ' . $locale);
            }
        } 
        // التحقق من وجود اللغة في الكوكي
        elseif ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            if (in_array($locale, ['ar', 'en'])) {
                $currentLang = $locale;
                Session::put('locale', $locale); // حفظ في الجلسة أيضًا
                Log::info('FORCE LANG: Using cookie locale = ' . $locale);
            }
        }
        
        // فرض اللغة مباشرة
        App::setLocale($currentLang);
        
        // التأكد من حفظ اللغة في الجلسة
        Session::put('locale', $currentLang);
        
        // التأكد من تطبيق اللغة في التطبيق
        Log::info('FORCE LANG: Force applied language = ' . App::getLocale());
        
        // يمكن استخدام هذا التالي لتشخيص المشكلة
        // dd([
        //     'session_locale' => Session::get('locale'),
        //     'cookie_locale' => $request->cookie('locale'),
        //     'app_locale' => App::getLocale(),
        //     'requested_uri' => $request->getRequestUri()
        // ]);
        
        return $next($request);
    }
}
