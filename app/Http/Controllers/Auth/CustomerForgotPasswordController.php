<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller; 

class CustomerForgotPasswordController extends Controller
{
    // Hiển thị form quên mật khẩu
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password'); // view của bạn
    }

    // Xử lý gửi email reset
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
}

