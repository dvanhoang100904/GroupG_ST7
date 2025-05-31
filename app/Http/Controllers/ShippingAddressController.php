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
    public function edit($id)
{
    try {
        $shippingAddress = ShippingAddress::where('user_id', Auth::id())
            ->where('shipping_address_id', $id)
            ->firstOrFail();

        return view('customer.address.edit', compact('shippingAddress'));

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('shipping_address.index')
            ->with('warning', 'Địa chỉ không còn tồn tại. Có thể đã bị xoá hoặc bạn không có quyền chỉnh sửa.');
    }
}

    // Cập nhật địa chỉ
   public function update(Request $request, $id)
{
    try {
        $address = ShippingAddress::where('user_id', Auth::id())
            ->where('shipping_address_id', $id)
            ->firstOrFail();

        // Kiểm tra nếu dữ liệu đã bị sửa từ trước (so sánh updated_at)
        if ($request->input('updated_at') !== $address->updated_at->toISOString()) {
    return redirect()->route('shipping_address.index')
        ->with('error', 'Địa chỉ đã được chỉnh sửa trước đó, vui lòng tải lại!');
}

        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $address->update($request->only(['name', 'address', 'phone']));

        return redirect()->route('shipping_address.index')->with('success', 'Cập nhật địa chỉ thành công!');
    } catch (ModelNotFoundException $e) {
        return redirect()->route('shipping_address.index')
            ->with('warning', 'Không thể cập nhật vì địa chỉ không còn tồn tại!');
    } catch (\Exception $e) {
        return redirect()->route('shipping_address.index')
            ->with('error', 'Có lỗi xảy ra khi cập nhật địa chỉ.');
    }
}

    // Xóa địa chỉ
    public function destroy($id)
{
    try {
        $address = ShippingAddress::findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Xoá địa chỉ thành công!');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->back()->with('warning', 'Địa chỉ này đã được xoá hoặc không tồn tại!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại.');
    }
}

}

