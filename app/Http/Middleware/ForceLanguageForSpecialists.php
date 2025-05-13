<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ForceLanguageForSpecialists
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
        Log::info('ForceLanguageForSpecialists middleware is running');
        
        // تحقق إذا كان المسار هو صفحة المتخصصين
        if ($request->is('specialists') || $request->is('specialists/*')) {
            $locale = Session::get('locale', config('app.locale'));
            
            // سجل اللغة التي سنستخدمها
            Log::info('Setting locale for specialists page to: ' . $locale);
            
            // فرض اللغة المطلوبة
            App::setLocale($locale);
            
            // تأكد من أن الجلسة تحتوي على نفس اللغة
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }
}
