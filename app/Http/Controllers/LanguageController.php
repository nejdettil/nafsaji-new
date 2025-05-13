<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        // تسجيل للتتبع
        Log::info('LANGUAGE SWITCH CONTROLLER: Attempt to change language to: ' . $lang);
        
        // تحقق من أن اللغة المطلوبة مدعومة
        if (in_array($lang, ['ar', 'en'])) {
            // تعيين اللغة في التطبيق فوراً
            App::setLocale($lang);
            
            // تعيين اللغة في الجلسة
            Session::put('locale', $lang);
            
            // تعيين كوكي اللغة للمتصفح
            Cookie::queue('locale', $lang, 60*24*365); // كوكي لمدة سنة
            
            // تأكيد تغيير اللغة
            Cache::put('app_locale', $lang, 60*24); // تخزين اللغة في ذاكرة التخزين المؤقتة
            
            // تسجيل نجاح التغيير
            Log::info('LANGUAGE SWITCH CONTROLLER: Successfully changed to: ' . $lang);
            Log::info('LANGUAGE SWITCH CONTROLLER: Session locale is now: ' . Session::get('locale'));
            Log::info('LANGUAGE SWITCH CONTROLLER: App locale is now: ' . App::getLocale());
            
            // إعادة توجيه إلى الصفحة الرئيسية لضمان تطبيق التغييرات
            $previousPage = url()->previous();
            
            // التحقق من الرابط السابق
            if (strpos($previousPage, 'debug-language') !== false) {
                return redirect('/debug-language');
            }
            
            // إعادة توجيه إلى الصفحة السابقة
            return redirect()->back();
        } else {
            Log::warning('LANGUAGE SWITCH CONTROLLER: Invalid language code: ' . $lang);
            return redirect()->back()->with('error', "Invalid language code: {$lang}");
        }
    }
    
    /**
     * التحقق من حالة اللغة الحالية
     */
    public function checkLang()
    {
        $data = [
            'app_locale' => App::getLocale(),
            'session_locale' => Session::get('locale'),
            'cookie_locale' => request()->cookie('locale'),
            'cache_locale' => Cache::get('app_locale'),
            'config_locale' => config('app.locale'),
            'default_locale' => config('app.fallback_locale'),
            'supported_languages' => config('app.supported_languages', ['ar', 'en'])
        ];
        
        return response()->json($data);
    }
}
