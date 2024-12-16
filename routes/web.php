<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Livewire\Admin\Brand\Index as BrandIndex;
use App\Http\Livewire\Admin\Color\Index as ColorIndex;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\UserController as FrontendUserController;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::controller(FrontendController::class)->group(function() {
    Route::get('/', 'index');

    // Collections
    Route::get('/collections', 'categories');
    Route::get('/collections/{category_slug}', 'products');
    Route::get('/collections/{category_slug}/{product_slug}', 'productView');

    // New Arrivals
    Route::get('/new-arrivals', 'newArrival');

    // Featured Products
    Route::get('/featured-products', 'featuredProducts');

    // Search Bar
    Route::get('search', 'searchProducts');
});

Route::middleware(['auth'])->group(function () {

    Route::controller(WishlistController::class)->group(function() {
        Route::get('wishlist', 'index');
    });

    Route::controller(CartController::class)->group(function() {
        Route::get('cart', 'index');
    });

    Route::controller(CheckoutController::class)->group(function() {
        Route::get('checkout', 'index');
    });

    Route::controller(OrderController::class)->group(function() {
        Route::get('orders', 'index');
        Route::get('orders/{orderId}', 'show');
    });

    Route::controller(FrontendUserController::class)->group(function() {
        Route::get('profile', 'index');
        Route::post('profile', 'update');
        Route::get('change-password', 'passwordCreate');
        Route::post('change-password', 'changePassword');
    });



});

Route::get('/thank-you', [FrontendController::class, 'thankyou']);


/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware('auth', 'isAdmin')->group(function(){

    // Dashboard Route
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Site Setting Route
    Route::get('settings', [SettingController::class, 'index']);
    Route::post('settings', [SettingController::class, 'store']);

    // Category Routes
    Route::controller(CategoryController::class)->group(function() {
        Route::get('category', 'index');
        Route::get('category/create', 'create');
        Route::post('category', 'store');
        Route::get('category/{category}/edit/', 'edit');
        Route::put('category/{category}', 'update');
        Route::get('category/delete/{id}', 'destroy');
    });

    // Product Routes
    Route::controller(ProductController::class)->group(function() {
        Route::get('product', 'index');
        Route::get('product/create', 'create');
        Route::post('product', 'store');
        Route::get('product/edit/{id}', 'edit');
        Route::put('product/{id}', 'update');
        Route::get('product/delete/{id}', 'destroy');
        Route::get('product-image/delete/{id}', 'destroyImage');
        // Product Color Routes
        Route::post('product-color/{product_color_id}', 'updateProductColorQty');
        Route::get('product-color/delete/{product_color_id}', 'deleteProductColor');
    });

    // Slider Routes
    Route::controller(SliderController::class)->group(function() {
        Route::get('slider', 'index');
        Route::get('slider/create', 'create');
        Route::post('slider', 'store');
        Route::get('slider/edit/{id}', 'edit');
        Route::put('slider/{id}', 'update');
        Route::get('slider/delete/{id}', 'destroy');
    });

    // Order Routes
    Route::controller(AdminOrder::class)->group(function() {
        Route::get('orders', 'index');
        Route::get('orders/{orderId}', 'show');
        Route::put('orders/{orderId}', 'updateOrderStatus');

        // Generate Invoice
        Route::get('invoice/{orderId}', 'viewInvoice');
        Route::get('invoice/{orderId}/generate', 'generateInvoice');
        Route::get('invoice/{orderId}/mail', 'mailInvoice');
    });

    // User Routes
    Route::controller(UserController::class)->group(function() {
        Route::get('users', 'index');
        Route::get('users/create', 'create');
        Route::post('users', 'store');
        Route::get('users/edit/{id}', 'edit');
        Route::put('users/{id}', 'update');
        Route::get('users/delete/{id}', 'destroy');
    });

/*
|--------------------------------------------------------------------------
| Livewire Routes
|--------------------------------------------------------------------------
*/
    // Brand Route
    Route::get('brands', BrandIndex::class);

    // Color Route
    Route::get('colors', ColorIndex::class);

});
