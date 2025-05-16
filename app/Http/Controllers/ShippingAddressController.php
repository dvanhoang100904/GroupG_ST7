<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    // Hiển thị danh sách địa chỉ của user hiện tại
        public function index() {
        
        $userId = Auth::id();

        $addresses = ShippingAddress::where('user_id', $userId)->paginate(10);


        // Trả về view
        return view('customer.address.index', compact('addresses'));
    }

    // Form tạo địa chỉ mới
    public function create()
    {
        return view('customer.address.create');
    }

    // Lưu địa chỉ mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        ShippingAddress::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('shipping_address.index')->with('success', 'Thêm địa chỉ thành công!');
    }

    // Form sửa địa chỉ
    public function edit($shipping_address_id)
    {
        $shippingAddress = ShippingAddress::where('user_id', Auth::id())
                                        ->where('shipping_address_id', $shipping_address_id)
                                        ->firstOrFail();

        return view('customer.address.edit', compact('shippingAddress'));
    }


    // Cập nhật địa chỉ
    public function update(Request $request, $id)
    {
        $address = ShippingAddress::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $address->update($request->only(['name', 'address', 'phone']));

        return redirect()->route('shipping_address.index')->with('success', 'Cập nhật địa chỉ thành công!');
    }

    // Xóa địa chỉ
    public function destroy($id)
    {
        $address = ShippingAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return redirect()->route('shipping_address.index')->with('success', 'Xóa địa chỉ thành công!');
    }
}

