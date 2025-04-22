<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CategoryControllers;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\LogoutController as CustomerLogoutController;
use App\Http\Controllers\Customer\CartController as CustomerCartController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh sách sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Chi tiết sản phẩm theo slug
Route::get('product/{slug}', [ProductController::class, 'detail'])->name('products.detail');

// Sản phẩm theo danh mục (theo slug)
Route::get('/category/{slug}', [CategoryControllers::class, 'show'])->name('category.show');


// Đăng nhập    
Route::get('login', [CustomerLoginController::class, 'login'])->name('customer.login')->middleware('redirectIf.customer.auth');
Route::post('login', [CustomerLoginController::class, 'authLogin'])->name('customer.authLogin')->middleware('redirectIf.customer.auth');

// Đăng ký
Route::post('logout', [CustomerLogoutController::class, 'logout'])->name('customer.logout')->middleware('auth');

// Giỏ hàng
route::middleware(['web'])->group(function () {
    route::get('/gio-hang', [CustomerCartController::class, 'index'])->name('cart.list');
    // Thêm giỏ hàng
    route::post('/them-vao-gio-hang', [CustomerCartController::class, 'addToCart'])->name('cart.addToCart');
    // Xóa giỏ hàng
    Route::post('/gio-hang/xoa', [CustomerCartController::class, 'removeFromCart'])->name('cart.removeFromCart');
    // cập nhật giỏ hàng
    Route::post('gio-hang/cap-nhat', [CustomerCartController::class, 'updateCart'])->name('cart.updateCart');
});

// Order
route::middleware('check.login.customer')->group(function () {
    // Thanh toán
    Route::get('/thanh-toan', [CustomerOrderController::class, 'checkout'])->name('order.checkout');
    // Đặt hàng
    Route::post('/dat-hang', [CustomerOrderController::class, 'placeOrder'])->name('order.placeOrder');
    // Thông báo đặt hàng
    Route::get('/dat-hang-thanh-cong/{order}', [CustomerOrderController::class, 'orderSuccess'])->name('order.success');
});

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
        // edit
        Route::get('orders/{id}/edit', [AdminOrderController::class, 'edit'])->name('order.edit');
        // update
        Route::put('orders/{id}', [AdminOrderController::class, 'update'])->name('order.update');
        // delete
        Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->name('order.delete');
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
