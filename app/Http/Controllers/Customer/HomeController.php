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
        // Lấy tất cả các danh mục từ bảng 'categories'
        $categories = Category::orderBy('category_id')->get();

        // Lọc sản phẩm nổi bật
        $featuredProducts = Product::whereBetween('price', [3000000, 10000000]) // Giá từ 3 triệu đến 10 triệu
            ->orWhere('created_at', '>', Carbon::now()->subMonths(6)) // Sản phẩm mới trong 6 tháng
            ->take(8) // Giới hạn chỉ lấy 8 sản phẩm
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
