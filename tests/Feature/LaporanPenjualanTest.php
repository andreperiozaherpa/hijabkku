<?php

namespace Tests\Feature;

use App\Models\Pembayaran;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPenjualanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RBACSeeder::class);

        $toko = new Toko;
        $toko->kode = 'TK_test';
        $toko->nama_toko = 'Test Toko';
        $toko->save();
    }

    public function test_admin_can_access_laporan_penjualan_index()
    {
        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($admin)->get('/laporan/penjualan');
        $response->assertStatus(200);
    }

    public function test_laporan_penjualan_summary_and_datatables_endpoints()
    {
        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // Create mock transaction & payment
        $pembayaran = new Pembayaran;
        $pembayaran->kode_invoice = 'INV-12345';
        $pembayaran->user_id = $admin->id;
        $pembayaran->user_name = $admin->name;
        $pembayaran->total_harga = 100000;
        $pembayaran->pembayaran = 100000;
        $pembayaran->kembalian = 0;
        $pembayaran->save();

        $transaksi = new Transaksi;
        $transaksi->kode_invoice = 'INV-12345';
        $transaksi->kode_toko = 'TK_test';
        $transaksi->kode_barang = 'BRG-01';
        $transaksi->nama_barang = 'Test Hijab';
        $transaksi->metode = 'umum';
        $transaksi->jumlah = 2;
        $transaksi->harga = 50000;
        $transaksi->harga_beli = 30000;
        $transaksi->harga_total = 100000;
        $transaksi->save();

        // 1. Test Summary (KPI & Chart) endpoint
        $responseSummary = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query([
            'param' => 'hari',
            'date' => date('Y-m-d'),
            'toko' => 'TK_test',
            'karyawan' => 'semua',
        ]));

        $responseSummary->assertStatus(200);
        $responseSummary->assertJsonStructure([
            'data' => [
                'laporan',
                'counts' => ['umum', 'grosir'],
                'total' => ['umum', 'modal_umum', 'grosir', 'modal_grosir'],
            ],
            'param',
            'karyawan',
        ]);

        // 2. Test DataTables Server-Side Pagination endpoint
        $responseDataTables = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query([
            'draw' => '1',
            'start' => '0',
            'length' => '25',
            'metode' => 'umum',
            'param' => 'hari',
            'date' => date('Y-m-d'),
            'toko' => 'TK_test',
            'karyawan' => 'semua',
        ]));

        $responseDataTables->assertStatus(200);
        $responseDataTables->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'tanggal',
                    'kode_invoice',
                    'user_name',
                    'nama_barang',
                    'metode',
                    'metode_pembayaran',
                    'jumlah',
                    'harga',
                    'total',
                ],
            ],
        ]);
    }

    public function test_payment_method_filter_separates_cash_from_non_cash()
    {
        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // Cash transaction (TUNAI)
        $pembayaranTunai = new Pembayaran;
        $pembayaranTunai->kode_invoice = 'INV-CASH-001';
        $pembayaranTunai->user_id = $admin->id;
        $pembayaranTunai->user_name = $admin->name;
        $pembayaranTunai->total_harga = 100000;
        $pembayaranTunai->pembayaran = 100000;
        $pembayaranTunai->kembalian = 0;
        $pembayaranTunai->metode_pembayaran = 'TUNAI';
        $pembayaranTunai->save();

        $transaksiTunai = new Transaksi;
        $transaksiTunai->kode_invoice = 'INV-CASH-001';
        $transaksiTunai->kode_toko = 'TK_test';
        $transaksiTunai->kode_barang = 'BRG-01';
        $transaksiTunai->nama_barang = 'Hijab Cash';
        $transaksiTunai->metode = 'umum';
        $transaksiTunai->jumlah = 2;
        $transaksiTunai->harga = 50000;
        $transaksiTunai->harga_beli = 30000;
        $transaksiTunai->harga_total = 100000;
        $transaksiTunai->save();

        // Non-cash transaction (QRIS)
        $pembayaranQris = new Pembayaran;
        $pembayaranQris->kode_invoice = 'INV-QRIS-001';
        $pembayaranQris->user_id = $admin->id;
        $pembayaranQris->user_name = $admin->name;
        $pembayaranQris->total_harga = 50000;
        $pembayaranQris->pembayaran = 50000;
        $pembayaranQris->kembalian = 0;
        $pembayaranQris->metode_pembayaran = 'QRIS';
        $pembayaranQris->save();

        $transaksiQris = new Transaksi;
        $transaksiQris->kode_invoice = 'INV-QRIS-001';
        $transaksiQris->kode_toko = 'TK_test';
        $transaksiQris->kode_barang = 'BRG-02';
        $transaksiQris->nama_barang = 'Hijab QRIS';
        $transaksiQris->metode = 'umum';
        $transaksiQris->jumlah = 1;
        $transaksiQris->harga = 50000;
        $transaksiQris->harga_beli = 20000;
        $transaksiQris->harga_total = 50000;
        $transaksiQris->save();

        $baseQuery = [
            'param' => 'hari',
            'date' => date('Y-m-d'),
            'toko' => 'TK_test',
            'karyawan' => 'semua',
        ];

        // Summary: cash only -> total umum = 100000
        $responseCash = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query($baseQuery + [
            'metode_pembayaran' => 'cash',
        ]));
        $responseCash->assertStatus(200);
        $responseCash->assertJsonPath('data.total.umum', 100000);
        $responseCash->assertJsonPath('data.counts.umum', 1);

        // Summary: non-cash only -> total umum = 50000
        $responseNonCash = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query($baseQuery + [
            'metode_pembayaran' => 'non-cash',
        ]));
        $responseNonCash->assertStatus(200);
        $responseNonCash->assertJsonPath('data.total.umum', 50000);
        $responseNonCash->assertJsonPath('data.counts.umum', 1);

        // Summary: all -> total umum = 150000
        $responseAll = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query($baseQuery));
        $responseAll->assertStatus(200);
        $responseAll->assertJsonPath('data.total.umum', 150000);
        $responseAll->assertJsonPath('data.counts.umum', 2);

        // DataTables: cash filter returns only TUNAI rows
        $responseTableCash = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query($baseQuery + [
            'draw' => '1',
            'start' => '0',
            'length' => '25',
            'metode' => 'umum',
            'metode_pembayaran' => 'cash',
        ]));
        $responseTableCash->assertStatus(200);
        $responseTableCash->assertJsonPath('recordsFiltered', 1);
        $responseTableCash->assertJsonPath('data.0.metode_pembayaran', 'TUNAI');

        // DataTables: non-cash filter returns only QRIS rows
        $responseTableNonCash = $this->actingAs($admin)->getJson('/laporan/penjualan/show?'.http_build_query($baseQuery + [
            'draw' => '1',
            'start' => '0',
            'length' => '25',
            'metode' => 'umum',
            'metode_pembayaran' => 'non-cash',
        ]));
        $responseTableNonCash->assertStatus(200);
        $responseTableNonCash->assertJsonPath('recordsFiltered', 1);
        $responseTableNonCash->assertJsonPath('data.0.metode_pembayaran', 'QRIS');
    }
}
