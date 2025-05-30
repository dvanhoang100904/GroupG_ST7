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
        $page = $request->input('page');         // Lấy tham số phân trang

        // ✅ Kiểm tra nếu page không phải là số nguyên dương
        if ($page && (!ctype_digit($page) || (int)$page < 1)) {
            return redirect()->route('products.index')->with('error', 'Tham số phân trang không hợp lệ.');
        }

        // Truy vấn danh sách sản phẩm
        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('category_name', 'LIKE', '%' . $search . '%');
                    });
            })
            ->when(true, function ($query) {
                return $query->where(function ($query) {
                    $query->whereBetween('price', [4000000, 10000000])
                        ->orWhere('created_at', '>=', now()->subDays(7));
                });
            });

        // Áp dụng sắp xếp theo lựa chọn từ dropdown
        switch ($sort) {
            case 'name_asc':
                $products->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $products->orderBy('product_name', 'desc');
                break;
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            default:
                $products->orderBy('product_id');
        }

        // Phân trang với giới hạn 8 sản phẩm
        $products = $products->paginate(8)->appends($request->query());

        // ✅ Nếu không có sản phẩm ở trang hiện tại và trang > 1 => quay về trang chính
        if ($products->isEmpty() && $products->currentPage() > 1) {
            return redirect()->route('products.index')->with('error', 'Trang bạn yêu cầu không tồn tại.');
        }

        $categories = Category::orderBy('category_id')->get();

        return view('customer.pages.products', compact('products', 'categories', 'search', 'sort'));
    }


    // Trang chi tiết sản phẩm
    public function detail($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Lấy các sản phẩm tương tự nếu có
        $similarProducts = Product::where('product_id', '!=', $product->product_id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                    ->orWhereBetween('price', [$product->price * 0.9, $product->price * 1.1]);
            })
            ->limit(4)
            ->get();

        // Xử lý lọc theo số sao (rating)
        $rating = request()->query('rating');

        $reviewsQuery = Review::where('product_id', $product->product_id)
            ->orderBy('created_at', 'desc');

        if (is_numeric($rating)) {
            $reviewsQuery->where('rating', $rating);
        }

        $reviews = $reviewsQuery->get();

        // ✅ Chỉ return 1 lần sau khi đã xử lý lọc
        return view('customer.pages.detail-product', compact('product', 'similarProducts', 'reviews'));
    }
}
