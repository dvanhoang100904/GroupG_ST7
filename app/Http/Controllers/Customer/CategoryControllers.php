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
    public function show($slug)
    {
        // Tìm danh mục theo slug, nếu không có sẽ trả về 404
        $category = Category::where('slug', $slug)->firstOrFail();

        

        // Lấy các sản phẩm thuộc danh mục đó
        $products = Product::where('category_id', $category->category_id)->get();

        // Lấy toàn bộ danh mục (dùng cho sidebar nếu cần)
        $categories = Category::orderBy('category_id')->get();

        // Trả view hiển thị sản phẩm theo danh mục
        return view('customer.pages.category-products', compact('category', 'products', 'categories'));
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