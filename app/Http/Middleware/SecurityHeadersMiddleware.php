<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * رؤوس HTTP الأمنية التي سيتم إضافتها لكل استجابة
     *
     * @var array
     */
    protected $securityHeaders = [
        'X-XSS-Protection' => '1; mode=block',
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=*, microphone=*, geolocation=*, interest-cohort=()',
        'X-Permitted-Cross-Domain-Policies' => 'none',
    ];

    /**
     * رؤوس HTTP التي سيتم إزالتها من الاستجابة
     *
     * @var array
     */
    protected $removeHeaders = [
        'Server',
        'X-Powered-By',
    ];

    /**
     * معالجة الطلب الوارد وإضافة رؤوس الأمان
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // رؤوس أمنية قياسية
        foreach ($this->securityHeaders as $header => $value) {
            $response->headers->set($header, $value);
        }
        
        // إزالة رؤوس غير ضرورية قد تكشف معلومات عن الخادم
        foreach ($this->removeHeaders as $header) {
            $response->headers->remove($header);
        }
        
        // إضافة سياسة أمان المحتوى بناءً على بيئة التشغيل
        if (app()->environment('production')) {
            // سياسة أمان المحتوى مشددة في الإنتاج
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com https://cdnjs.cloudflare.com; " .
                  "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
                  "img-src 'self' data: https:; " .
                  "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                  "connect-src 'self' https://*.googleapis.com; " .
                  "frame-src 'self';";
        } else {
            // سياسة أقل تشددًا للتطوير
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:; " .
                  "style-src 'self' 'unsafe-inline' https: http:; " .
                  "img-src 'self' data: https: http:; " .
                  "font-src 'self' data: https: http:; " .
                  "connect-src 'self' https: http:; " .
                  "frame-src 'self' https: http:;";
        }
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        return $response;
    }
}
