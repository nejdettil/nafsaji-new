<?php

if (!function_exists('setting')) {
    /**
     * الحصول على قيمة إعداد محدد أو ضبط قيمة إعداد
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed|\App\Services\SettingsService
     */
    function setting($key = null, $default = null)
    {
        $instance = app('settings');
        
        if (is_null($key)) {
            return $instance;
        }
        
        if (is_array($key)) {
            $instance->set($key);
            
            return $instance;
        }
        
        return $instance->get($key, $default);
    }
}
