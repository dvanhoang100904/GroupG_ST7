<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void {}


    public function boot(): void
    {
        // Tự động chia sẻ biến $categories cho tất cả các view

        // hàm này lỗi phân trangtrang
        // View::composer('*', function ($view) {
        //     $categories = Category::all(); // Lấy toàn bộ danh mục từ database
        //     $view->with('categories', $categories); 
        // });
        /// fix sau:
        View::composer('*', function ($view) {
            $categoryList = Category::all(); // hoặc Category::select('id', 'name')->get(); nếu chỉ cần 1 số cột bat kì
            $view->with('categoryList', $categoryList);
        });
    }
}
