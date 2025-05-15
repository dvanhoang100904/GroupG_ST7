<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy dữ liệu slides
        $slides = Slide::all();  // Hoặc tùy vào cách bạn lưu trữ slide
        
        // Lấy tất cả các danh mục từ bảng 'categories'
        $categories = Category::orderBy('category_id')->get();

        // Lọc sản phẩm nổi bật
        $featuredProducts = Product::whereBetween('price', [3000000, 10000000]) // Giá từ 3 triệu đến 10 triệu
            ->orWhere('created_at', '>', Carbon::now()->subMonths(6)) // Sản phẩm mới trong 6 tháng
            ->take(8) // Giới hạn chỉ lấy 8 sản phẩm
            ->get();

        // ktra và lấy trạng thái == true từ crud
        $slides = Slide::where('is_visible', true)->get();


        return view('customer.pages.home', compact('categories', 'featuredProducts', 'slides'));
    }
}
