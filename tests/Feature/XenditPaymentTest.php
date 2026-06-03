<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DataBarang;
use App\Models\StockToko;
use App\Models\Toko;
use App\Models\PendingTransaction;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;

class XenditPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        putenv('XENDIT_SECRET_KEY=xnd_test_mock');
        putenv('XENDIT_WEBHOOK_TOKEN=webhook_test_mock');
        
        $this->seed(\Database\Seeders\RBACSeeder::class);
        
        $toko = new Toko();
        $toko->kode = 'TK_XENDIT';
        $toko->nama_toko = 'Toko Xendit Test';
        $toko->save();

        $barang = new DataBarang();
        $barang->kode = 'BRG_XENDIT_1';
        $barang->nama_barang = 'Hijab Xendit';
        $barang->harga_beli = '10000';
        $barang->harga_jual = '20000';
        $barang->harga_grosir = '18000';
        $barang->jenis_barang = 'Hijab';
        $barang->save();

        $stock = new StockToko();
        $stock->kode_input = 'INPUT_01';
        $stock->kode_toko = 'TK_XENDIT';
        $stock->kode_barang = 'BRG_XENDIT_1';
        $stock->nama_barang = 'Hijab Xendit';
        $stock->jumlah = 10;
        $stock->terjual = 0;
        $stock->supplier = 'SUPP_01';
        $stock->save();
    }

    public function test_can_create_qris_invoice_with_gross_up_fee()
    {
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'inv_test_qris_123',
                'invoice_url' => 'https://checkout.xendit.co/web/inv_test_qris_123'
            ], 200),
        ]);

        $user = User::factory()->create(['role' => 'kasir', 'kode_toko' => 'TK_XENDIT', 'status' => 'on']);

        $response = $this->actingAs($user)->post('/transaksi/penjualan/xendit/create', [
            'invoice' => 'INV-XENDIT-001',
            'total_harga' => 100000,
            'pembayaran' => 'QRIS',
            'data' => [
                [
                    'nomor_paket' => 'BRG_XENDIT_1',
                    'nama_barang' => 'Hijab Xendit',
                    'jumlah_barang' => 5,
                    'method' => 'umum',
                    'harga_item' => 20000,
                    'harga_jual' => 100000
                ]
            ]
        ]);

        // Gross-up QRIS: 100000 / 0.993 = 100704.93 -> 100705
        $response->assertJson([
            'success' => true,
            'grand_total' => 100705,
            'fee' => 705,
            'checkout_url' => 'https://checkout.xendit.co/web/inv_test_qris_123'
        ]);

        $this->assertDatabaseHas('pending_transactions', [
            'kode_invoice' => 'INV-XENDIT-001',
            'payment_method' => 'QRIS',
            'fee' => 705,
            'grand_total' => 100705,
            'status' => 'PENDING'
        ]);
    }

    public function test_can_create_va_invoice_with_flat_fee()
    {
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'inv_test_va_123',
                'invoice_url' => 'https://checkout.xendit.co/web/inv_test_va_123'
            ], 200),
        ]);

        $user = User::factory()->create(['role' => 'kasir', 'kode_toko' => 'TK_XENDIT', 'status' => 'on']);

        $response = $this->actingAs($user)->post('/transaksi/penjualan/xendit/create', [
            'invoice' => 'INV-XENDIT-002',
            'total_harga' => 100000,
            'pembayaran' => 'VA',
            'data' => [
                [
                    'nomor_paket' => 'BRG_XENDIT_1',
                    'nama_barang' => 'Hijab Xendit',
                    'jumlah_barang' => 5,
                    'method' => 'umum',
                    'harga_item' => 20000,
                    'harga_jual' => 100000
                ]
            ]
        ]);

        // Flat VA: 100000 + 4500 + 540 = 105040
        $response->assertJson([
            'success' => true,
            'grand_total' => 105040,
            'fee' => 5040,
        ]);
    }

    public function test_xendit_webhook_processes_pending_transaction()
    {
        // First create the pending transaction
        PendingTransaction::create([
            'kode_invoice' => 'INV-WEBHOOK-001',
            'kode_toko' => 'TK_XENDIT',
            'user_id' => 1,
            'user_name' => 'Test User',
            'total_harga' => 20000,
            'fee' => 5040,
            'grand_total' => 25040,
            'payment_method' => 'VA',
            'xendit_id' => 'inv_x_123',
            'status' => 'PENDING',
            'cart_payload' => json_encode([
                [
                    'nomor_paket' => 'BRG_XENDIT_1',
                    'nama_barang' => 'Hijab Xendit',
                    'jumlah_barang' => 1,
                    'method' => 'umum',
                    'harga_item' => 20000,
                    'harga_jual' => 20000
                ]
            ])
        ]);

        // Hit Webhook
        $token = env('XENDIT_WEBHOOK_TOKEN') ?: 'webhook_test_mock';
        $response = $this->postJson('/api/xendit/webhook', [
            'external_id' => 'INV-WEBHOOK-001',
            'status' => 'PAID',
            'id' => 'inv_x_123'
        ], [
            'x-callback-token' => $token
        ]);

        $response->assertStatus(200);

        // Check if status updated
        $this->assertDatabaseHas('pending_transactions', [
            'kode_invoice' => 'INV-WEBHOOK-001',
            'status' => 'PAID'
        ]);

        // Check if actual stock was deducted (10 -> 1 sold = 9 left, terjual 1)
        $this->assertDatabaseHas('stock_tokos', [
            'kode_barang' => 'BRG_XENDIT_1',
            'terjual' => 1
        ]);

        // Check if transaksi was created
        $this->assertDatabaseHas('transaksis', [
            'kode_invoice' => 'INV-WEBHOOK-001',
            'kode_barang' => 'BRG_XENDIT_1'
        ]);

        // Check if pembayaran was created
        $this->assertDatabaseHas('pembayarans', [
            'kode_invoice' => 'INV-WEBHOOK-001',
            'total_harga' => 20000,
            'pembayaran' => 25040
        ]);
    }
    public function test_xendit_webhook_processes_expired_transaction()
    {
        // First create the pending transaction
        PendingTransaction::create([
            'kode_invoice' => 'INV-WEBHOOK-EXP',
            'kode_toko' => 'TK_XENDIT',
            'user_id' => 1,
            'user_name' => 'Test User',
            'total_harga' => 20000,
            'fee' => 5040,
            'grand_total' => 25040,
            'payment_method' => 'VA',
            'xendit_id' => 'inv_x_exp',
            'status' => 'PENDING',
            'cart_payload' => json_encode([])
        ]);

        // Hit Webhook with EXPIRED status
        $token = env('XENDIT_WEBHOOK_TOKEN') ?: 'webhook_test_mock';
        $response = $this->postJson('/api/xendit/webhook', [
            'external_id' => 'INV-WEBHOOK-EXP',
            'status' => 'EXPIRED',
            'id' => 'inv_x_exp'
        ], [
            'x-callback-token' => $token
        ]);

        $response->assertStatus(200);

        // Check if status updated to EXPIRED
        $this->assertDatabaseHas('pending_transactions', [
            'kode_invoice' => 'INV-WEBHOOK-EXP',
            'status' => 'EXPIRED'
        ]);

        // Verify stock is NOT deducted for expired transaction
        $this->assertDatabaseHas('stock_tokos', [
            'kode_barang' => 'BRG_XENDIT_1',
            'terjual' => 0 // Original setup amount
        ]);
    }
}
