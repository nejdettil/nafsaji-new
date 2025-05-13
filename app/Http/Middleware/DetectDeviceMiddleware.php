<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;

class DetectDeviceMiddleware
{
    /**
     * اكتشاف نوع الجهاز (جوال، لوحي، سطح مكتب) وتخزينه في الجلسة
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحميل وحدة اكتشاف الأجهزة
        $agent = new Agent();
        
        // تحديد نوع الجهاز
        $isMobile = $agent->isMobile() && !$agent->isTablet();
        $isTablet = $agent->isTablet();
        $isDesktop = !$isMobile && !$isTablet;
        
        // تخزين معلومات الجهاز في الجلسة لاستخدامها لاحقًا
        Session::put('device', [
            'isMobile' => $isMobile,
            'isTablet' => $isTablet,
            'isDesktop' => $isDesktop,
            'type' => $isMobile ? 'mobile' : ($isTablet ? 'tablet' : 'desktop')
        ]);
        
        // التحقق إذا كان المستخدم يطلب عرض نسخة سطح المكتب على الجوال
        if ($request->has('desktop_view')) {
            Session::put('force_desktop_view', true);
        }
        
        // إعادة تحميل نوع الجهاز من الجلسة إذا تم فرض عرض سطح المكتب
        if (Session::has('force_desktop_view') && Session::get('force_desktop_view') === true) {
            Session::put('device.isMobile', false);
            Session::put('device.type', 'desktop');
        }
        
        return $next($request);
    }
}
