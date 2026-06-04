<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Toko;
use App\Models\PesananPickup;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PesananPickupTest extends TestCase
{
    use RefreshDatabase;

    private Toko $toko1;
    private Toko $toko2;
    private User $admin;
    private User $kasir1;
    private User $kasir2;
    private User $gudang;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed initial permissions and roles mapping
        $this->seed(RBACSeeder::class);

        // Create test stores
        $this->toko1 = new Toko();
        $this->toko1->kode = 'TK01';
        $this->toko1->nama_toko = 'Toko Satu';
        $this->toko1->save();

        $this->toko2 = new Toko();
        $this->toko2->kode = 'TK02';
        $this->toko2->nama_toko = 'Toko Dua';
        $this->toko2->save();

        // Create users
        $this->admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK01',
            'shift' => 0,
        ]);

        $this->kasir1 = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK01',
            'shift' => 0,
        ]);

        $this->kasir2 = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK02',
            'shift' => 0,
        ]);

        $this->gudang = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK01',
            'shift' => 0,
        ]);
    }

    public function test_admin_and_kasir_have_access_to_pickup_page()
    {
        $response = $this->actingAs($this->admin)->get('/transaksi/pickup');
        $response->assertStatus(200);

        $response = $this->actingAs($this->kasir1)->get('/transaksi/pickup');
        $response->assertStatus(200);
    }

    public function test_gudang_role_is_denied_from_accessing_pickup_page()
    {
        $response = $this->actingAs($this->gudang)->get('/transaksi/pickup');
        $response->assertStatus(403);
    }

    public function test_kasir_can_only_see_their_own_store_pickup_orders()
    {
        // Insert pickup order for Toko Satu
        $pickup1 = PesananPickup::create([
            'kode_invoice' => 'INV-001',
            'kode_toko' => 'TK01',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'status_pengambilan' => 'Belum Diambil',
        ]);

        // Insert pickup order for Toko Dua
        $pickup2 = PesananPickup::create([
            'kode_invoice' => 'INV-002',
            'kode_toko' => 'TK02',
            'customer_name' => 'Jane Smith',
            'customer_phone' => '08987654321',
            'status_pengambilan' => 'Belum Diambil',
        ]);

        // Access as Kasir 1 (Store TK01)
        $response = $this->actingAs($this->kasir1)->getJson('/transaksi/pickup/data?status_pengambilan=Belum Diambil');
        $response->assertStatus(200);
        $data = $response->json('data');

        // Should only see INV-001
        $this->assertCount(1, $data);
        $this->assertEquals('INV-001', $data[0]['kode_invoice']);

        // Access as Admin - should see both
        $responseAdmin = $this->actingAs($this->admin)->getJson('/transaksi/pickup/data?status_pengambilan=Belum Diambil');
        $responseAdmin->assertStatus(200);
        $this->assertCount(2, $responseAdmin->json('data'));
    }

    public function test_retrieve_items_for_pickup_order()
    {
        $pickup = PesananPickup::create([
            'kode_invoice' => 'INV-ITEMS-001',
            'kode_toko' => 'TK01',
            'customer_name' => 'Alice',
            'customer_phone' => '081223344',
            'status_pengambilan' => 'Belum Diambil',
        ]);

        // Create transaction details in transaksis table
        DB::table('transaksis')->insert([
            [
                'kode_invoice' => 'INV-ITEMS-001',
                'kode_toko' => 'TK01',
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Hijab Bella Bella',
                'metode' => 'umum',
                'jumlah' => 2,
                'harga' => 17000,
                'harga_beli' => 10000,
                'harga_total' => 34000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_invoice' => 'INV-ITEMS-001',
                'kode_toko' => 'TK01',
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Ciput Rajut',
                'metode' => 'umum',
                'jumlah' => 1,
                'harga' => 5000,
                'harga_beli' => 3000,
                'harga_total' => 5000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $response = $this->actingAs($this->kasir1)->getJson("/transaksi/pickup/{$pickup->id}/items");
        $response->assertStatus(200);
        $response->assertJsonPath('pickup.customer_name', 'Alice');
        $response->assertJsonCount(2, 'items');
        $response->assertJsonPath('grand_total', 39000);
    }

    public function test_complete_pickup_order()
    {
        $pickup = PesananPickup::create([
            'kode_invoice' => 'INV-COMP-001',
            'kode_toko' => 'TK01',
            'customer_name' => 'Bob',
            'customer_phone' => '08122334455',
            'status_pengambilan' => 'Belum Diambil',
        ]);

        $response = $this->actingAs($this->kasir1)->postJson("/transaksi/pickup/{$pickup->id}/complete");
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Check DB state
        $this->assertDatabaseHas('pesanan_pickups', [
            'id' => $pickup->id,
            'status_pengambilan' => 'Sudah Diambil',
        ]);
    }

    public function test_kasir_cannot_complete_other_store_pickup_order()
    {
        $pickup = PesananPickup::create([
            'kode_invoice' => 'INV-COMP-002',
            'kode_toko' => 'TK02',
            'customer_name' => 'Charlie',
            'customer_phone' => '08122334455',
            'status_pengambilan' => 'Belum Diambil',
        ]);

        // Kasir1 belongs to TK01, trying to complete order for TK02
        $response = $this->actingAs($this->kasir1)->postJson("/transaksi/pickup/{$pickup->id}/complete");
        $response->assertStatus(403);
    }
}
