<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
   public function index()
{
    $user = auth()->user();

    // Lấy danh sách sản phẩm yêu thích của user
    // Giả sử quan hệ user->favorites trả về collection sản phẩm
    $products = $user->favorites()->paginate(10);


    // Nếu cần phân trang thì dùng paginate, hoặc dùng collection bình thường
    // Ví dụ: $products = $user->favorites()->paginate(10);

    // Truyền $products xuống view
    return view('favorites.index', compact('products'));
}


    public function store(Request $request, $productId)
{
    $user = Auth::user();

    // Hoặc lấy product_id từ param hoặc từ request, tùy route với form
    $productId = $productId ?? $request->product_id;

    if (!$user->favorites->contains($productId)) {
        $user->favorites()->attach($productId);
    }

    return redirect()->back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
}


    public function destroy($productId) {
    Auth::user()->favorites()->detach($productId);
    return response()->json(['message' => 'Đã xóa khỏi yêu thích']);
}
}
