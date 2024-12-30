<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\DashboardContoller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [HomeController::class, 'detail'])->name('product.detail');
Route::get('/product', [HomeController::class, 'search'])->name('product.search');
Route::get('/testimoni', [HomeController::class, 'testimoni'])->name('testimoni');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/category/data', [CategoryController::class, 'data'])->name('category.data');
    Route::resource('/category', CategoryController::class);

    Route::get('/product/data', [ProductController::class, 'data'])->name('product.data');
    Route::resource('/product', ProductController::class);

    Route::get('/order/data', [OrderController::class, 'data'])->name('order.data');
    Route::resource('/order', OrderController::class);

    Route::get('/comment/data', [CommentController::class, 'data'])->name('comment.data');
    Route::resource('/comment', CommentController::class);
});

Route::middleware('auth')->group(function () {

    Route::get('/order', [HomeController::class, 'order'])->name('order');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/addcart', [CartController::class, 'addCart'])->name('cart.add');
    Route::delete('/delete-cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::post('/comment', [HomeController::class, 'createComment'])->name('create.comment');
});

require __DIR__ . '/auth.php';
