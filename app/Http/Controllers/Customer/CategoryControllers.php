<?php

// namespace App\Http\Controllers\Customer;

// use App\Http\Controllers\Controller;
// use App\Models\Category;
// use App\Models\Product;
// use Illuminate\Http\Request;
// use Illuminate\Support\Str;

// class CategoryControllers extends Controller
// {
//     public function show(Request $request, $slug)
//     {
//         $sort = $request->input('sort');

//         $category = Category::where('slug', $slug)->first();

//         if (!$category) {
//             $displayName = ucwords(str_replace('-', ' ', $slug));
//             return view('customer.pages.category-not-found', compact('displayName'));
//         }

//         $products = Product::where('category_id', $category->category_id)
//             ->when($sort == 'name_asc', function ($query) {
//                 return $query->orderBy('product_name', 'asc');
//             })
//             ->when($sort == 'name_desc', function ($query) {
//                 return $query->orderBy('product_name', 'desc');
//             })
//             ->when($sort == 'price_asc', function ($query) {
//                 return $query->orderBy('price', 'asc');
//             })
//             ->when($sort == 'price_desc', function ($query) {
//                 return $query->orderBy('price', 'desc');
//             })
//             ->when(!$sort, function ($query) {
//                 return $query->orderBy('product_id', 'desc');
//             })
//             ->paginate(12)
//             ->withQueryString();

//         $categories = Category::orderBy('category_id')->get();

//         return view('customer.pages.category-products', compact('products', 'category', 'categories', 'sort'));
//     }
// }


// CODE cua yen
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryControllers extends Controller
{
    /**
     * Hiển thị sản phẩm theo danh mục
     */
    public function show(Request $request, $slug)
    {
        $sort = $request->input('sort');

        // Tìm danh mục theo slug
        $category = Category::where('slug', $slug)->first();

        // xap sep theo ten mess loi
        if (!$category) {
            return view('customer.pages.category-not-found');
        }

        // Lưu thông tin danh mục vào session để kiểm tra sau này
        session()->put('current_category', [
            'id' => $category->category_id,
            'slug' => $category->slug,
            'name' => $category->category_name
        ]);

        $products = $this->getProductsByCategory($category->category_id, $sort);

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
