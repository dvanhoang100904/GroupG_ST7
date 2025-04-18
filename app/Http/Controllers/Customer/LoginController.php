<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('customer.pages.login');
    }

    public function authLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role_id === 1) {
                Auth::logout();
                return redirect()->route('customer.login')->withErrors('Bạn không có quyền truy cập trang khách hàng.');
            }
            return redirect()->route('customer.index')->with('success', 'Đăng nhập thành công');
        }

        return redirect()->route('customer.login')->withErrors('Email hoặc Mật khẩu không chính xác');
    }
}
