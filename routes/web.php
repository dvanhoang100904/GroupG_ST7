<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\LogoutController as CustomerLogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;


// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('customer.index');
// Đăng nhập    
Route::get('login', [CustomerLoginController::class, 'login'])->name('customer.login')->middleware('redirectIf.customer.auth');
Route::post('login', [CustomerLoginController::class, 'authLogin'])->name('customer.authLogin')->middleware('redirectIf.customer.auth');

// Đăng ký
Route::post('logout', [CustomerLogoutController::class, 'logout'])->name('customer.logout')->middleware('auth');

// admin
route::prefix('admin')->group(function () {
    // Đăng nhập
    Route::get('login', [AdminLoginController::class, 'login'])->name('admin.login')->middleware('redirectIf.admin.auth');
    Route::post('login', [AdminLoginController::class, 'authLogin'])->name('admin.authLogin')->middleware('redirectIf.admin.auth');

    // Đăng Xuất
    Route::post('logout', [AdminLogoutController::class, 'logout'])->name('admin.logout')->middleware('auth');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard')->middleware('check.login.admin');
});


Route::get('/admin/category', function () {
    return view('admin.content.category.list');
});

// Route chuyển tới trang đánh giá khách hàng
Route::get('/admin/reviews', function () {
    return view('admin.content.website.website');
})->name('admin.reviews');
