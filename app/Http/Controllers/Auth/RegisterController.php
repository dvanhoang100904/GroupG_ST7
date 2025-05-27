<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'user_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'phone' => 'nullable|string|max:20',
    ]);

    User::create([
        'user_name' => $request->user_name,
        'email' => $request->email,
        'password' => Hash::make($request->password), 
        'role_id' => 2, 
    ]);

    return redirect()->route('login')->with('success', 'Đăng ký thành công!');
}

}

