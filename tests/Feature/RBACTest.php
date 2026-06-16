<?php

namespace Tests\Feature;

use App\Models\Toko;
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
        $toko = new Toko;
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
        $response->assertSee('Akses Ditolak');
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

    public function test_admin_can_switch_stores_on_pos_page()
    {
        // Create another Toko
        $toko2 = new Toko;
        $toko2->kode = 'TK_other';
        $toko2->nama_toko = 'Other Store';
        $toko2->save();

        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // Access POS without param (uses TK_test)
        $response = $this->actingAs($admin)->get('/transaksi/penjualan');
        $response->assertStatus(200);
        $response->assertViewHas('data_toko', function ($toko) {
            return $toko->kode === 'TK_test';
        });

        // Access POS with custom store (uses TK_other)
        $responseWithToko = $this->actingAs($admin)->get('/transaksi/penjualan?kode_toko=TK_other');
        $responseWithToko->assertStatus(200);
        $responseWithToko->assertViewHas('data_toko', function ($toko) {
            return $toko->kode === 'TK_other';
        });
    }

    public function test_admin_can_manage_dynamic_roles_and_permissions()
    {
        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // 1. Get RBAC page
        $response = $this->actingAs($admin)->get(route('user.rbac'));
        $response->assertStatus(200);

        // 2. Create new dynamic role "manager"
        $responseRole = $this->actingAs($admin)->postJson(route('user.rbac.role.store'), [
            'name' => 'manager',
            'display_name' => 'Manager Store',
        ]);

        $responseRole->assertStatus(200)
            ->assertJsonPath('icon', 'success')
            ->assertJsonPath('title', 'Sukses');

        $this->assertDatabaseHas('roles', [
            'name' => 'manager',
            'display_name' => 'Manager Store',
        ]);

        // 3. Grant 'kelola_supplier' permission to dynamic role "manager"
        $permission = \DB::table('permissions')->where('name', 'kelola_supplier')->first();
        $responsePerm = $this->actingAs($admin)->postJson(route('user.rbac.update'), [
            'role' => 'manager',
            'permission_id' => $permission->id,
            'checked' => 1,
        ]);

        $responsePerm->assertStatus(200)
            ->assertJsonPath('icon', 'success');

        $this->assertDatabaseHas('role_permissions', [
            'role' => 'manager',
            'permission_id' => $permission->id,
        ]);

        // 4. Create user with role "manager" and test access
        $manager = User::factory()->create([
            'status' => 'on',
            'role' => 'manager',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // Manager has 'kelola_supplier' permission -> can access
        $responseAccess = $this->actingAs($manager)->get('/manajemen/supplier/index');
        $responseAccess->assertStatus(200);

        // Manager does not have 'proses_transaksi' permission -> denied
        $responseDenied = $this->actingAs($manager)->get('/transaksi/penjualan');
        $responseDenied->assertStatus(403);
        $responseDenied->assertSee('Akses Ditolak');
    }
}
