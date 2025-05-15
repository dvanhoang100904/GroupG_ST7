<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Customer\PageController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CategoryControllers;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\RegisterController;
use App\Http\Controllers\Customer\LogoutController as CustomerLogoutController;
use App\Http\Controllers\Customer\CartController as CustomerCartController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;
use App\Http\Controllers\Admin\ProductsController as AdminProductsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Customer\SocialController;



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

//Đăng nhập qua FaceBook, Google


// Route cho Google
Route::get('/auth/google', [SocialController::class, 'redirectGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook', [SocialController::class, 'redirectFacebook']);
Route::get('/auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);



// Đăng ký
Route::post('logout', [CustomerLogoutController::class, 'logout'])->name('customer.logout')->middleware('auth');

// Giỏ hàng
route::middleware(['web'])->group(function () {
    Route::get('/gio-hang', [CustomerCartController::class, 'index'])->name('cart.list');
    // Thêm giỏ hàng
    Route::post('/them-vao-gio-hang', [CustomerCartController::class, 'addToCart'])->name('cart.addToCart');
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
//Đánh giá sản phẩm
Route::post('/product/{productId}/review', [ReviewController::class, 'store'])->name('reviews.store'); // POST để gửi đánh giá
Route::get('/product/{productId}/reviews', [ReviewController::class, 'getReviews'])->name('reviews.get'); // GET để lấy đánh giá 


// admin
Route::prefix('admin')->group(function () {

    // Đăng nhập
    Route::get('login', [AdminLoginController::class, 'login'])->name('admin.login')->middleware('redirectIf.admin.auth');
    Route::post('login', [AdminLoginController::class, 'authLogin'])->name('admin.authLogin')->middleware('redirectIf.admin.auth');

    // Đăng Xuất
    Route::post('logout', [AdminLogoutController::class, 'logout'])->name('admin.logout')->middleware('auth');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard')->middleware('check.login.admin');

    // Product routes trong admin
    Route::middleware('check.login.admin')->group(function () {
        Route::get('products', [AdminProductsController::class, 'index'])->name('products.list');
        Route::get('products/create', [AdminProductsController::class, 'create'])->name('products.create');
        Route::post('products', [AdminProductsController::class, 'store'])->name('products.store');
        Route::get('products/{product}', [AdminProductsController::class, 'read'])->name('products.read');
        Route::get('products/{product}/edit', [AdminProductsController::class, 'edit'])->name('edit');
        Route::put('products/{product}', [AdminProductsController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [AdminProductsController::class, 'destroy'])->name('products.destroy');
    }); 
    

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

    // Route chuyển tới Slide - Banner
    Route::middleware('auth')->group(function () {
        Route::get('/slides', [SlideController::class, 'index'])->name('slide.index'); // Danh sách
        Route::get('/slides/create', [SlideController::class, 'create'])->name('slide.create'); // Form tạo mới
        Route::post('/slides', [SlideController::class, 'store'])->name('slide.store'); // Lưu mới
        Route::get('/slides/{slide}', [SlideController::class, 'read'])->name('slide.read'); // Xem chi tiết
        Route::get('/slides/{slide}/edit', [SlideController::class, 'edit'])->name('slide.edit'); // Form sửa
        Route::put('/slides/{slide}', [SlideController::class, 'update'])->name('slide.update'); // Lưu sửa
        Route::delete('/slides/{slide}', [SlideController::class, 'destroy'])->name('slide.destroy'); // Xóa
        Route::put('/slides/{slide}/toggle-visibility', [SlideController::class, 'toggleVisibility'])->name('slide.toggleVisibility'); //hiện trang chủ
    });
});
   // Route chuyển tới trang đánh giá khách hàng
   Route::get('/admin/reviews', function () {
    return view('admin.content.website.website');
})->name('admin.reviews');


// Route chuyển tới trang chính sách bảo mật
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');

// Route chuyển tới trang chính sách bảo hành
Route::get('/warranty-policy', [PageController::class, 'warrantyPolicy'])->name('warranty-policy');

// Đăng ký

Route::get('register', [RegisterController::class, 'authRegister'])->name('customer.register');
Route::post('register', [RegisterController::class, 'register'])->name('customer.register.submit');



Route::get('/customer/home', function () {
    return view('customer.pages.home');
})->name('customer.home');

