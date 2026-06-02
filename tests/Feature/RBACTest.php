<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed initial permissions and roles mapping
        $this->seed(RBACSeeder::class);

        // Create a mock Toko to satisfy TransaksiController requirement
        $toko = new \App\Models\Toko();
        $toko->kode = 'TK_test';
        $toko->nama_toko = 'Test Toko';
        $toko->save();
    }

    public function test_admin_has_access_to_management_pages()
    {
        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($admin)->get('/manajemen/supplier/index');

        // Admin has 'kelola_supplier' permission, so they should be allowed (returns 200)
        $response->assertStatus(200);
    }

    public function test_kasir_is_denied_from_accessing_management_pages()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($kasir)->get('/manajemen/supplier/index');

        // Cashier does not have 'kelola_supplier' permission, so they should get 403 Forbidden
        $response->assertStatus(403);
        $response->assertSee('Anda tidak memiliki hak akses');
    }

    public function test_kasir_can_access_cashier_pages()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($kasir)->get('/transaksi/penjualan');

        // Cashier has 'proses_transaksi' permission, so they should get 200 OK
        $response->assertStatus(200);
    }

    public function test_gudang_is_denied_from_accessing_cashier_pages()
    {
        $gudang = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($gudang)->get('/transaksi/penjualan');

        // Warehouse staff does not have 'proses_transaksi' permission, so they should get 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_kasir_with_kelola_stock_opname_permission_can_access_opname()
    {
        // 1. Create kasir user
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // 2. Access /laporan/opname before permission (should be 403)
        $response = $this->actingAs($kasir)->get('/laporan/opname');
        $response->assertStatus(403);

        // 3. Grant permission to kasir role dynamically
        $permission = \DB::table('permissions')->where('name', 'kelola_stock_opname')->first();
        \DB::table('role_permissions')->insert([
            'role' => 'kasir',
            'permission_id' => $permission->id,
        ]);

        // 4. Access /laporan/opname after permission (should be 200)
        $response2 = $this->actingAs($kasir)->get('/laporan/opname');
        $response2->assertStatus(200);
    }
}
