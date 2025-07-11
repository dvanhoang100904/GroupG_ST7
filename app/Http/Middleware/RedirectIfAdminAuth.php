<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Nếu admin đã đăng nhập
            if ($user->role_id === 1) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role_id === 2) {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
