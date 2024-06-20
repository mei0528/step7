<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/(root)', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/list', [App\Http\Controllers\ProductController::class, 'index'])->name('list');
Route::get('/Product', [App\Http\Controllers\ProductController::class, 'getList'])->name('products');
Route::get('/regist',[App\Http\Controllers\ProductController::class, 'showRegistForm'])->name('regist');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'index'])->name('products.search');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.delete');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::resource('/products', ProductController::class);
