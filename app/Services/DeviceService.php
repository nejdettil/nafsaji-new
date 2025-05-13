<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class DeviceService
{
    /**
     * التحقق مما إذا كان الجهاز هاتف محمول
     * 
     * @return boolean
     */
    public static function isMobile(): bool
    {
        // التحقق أولاً من الجلسة (تم تعيينها بواسطة middleware)
        if (Session::has('device.isMobile')) {
            return Session::get('device.isMobile');
        }
        
        // التحقق مباشرة إذا لم يتم العثور على البيانات في الجلسة
        $agent = new Agent();
        return $agent->isMobile() && !$agent->isTablet();
    }
    
    /**
     * التحقق مما إذا كان الجهاز لوحي
     * 
     * @return boolean
     */
    public static function isTablet(): bool
    {
        // التحقق أولاً من الجلسة
        if (Session::has('device.isTablet')) {
            return Session::get('device.isTablet');
        }
        
        // التحقق مباشرة
        $agent = new Agent();
        return $agent->isTablet();
    }
    
    /**
     * التحقق مما إذا كان الجهاز سطح مكتب
     * 
     * @return boolean
     */
    public static function isDesktop(): bool
    {
        // التحقق أولاً من الجلسة
        if (Session::has('device.isDesktop')) {
            return Session::get('device.isDesktop');
        }
        
        // التحقق مباشرة
        $agent = new Agent();
        return !$agent->isMobile() && !$agent->isTablet();
    }
    
    /**
     * الحصول على نوع الجهاز (mobile, tablet, desktop)
     * 
     * @return string
     */
    public static function getDeviceType(): string
    {
        // التحقق أولاً من الجلسة
        if (Session::has('device.type')) {
            return Session::get('device.type');
        }
        
        // التحقق مباشرة
        if (self::isMobile()) {
            return 'mobile';
        } elseif (self::isTablet()) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }
    
    /**
     * التحقق مما إذا كان المستخدم طلب عرض إصدار سطح المكتب على الجوال
     * 
     * @return boolean
     */
    public static function isForceDesktopView(): bool
    {
        return Session::has('force_desktop_view') && Session::get('force_desktop_view') === true;
    }
    
    /**
     * التحقق مما إذا كان يجب عرض واجهة الجوال
     * 
     * @return boolean
     */
    public static function shouldShowMobileView(): bool
    {
        return self::isMobile() && !self::isForceDesktopView();
    }
}
