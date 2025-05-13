<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ForceLanguageForServices
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
        // سجل للمتابعة
        Log::info('ForceLanguageForServices middleware is running');
        
        // تحقق إذا كان المسار هو صفحة الخدمات
        if ($request->is('services') || $request->is('services/*')) {
            $locale = Session::get('locale', config('app.locale'));
            
            // سجل اللغة التي سنستخدمها
            Log::info('Setting locale for services page to: ' . $locale);
            
            // فرض اللغة المطلوبة
            App::setLocale($locale);
            
            // تأكد من أن الجلسة تحتوي على نفس اللغة
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }
}
