<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;

Auth::routes();

Route::get("/", function(){

    return view("welcome");

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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