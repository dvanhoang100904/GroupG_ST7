<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     *  Xử lý đăng xuất 
     */
    public function logout(Request $request)
    {
        // Đăng xuất người dùng hiện tại
        Auth::logout();

        // Xóa tất cả các phiên hiện tại (invalidate session)
        $request->session()->invalidate();

        // Tạo lại token CSRF để bảo vệ khỏi tấn công CSRF
        $request->session()->regenerateToken();

        // Chuyển hướng người dùng về trang đăng nhập
        return redirect()->route('customer.login')->with('success', 'Đăng xuất thành công');
    }
}
