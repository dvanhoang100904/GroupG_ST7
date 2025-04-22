<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Cart;
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
        // Lưu lại session_id trước khi login
        $session_id = $request->session()->getId();

        // Lấy thông tin email và mật khẩu từ request
        $credentials = $request->only('email', 'password');

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role_id === 1) {
                // Kiểm tra nếu người dùng có role_id là 1 (admin), nếu đúng thì đăng xuất
                Auth::logout();
                return redirect()->route('customer.login')->withErrors('Bạn không có quyền truy cập trang khách hàng.');
            }

            // Tìm giỏ hàng của session (guest)
            $guestCart = Cart::where('session_id', $session_id)
                ->whereNull('user_id')
                ->first();

            if ($guestCart) {
                // Tìm hoặc tạo giỏ hàng của user
                $userCart = Cart::firstOrCreate(['user_id' => $user->user_id]);

                foreach ($guestCart->cartItems as $item) {
                    $existingItem = $userCart->cartItems()
                        ->where('product_id', $item->product_id)
                        ->first();

                    if ($existingItem) {
                        $existingItem->increment('quantity', $item->quantity);
                    } else {
                        $userCart->cartItems()->create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ]);
                    }
                }

                // Xóa giỏ hàng guest
                $guestCart->cartItems()->delete();
                $guestCart->delete();
            }

            // Đăng nhập thành công, chuyển hướng đến trang chủ của khách hàng
            return redirect()->route('home')->with('success', 'Đăng nhập thành công');
        }

        // Nếu thông tin đăng nhập sai, quay lại trang đăng nhập
        return redirect()->route('customer.login')->withErrors('Email hoặc Mật khẩu không chính xác');
    }
}
