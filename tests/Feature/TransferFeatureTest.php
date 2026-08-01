<?php

use App\Models\RekeningClient;
use App\Models\Transfer;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Database\Seeders\TransferPermissionSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    putenv('XENDIT_SECRET_KEY=xnd_test_mock');
    putenv('XENDIT_WEBHOOK_TOKEN=webhook_test_mock');
    Config::set('services.xendit.secret_key', 'xnd_test_mock');
    Config::set('services.xendit.webhook_token', 'webhook_test_mock');

    $this->seed(RBACSeeder::class);
    $this->seed(TransferPermissionSeeder::class);
});

it('menampilkan halaman index transfer untuk admin', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on']);

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 1000000], 200),
    ]);

    actingAs($admin)
        ->get(route('transfer.index'))
        ->assertStatus(200)
        ->assertSee('Transaksi Transfer Dana');
});

it('menolak akses halaman transfer untuk kasir tanpa permission', function () {
    $kasir = User::factory()->create(['role' => 'kasir', 'status' => 'on']);

    actingAs($kasir)
        ->get(route('transfer.index'))
        ->assertStatus(403);
});

it('menyimpan rekening client baru', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on']);

    actingAs($admin)
        ->post('/transfer/rekening/store', [
            'nama_client' => 'PT Maju Jaya',
            'bank_code' => 'BCA',
            'bank_name' => 'Bank Central Asia',
            'account_number' => '1234567890',
            'account_holder_name' => 'Budi Santoso',
            'recipient_type' => 'INDIVIDUAL',
            'relationship' => 'CUSTOMER',
            'channel_type' => 'BANK',
            'city' => 'Jakarta',
            'street_line_1' => 'Jl. Merdeka No. 1',
            'keterangan' => 'Supplier hijab',
        ])
        ->assertJsonPath('icon', 'success');

    $this->assertDatabaseHas('rekening_clients', [
        'nama_client' => 'PT Maju Jaya',
        'bank_code' => 'BCA',
        'account_number' => '1234567890',
    ]);
});

it('mengatur PIN transfer untuk user oleh admin', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on']);
    $target = User::factory()->create(['role' => 'kasir', 'status' => 'on']);

    actingAs($admin)
        ->post('/user/pin', [
            'email' => $target->email,
            'transfer_pin' => '654321',
            'confirm_transfer_pin' => '654321',
        ])
        ->assertJsonPath('icon', 'success');

    $this->assertTrue(Hash::check('654321', $target->fresh()->transfer_pin));
});

it('menolak PIN transfer yang tidak 6 digit', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on']);
    $target = User::factory()->create(['role' => 'kasir', 'status' => 'on']);

    actingAs($admin)
        ->post('/user/pin', [
            'email' => $target->email,
            'transfer_pin' => '123',
            'confirm_transfer_pin' => '123',
        ])
        ->assertSessionHasErrors('transfer_pin');

    $this->assertNull($target->fresh()->transfer_pin);
});

it('membuat transfer dan mengirim payout ke xendit', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([
            'payout_id' => 'payout_test_123',
            'reference_id' => 'TRF-TEST-1',
            'status' => 'ACCEPTED',
        ], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'description' => 'Pembayaran invoice',
            'transfer_pin' => '123456',
        ])
        ->assertJsonPath('icon', 'success');

    $this->assertDatabaseHas('transfers', [
        'rekening_client_id' => $rekening->id,
        'amount' => 100000,
        'status' => 'ACCEPTED',
        'xendit_payout_id' => 'payout_test_123',
    ]);
});

it('menolak transfer tanpa PIN validasi', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
        ])
        ->assertSessionHasErrors('transfer_pin');

    $this->assertDatabaseMissing('transfers', [
        'rekening_client_id' => $rekening->id,
        'amount' => 100000,
    ]);
});

it('menolak transfer ketika PIN validasi salah', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'transfer_pin' => '000000',
        ])
        ->assertStatus(422)
        ->assertJsonPath('icon', 'error')
        ->assertJsonPath('text', 'PIN validasi transfer tidak sesuai. Hubungi admin untuk mengatur PIN Anda.');

    $this->assertDatabaseMissing('transfers', [
        'rekening_client_id' => $rekening->id,
        'amount' => 100000,
    ]);
});

