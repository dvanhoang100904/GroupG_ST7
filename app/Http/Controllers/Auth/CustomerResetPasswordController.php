<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class CustomerResetPasswordController extends Controller
{
    // Hiển thị form reset mật khẩu
    public function showResetForm(Request $request, $token = null)
{
    return view('auth.reset-password')->with([
        'token' => $token,
        'email' => $request->email,
        // Nếu bạn muốn dùng $request trực tiếp trong view, truyền luôn:
        'request' => $request, 
    ]);
}


    // Xử lý form reset mật khẩu
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

       return $status === Password::PASSWORD_RESET
    ? redirect()->route('customer.login')->with('status', __($status))
    : back()->withErrors(['email' => __($status)]);

    }
}
