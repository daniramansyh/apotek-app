<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Models\Medicine;

// Route::httpMethod('/path', [NamaController::class, 'namaFunc'])->name('identitas_route');
// httpMethod 
// get -> mengambil data/menampilkan halaman
// post -> mengirim data ke database (tambah data)
// patch/put -> mengubah data di database
// delete -> menghapus data

Route::middleware(['IsLogout'])->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [UserController::class, 'loginProses'])->name('login.proses');
});

Route::middleware(['IsLogin'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/landing-page', [LandingPageController::class, 'index'])->name('landing_page');
    Route::middleware('IsAdmin')->group(function () {
        // mengelola data obat
        Route::prefix('/data-obat')->name('data_obat.')->group(function () {
            Route::get('/data', [MedicineController::class, 'index'])->name('data');
            Route::get('/tambah', [MedicineController::class, 'create'])->name('tambah');
            Route::post('/tambah/proses', [MedicineController::class, 'store'])->name('tambah.proses');
            Route::get('/ubah/{id}', [MedicineController::class, 'edit'])->name('ubah');
            Route::patch('/ubah/{id}/proses', [MedicineController::class, 'update'])->name('ubah.proses');
            Route::delete('/hapus/{id}', [MedicineController::class, 'destroy'])->name('hapus');
            Route::patch('/ubah/stok/{id}', [MedicineController::class, 'updateStock'])->name('ubah.stock');
            Route::get('export-excel', [MedicineController::class, 'exportExcel'])->name('export-excel');
        });
        // mengelola akun
        Route::prefix('/kelola-akun')->name('kelola_akun.')->group(function () {
            Route::get('/data', [UserController::class, 'index'])->name('data');
            Route::get('/tambah', [UserController::class, 'create'])->name('tambah');
            Route::post('/tambah/proses', [UserController::class, 'store'])->name('tambah.proses');
            Route::get('/ubah/{id}', [UserController::class, 'edit'])->name('ubah');
            Route::patch('/ubah/{id}/proses', [UserController::class, 'update'])->name('ubah.proses');
            Route::delete('/hapus/{id}', [UserController::class, 'destroy'])->name('hapus');
            Route::get('export-excel', [UserController::class, 'exportExcel'])->name('export-excel');
        });
        Route::prefix('/order')->name('order.')->group(function () {
            Route::get('/data', [OrderController::class, 'data'])->name('data');
            Route::get('/download/{id}', [OrderController::class, 'downloadPDF'])->name('download');
            Route::get('export-excel', [OrderController::class, 'exportExcel'])->name('export-excel');
        });
    });

    Route::middleware('IsKasir')->group(function () {
        Route::prefix('/kasir')->name('kasir.')->group(function () {
            Route::get('/order', [OrderController::class, 'index'])->name('order');
            Route::get('/tambah', [OrderController::class, 'create'])->name('order.tambah');
            Route::post('/tambah/proses', [OrderController::class, 'store'])->name('order.tambah.proses');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('order.print');
            Route::get('/download/{id}', [OrderController::class, 'downloadPDF'])->name('download');
            Route::delete('/order/hapus/{id}', [OrderController::class, 'destroy'])->name('order.hapus');
        });
    });
});
