<?php

use App\Http\Controllers\Customer\HomeController;
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('customer.index');





Route::get('/admin/dashboard', function () {
    return view('admin.content.dashboard.index');
});

Route::get('/admin/category', function () {
    return view('admin.content.category.list');
});

// Route chuyển tới trang đánh giá khách hàng
Route::get('/admin/reviews', function () {
    return view('admin.content.website.website');
})->name('admin.reviews');
