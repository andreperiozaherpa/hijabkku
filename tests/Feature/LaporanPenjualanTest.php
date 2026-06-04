<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\Pembayaran;
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

        $toko = new Toko();
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
        $pembayaran = new Pembayaran();
        $pembayaran->kode_invoice = 'INV-12345';
        $pembayaran->user_id = $admin->id;
        $pembayaran->user_name = $admin->name;
        $pembayaran->total_harga = 100000;
        $pembayaran->pembayaran = 100000;
        $pembayaran->kembalian = 0;
        $pembayaran->save();

        $transaksi = new Transaksi();
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
        $responseSummary = $this->actingAs($admin)->getJson('/laporan/penjualan/show?' . http_build_query([
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
                'total' => ['umum', 'modal_umum', 'grosir', 'modal_grosir']
            ],
            'param',
            'karyawan'
        ]);

        // 2. Test DataTables Server-Side Pagination endpoint
        $responseDataTables = $this->actingAs($admin)->getJson('/laporan/penjualan/show?' . http_build_query([
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
                    'jumlah',
                    'harga',
                    'total'
                ]
            ]
        ]);
    }
}
