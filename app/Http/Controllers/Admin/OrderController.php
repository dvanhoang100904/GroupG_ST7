<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Định nghĩa số lượng đơn hàng hiển thị mỗi trang
    const PER_PAGES = 10;

    /**
     * Liệt kê danh sách đơn hàng
     */
    public function index(Request $request)
    {
        // Tạo truy vấn cơ sở dữ liệu cho đơn hàng
        $query = Order::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('q')) {
            $search = $request->q;
            // Tìm kiếm theo order_id, hoặc thông tin người dùng (tên, email)
            $query->where('order_id', 'LIKE', "%$search%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                });
        }

        // Lấy các đơn hàng mới nhất có phân trang và giữ lại từ khóa tìm kiếm
        $orders = $query->latest()->paginate(self::PER_PAGES)->appends($request->only('q'));

        // Trả về view danh sách đơn hàng
        return view('admin.content.order.list', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        // Tìm đơn hàng theo id
        $order = Order::find($id);
        // Trả về view chi tiết đơn hàng
        return view('admin.content.order.detail', compact('order'));
    }

    /**
     * Xóa đơn hàng
     */
    public function destroy($id, Request $request)
    {
        // Tìm đơn hàng theo id
        $order = Order::find($id);

        // Xóa các chi tiết đơn hàng liên quan
        $order->orderDetails()->delete();

        // Xóa thông tin thanh toán nếu có
        if ($order->payment) {
            $order->payment()->delete();
        }

        // Xoá chính đơn hàng
        $order->delete();

        // Lấy trang hiện tại để duy trì trang khi chuyển hướng
        $page = $request->get('page');

        // Chuyển hướng về danh sách đơn hàng
        return redirect()->route('order.list', ['page' => $page])->with('success', 'Xóa đơn hàng thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa đơn hàng
     */
    public function edit($id)
    {
        // Tìm đơn hàng theo id
        $order = Order::find($id);

        // Trả về view chỉnh sửa đơn hàng
        return view('admin.content.order.edit', compact('order'));
    }

    /**
     * Cập nhật thông tin đơn hàng
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        // Tìm đơn hàng theo id
        $order = Order::find($id);

        // Cập nhật trạng thái đơn hàng
        $order->status = $request->status;
        $order->save();

        // Cập nhật thông tin thanh toán nếu có
        $payment = $order->payment;
        if ($payment) {
            $payment->status = $request->payment_status;
            $payment->method = $request->payment_method;
            $payment->save();
        }

        // Lấy trang hiện tại để duy trì trang khi chuyển hướng
        $page = $request->get('page');

        // Chuyển hướng về danh sách đơn hàng
        return redirect()->route('order.list', ['page' => $page])->with('success', 'Cập nhật đơn hàng thành công!');
    }
}
