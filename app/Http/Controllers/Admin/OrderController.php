<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * List Orders
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('q')) {
            $search = $request->q;
            $query->where('order_id', 'LIKE', "%$search%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                });
        }

        $perPage = 10;
        $orders = $query->latest()->paginate($perPage)->appends($request->only('q'));

        return view('admin.content.order.list', compact('orders'));
    }

    /**
     * Detail order
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('admin.content.order.detail', compact('order'));
    }

    /**
     * delete Order
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        $order = Order::find($id);

        // Xoá các chi tiết đơn hàng 
        $order->orderDetails()->delete();

        // Xoá thông tin thanh toán
        if ($order->payment) {
            $order->payment()->delete();
        }

        // Xoá chính đơn hàng
        $order->delete();

        DB::commit();

        return redirect()->route('order.list')->with('success', 'Xóa đơn hàng thành công.');
    }

    /**
     * Form Edit Order
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('admin.content.order.edit', compact('order'));
    }

    /**
     * Update Order
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        DB::beginTransaction();

        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();

        // Cập nhật payment nếu tồn tại
        $payment = $order->payment;
        if ($payment) {
            $payment->status = $request->payment_status;
            $payment->method = $request->payment_method;
            $payment->save();
        }

        DB::commit();

        $page = $request->get('page');

        return redirect()->route('order.list', ['page' => $page])->with('success', 'Cập nhật đơn hàng thành công!');
    }
}
