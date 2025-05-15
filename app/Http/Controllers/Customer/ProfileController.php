<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('customer.profile.edit', compact('user'));
    }

     public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        'phone' => 'nullable|string|max:20',
    ]
    );

    $user = auth()->user();
    $user->update($request->only('name', 'email', 'phone'));

    return back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }
    public function destroy(Request $request)
        {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);
            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('message', 'Tài khoản đã được xoá.');
        }
}

