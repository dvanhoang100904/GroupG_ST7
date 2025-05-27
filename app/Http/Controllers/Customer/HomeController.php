<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy dữ liệu slides
        $slides = Slide::all();  // Hoặc tùy vào cách bạn lưu trữ slide
        
        // Lấy tất cả các danh mục từ bảng 'categories'
        $categories = Category::orderBy('category_id')->get();

        // Lấy sản phẩm nổi bật (giá từ 4tr đến 10tr hoặc mới tạo trong 7 ngày)
        $featuredProducts = Product::where(function ($query) {
            $query->whereBetween('price', [4000000, 10000000])
                ->orWhere('created_at', '>=', now()->subDays(7));
        })
        ->orderByDesc('created_at')
        ->limit(8)
        ->get();
        
        // ktra và lấy trạng thái == true từ crud
        $slides = Slide::where('is_visible', true)->get();
        // Lấy danh sách chat nếu người dùng là khách hàng
        $chats = [];

        if (Auth::check() && Auth::user()->role_id === 2) {
            $chats = Chat::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            })
                ->whereNull('assessment_star_id') // loại bỏ reply đánh giá nếu có
                ->orderBy('created_at')
                ->get();
        }

        return view('customer.pages.home', compact('categories', 'featuredProducts', 'slides' , 'chats'));
    }
}