it('menolak transfer oleh akun non-admin', function () {
    $kasir = User::factory()->create(['role' => 'kasir', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $permissionId = DB::table('permissions')->where('name', 'kelola_transfer')->value('id');
    DB::table('role_permissions')->insert([
        'role' => 'kasir',
        'permission_id' => $permissionId,
    ]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($kasir)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'transfer_pin' => '123456',
        ])
        ->assertStatus(403)
        ->assertJsonPath('icon', 'error')
        ->assertJsonPath('text', 'Hanya akun admin yang dapat melakukan transfer dana.');

    $this->assertDatabaseMissing('transfers', [
        'rekening_client_id' => $rekening->id,
        'amount' => 100000,
    ]);
});

it('menyimpan rekening e-wallet baru', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on']);

    actingAs($admin)
        ->post('/transfer/rekening/store', [
            'nama_client' => 'Client DANA',
            'bank_code' => 'DANA',
            'bank_name' => 'DANA',
            'account_number' => '081234567890',
            'account_holder_name' => 'Siti Aminah',
            'recipient_type' => 'INDIVIDUAL',
            'relationship' => 'CUSTOMER',
            'channel_type' => 'EWALLET',
            'city' => 'Jakarta',
            'street_line_1' => 'Jl. Merdeka No. 1',
            'keterangan' => 'Payout e-wallet DANA',
        ])
        ->assertJsonPath('icon', 'success');

    $this->assertDatabaseHas('rekening_clients', [
        'nama_client' => 'Client DANA',
        'bank_code' => 'DANA',
        'channel_type' => 'EWALLET',
        'account_number' => '081234567890',
    ]);
});

it('mengirim payout ke e-wallet dengan routing WALLET', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->ewallet('DANA')->create([
        'account_number' => '081234567890',
    ]);

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([
            'payout_id' => 'payout_wallet_123',
            'reference_id' => 'TRF-WALLET-1',
            'status' => 'ACCEPTED',
        ], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 25000,
            'transfer_pin' => '123456',
        ])
        ->assertJsonPath('icon', 'success');

    Http::assertSent(function ($request) {
        if (! str_contains($request->url(), '/v3/payouts')) {
            return false;
        }

        $payload = json_decode($request->body(), true) ?? [];
        $accountDetails = $payload['recipient']['account_details'] ?? [];

        return $accountDetails['routing_type_1'] === 'WALLET'
            && $accountDetails['routing_value_1'] === 'ID_DANA'
            && ($payload['recipient']['details']['personal_mobile_number'] ?? null) === '081234567890';
    });

    $this->assertDatabaseHas('transfers', [
        'rekening_client_id' => $rekening->id,
        'amount' => 25000,
        'status' => 'ACCEPTED',
        'routing_type' => 'WALLET',
        'routing_value' => 'ID_DANA',
        'xendit_payout_id' => 'payout_wallet_123',
    ]);
});

it('menolak transfer ketika saldo tidak mencukupi', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 50000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'transfer_pin' => '123456',
        ])
        ->assertStatus(422)
        ->assertJsonPath('icon', 'error');

    $this->assertDatabaseMissing('transfers', [
        'rekening_client_id' => $rekening->id,
        'amount' => 100000,
    ]);
});

it('menolak transfer duplikat yang masih diproses', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Transfer::factory()->create([
        'kode_transfer' => 'TRF-DUPLICATE-1',
        'rekening_client_id' => $rekening->id,
        'amount' => 100000,
        'created_by' => $admin->id,
        'status' => 'ACCEPTED',
        'created_at' => now()->subMinute(),
    ]);

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'transfer_pin' => '123456',
        ])
        ->assertStatus(422)
        ->assertJsonPath('icon', 'error')
        ->assertJsonPath('text', 'Transfer dengan tujuan dan nominal yang sama masih diproses. Tunggu beberapa saat sebelum mencoba lagi.');
});

it('menolak transfer dengan nominal melebihi batas', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 1000000001,
            'transfer_pin' => '123456',
        ])
        ->assertSessionHasErrors('amount');
});

it('menolak transfer ketika lock transfer sedang terkunci', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Cache::lock('xendit_payout', 30)->get();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([], 200),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'transfer_pin' => '123456',
        ])
        ->assertStatus(429)
        ->assertJsonPath('icon', 'error');

    Cache::lock('xendit_payout', 30)->forceRelease();
});

it('mencatat status gagal ketika xendit mengembalikan error', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'on', 'transfer_pin' => Hash::make('123456')]);
    $rekening = RekeningClient::factory()->create();

    Http::fake([
        'api.xendit.co/balance*' => Http::response(['balance' => 5000000], 200),
        'api.xendit.co/v3/payouts*' => Http::response([
            'message' => 'Nomor rekening tidak valid',
        ], 422),
    ]);

    actingAs($admin)
        ->post('/transfer/store', [
            'rekening_client_id' => $rekening->id,
            'amount' => 100000,
            'transfer_pin' => '123456',
        ])
        ->assertStatus(422)
        ->assertJsonPath('icon', 'error');

    $this->assertDatabaseHas('transfers', [
        'rekening_client_id' => $rekening->id,
        'status' => 'FAILED',
        'failure_message' => 'Nomor rekening tidak valid',
    ]);
});

