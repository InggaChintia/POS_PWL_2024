<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\StokController;



//JOBSHEET 7 TUGAS 4
Route::get('registrasi', [RegistrasiController::class, 'registrasi'])->name('registrasi');
Route::post('registrasi', [RegistrasiController::class, 'store']);


//JOBSHEET 7 PRAKTIKUM 1
Route::pattern('id', '[0-9]+'); //artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function() { // semua route di dalam group harus login dulu
    // masukkan semua route yang perlu autentikasi di sini
    // JS 7 PRAKTIKUM 2
    Route::get('/', [WelcomeController::class, 'index']);
    // route level

    //JOBSHEET 5 TUGAS PRAKTIKUM m_level
    //JS 7 PRAKTIKUM 2
    //artinya semua route di dalam group ini harus punya role ADM (administrator)
    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
    //Route::middleware(['authorize:ADM'])->group(function() {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']);
        Route::get('/create', [LevelController::class, 'create']);
        Route::post('/', [LevelController::class, 'store']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}', [LevelController::class, 'show']); 
        Route::get('/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/{id}', [LevelController::class, 'update']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
        Route::post('/{id}', [LevelController::class, 'destroy']);
    });


    //JOBSHEET 5 TUGAS PRAKTIKUM m_kategori
    //artinya semua route di dalam group ini harus punya role ADM (administrator) dan MNG (Manager)
    Route::group(['prefix' => 'kategori', 'middleware' => 'authorize:ADM,MNG'], function () {
        Route::get('/', [KategoriController::class, 'index']);
        Route::post('/list', [KategoriController::class, 'list']);
        Route::get('/create', [KategoriController::class, 'create']);
        Route::post('/', [KategoriController::class, 'store']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);
        Route::get('/{id}', [KategoriController::class, 'show']);
        Route::get('/{id}/edit', [KategoriController::class, 'edit']);
        Route::put('/{id}', [KategoriController::class, 'update']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
        //Route::post('/{id}', [KategoriController::class, 'destroy']);
    });

    
    //JOBSHEET 6 PRAKTIKUM 1, 2, 3
    //artinya semua route di dalam group ini harus punya role ADM (administrator)
    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [UserController::class, 'index']);          // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);      // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/create', [UserController::class, 'create']);   // menampilkan halaman form tambah user
        Route::post('/', [UserController::class, 'store']);         // menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);   // menampilkan halaman form tambah user ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);         // menyimpan data user baru ajax
        Route::get('/{id}', [UserController::class, 'show']);       // menampilkan detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user
        Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);   //menampilkan halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);  //menyimpan perubahan data user Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);  //untuk menampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); //untuk hapus data user Ajax
        Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
    });

    //JOBSHEET 5 TUGAS PRAKTIKUM m_barang
    //JS 7 PRAKTIKUM 3
     //artinya semua route di dalam group ini harus punya role ADM (administrator) dan MNG (Manager)
    Route::group(['prefix' => 'barang', 'middleware' => 'authorize:ADM,MNG'], function () {
    //Route::middleware(['authorize:ADM,MNG'])->group(function() {
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list', [BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']);
        Route::post('/', [BarangController::class, 'store']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/barang_ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
        Route::get('/import',[BarangController::class,'import']); //ajax form upload excel
        Route::post('/import_ajax',[BarangController::class,'import_ajax']); //ajax import excel
        Route::get('/export_excel',[BarangController::class,'export_excel']); //expost excel
        Route::get('/export_pdf',[BarangController::class,'export_pdf']); //export pdf
        Route::post('/{id}', [BarangController::class, 'destroy']);
    });


    //JOBSHEET 5 TUGAS PRAKTIKUM m_supplier
    //artinya semua route di dalam group ini harus punya role ADM (administrator)
    Route::group(['prefix' => 'supplier', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/list', [SupplierController::class, 'list']);
        Route::get('/create', [SupplierController::class, 'create']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);
        Route::put('/{id}', [SupplierController::class, 'update']);
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
        Route::post('/{id}', [SupplierController::class, 'destroy']);
    });

    //STOK BARANG
    Route::prefix('stok')->group(function () {
        Route::get('/', [StokController::class, 'index'])->name('stok.index');
        Route::get('/create', [StokController::class, 'create'])->name('stok.create');
        Route::post('/store', [StokController::class, 'store'])->name('stok.store');
        Route::get('/reduce/{id}', [StokController::class, 'reduce'])->name('stok.reduce');
        Route::put('/update/{id}', [StokController::class, 'update'])->name('stok.update');
    });


    //JOBSHEET 5 PRAKTIKUM 3
    //Route::delete('/{id}', [UserController::class, 'destroy']);

    //JOBSHEET 5 PRAKTIKUM 2
    //Route::get('/', [WelcomeController::class, 'index']);

    // Route::group(['prefix' => 'user'], function() {
    //     Route::get('/', [UserController::class, 'index']);              //menampilkan halaman awal user
    //     Route::post('/list', [UserController::class, 'list']);          //menampilkan data user dalam bentuk json untuk datatables
    //     Route::get('/create', [UserController::class, 'create']);       //manampilkan halaman form tambah user
    //     Route::post('/', [UserController::class, 'store']);             //menyimpan data user baru
    //     Route::get('/{id}', [UserController::class, 'show']);         //menampilkan detail user
    //     Route::get('/{id}/edit', [UserController::class, 'edit']);         //menampilkan halaman form edit user
    //     Route::put('/{id}', [UserController::class, 'update']);       //menyimpan perubahan data user
    //     Route::delete('/{id}', [UserController::class, 'destroy']);   //menghapus data user
    //     Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax
    //     Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
    //     Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
    //     Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
    // });

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
