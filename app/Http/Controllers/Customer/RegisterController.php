<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function authRegister()
    {
        return view('customer.pages.register'); // Hoặc tùy đường dẫn view của bạn
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 2, // ID của role khách hàng
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')->with('success', 'Đăng ký thành công!');
    }
}
