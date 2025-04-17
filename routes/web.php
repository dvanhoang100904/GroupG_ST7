<?php

use App\Http\Controllers\Customer\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('customer.index');


Route::get('/admin/dashboard', function () {
    return view('admin.content.dashboard.index');
});


// Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {   //KHI có login thì sài dòng này!!!
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index'); // Hiển thị danh sách danh mục
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create'); // Hiển thị form tạo danh mục mới
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store'); // Lưu
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy'); // Xóa
    Route::get('/category/{category}', [CategoryController::class, 'read'])->name('category.read'); //chi tiet

    Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit'); // Hiển thị form sửa
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update'); // Lưu cập nhật
});



// Route chuyển tới trang đánh giá khách hàng
Route::get('/admin/reviews', function () {
    return view('admin.content.website.website');
})->name('admin.reviews');
