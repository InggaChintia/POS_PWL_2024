<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WelcomeController;


//JOBSHEET 5 PRAKTIKUM 3
Route::delete('/{id}', [UserController::class, 'destroy']);

//JOBSHEET 5 PRAKTIKUM 2
Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'index']);              //menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);          //menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);       //manampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);             //menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);         //menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);         //menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);       //menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']);   //menghapus data user
});


//JOBSHEET 4 PRAKTIKUM 2.6
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);


//JOBSHEET 3 PRAKTIKUM 6
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);
// Route::get('/user', [UserController::class, 'index']);


//JOBSHEET 3 PRAKTIKUM 5
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);


//JOBSHEET 3 PRAKTIKUM 4
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);


// Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::prefix('ViewProduct')->group(function() {
//     Route::get('/', [ProductController::class, 'index'])->name('ViewProduct');
//     Route::get('/category/food-beverage', [ProductController::class, 'foodBeverage'])->name('food-beverage');
//     Route::get('/category/beauty-health', [ProductController::class, 'beautyHealth'])->name('beauty-health');
//     Route::get('/category/home-care', [ProductController::class, 'homeCare'])->name('home-care');
//     Route::get('/category/baby-kid', [ProductController::class, 'babyKid'])->name('baby-kid');
// });

// Route::get('/ViewUser/{id}/name/{name}', [UserController::class, 'cust'])->name('cust');

// Route::get('/ViewSales', [SalesController::class, 'index'])->name('index');



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
