<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', __('pages.auth.login_required'));
        }

        // التحقق من دور المستخدم إذا كان محددًا
        if ($role && Auth::user()->role !== $role) {
            return redirect()->route('home')
                ->with('error', __('pages.auth.unauthorized'));
        }

        return $next($request);
    }
}
