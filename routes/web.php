<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\LogoutController as CustomerLogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('customer.index');
// Đăng nhập    
Route::get('login', [CustomerLoginController::class, 'login'])->name('customer.login')->middleware('redirectIf.customer.auth');
Route::post('login', [CustomerLoginController::class, 'authLogin'])->name('customer.authLogin')->middleware('redirectIf.customer.auth');

// Đăng ký
Route::post('logout', [CustomerLogoutController::class, 'logout'])->name('customer.logout')->middleware('auth');


// admin
Route::prefix('admin')->group(function () {
    // Đăng nhập
    Route::get('login', [AdminLoginController::class, 'login'])->name('admin.login')->middleware('redirectIf.admin.auth');
    Route::post('login', [AdminLoginController::class, 'authLogin'])->name('admin.authLogin')->middleware('redirectIf.admin.auth');

    // Đăng Xuất
    Route::post('logout', [AdminLogoutController::class, 'logout'])->name('admin.logout')->middleware('auth');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard')->middleware('check.login.admin');

    // Orders
    Route::middleware('check.login.admin')->group(function () {
        // list
        Route::get('orders', [AdminOrderController::class, 'index'])->name('order.list');
        // detail
        Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('order.detail');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index'); // Hiển thị danh sách danh mục
        Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create'); // Hiển thị form tạo danh mục mới
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store'); // Lưu
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy'); // Xóa
        Route::get('/category/{category}', [CategoryController::class, 'read'])->name('category.read'); //chi tiet
        Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit'); // Hiển thị form sửa
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update'); // Lưu cập nhật
    });
});

// Route chuyển tới trang đánh giá khách hàng
Route::get('/admin/reviews', function () {
    return view('admin.content.website.website');
})->name('admin.reviews');
