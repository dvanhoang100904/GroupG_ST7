<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Dùng để thao tác với các view (truyền dữ liệu dùng chung)
use App\Models\Category; // Gọi model Category để truy xuất dữ liệu danh mục

class AppServiceProvider extends ServiceProvider
{
    /**
     * Đăng ký các dịch vụ ứng dụng.
     */
    public function register(): void
    {
        // Chưa cần đăng ký gì thêm ở đây
    }

    /**
     * Khởi động bất kỳ dịch vụ nào sau khi ứng dụng được load xong.
     */
    public function boot(): void
    {
        // Tự động chia sẻ biến $categories cho tất cả các view
        View::composer('*', function ($view) {
            $categories = Category::all(); // Lấy toàn bộ danh mục từ database
            $view->with('categories', $categories); // Gán biến $categories cho view
        });
    }
}
