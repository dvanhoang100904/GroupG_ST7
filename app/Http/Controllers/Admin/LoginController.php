<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin.content.login.login');
    }

    public function authLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role_id !== 1) {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors('Bạn không có quyền truy cập trang admin.');
            }

            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công');
        }
        return redirect()->route('admin.login')->withErrors('Email hoặc Mật khẩu không chính xác');
    }
}