it('memproses webhook payout sukses', function () {
    $transfer = Transfer::factory()->create([
        'kode_transfer' => 'TRF-WEBHOOK-1',
        'status' => 'ACCEPTED',
        'xendit_payout_id' => null,
    ]);

    Http::fake();

    $response = $this->postJson('/api/xendit/payout-webhook', [
        'event' => 'v3_payout.succeeded',
        'data' => [
            'payout_id' => 'payout_test_webhook',
            'reference_id' => 'TRF-WEBHOOK-1',
            'status' => 'SUCCEEDED',
        ],
    ], [
        'x-callback-token' => 'webhook_test_mock',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('transfers', [
        'kode_transfer' => 'TRF-WEBHOOK-1',
        'status' => 'SUCCEEDED',
        'xendit_payout_id' => 'payout_test_webhook',
    ]);
});

it('memproses webhook payout gagal dan mencatat failure', function () {
    $transfer = Transfer::factory()->create([
        'kode_transfer' => 'TRF-WEBHOOK-FAIL',
        'status' => 'ACCEPTED',
    ]);

    Http::fake();

    $response = $this->postJson('/api/xendit/payout-webhook', [
        'event' => 'v3_payout.failed',
        'data' => [
            'payout_id' => 'payout_test_fail',
            'reference_id' => 'TRF-WEBHOOK-FAIL',
            'status' => 'FAILED',
            'failure_code' => 'TRANSFER_FAILED',
            'description' => 'Rekening tujuan tidak valid',
        ],
    ], [
        'x-callback-token' => 'webhook_test_mock',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('transfers', [
        'kode_transfer' => 'TRF-WEBHOOK-FAIL',
        'status' => 'FAILED',
        'failure_code' => 'TRANSFER_FAILED',
        'failure_message' => 'Rekening tujuan tidak valid',
    ]);
});

it('menolak webhook dengan token yang salah', function () {
    Transfer::factory()->create([
        'kode_transfer' => 'TRF-WEBHOOK-UNAUTH',
        'status' => 'ACCEPTED',
    ]);

    Http::fake();

    $this->postJson('/api/xendit/payout-webhook', [
        'event' => 'v3_payout.succeeded',
        'data' => [
            'reference_id' => 'TRF-WEBHOOK-UNAUTH',
            'status' => 'SUCCEEDED',
        ],
    ], [
        'x-callback-token' => 'salah-token',
    ])->assertStatus(401);

    $this->assertDatabaseHas('transfers', [
        'kode_transfer' => 'TRF-WEBHOOK-UNAUTH',
        'status' => 'ACCEPTED',
    ]);
});

it('menolak webhook tanpa token callback', function () {
    Transfer::factory()->create([
        'kode_transfer' => 'TRF-WEBHOOK-NOTOKEN',
        'status' => 'ACCEPTED',
    ]);

    Http::fake();

    $this->postJson('/api/xendit/payout-webhook', [
        'event' => 'v3_payout.succeeded',
        'data' => [
            'reference_id' => 'TRF-WEBHOOK-NOTOKEN',
            'status' => 'SUCCEEDED',
        ],
    ])->assertStatus(401);

    $this->assertDatabaseHas('transfers', [
        'kode_transfer' => 'TRF-WEBHOOK-NOTOKEN',
        'status' => 'ACCEPTED',
    ]);
});

it('menolak webhook ketika token tidak dikonfigurasi', function () {
    Config::set('services.xendit.webhook_token', '');
    putenv('XENDIT_WEBHOOK_TOKEN=');

    Transfer::factory()->create([
        'kode_transfer' => 'TRF-WEBHOOK-NOCONFIG',
        'status' => 'ACCEPTED',
    ]);

    Http::fake();

    $this->postJson('/api/xendit/payout-webhook', [
        'event' => 'v3_payout.succeeded',
        'data' => [
            'reference_id' => 'TRF-WEBHOOK-NOCONFIG',
            'status' => 'SUCCEEDED',
        ],
    ], [
        'x-callback-token' => 'token-apapun',
    ])->assertStatus(401);

    $this->assertDatabaseHas('transfers', [
        'kode_transfer' => 'TRF-WEBHOOK-NOCONFIG',
        'status' => 'ACCEPTED',
    ]);
});
