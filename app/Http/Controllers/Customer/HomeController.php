<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả các danh mục từ bảng 'categories'
        $categories = Category::orderBy('category_id')->get();

        // Lọc sản phẩm nổi bật
        $featuredProducts = Product::whereBetween('price', [3000000, 10000000]) // Giá từ 3 triệu đến 10 triệu
            ->orWhere('created_at', '>', Carbon::now()->subMonths(6)) // Sản phẩm mới trong 6 tháng
            ->take(8) // Giới hạn chỉ lấy 8 sản phẩm
            ->get();

        // Trả về view và truyền vào danh mục và sản phẩm nổi bật
        return view('customer.pages.home', compact('categories', 'featuredProducts'));
    }
}
