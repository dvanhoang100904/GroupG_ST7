<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrangChuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/trangchu', [TrangChuController::class, 'index'])->name('trangchu');

Route::get('/admin/dashboard', function () {
    return view('admin.content.dashboard');
});

Route::get('/admin/category', function () {
    return view('admin.content.category');
});

// Route chuyển tới trang đánh giá khách hàng
Route::get('/admin/reviews', function () {
    return view('admin.content.website');
})->name('admin.reviews');
