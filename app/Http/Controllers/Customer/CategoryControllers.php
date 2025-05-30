<?php
// CODE cua yen
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryControllers extends Controller
{
    /**
     * Hiển thị sản phẩm theo danh mục
     */
    public function show(Request $request, $slug)
    {
        $sort = $request->input('sort');
        $page = $request->input('page');

        // ✅ Kiểm tra nếu page không hợp lệ
        if ($page && (!ctype_digit($page) || (int)$page < 1)) {
            return redirect()->route('category.show', ['slug' => $slug])
                ->with('error', 'Tham số phân trang không hợp lệ.');
        }

        // Tìm danh mục theo slug
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return view('customer.pages.category-not-found');
        }

        // Lưu danh mục vào session
        session()->put('current_category', [
            'id' => $category->category_id,
            'slug' => $category->slug,
            'name' => $category->category_name
        ]);

        // Lấy danh sách sản phẩm theo danh mục và sort
        $products = $this->getProductsByCategory($category->category_id, $sort);

        // ✅ Nếu trang hiện tại vượt quá số trang thực tế → redirect về trang đầu
        if ($products instanceof LengthAwarePaginator && $products->isEmpty() && $products->currentPage() > 1) {
            return redirect()->route('category.show', ['slug' => $slug])
                ->with('error', 'Trang bạn yêu cầu không tồn tại.');
        }

        $categories = Category::orderBy('category_id')->get();

        return view('customer.pages.category-products', compact('products', 'category', 'categories', 'sort'));
    }

    /**
     * Kiểm tra danh mục trước khi thực hiện các hành động
     */
    private function checkCategory($category_id)
    {
        $currentCategory = session('current_category');

        // Nếu không có trong session hoặc không khớp với database
        if (!$currentCategory || !Category::where('category_id', $category_id)->exists()) {
            $displayName = $currentCategory['name'] ?? 'Danh mục này';
            return view('customer.pages.category-not-found', compact('displayName'));
        }

        return null;
    }

    /**
     * Lấy sản phẩm theo danh mục với điều kiện sắp xếp
     */
    private function getProductsByCategory($category_id, $sort)
    {
        return Product::where('category_id', $category_id)
            ->when($sort == 'name_asc', function ($query) {
                return $query->orderBy('product_name', 'asc');
            })
            ->when($sort == 'name_desc', function ($query) {
                return $query->orderBy('product_name', 'desc');
            })
            ->when($sort == 'price_asc', function ($query) {
                return $query->orderBy('price', 'asc');
            })
            ->when($sort == 'price_desc', function ($query) {
                return $query->orderBy('price', 'desc');
            })
            ->when(!$sort, function ($query) {
                return $query->orderBy('product_id', 'desc');
            })
            ->paginate(12)
            ->withQueryString();
    }

    /**
     * Middleware để kiểm tra danh mục trước khi thêm vào giỏ hàng hoặc xem chi tiết
     */
    public function validateCategory(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = Product::find($product_id);

        if (!$product) {
            $displayName = 'Sản phẩm';
            return view('customer.pages.category-not-found', compact('displayName'));
        }

        $categoryCheck = $this->checkCategory($product->category_id);
        if ($categoryCheck) {
            return $categoryCheck;
        }

        return response()->json(['valid' => true]);
    }
}
