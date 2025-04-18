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
        $order = Order::findOrFail($id);
        return view('admin.content.order.detail', compact('order'));
    }
}
