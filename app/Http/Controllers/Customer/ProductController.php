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
        // Kiểm tra nếu có từ khóa tìm kiếm
        $search = $request->input('search', ''); // Lấy từ khóa tìm kiếm

        // Tìm kiếm sản phẩm theo tên, tên danh mục, hoặc tên sản phẩm tương tự
        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('category_name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->orderBy('product_id')
            ->paginate(12);

        // Lấy tất cả danh mục
        $categories = Category::orderBy('category_id')->get();

        return view('customer.pages.products', compact('products', 'categories', 'search'));
    }

    // Trang sản phẩm theo danh mục
    public function show($slug)
    {
        // Tìm danh mục theo slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Lấy sản phẩm thuộc danh mục, phân trang 12 sản phẩm mỗi trang
        $products = Product::where('category_id', $category->id)
            ->orderBy('product_id')
            ->paginate(12);

        // Lấy tất cả danh mục (nếu dùng trong sidebar)
        $categories = Category::orderBy('category_id')->get();

        return view('customer.pages.products-by-category', compact('products', 'category', 'categories'));
    }

    // Trang chi tiết sản phẩm
    public function detail($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $similarProducts = Product::where('product_id', '!=', $product->product_id)
            ->where(function ($query) use ($product) {
                $query->where('product_name', 'LIKE', '%' . $product->product_name . '%')
                    ->orWhereBetween('price', [$product->price * 0.9, $product->price * 1.1]);
            })
            ->limit(4)
            ->get();

        $reviews = Review::where('product_id', $product->product_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.pages.detail-product', compact('product', 'similarProducts', 'reviews'));
    }
}
