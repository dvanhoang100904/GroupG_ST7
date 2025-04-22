<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function login()
    {
        // Trả về view trang đăng nhập cho khách hàng
        return view('customer.pages.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function authLogin(LoginRequest $request)
    {
        // Lấy thông tin email và mật khẩu từ request
        $credentials = $request->only('email', 'password');

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role_id === 1) {
                // Kiểm tra nếu người dùng có role_id là 1 (admin), nếu đúng thì đăng xuất
                Auth::logout();
                return redirect()->route('customer.login')->withErrors('Bạn không có quyền truy cập trang khách hàng.');
            }

            // Đăng nhập thành công, chuyển hướng đến trang chủ của khách hàng
            return redirect()->route('home')->with('success', 'Đăng nhập thành công');
        }

        // Nếu thông tin đăng nhập sai, quay lại trang đăng nhập
        return redirect()->route('customer.login')->withErrors('Email hoặc Mật khẩu không chính xác');
    }
}
