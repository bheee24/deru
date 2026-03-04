<?php

// ── Full routes/web.php ──
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\AdminOrderController;

// ── Welcome (passes products + categories to view) ──
Route::get('/', function () {
    $categories = \App\Models\Category::withCount('products')->ordered()->get();

    $productsQuery = \App\Models\Product::with('category')->latest();

    if (request('category')) {
        $productsQuery->whereHas('category', function ($q) {
            $q->where('slug', request('category'));
        });
    }

    $products = $productsQuery->take(8)->get();

    return view('welcome', compact('products', 'categories'));
});

Auth::routes();

Route::get('/home', function () {
    $orders = auth()->user()
                    ->orders()
                    ->with('items')
                    ->latest()
                    ->get();
    return view('home', compact('orders'));
})->middleware('auth')->name('home');

Route::get('/products/{product:slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');


// ── Cart (session-based, guests + logged in) ──
Route::get('/cart',           [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add',      [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',   [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',   [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',    [CartController::class, 'clear'])->name('cart.clear');

// ── Checkout (requires login) ──
Route::get('/checkout', function () {
    return view('checkout');
})->middleware('auth')->name('checkout');

// ── Admin Routes ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class)->except(['show']);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Customers
    Route::get('/customers',                     [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}',          [CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');


    // Orders 
    Route::get('/orders',           [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',   [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout',              [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout',             [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    Route::post('/checkout/update-intent',  [CheckoutController::class, 'updateIntent'])->name('checkout.updateIntent');

    Route::patch('/profile/info',     [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::patch('/profile/address',  [ProfileController::class, 'updateAddress'])->name('profile.address');
    Route::patch('/profile/email',    [ProfileController::class, 'updateEmail'])->name('profile.email');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Customer order detail
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

});