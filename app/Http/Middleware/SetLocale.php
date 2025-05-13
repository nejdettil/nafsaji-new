<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class SetLocale
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
        // تحديد اللغة من الجلسة التي تم تعيينها بالفعل
        $locale = Session::get('locale');
        Log::info('SetLocale middleware: Session locale = ' . ($locale ?? 'null'));
        
        // إذا لم تكن اللغة موجودة في الجلسة، نبحث في الكوكي
        if (!$locale && $request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            Log::info('SetLocale middleware: Cookie locale = ' . $locale);
            // تخزين قيمة الكوكي في الجلسة للاستخدام المستقبلي
            Session::put('locale', $locale);
        }
        
        // إذا لم نجد اللغة في الجلسة أو الكوكي، نستخدم اللغة الافتراضية
        if (!$locale) {
            $locale = config('app.locale', 'ar');
            Log::info('SetLocale middleware: Using default locale = ' . $locale);
        }
        
        // التأكد من أن اللغة ضمن اللغات المدعومة
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
            Log::info('SetLocale middleware: Reset invalid locale to ar');
        }
        
        // تعيين اللغة في التطبيق
        App::setLocale($locale);
        
        // تأكيد اللغة الحالية في السجل
        Log::info('SetLocale middleware: FINAL APP LOCALE SET TO = ' . App::getLocale());
        
        // تخزين اللغة في الجلسة للتأكد من استمرارها
        if (Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }
}
