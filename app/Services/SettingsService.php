<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * قراءة قيمة الإعداد
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }
        
        return config('settings.' . $key, $default);
    }
    
    /**
     * قراءة قيم متعددة
     *
     * @param array $keys
     * @return array
     */
    public function getMany($keys)
    {
        $values = [];
        
        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                $values[$default] = $this->get($default);
            } else {
                $values[$key] = $this->get($key, $default);
            }
        }
        
        return $values;
    }
    
    /**
     * حفظ قيمة الإعداد
     *
     * @param string|array $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];
        
        foreach ($keys as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
            
            // تحديث التكوين وقت التشغيل
            config(['settings.' . $key => $value]);
        }
    }
    
    /**
     * حفظ الإعدادات وتحديث ذاكرة التخزين المؤقت
     *
     * @return void
     */
    public function save()
    {
        // مسح التخزين المؤقت
        Cache::forget('app_settings');
        
        // إعادة تخزين الإعدادات
        Cache::remember('app_settings', 60 * 24, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * حذف الإعداد
     *
     * @param string $key
     * @return void
     */
    public function forget($key)
    {
        Setting::where('key', $key)->delete();
        
        // مسح التخزين المؤقت
        Cache::forget('app_settings');
    }
}
