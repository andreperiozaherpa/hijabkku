<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\DataBarang;
use App\Models\StockToko;
use App\Models\Toko;
use App\Models\PendingTransaction;
use App\Models\PesananPickup;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LandingCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.recaptcha.secret_key' => null]);
        putenv('XENDIT_SECRET_KEY=xnd_test_mock');
        
        $this->seed(\Database\Seeders\RBACSeeder::class);

        // Setup Toko
        $toko = new Toko();
        $toko->kode = 'T001';
        $toko->nama_toko = 'Toko Test 1';
        $toko->save();

        // Setup Data Barang
        $barang = new DataBarang();
        $barang->kode = 'BRG001';
        $barang->nama_barang = 'Hijab Bella';
        $barang->harga_beli = '10000';
        $barang->harga_jual = '20000';
        $barang->harga_grosir = '18000';
        $barang->jenis_barang = 'Hijab';
        $barang->save();

        // Setup Stock
        $stock = new StockToko();
        $stock->kode_input = 'INPUT001';
        $stock->kode_toko = 'T001';
        $stock->kode_barang = 'BRG001';
        $stock->nama_barang = 'Hijab Bella';
        $stock->jumlah = 10;
        $stock->terjual = 0;
        $stock->supplier = 'SUPP01';
        $stock->save();
    }

    public function test_guest_can_checkout_successfully_via_landing_api()
    {
        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'inv_guest_123',
                'invoice_url' => 'https://checkout.xendit.co/web/inv_guest_123'
            ], 200),
        ]);

        $response = $this->postJson('/api/landing/checkout', [
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'alice@example.com',
            'payment_method' => 'QRIS',
            'cart' => [
                [
                    'kode_barang' => 'BRG001',
                    'jumlah' => 2
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'checkout_url' => 'https://checkout.xendit.co/web/inv_guest_123'
        ]);

        // Assert pending transaction exists in db
        $this->assertDatabaseHas('pending_transactions', [
            'payment_method' => 'QRIS',
            'status' => 'PENDING',
            'user_name' => 'Guest (Alice Guest)',
            'kode_toko' => 'T001'
        ]);

        // Check customer metadata inside cart_payload
        $pendingTx = PendingTransaction::where('user_name', 'Guest (Alice Guest)')->first();
        $this->assertNotNull($pendingTx);
        $cartPayload = json_decode($pendingTx->cart_payload, true);
        
        $this->assertArrayHasKey('items', $cartPayload);
        $this->assertArrayHasKey('customer', $cartPayload);
        
        $this->assertEquals('BRG001', $cartPayload['items'][0]['nomor_paket']);
        $this->assertEquals(2, $cartPayload['items'][0]['jumlah_barang']);
        $this->assertEquals('Alice Guest', $cartPayload['customer']['name']);
        $this->assertEquals('081234567890', $cartPayload['customer']['phone']);
        $this->assertEquals('alice@example.com', $cartPayload['customer']['email']);
    }

    public function test_guest_checkout_fails_if_stock_insufficient()
    {
        $response = $this->postJson('/api/landing/checkout', [
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'alice@example.com',
            'payment_method' => 'QRIS',
            'cart' => [
                [
                    'kode_barang' => 'BRG001',
                    'jumlah' => 15 // Exceeds available stock of 10
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Stok untuk barang Hijab Bella tidak mencukupi!'
        ]);
    }

    public function test_xendit_webhook_creates_pesanan_pickup_upon_payment()
    {
        // Setup a pending transaction for Guest Checkout
        PendingTransaction::create([
            'kode_invoice' => 'INV-GP-001',
            'kode_toko' => 'T001',
            'user_id' => 0, // guest
            'user_name' => 'Guest (Alice Guest)',
            'total_harga' => 40000,
            'fee' => 283,
            'grand_total' => 40283,
            'payment_method' => 'QRIS',
            'xendit_id' => 'inv_guest_webhook_123',
            'status' => 'PENDING',
            'cart_payload' => json_encode([
                'items' => [
                    [
                        'nomor_paket' => 'BRG001',
                        'nama_barang' => 'Hijab Bella',
                        'jumlah_barang' => 2,
                        'harga_item' => 20000,
                        'harga_jual' => 40000,
                        'method' => 'umum'
                    ]
                ],
                'customer' => [
                    'name' => 'Alice Guest',
                    'phone' => '081234567890',
                    'email' => 'alice@example.com'
                ]
            ])
        ]);

        // Send PAID webhook
        $token = env('XENDIT_WEBHOOK_TOKEN') ?: 'webhook_test_mock';
        $response = $this->postJson('/api/xendit/webhook', [
            'external_id' => 'INV-GP-001',
            'status' => 'PAID',
            'id' => 'inv_guest_webhook_123'
        ], [
            'x-callback-token' => $token
        ]);

        $response->assertStatus(200);

        // Check if pending transaction is updated
        $this->assertDatabaseHas('pending_transactions', [
            'kode_invoice' => 'INV-GP-001',
            'status' => 'PAID'
        ]);

        // Check if pesanan_pickups record was successfully inserted
        $this->assertDatabaseHas('pesanan_pickups', [
            'kode_invoice' => 'INV-GP-001',
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'alice@example.com',
            'status_pengambilan' => 'Belum Diambil'
        ]);

        // Check if stock was correctly decremented in stock_tokos (10 - 2 = 8 left, terjual 2)
        $this->assertDatabaseHas('stock_tokos', [
            'kode_barang' => 'BRG001',
            'terjual' => 2
        ]);
    }

    public function test_guest_checkout_fails_if_recaptcha_secret_set_but_token_missing()
    {
        config(['services.recaptcha.secret_key' => 'mock_secret_key']);

        $response = $this->postJson('/api/landing/checkout', [
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'alice@example.com',
            'payment_method' => 'QRIS',
            'cart' => [
                [
                    'kode_barang' => 'BRG001',
                    'jumlah' => 2
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('g-recaptcha-response');
    }

    public function test_guest_checkout_fails_if_recaptcha_verification_fails()
    {
        config(['services.recaptcha.secret_key' => 'mock_secret_key']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response']
            ], 200),
        ]);

        $response = $this->postJson('/api/landing/checkout', [
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'alice@example.com',
            'payment_method' => 'QRIS',
            'cart' => [
                [
                    'kode_barang' => 'BRG001',
                    'jumlah' => 2
                ]
            ],
            'g-recaptcha-response' => 'invalid_token'
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi!'
        ]);
    }

    public function test_guest_checkout_succeeds_without_saving_transaction_if_xendit_simulation_mode_is_active()
    {
        // Turn on simulation mode
        \App\Models\SystemSetting::setByKey('xendit_simulation_mode', 'true');

        Http::fake([
            'api.xendit.co/v2/invoices' => Http::response([
                'id' => 'inv_guest_sim_123',
                'invoice_url' => 'https://checkout.xendit.co/web/inv_guest_sim_123'
            ], 200),
        ]);

        $response = $this->postJson('/api/landing/checkout', [
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'alice@example.com',
            'payment_method' => 'QRIS',
            'cart' => [
                [
                    'kode_barang' => 'BRG001',
                    'jumlah' => 2
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'checkout_url' => 'https://checkout.xendit.co/web/inv_guest_sim_123'
        ]);

        // Assert pending transaction DOES NOT exist in db
        $this->assertDatabaseMissing('pending_transactions', [
            'kode_toko' => 'T001',
            'user_name' => 'Guest (Alice Guest)'
        ]);
    }

    public function test_local_redirect_auto_completes_pending_transaction()
    {
        // Setup a pending transaction
        $pending = PendingTransaction::create([
            'kode_invoice' => 'INV-OL-TEST-123',
            'kode_toko' => 'T001',
            'user_id' => 0,
            'user_name' => 'Guest (Bob)',
            'total_harga' => 20000,
            'fee' => 0,
            'grand_total' => 20000,
            'payment_method' => 'QRIS',
            'xendit_id' => 'inv_test_xnd_123',
            'checkout_url' => 'https://checkout.xendit.co/web/inv_test_xnd_123',
            'cart_payload' => json_encode([
                'items' => [
                    [
                        'nomor_paket' => 'BRG001',
                        'nama_barang' => 'Hijab Bella',
                        'jumlah_barang' => 1,
                        'harga_item' => 20000,
                        'harga_jual' => 20000,
                        'method' => 'umum'
                    ]
                ],
                'customer' => [
                    'name' => 'Bob',
                    'email' => 'bob@example.com',
                    'phone' => '081234567890'
                ]
            ]),
            'status' => 'PENDING'
        ]);

        // Ensure env is local in testing config
        config(['app.env' => 'local']);

        // Visit redirect success URL
        $response = $this->get('/catalog?payment_status=success&invoice=INV-OL-TEST-123');

        $response->assertStatus(200);

        // Assert pending transaction is updated to PAID
        $this->assertDatabaseHas('pending_transactions', [
            'kode_invoice' => 'INV-OL-TEST-123',
            'status' => 'PAID'
        ]);

        // Assert payment is created
        $this->assertDatabaseHas('pembayarans', [
            'kode_invoice' => 'INV-OL-TEST-123',
            'user_name' => 'Guest (Bob)'
        ]);

        // Assert pickup order is created
        $this->assertDatabaseHas('pesanan_pickups', [
            'kode_invoice' => 'INV-OL-TEST-123',
            'customer_name' => 'Bob'
        ]);
    }

    public function test_guest_checkout_fails_with_invalid_email_format()
    {
        $response = $this->postJson('/api/landing/checkout', [
            'kode_toko' => 'T001',
            'customer_name' => 'Alice Guest',
            'customer_phone' => '081234567890',
            'customer_email' => 'ands@g.c', // 1-char TLD which is invalid
            'payment_method' => 'QRIS',
            'cart' => [
                [
                    'kode_barang' => 'BRG001',
                    'jumlah' => 2
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['customer_email']);
    }
}

