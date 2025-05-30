<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Customer\PageController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CategoryControllers;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\ChatController;
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
use App\Http\Controllers\Auth\CustomerForgotPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\CustomerResetPasswordController;
use App\Http\Controllers\Auth\PasswordController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\ProfileController;


// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh sách sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Chi tiết sản phẩm theo slug
Route::get('product/{slug}', [ProductController::class, 'detail'])->name('products.detail');

// Sản phẩm theo danh mục (theo slug)
Route::get('/category/{slug}', [CategoryControllers::class, 'show'])->name('category.show');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


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
Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard')->middleware('check.login.admin');
//Đánh giá sản phẩm
Route::post('/product/{productId}/review', [ReviewController::class, 'store'])->name('reviews.store'); // POST để gửi đánh giá
Route::get('/product/{productId}/reviews', [ReviewController::class, 'getReviews'])->name('reviews.get'); // GET để lấy đánh giá 

Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::get('/products/{id}', [ProductController::class, 'showpage'])->name('products.show');



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
        Route::get('/products/{id}', [AdminProductsController::class, 'read'])->name('products.read');
        Route::get('/products/{id}/edit', [AdminProductsController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [AdminProductsController::class, 'update'])->name('products.update');
        Route::delete('products/delete/{id}', [AdminProductsController::class, 'destroy'])->name('products.destroy');
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
Route::middleware('auth')->group(function () {
    // Route cho dashboard chính
    Route::get('/admin/website', [ReviewController::class, 'index'])->name('admin.website');

    // Route hiển thị danh sách đánh giá
    Route::get('/admin/reviews/list', [ReviewsController::class, 'listReviews'])->name('admin.reviews.index');

    // Route xem chi tiết đánh giá
    Route::get('/admin/reviews/{review}', [ReviewController::class, 'show'])->name('admin.reviews.detail');

    // Route form phản hồi cho đánh giá
    Route::get('admin/reviews/{review}/reply', [ReviewController::class, 'replyForm'])->name('admin.reviews.reply');
 Route::get('{review_id}/temp-replies', [ReviewController::class, 'getTemporaryReplies'])->name('admin.reviews.tempReplies.get');
    Route::post('{review_id}/temp-replies', [ReviewController::class, 'addTemporaryReply'])->name('admin.reviews.tempReplies.add');
    Route::delete('{review_id}/temp-replies', [ReviewController::class, 'deleteTemporaryReply'])->name('admin.reviews.tempReplies.delete');
    // Route xử lý phản hồi cho đánh giá
    Route::post('/admin/reviews/{review}/reply', [ReviewController::class, 'storeReply'])->name('admin.reviews.storeReply');
    Route::post('admin/review/temp-confirm', [ReviewController::class, 'tempConfirm'])->name('admin.review.tempConfirm');
    // Route cho danh sách tin nhắn (admin)
    Route::get('/admin/chats', [ChatController::class, 'index'])->name('admin.chats.index');
    Route::get('/admin/chat/{id}/detail', [ChatController::class, 'detail']);
    Route::get('/chat/{id}', [ChatController::class, 'showChat'])->name('admin.chat.show');
    Route::post('/admin/chat/reply/{id}', [ChatController::class, 'reply'])->name('admin.chat.reply');
    // Chỉnh sửa
    Route::get('/admin/chat/edit/{chat}', [ChatController::class, 'edit'])->name('admin.chat.edit');
    Route::put('/admin/chat/update/{chat}', [ChatController::class, 'update'])->name('admin.chat.update');
    Route::get('/admin/chat/cancel-edit', function () {
        session()->forget(['editing_chat_id', 'editing_chat_content']);
        return back();
    })->name('admin.chat.cancelEdit');

    // Xóa
    Route::delete('/admin/chat/delete/{chat}', [ChatController::class, 'destroy'])->name('admin.chat.delete');
});
Route::post('/customer/chats', [ChatController::class, 'store'])->name('customer.chats.store')->middleware('auth');
Route::get('/home', [HomeController::class, 'indexx'])->name('customer.home');
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



//Forgot password
Route::get('quen-mat-khau', [CustomerForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('quen-mat-khau', [CustomerForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [CustomerResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('reset-password', [CustomerResetPasswordController::class, 'reset'])
    ->name('password.store');




//Xoá tài khoản
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

Route::put('/user/password', [PasswordController::class, 'update'])->name('password.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});;



//Favorite list
Route::middleware(['auth'])->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{productId}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});



//crud user
Route::prefix('admin/users')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('users.list');
    Route::get('/create', 'create')->name('users.create');
    Route::post('/store', 'store')->name('users.store');
    Route::get('/{user}/read', 'read')->name('users.read');
    Route::get('/{user}/edit', 'edit')->name('users.edit');
    Route::put('/{user}', 'update')->name('users.update');
    Route::delete('/{user}', 'destroy')->name('users.destroy');
});



//notification 
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});



// Lịch sử  mua hàng
// Route::get('/history-order', [OrderController::class, 'history'])->name('purchase.history');
Route::middleware('auth')->group(function () {
    Route::get('/history-order', [CustomerOrderController::class, 'history'])->name('purchase.history');
});

//sổ địa chỉ
Route::middleware(['auth'])->group(function () {
    Route::get('/shipping-addresses', [ShippingAddressController::class, 'index'])->name('shipping_address.index');
    Route::get('/shipping-addresses/create', [ShippingAddressController::class, 'create'])->name('shipping_address.create');
    Route::post('/shipping-addresses', [ShippingAddressController::class, 'store'])->name('shipping_address.store');
    Route::get('/shipping-addresses/{id}/edit', [ShippingAddressController::class, 'edit'])->name('shipping_address.edit');
    Route::put('/shipping-addresses/{id}', [ShippingAddressController::class, 'update'])->name('shipping_address.update');
    Route::delete('/shipping-addresses/{id}', [ShippingAddressController::class, 'destroy'])->name('shipping_address.destroy');
});



//Profile
Route::prefix('customer')->middleware('auth')->name('customer.')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
