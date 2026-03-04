<?php

// ── Full routes/web.php ──
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
    Route::resource('products', ProductController::class)->except(['show']);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Customers
    Route::get('/customers',                     [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}',          [CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');

});