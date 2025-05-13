<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Trang tất cả sản phẩm hoặc tìm kiếm
    public function index(Request $request)
    {
        $search = $request->input('search', ''); // Lấy từ khóa tìm kiếm nếu có
        $sort = $request->input('sort', '');     // Lấy lựa chọn sắp xếp nếu có

        // Truy vấn danh sách sản phẩm
        $products = Product::query()
            ->when($search, function ($query, $search) {
                // Nếu có từ khóa tìm kiếm thì lọc theo tên sản phẩm hoặc tên danh mục
                return $query->where('product_name', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('category_name', 'LIKE', '%' . $search . '%');
                    });
            });

        // Áp dụng sắp xếp theo lựa chọn từ dropdown
        switch ($sort) {
            case 'name_asc':
                $products->orderBy('product_name', 'asc'); // Sắp xếp theo tên A-Z
                break;
            case 'name_desc':
                $products->orderBy('product_name', 'desc'); // Sắp xếp theo tên Z-A
                break;
            case 'price_asc':
                $products->orderBy('price', 'asc'); // Sắp xếp giá thấp đến cao
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc'); // Sắp xếp giá cao đến thấp
                break;
            default:
                $products->orderBy('product_id'); // Mặc định theo thứ tự thêm vào
        }

        // Phân trang và giữ lại các query string (search, sort)
        $products = $products->paginate(12)->appends($request->query());

        // Lấy tất cả danh mục để hiển thị nếu cần
        $categories = Category::orderBy('category_id')->get();

        // Truyền dữ liệu sang view
        return view('customer.pages.products', compact('products', 'categories', 'search', 'sort'));
    }


    // Trang chi tiết sản phẩm
    public function detail($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Tìm các sản phẩm tương tự (không trùng ID, cùng tên gần giống hoặc giá gần giống)
        $similarProducts = Product::where('product_id', '!=', $product->product_id)
            ->where(function ($query) use ($product) {
                $query->where('product_name', 'LIKE', '%' . $product->product_name . '%')
                    ->orWhereBetween('price', [$product->price * 0.9, $product->price * 1.1]);
            })
            ->limit(4)
            ->get();

        // Lấy đánh giá sản phẩm theo thời gian mới nhất
        $reviews = Review::where('product_id', $product->product_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Truyền dữ liệu sang view chi tiết sản phẩm
        return view('customer.pages.detail-product', compact('product', 'similarProducts', 'reviews'));
    }
}