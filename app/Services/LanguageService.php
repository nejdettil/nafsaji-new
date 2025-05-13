<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LanguageService
{
    /**
     * الحصول على اللغة الحالية
     *
     * @return string
     */
    public static function getCurrentLanguage(): string
    {
        return App::getLocale();
    }

    /**
     * تعيين اللغة الحالية
     *
     * @param string $lang
     * @return bool
     */
    public static function setLanguage(string $lang): bool
    {
        // التحقق من أن اللغة مدعومة
        if (!in_array($lang, config('app.supported_languages', ['ar', 'en']))) {
            Log::warning('LANGUAGE SERVICE: Attempted to set unsupported language: ' . $lang);
            return false;
        }

        try {
            // تحديث اللغة في التطبيق
            App::setLocale($lang);
            
            // تحديث اللغة في الجلسة
            Session::put('locale', $lang);
            
            // تحديث اللغة في الكوكيز (لمدة سنة)
            Cookie::queue('locale', $lang, 60*24*365);
            
            // تخزين اللغة في ذاكرة التخزين المؤقت
            Cache::put('app_locale', $lang, 60*24);
            
            Log::info('LANGUAGE SERVICE: Successfully set language to: ' . $lang);
            return true;
        } catch (\Exception $e) {
            Log::error('LANGUAGE SERVICE: Error setting language: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * الحصول على جميع بيانات حالة اللغة
     *
     * @return array
     */
    public static function getLanguageStatus(): array
    {
        return [
            'app_locale' => App::getLocale(),
            'session_locale' => Session::get('locale', 'not set'),
            'cookie_locale' => request()->cookie('locale') ?? 'not set',
            'cache_locale' => Cache::get('app_locale', 'not set'),
            'config_locale' => config('app.locale'),
            'default_locale' => config('app.fallback_locale'),
            'supported_languages' => config('app.supported_languages', ['ar', 'en']),
            'html_lang' => str_replace('_', '-', App::getLocale()),
            'html_dir' => App::getLocale() == 'ar' ? 'rtl' : 'ltr'
        ];
    }

    /**
     * التحقق من أن اللغة مدعومة
     *
     * @param string $lang
     * @return bool
     */
    public static function isLanguageSupported(string $lang): bool
    {
        return in_array($lang, config('app.supported_languages', ['ar', 'en']));
    }
}
