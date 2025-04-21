<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Trang tất cả sản phẩm
    public function index()
    {
        // Lấy tất cả sản phẩm và sắp xếp theo product_id
        $products = Product::orderBy('product_id')->get();

        // Lấy tất cả danh mục và sắp xếp theo category_id
        $categories = Category::orderBy('category_id')->get();

        // Trả về view 'products.product' và truyền dữ liệu products, categories
        return view('products.product', compact('products', 'categories'));
    }

    // Trang sản phẩm theo danh mục
    public function show($slug)
    {
        // Tìm danh mục theo slug
        $category = Category::where('slug', $slug)->firstOrFail();
    
        // Lấy tất cả sản phẩm thuộc danh mục này
        $products = Product::where('category_id', $category->id)->get();
    
        // Lấy tất cả danh mục để hiển thị trong sidebar (nếu cần)
        $categories = Category::orderBy('category_id')->get();
    
        return view('products.show', compact('products', 'category', 'categories'));
    }    
    

    // Trang chi tiết sản phẩm
    public function detail($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::where('slug', $slug)->firstOrFail();
    
        // Lấy các sản phẩm tương tự nếu có
        $similarProducts = Product::where('product_id', '!=', $product->product_id)
            ->where(function ($query) use ($product) {
                $query->where('product_name', 'LIKE', '%' . $product->product_name . '%')
                      ->orWhereBetween('price', [$product->price * 0.9, $product->price * 1.1]);
            })
            ->limit(4)
            ->get();
    
        // Trả về view chi tiết sản phẩm và các sản phẩm tương tự
        return view('products.detail', compact('product', 'similarProducts'));
    }
    
}