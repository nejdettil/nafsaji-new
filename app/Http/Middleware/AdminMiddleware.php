<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        // التحقق من أن المستخدم لديه دور المشرف
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // إعادة توجيه المستخدم غير المصرح له
        return redirect()->route('home')->with('error', __('messages.admin_access_denied'));
    }
}
