<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     * Lấy giỏ hàng của người dùng và tính tổng tiền đơn hàng
     */
    public function checkout()
    {
        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        // Lấy giỏ hàng của người dùng
        $cart = $user->cart;

        // Kiểm tra xem giỏ hàng có tồn tại và không trống không
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.list')->withErrors('Giỏ hàng của bạn đang trống hoặc không tồn tại.');
        }

        $cartItems = $cart->cartItems;

        // Tính tổng tiền cho tất cả sản phẩm trong giỏ hàng
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        // Trả về view thanh toán với thông tin giỏ hàng và tổng tiền
        return view('customer.pages.payment', compact('cartItems', 'totalPrice'));
    }

    /**
     * Xử lý khi khách hàng đặt hàng
     * Tạo đơn hàng mới, lưu thông tin địa chỉ giao hàng và thanh toán.
     */
    public function placeOrder(OrderRequest $request)
    {
        $user = Auth::user();
        $cart = $user->cart;

        // Kiểm tra xem giỏ hàng có tồn tại và không trống không
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.list')->withErrors('Không thể đặt hàng vì giỏ hàng của bạn đang trống.');
        }

        $cartItems = $cart->cartItems;

        // Tính toán tổng tiền đơn hàng
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });

        // Lưu thông tin địa chỉ giao hàng
        $shippingAddress = ShippingAddress::create([
            'user_id' => $user->user_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => $user->user_id,
            'total_price' => $totalPrice,
            'status' => 'chờ_xử_lý',
            'shipping_address_id' => $shippingAddress->shipping_address_id,
            'notes' => $request->notes,
        ]);

        // Thêm chi tiết cho từng sản phẩm trong đơn hàng
        foreach ($cartItems as $cartItem) {
            OrderDetail::create([
                'order_id' => $order->order_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
                'total_price' => $cartItem->product->price * $cartItem->quantity,  // Lưu tổng giá cho mỗi sản phẩm
            ]);
        }

        // Xóa giỏ hàng sau khi đơn hàng được tạo
        $cart->cartItems()->delete();
        // Xóa giỏ hàng của người dùng
        $cart->delete();

        // Tạo thông tin thanh toán với trạng thái 'đang_chờ'
        Payment::create([
            'order_id' => $order->order_id,
            'method' => $request->paymentMethod,
            'status' => 'đang_chờ',
        ]);

        // Chuyển hướng đến trang thành công với order_id đơn hàng
        return redirect()->route('order.success', $order->order_id);
    }

    /**
     * Trang hiển thị đơn hàng thành công sau khi đặt
     */
    public function orderSuccess($order_id)
    {
        $user = Auth::user();

        $order = Order::with('orderDetails.product', 'shippingAddress')
            ->where('order_id', $order_id)
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        return view('customer.pages.order-success', compact('order'));
    }
}
