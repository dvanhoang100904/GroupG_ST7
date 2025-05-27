<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

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
                    $q->where('session_id', $session_id)
                        ->whereNull('user_id');
                }
            })->first();

        // Nếu không có giỏ hàng, tạo mới
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user_id,
                'session_id' => $session_id,
            ]);
        }

        // Lọc bỏ cartItem không còn product
        $cartItems = $cart->cartItems->filter(function ($item) {
            return $item->product !== null;
        });

        // Lấy các item trong giỏ hàng và tính tổng giá
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        // Trả về trang giỏ hàng với các sản phẩm và tổng giá
        return view('customer.pages.carts', compact('cartItems', 'totalPrice'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function addToCart(Request $request)
    {
        // Lấy thông tin sản phẩm và số lượng từ request
        $product_id = $request->input('product_id');

        // Mặc định số lượng là 1
        $quantity = $request->input('quantity', 1);

        // Kiểm tra hợp lệ số lượng
        if ($quantity <= 0) {
            return back()->with('error', 'Số lượng không hợp lệ.');
        }

        // Kiểm tra sản phẩm có tồn tại và còn hàng không
        $product = Product::find($product_id);
        if (!$product) {
            return back()->with('error', 'Sản phẩm không tồn tại.');
        }

        // Kiểm tra xem người dùng đã đăng nhập chưa
        $user_id = auth()->check() ? auth()->id() : null;
        $session_id = session()->getId();

        //  Ưu tiên lấy cart theo session nếu chưa đăng nhập
        $cart = null;

        if ($user_id) {
            // Nếu đã đăng nhập, tìm giỏ hàng theo user_id
            $cart = Cart::where('user_id', $user_id)->first();

            // Nếu chưa có giỏ hàng user, nhưng có giỏ hàng guest, thì gán user_id cho giỏ hàng guest
            $guestCart = Cart::where('session_id', $session_id)
                ->whereNull('user_id')
                ->first();

            if (!$cart && $guestCart) {
                $guestCart->update(['user_id' => $user_id]);
                $cart = $guestCart;
            }

            // Nếu không có guestCart, tạo giỏ mới cho user
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $user_id,
                    'session_id' => $session_id,
                ]);
            }
        } else {
            // Chưa đăng nhập → tạo hoặc lấy cart guest theo session
            $cart = Cart::firstOrCreate([
                'user_id' => null,
                'session_id' => $session_id,
            ]);
        }

        // Thêm hoặc cập nhật sản phẩm trong giỏ
        $cartItem = $cart->cartItems()
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            // Nếu có, tăng số lượng lên
            $cartItem->increment('quantity', $quantity);
        } else {
            // Nếu chưa có, thêm mới vào giỏ
            $cart->cartItems()->create([
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        }

        // Redirect lại trang giỏ hàng
        return redirect()->route('cart.list')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng.
     */
    public function removeFromCart(Request $request)
    {
        // Lấy product_id sản phẩm cần xóa
        $product_id = $request->input('product_id');

        // Kiểm tra xem người dùng đã đăng nhập chưa
        $user_id = auth()->check() ? auth()->id() : null;
        $session_id = session()->getId();

        // Tìm giỏ hàng của người dùng hoặc của guest theo session
        $cart = Cart::where(function ($q) use ($user_id, $session_id) {
            if ($user_id) {
                $q->where('user_id', $user_id);
            } else {
                $q->where('session_id', $session_id);
            }
        })->first();

        if ($cart) {
            // Tìm item trong giỏ hàng và xóa
            $cartItem = $cart->cartItems()
                ->where('product_id', $product_id)
                ->first();

            if ($cartItem) {
                // Xóa sản phẩm khỏi giỏ hàng
                $cartItem->delete();

                // Tính lại tổng tiền của giỏ hàng
                $totalPrice = $cart->cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                // Redirect lại trang giỏ hàng và truyền tổng giá mới
                return redirect()->route('cart.list')->with('totalPrice', $totalPrice);
            }
        }

        return back()->withErrors('Sản phẩm không có trong giỏ hàng!');
    }
}
