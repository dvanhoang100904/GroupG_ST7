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
            })
            ->when(true, function ($query) {
                // Điều kiện lọc sản phẩm nổi bật: Giá từ 4 triệu đến 10 triệu hoặc sản phẩm mới tạo trong vòng 7 ngày qua
                return $query->where(function ($query) {
                    $query->whereBetween('price', [4000000, 10000000])
                        ->orWhere('created_at', '>=', now()->subDays(7)); // Sản phẩm mới tạo trong 7 ngày qua
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

        // Lấy chỉ 8 sản phẩm (giới hạn) và phân trang
        $products = $products->limit(8)->paginate(8)->appends($request->query());

        // Lấy tất cả danh mục để hiển thị nếu cần
        $categories = Category::orderBy('category_id')->get();

        // Truyền dữ liệu sang view
        return view('customer.pages.products', compact('products', 'categories', 'search', 'sort'));
    }

    // Trang chi tiết sản phẩm OLD
    // public function detail($slug)
    // {
    //     // Tìm sản phẩm theo slug
    //     $product = Product::where('slug', $slug)->firstOrFail();

    //     // Lấy các sản phẩm tương tự nếu có
    //     $similarProducts = Product::where('product_id', '!=', $product->product_id)
    //         ->where(function ($query) use ($product) {
    //             $query->where('category_id', $product->category_id)
    //                 ->orWhereBetween('price', [$product->price * 0.9, $product->price * 1.1]);
    //         })
    //         ->limit(4)
    //         ->get();

    //     // Xử lý lọc theo số sao (rating)
    //     $rating = request()->query('rating');

    //     $reviewsQuery = Review::where('product_id', $product->product_id)
    //         ->orderBy('created_at', 'desc');

    //     if (is_numeric($rating)) {
    //         $reviewsQuery->where('rating', $rating);
    //     }

    //     $reviews = $reviewsQuery->get();

    //     // ✅ Chỉ return 1 lần sau khi đã xử lý lọc
    //     return view('customer.pages.detail-product', compact('product', 'similarProducts', 'reviews'));
    // }

    public function detail($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->first();

        // Xử lý nếu không tìm thấy sản phẩm
        if (!$product) {
            $displayName = ucwords(str_replace('-', ' ', $slug));
            return view('customer.pages.category-not-found', compact('displayName'));
        }

        // Kiểm tra danh mục có tồn tại
        $category = Category::find($product->category_id);
        if (!$category) {
            $displayName = ucwords(str_replace('-', ' ', $slug));
            return view('customer.pages.category-not-found', compact('displayName'));
        }

        // Lấy các sản phẩm tương tự: cùng danh mục hoặc gần giá
        $similarProducts = Product::where('product_id', '!=', $product->product_id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                    ->orWhereBetween('price', [$product->price * 0.9, $product->price * 1.1]);
            })
            ->limit(4)
            ->get();

        // Lấy đánh giá sản phẩm (có thể lọc theo số sao)
        $rating = request('rating');

        $reviewsQuery = Review::where('product_id', $product->product_id)
            ->orderBy('created_at', 'desc')
            ->with(['user', 'replies']);

        if (is_numeric($rating)) {
            $reviewsQuery->where('rating', $rating);
        }

        $reviews = $reviewsQuery->get();

        // Trả về view chi tiết sản phẩm
        return view('customer.pages.detail-product', compact('product', 'similarProducts', 'reviews'));
    }


    /// yen
    // public function detail($slug)
    // {
    //     // Tìm sản phẩm theo slug
    //     $product = Product::where('slug', $slug)->first();

    //     // Kiểm tra sản phẩm có tồn tại không
    //     if (!$product) {
    //         $displayName = ucwords(str_replace('-', ' ', $slug));
    //         return view('customer.pages.category-not-found', compact('displayName'));
    //     }

    //     // Kiểm tra danh mục của sản phẩm có tồn tại không
    //     $category = Category::find($product->category_id);
    //     if (!$category) {
    //         $displayName = ucwords(str_replace('-', ' ', $slug));
    //         return view('customer.pages.category-not-found', compact('displayName'));
    //     }

    //     // Lấy các sản phẩm tương tự (cùng danh mục)
    //     $similarProducts = Product::where('category_id', $product->category_id)
    //         ->where('product_id', '!=', $product->product_id)
    //         ->limit(4)
    //         ->get();

    //     // Lấy đánh giá của sản phẩm
    //     $reviewsQuery = Review::where('product_id', $product->product_id);
    //     if (request('rating')) {
    //         $reviewsQuery->where('rating', request('rating'));
    //     }
    //     $reviews = $reviewsQuery->with(['user', 'replies'])->get();

    //     // Trả về view chi tiết sản phẩm
    //     return view('customer.pages.detail-product', compact('product', 'similarProducts', 'reviews'));
    // }
}
