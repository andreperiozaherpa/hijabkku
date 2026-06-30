<?php

use App\Http\Controllers\Api\XenditController;
use App\Http\Controllers\BahanBarangController;
use App\Http\Controllers\BukuPanduanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\FotoBarangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\landing\InvoiceController;
use App\Http\Controllers\landing\LandingController;
use App\Http\Controllers\LaporanBarangController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\MerekBarangController;
use App\Http\Controllers\ModelBarangController;
use App\Http\Controllers\PackagingBarangController;
use App\Http\Controllers\PesananPickupController;
use App\Http\Controllers\RevisiPenjualanController;
use App\Http\Controllers\SesiKasirController;
use App\Http\Controllers\StockInOutController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockTokoController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SystemSettingController;
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

Route::get('/', [LandingController::class, 'index']);
Route::get('/catalog', [LandingController::class, 'catalog'])->name('catalog');
Route::get('/invoice/{kode}', [InvoiceController::class, 'show'])->name('invoice');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [LandingController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/refund-policy', [LandingController::class, 'refundPolicy'])->name('refund-policy');

Route::middleware('auth', 'role:gudang|kasir|admin', 'aktifasi:on')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:lihat_dashboard')->name('dashboard');

    Route::prefix('/transaksi')->group(function () {
        Route::prefix('/penjualan')->middleware('permission:proses_transaksi')->group(function () {
            Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.penjualan');
            Route::get('/create', [TransaksiController::class, 'create']);
            Route::post('/store', [TransaksiController::class, 'store']);
            Route::post('/xendit/create', [XenditController::class, 'createInvoice']);

            Route::post('/sesi-kasir/buka', [SesiKasirController::class, 'buka'])->name('sesi_kasir.buka');
            Route::get('/sesi-kasir/summary', [SesiKasirController::class, 'summary'])->name('sesi_kasir.summary');
            Route::post('/sesi-kasir/tutup', [SesiKasirController::class, 'tutup'])->name('sesi_kasir.tutup');
        });

        Route::prefix('/daftar')->middleware('permission:lihat_daftar_penjualan')->group(function () {
            Route::get('/', [TransaksiController::class, 'index_daftar'])->name('transaksi.daftar');
            Route::get('/show', [TransaksiController::class, 'show']);
            Route::get('/show_detail', [TransaksiController::class, 'show_detail']);
            Route::get('/edit', [TransaksiController::class, 'edit']);
            Route::get('/neraca', [TransaksiController::class, 'neraca']);
        });

        Route::prefix('/revisi')->middleware('permission:revisi_penjualan')->group(function () {
            Route::get('/', [RevisiPenjualanController::class, 'index'])->name('revisi.index');
            Route::get('/cari', [RevisiPenjualanController::class, 'cariInvoice'])->name('revisi.cari');
            Route::get('/cari-barang', [RevisiPenjualanController::class, 'cariBarang'])->name('revisi.cari_barang');
            Route::post('/proses', [RevisiPenjualanController::class, 'prosesRevisi'])->name('revisi.proses');
        });

        Route::prefix('/pickup')->middleware('permission:kelola_pesanan_pickup')->group(function () {
            Route::get('/', [PesananPickupController::class, 'index'])->name('transaksi.pickup');
            Route::get('/data', [PesananPickupController::class, 'data'])->name('transaksi.pickup.data');
            Route::get('/{id}/items', [PesananPickupController::class, 'showItems'])->name('transaksi.pickup.items');
            Route::post('/{id}/complete', [PesananPickupController::class, 'complete'])->name('transaksi.pickup.complete');
        });
    });

    Route::prefix('/manajemen')->group(function () {
        Route::prefix('/barang')->middleware('permission:kelola_barang')->group(function () {
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

        Route::prefix('/barang/foto')->middleware('permission:kelola_detail_produk')->group(function () {
            Route::get('/', [FotoBarangController::class, 'index']);
            Route::get('/data', [FotoBarangController::class, 'data']);
            Route::get('/show/{id}', [FotoBarangController::class, 'show']);
            Route::post('/upload', [FotoBarangController::class, 'upload']);
            Route::post('/set-main', [FotoBarangController::class, 'setMain']);
            Route::post('/delete', [FotoBarangController::class, 'delete']);
            Route::post('/verify', [FotoBarangController::class, 'verify']);
            Route::post('/update-desc', [FotoBarangController::class, 'updateDescription']);
        });

        Route::prefix('/supplier')->middleware('permission:kelola_supplier')->group(function () {
            Route::get('/index', [SupplierController::class, 'index']);
            Route::get('/show', [SupplierController::class, 'show']);
            Route::post('/store', [SupplierController::class, 'store']);
            Route::get('/edit', [SupplierController::class, 'edit']);
            Route::post('/update', [SupplierController::class, 'update']);
            Route::post('/destroy', [SupplierController::class, 'destroy']);
        });

        Route::prefix('/stock')->group(function () {
            Route::prefix('/inout')->middleware('permission:kelola_stok_inout')->group(function () {
                Route::get('/index', [StockInOutController::class, 'index']);
                Route::get('/show', [StockInOutController::class, 'show']);
                Route::post('/store', [StockInOutController::class, 'store']);
                Route::get('/edit', [StockInOutController::class, 'edit']);
                Route::post('/update', [StockInOutController::class, 'update']);
                Route::post('/destroy', [StockInOutController::class, 'destroy']);
            });

            Route::prefix('/toko')->middleware('permission:kelola_stok_toko')->group(function () {
                Route::get('/index', [StockTokoController::class, 'index']);
                Route::get('/index/{kode}', [StockTokoController::class, 'index_detail']);
                Route::get('/show', [StockTokoController::class, 'show']);
                Route::get('/show/{kode}', [StockTokoController::class, 'show_detail']);
                Route::get('/create', [StockTokoController::class, 'create']);
                Route::post('/store', [StockTokoController::class, 'store']);
                Route::get('/edit', [StockTokoController::class, 'edit']);
                Route::post('/update', [StockTokoController::class, 'update']);
            });
        });

        Route::prefix('/warehouse')->middleware('permission:kelola_cabang')->group(function () {
            Route::get('/index', [TokoController::class, 'index']);
            Route::get('/show', [TokoController::class, 'show']);
            Route::post('/store', [TokoController::class, 'store']);
            Route::get('/edit', [TokoController::class, 'edit']);
            Route::post('/update', [TokoController::class, 'update']);
            Route::post('/destroy', [TokoController::class, 'destroy']);
        });
    });

    Route::prefix('/buku')->group(function () {
        Route::prefix('/panduan')->middleware('permission:lihat_buku_panduan')->group(function () {
            Route::get('/', [BukuPanduanController::class, 'index']);
            Route::get('/show', [BukuPanduanController::class, 'show']);
            Route::post('/store', [BukuPanduanController::class, 'store']);
            Route::get('/edit', [BukuPanduanController::class, 'edit']);
            Route::post('/update', [BukuPanduanController::class, 'update']);
            Route::post('/destroy', [BukuPanduanController::class, 'destroy']);
        });
    });
    Route::prefix('/laporan')->group(function () {
        Route::prefix('/barang')->middleware('permission:lihat_laporan_penjualan')->group(function () {
            Route::get('/', [LaporanBarangController::class, 'index']);
            Route::get('/show', [LaporanBarangController::class, 'show']);
            Route::get('/detail', [LaporanBarangController::class, 'detail']);
        });

        Route::prefix('/penjualan')->middleware('permission:lihat_laporan_penjualan')->group(function () {
            Route::get('/', [LaporanPenjualanController::class, 'index']);
            Route::get('/show', [LaporanPenjualanController::class, 'show']);
            Route::get('/create', [LaporanPenjualanController::class, 'create']);
            Route::post('/store', [LaporanPenjualanController::class, 'store']);
            Route::get('/edit', [LaporanPenjualanController::class, 'edit']);
            Route::post('/update', [LaporanPenjualanController::class, 'update']);
            Route::post('/destroy', [LaporanPenjualanController::class, 'destroy']);
        });

        Route::prefix('/opname')->middleware('permission:kelola_stock_opname')->group(function () {
            Route::get('/', [StockOpnameController::class, 'index'])->name('stock.opname');
            Route::get('/show', [StockOpnameController::class, 'show']);
            Route::post('/store', [StockOpnameController::class, 'store']);
            Route::delete('/destroy/{id}', [StockOpnameController::class, 'destroy']);
            Route::get('/detail/{id}', [StockOpnameController::class, 'detail'])->name('stock.opname.detail');
            Route::get('/items-data/{id}', [StockOpnameController::class, 'itemsData']);
            Route::post('/start-counting', [StockOpnameController::class, 'startCounting']);
            Route::get('/search-master-products/{id}', [StockOpnameController::class, 'searchMasterProducts']);
            Route::post('/add-master-product/{id}', [StockOpnameController::class, 'addMasterProduct']);
            Route::post('/scan-barcode', [StockOpnameController::class, 'scanBarcode']);
            Route::post('/update-qty-manual', [StockOpnameController::class, 'updateQtyManual']);
            Route::post('/generate-recount', [StockOpnameController::class, 'generateRecount']);
            Route::post('/approve-final', [StockOpnameController::class, 'approveFinal']);
            Route::post('/post-adjustment', [StockOpnameController::class, 'postAdjustment']);
            Route::get('/export/{id}', [StockOpnameController::class, 'export']);
            Route::get('/audit-logs/{id}', [StockOpnameController::class, 'auditLogs']);
        });

        Route::prefix('/sesi-kasir')->middleware('permission:kelola_sesi_kasir')->group(function () {
            Route::get('/', [SesiKasirController::class, 'index'])->name('laporan.sesi_kasir');
            Route::get('/show', [SesiKasirController::class, 'show'])->name('laporan.sesi_kasir.show');
            Route::post('/approve/{id}', [SesiKasirController::class, 'approve'])->name('laporan.sesi_kasir.approve');
            Route::post('/reject/{id}', [SesiKasirController::class, 'reject'])->name('laporan.sesi_kasir.reject');
        });
    });

    Route::prefix('/user')->middleware('permission:kelola_pengguna')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::get('/show', [UserController::class, 'show']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/edit', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/destroy', [UserController::class, 'destroy']);
        Route::post('/toggle-status', [UserController::class, 'toggleStatus']);
        Route::get('/rbac', [UserController::class, 'rbacIndex'])->name('user.rbac');
        Route::post('/rbac/update', [UserController::class, 'rbacUpdate'])->name('user.rbac.update');
        Route::post('/rbac/role', [UserController::class, 'rbacRoleStore'])->name('user.rbac.role.store');
    });

    Route::middleware('permission:kelola_pengguna')->group(function () {
        Route::get('/pengaturan', [SystemSettingController::class, 'index'])->name('user.pengaturan');
        Route::post('/pengaturan/update', [SystemSettingController::class, 'update'])->name('user.pengaturan.update');
    });
});

require __DIR__.'/auth.php';
