<?php

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
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('category_id', $category->category_id)
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

        $categories = Category::orderBy('category_id')->get();

        return view('customer.pages.category-products', compact('products', 'category', 'categories', 'sort'));
    }


    /**
     * Thêm danh mục mới
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'description' => 'nullable|string',
        ]);

        // Tạo danh mục mới và tự động tạo slug từ tên danh mục
        Category::create([
            'category_name' => $request->category_name,
            'slug' => Str::slug($request->category_name), // slug tự sinh
            'description' => $request->description,
        ]);

        // Quay lại và thông báo thành công
        return redirect()->back()->with('success', 'Tạo danh mục thành công!');
    }
}