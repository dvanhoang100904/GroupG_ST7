<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Hiển thị trang login cho quản trị viên
     */
    public function login()
    {
        // Trả về view đăng nhập của quản trị viên
        return view('admin.content.login.login');
    }

    /**
     * Xử lý đăng nhập của quản trị viên
     */
    public function authLogin(LoginRequest $request)
    {
        // Lấy email và mật khẩu từ yêu cầu
        $credentials = $request->only('email', 'password');

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {
            // Kiểm tra quyền truy cập (chỉ cho phép role_id = 1)
            if (Auth::user()->role_id !== 1) {
                // Nếu không có quyền admin, thoát đăng nhập
                Auth::logout();
                return redirect()->route('admin.login')->withErrors('Bạn không có quyền truy cập trang admin.');
            }

            // Nếu đăng nhập thành công và có quyền admin, chuyển hướng về dashboard
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công');
        }
        // Nếu thông tin đăng nhập sai, quay lại trang đăng nhập
        return redirect()->route('admin.login')->withErrors('Email hoặc Mật khẩu không chính xác');
    }
}
