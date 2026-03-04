<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;

Auth::routes();

Route::get('/', function () {
    $products = \App\Models\Product::latest()->take(8)->get();
    return view('welcome', compact('products'));
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/cart',           [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add',      [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',   [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',   [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',    [CartController::class, 'clear'])->name('cart.clear');


// ── Admin Routes ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');


    // Products
    Route::resource('products', ProductController::class)->except(['show']);

    // Customers
    Route::get('/customers',               [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}',    [CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');
});