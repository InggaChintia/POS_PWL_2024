<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('ViewProduct')->group(function() {
    Route::get('/', [ProductController::class, 'index'])->name('ViewProduct');
    Route::get('/category/food-beverage', [ProductController::class, 'foodBeverage'])->name('food-beverage');
    Route::get('/category/beauty-health', [ProductController::class, 'beautyHealth'])->name('beauty-health');
    Route::get('/category/home-care', [ProductController::class, 'homeCare'])->name('home-care');
    Route::get('/category/baby-kid', [ProductController::class, 'babyKid'])->name('baby-kid');
});

Route::get('/ViewUser/{id}/name/{name}', [UserController::class, 'cust'])->name('cust');

Route::get('/ViewSales', [SalesController::class, 'index'])->name('index');



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
