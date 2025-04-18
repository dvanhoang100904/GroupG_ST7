<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkLoginCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // nếu khách hàng chưa đăng nhập
        if (!$user) {
            return redirect()->route('customer.login');
        }

        // nếu không phải khách hàng
        if ($user->role_id !== 2) {
            return redirect()->route('customer.index');
        }

        return $next($request);
    }
}
