<?php

use App\Http\Controllers\BahanBarangController;
use App\Http\Controllers\BukuPanduanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\MerekBarangController;
use App\Http\Controllers\ModelBarangController;
use App\Http\Controllers\PackagingBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockInOutController;
use App\Http\Controllers\StockTokoController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UkuranBarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariasiBarangController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth', 'role:gudang|kasir|admin', 'aktifasi:on')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/transaksi')->middleware('role:admin|kasir')->group(function () {
        Route::prefix('/penjualan')->group(function () {
            Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.penjualan');
            Route::get('/create', [TransaksiController::class, 'create']);
            Route::post('/store', [TransaksiController::class, 'store']);
        });

        Route::prefix('/daftar')->group(function () {
            Route::get('/', [TransaksiController::class, 'index_daftar'])->name('transaksi.daftar');
            Route::get('/show', [TransaksiController::class, 'show']);
            Route::get('/show_detail', [TransaksiController::class, 'show_detail']);
            Route::get('/edit', [TransaksiController::class, 'edit']);
            Route::get('/neraca', [TransaksiController::class, 'neraca']);
        });
    });

    Route::prefix('/manajemen')->middleware('role:admin')->group(function () {
        Route::prefix('/barang')->group(function () {
            Route::prefix('/data')->group(function () {
                Route::get('/', [DataBarangController::class, 'index']);
                Route::get('/show', [DataBarangController::class, 'show']);
                Route::post('/store', [DataBarangController::class, 'store']);
                Route::post('/update', [DataBarangController::class, 'update']);
                Route::get('/edit', [DataBarangController::class, 'edit']);
                Route::post('/destroy', [DataBarangController::class, 'destroy']);
            });
            Route::prefix('/jenis')->group(function () {
                Route::get('/', [JenisBarangController::class, 'index']);
                Route::get('/show', [JenisBarangController::class, 'show']);
                Route::post('/store', [JenisBarangController::class, 'store']);
                Route::post('/destroy', [JenisBarangController::class, 'destroy']);
            });
            Route::prefix('/merek')->group(function () {
                Route::get('/', [MerekBarangController::class, 'index']);
                Route::get('/show', [MerekBarangController::class, 'show']);
                Route::post('/store', [MerekBarangController::class, 'store']);
                Route::post('/destroy', [MerekBarangController::class, 'destroy']);
            });
            Route::prefix('/model')->group(function () {
                Route::get('/', [ModelBarangController::class, 'index']);
                Route::get('/show', [ModelBarangController::class, 'show']);
                Route::post('/store', [ModelBarangController::class, 'store']);
                Route::post('/destroy', [ModelBarangController::class, 'destroy']);
            });
            Route::prefix('/bahan')->group(function () {
                Route::get('/', [BahanBarangController::class, 'index']);
                Route::get('/show', [BahanBarangController::class, 'show']);
                Route::post('/store', [BahanBarangController::class, 'store']);
                Route::post('/destroy', [BahanBarangController::class, 'destroy']);
            });
            Route::prefix('/variasi')->group(function () {
                Route::get('/', [VariasiBarangController::class, 'index']);
                Route::get('/show', [VariasiBarangController::class, 'show']);
                Route::post('/store', [VariasiBarangController::class, 'store']);
                Route::post('/destroy', [VariasiBarangController::class, 'destroy']);
            });
            Route::prefix('/ukuran')->group(function () {
                Route::get('/', [UkuranBarangController::class, 'index']);
                Route::get('/show', [UkuranBarangController::class, 'show']);
                Route::post('/store', [UkuranBarangController::class, 'store']);
                Route::post('/destroy', [UkuranBarangController::class, 'destroy']);
            });
            Route::prefix('/packaging')->group(function () {
                Route::get('/', [PackagingBarangController::class, 'index']);
                Route::get('/show', [PackagingBarangController::class, 'show']);
                Route::post('/store', [PackagingBarangController::class, 'store']);
                Route::post('/destroy', [PackagingBarangController::class, 'destroy']);
            });
        });

        Route::prefix('/supplier')->group(function () {
            Route::get('/index', [SupplierController::class, 'index']);
            Route::get('/show', [SupplierController::class, 'show']);
            Route::post('/store', [SupplierController::class, 'store']);
            Route::get('/edit', [SupplierController::class, 'edit']);
            Route::post('/update', [SupplierController::class, 'update']);
            Route::post('/destroy', [SupplierController::class, 'destroy']);
        });

        Route::prefix('/stock')->group(function () {
            Route::prefix('/inout')->group(function () {
                Route::get('/index', [StockInOutController::class, 'index']);
                Route::get('/show', [StockInOutController::class, 'show']);
                Route::post('/store', [StockInOutController::class, 'store']);
                Route::get('/edit', [StockInOutController::class, 'edit']);
                Route::post('/update', [StockInOutController::class, 'update']);
                Route::post('/destroy', [StockInOutController::class, 'destroy']);
            });

            Route::prefix('/toko')->group(function () {
                Route::get('/index', [StockTokoController::class, 'index']);
                Route::get('/index/{kode}', [StockTokoController::class, 'index_detail']);
                Route::get('/show', [StockTokoController::class, 'show']);
                Route::get('/show/{kode}', [StockTokoController::class, 'show_detail']);
                Route::get('/create', [StockTokoController::class, 'create']);
                Route::post('/store', [StockTokoController::class, 'store']);
                Route::get('/edit', [StockTokoController::class, 'edit']);
                Route::post('/update', [StockTokoController::class, 'update']);
                // Route::post('/destroy', [StockTokoController::class, 'destroy']);
            });
        });

        Route::prefix('/warehouse')->group(function () {
            Route::get('/index', [TokoController::class, 'index']);
            Route::get('/show', [TokoController::class, 'show']);
            Route::post('/store', [TokoController::class, 'store']);
            Route::get('/edit', [TokoController::class, 'edit']);
            Route::post('/update', [TokoController::class, 'update']);
            Route::post('/destroy', [TokoController::class, 'destroy']);
        });
    });

    Route::prefix('/buku')->group(function () {
        Route::prefix('/panduan')->group(function () {
            Route::get('/', [BukuPanduanController::class, 'index']);
            Route::get('/show', [BukuPanduanController::class, 'show']);
            Route::post('/store', [BukuPanduanController::class, 'store']);
            Route::get('/edit', [BukuPanduanController::class, 'edit']);
            Route::post('/update', [BukuPanduanController::class, 'update']);
            Route::post('/destroy', [BukuPanduanController::class, 'destroy']);
        });
    });
    Route::prefix('/laporan')->group(function () {
        Route::prefix('/barang')->group(function () {
            Route::get('/', [BukuPanduanController::class, 'index']);
            Route::get('/show', [BukuPanduanController::class, 'show']);
            Route::post('/store', [BukuPanduanController::class, 'store']);
            Route::get('/edit', [BukuPanduanController::class, 'edit']);
            Route::post('/update', [BukuPanduanController::class, 'update']);
            Route::post('/destroy', [BukuPanduanController::class, 'destroy']);
        });

        Route::prefix('/penjualan')->group(function () {
            Route::get('/', [LaporanPenjualanController::class, 'index']);
            Route::get('/show', [LaporanPenjualanController::class, 'show']);
            Route::get('/create', [LaporanPenjualanController::class, 'create']);
            Route::post('/store', [LaporanPenjualanController::class, 'store']);
            Route::get('/edit', [LaporanPenjualanController::class, 'edit']);
            Route::post('/update', [LaporanPenjualanController::class, 'update']);
            Route::post('/destroy', [LaporanPenjualanController::class, 'destroy']);
        });
    });

    Route::prefix('/user')->middleware('role:admin')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::get('/show', [UserController::class, 'show']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/edit', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/destroy', [UserController::class, 'destroy']);
    });
});

require __DIR__ . '/auth.php';
