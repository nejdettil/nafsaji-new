<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // عمل Singleton للإعدادات
        $this->app->singleton('settings', function ($app) {
            return new \App\Services\SettingsService();
        });
        
        // تسجيل helper function
        require_once app_path('Helpers/settings.php');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تحميل الإعدادات من قاعدة البيانات (إذا كان الجدول موجودًا)
        if (Schema::hasTable('settings')) {
            // تخزين مؤقت للإعدادات
            $settings = Cache::remember('app_settings', 60 * 24, function () {
                return \App\Models\Setting::all()->pluck('value', 'key')->toArray();
            });
            
            // إضافة الإعدادات إلى ملف التكوين
            foreach ($settings as $key => $value) {
                Config::set('settings.' . $key, $value);
            }
        }
    }
}
