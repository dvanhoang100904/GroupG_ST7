<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng.
     */
    public function index()
    {
        // Lấy user_id người dùng đã đăng nhập (nếu có), nếu không thì lấy session_id
        $user_id = auth()->check() ? auth()->id() : null;
        $session_id = session()->getId();

        // Tìm giỏ hàng của người dùng hoặc của guest theo session
        $cart = Cart::with('cartItems.product')
            ->where(function ($q) use ($user_id, $session_id) {
                if ($user_id) {
                    $q->where('user_id', $user_id);
                } else {
                    // Nếu không có người dùng, tìm giỏ hàng của khách theo session
                    $q->where('session_id', $session_id);
                }
            })->first();

        // Nếu không có giỏ hàng, tạo mới
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user_id,
                'session_id' => $session_id,
            ]);
        }

        // Lấy các item trong giỏ hàng và tính tổng giá
        $cartItems = $cart->cartItems;
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Trả về trang giỏ hàng với các sản phẩm và tổng giá
        return view('customer.pages.carts', compact('cartItems', 'totalPrice'));
    }
}
