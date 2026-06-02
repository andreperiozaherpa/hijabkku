<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Toko;
use App\Models\DataBarang;
use App\Models\StockToko;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockOpnameTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $toko;
    protected $barang;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RBACSeeder::class);

        // Create standard Store
        $this->toko = new Toko();
        $this->toko->kode = 'TK_001';
        $this->toko->nama_toko = 'Toko Fashion Pusat';
        $this->toko->save();

        // Create Admin
        $this->admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        // Create dummy Product
        $this->barang = new DataBarang();
        $this->barang->kode = '88888888';
        $this->barang->jenis_barang = 'Hijab';
        $this->barang->nama_barang = 'Hijab Segiempat Voal';
        $this->barang->harga_beli = '50000';
        $this->barang->harga_jual = '75000';
        $this->barang->harga_grosir = '70000';
        $this->barang->save();

        // Create initial stock
        StockToko::create([
            'kode_input' => 'TK_001-INIT',
            'kode_toko' => 'TK_001',
            'kode_barang' => '88888888',
            'nama_barang' => 'Hijab Segiempat Voal',
            'jumlah' => 10,
            'terjual' => 0,
            'supplier' => 'Supplier A',
        ]);

        // Create second product with 0 stock
        $barang0 = new DataBarang();
        $barang0->kode = '99999999';
        $barang0->jenis_barang = 'Hijab';
        $barang0->nama_barang = 'Hijab Instan 0 Stock';
        $barang0->harga_beli = '40000';
        $barang0->harga_jual = '60000';
        $barang0->harga_grosir = '55000';
        $barang0->save();

        StockToko::create([
            'kode_input' => 'TK_001-INIT2',
            'kode_toko' => 'TK_001',
            'kode_barang' => '99999999',
            'nama_barang' => 'Hijab Instan 0 Stock',
            'jumlah' => 5,
            'terjual' => 5, // 5 - 5 = 0 available stock!
            'supplier' => 'Supplier A',
        ]);
    }

    public function test_can_create_opname_session()
    {
        $supervisor = User::factory()->create([
            'role' => 'gudang',
            'kode_toko' => 'TK_001',
        ]);

        $response = $this->actingAs($this->admin)->post('/laporan/opname/store', [
            'kode_toko' => 'TK_001',
            'supervisor_id' => $supervisor->id,
            'notes' => 'Test opname session notes',
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('stock_opnames', [
            'kode_toko' => 'TK_001',
            'status' => 'Draft',
            'supervisor_id' => $supervisor->id,
            'notes' => 'Test opname session notes',
        ]);
    }

    public function test_can_start_counting_with_snapshot()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-260601-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Draft',
            'petugas_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->post('/laporan/opname/start-counting', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('stock_opnames', [
            'id' => $so->id,
            'status' => 'Counting',
        ]);

        // Assert snapshot item created with correct snapshot Qty
        $this->assertDatabaseHas('stock_opname_items', [
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
        ]);

        // Assert 0-stock item is skipped from the snapshot
        $this->assertDatabaseMissing('stock_opname_items', [
            'stock_opname_id' => $so->id,
            'kode_barang' => '99999999',
        ]);
    }

    public function test_can_scan_barcode_to_increment_qty()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-260601-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'final_qty' => 0,
        ]);

        $response = $this->actingAs($this->admin)->post('/laporan/opname/scan-barcode', [
            'stock_opname_id' => $so->id,
            'barcode' => '88888888',
            'round' => 1,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('stock_opname_items', [
            'id' => $item->id,
            'round_1_qty' => 1,
            'final_qty' => 1,
            'difference' => -9,
        ]);

        $this->assertDatabaseHas('stock_opname_audits', [
            'stock_opname_id' => $so->id,
            'stock_opname_item_id' => $item->id,
            'qty_before' => null,
            'qty_after' => 1,
            'action' => 'Scan Barcode',
        ]);
    }

    public function test_can_generate_recount_for_variance()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-260601-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'round_1_qty' => 8,
            'final_qty' => 8,
        ]);

        $response = $this->actingAs($this->admin)->post('/laporan/opname/generate-recount', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('stock_opnames', [
            'id' => $so->id,
            'status' => 'Recount',
        ]);

        $this->assertDatabaseHas('stock_opname_items', [
            'id' => $item->id,
            'status' => 'Need Recount',
        ]);
    }

    public function test_can_post_adjustments_to_actual_stock()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-260601-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Review',
            'petugas_id' => $this->admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'round_1_qty' => 12,
            'final_qty' => 12,
            'difference' => 2,
            'difference_value' => 100000,
        ]);

        $response = $this->actingAs($this->admin)->post('/laporan/opname/post-adjustment', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('stock_opnames', [
            'id' => $so->id,
            'status' => 'Posted',
        ]);

        // Check if actual stock is corrected (+2 difference added to original 10 qty -> 12)
        $this->assertDatabaseHas('stock_tokos', [
            'kode_toko' => 'TK_001',
            'kode_barang' => '88888888',
            'jumlah' => 12,
        ]);

        // Verify that NO stock movement was logged in stock_in_outs table
        $this->assertDatabaseMissing('stock_in_outs', [
            'kode_input' => 'SO-260601-TEST',
        ]);
    }

    public function test_can_fetch_audit_logs()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-AUDIT-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'final_qty' => 0,
        ]);

        \App\Models\StockOpnameAudit::create([
            'stock_opname_id' => $so->id,
            'stock_opname_item_id' => $item->id,
            'user_id' => $this->admin->id,
            'round' => 1,
            'qty_before' => 0,
            'qty_after' => 1,
            'action' => 'Scan Barcode',
        ]);

        $response = $this->actingAs($this->admin)->get('/laporan/opname/audit-logs/' . $so->id);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        
        $this->assertCount(1, $response->json('logs'));
    }

    public function test_non_admin_cannot_approve_final()
    {
        $nonAdmin = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-NONADMIN-FINAL-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $nonAdmin->id,
        ]);

        $response = $this->actingAs($nonAdmin)->post('/laporan/opname/approve-final', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya Admin yang dapat memfinalkan Stock Opname!',
        ]);
    }

    public function test_non_admin_cannot_post_adjustment()
    {
        $nonAdmin = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-NONADMIN-POST-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Review',
            'petugas_id' => $nonAdmin->id,
        ]);

        $response = $this->actingAs($nonAdmin)->post('/laporan/opname/post-adjustment', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya Admin yang dapat memposting penyesuaian stok!',
        ]);
    }

    public function test_non_admin_cannot_generate_recount()
    {
        $nonAdmin = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-RECOUNT-LOCK-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $nonAdmin->id,
        ]);

        $response = $this->actingAs($nonAdmin)->post('/laporan/opname/generate-recount', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya Admin atau Supervisor yang ditunjuk yang berhak melakukan validasi round!',
        ]);
    }

    public function test_non_admin_can_scan_barcode_in_round_2()
    {
        $nonAdmin = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-SCAN-ROUND2-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Recount',
            'petugas_id' => $nonAdmin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'round_1_qty' => 8,
            'final_qty' => 8,
        ]);

        $response = $this->actingAs($nonAdmin)->post('/laporan/opname/scan-barcode', [
            'stock_opname_id' => $so->id,
            'barcode' => '88888888',
            'round' => 2,
        ]);

        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_non_admin_can_update_qty_manual_in_round_2()
    {
        $nonAdmin = User::factory()->create([
            'status' => 'on',
            'role' => 'gudang',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-MANUAL-ROUND2-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Recount',
            'petugas_id' => $nonAdmin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'round_1_qty' => 8,
            'final_qty' => 8,
        ]);

        $response = $this->actingAs($nonAdmin)->post('/laporan/opname/update-qty-manual', [
            'item_id' => $item->id,
            'qty' => 9,
            'round' => 2,
        ]);

        $response->assertJson([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui!',
        ]);
    }

    public function test_recalculate_expected_qty_with_sales_during_opname()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $barang = DataBarang::create([
            'kode' => '99999999',
            'nama_barang' => 'Test Sales Qty Item',
            'harga_beli' => '20000',
            'harga_jual' => '25000',
            'harga_grosir' => '22000',
            'jenis_barang' => 'Hijab',
        ]);

        $stock = StockToko::create([
            'kode_input' => 'IN-001',
            'kode_toko' => 'TK_001',
            'kode_barang' => '99999999',
            'nama_barang' => 'Test Sales Qty Item',
            'jumlah' => 100,
            'terjual' => 0,
            'supplier' => 'Test Supplier',
        ]);

        // Start Opname
        $so = StockOpname::create([
            'nomor_so' => 'SO-SALES-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '99999999',
            'snapshot_qty' => 100,
            'round_1_qty' => null,
            'final_qty' => 0,
        ]);

        // A transaction of 5 items is created DURING opname (created_at >= session->created_at)
        \App\Models\Transaksi::create([
            'kode_invoice' => 'INV-001',
            'kode_toko' => 'TK_001',
            'kode_barang' => '99999999',
            'nama_barang' => 'Test Sales Qty Item',
            'metode' => 'umum',
            'jumlah' => 5,
            'harga' => 25000,
            'harga_beli' => 20000,
            'harga_total' => 125000,
            'created_at' => now()->addMinutes(1),
        ]);

        // The counter counts 95 items (which is correct shelf count after 5 were sold)
        $response = $this->actingAs($admin)->post('/laporan/opname/update-qty-manual', [
            'item_id' => $item->id,
            'qty' => 95,
            'round' => 1,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        // Verify the dynamic difference is calculated as 0!
        $item->refresh();
        $this->assertEquals(0, $item->difference);
        $this->assertEquals(0, $item->difference_value);
    }

    public function test_cannot_create_multiple_active_sessions_for_same_store()
    {
        $supervisor = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);

        // First session (Draft - Active)
        StockOpname::create([
            'nomor_so' => 'SO-ACTIVE-1',
            'kode_toko' => 'TK_001',
            'status' => 'Draft',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor->id,
        ]);

        // Attempting to create a second session for same store TK_001
        $response = $this->actingAs($this->admin)->post('/laporan/opname/store', [
            'kode_toko' => 'TK_001',
            'supervisor_id' => $supervisor->id,
            'notes' => 'Should fail',
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Toko ini masih memiliki sesi Stock Opname yang aktif! Selesaikan sesi sebelumnya terlebih dahulu.'
        ]);
    }

    public function test_admin_can_delete_draft_session()
    {
        $supervisor = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-DRAFT-DEL',
            'kode_toko' => 'TK_001',
            'status' => 'Draft',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($this->admin)->delete('/laporan/opname/destroy/' . $so->id);

        $response->assertJson([
            'success' => true,
            'message' => 'Sesi Stock Opname berhasil dihapus!'
        ]);

        $this->assertDatabaseMissing('stock_opnames', ['id' => $so->id]);
    }

    public function test_cannot_delete_non_draft_session()
    {
        $supervisor = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-COUNTING-DEL',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($this->admin)->delete('/laporan/opname/destroy/' . $so->id);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya sesi berstatus Draft yang dapat dihapus!'
        ]);

        $this->assertDatabaseHas('stock_opnames', ['id' => $so->id]);
    }

    public function test_assigned_supervisor_can_generate_recount()
    {
        $supervisor = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-SUPERVISOR-OK',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor->id,
        ]);

        // Supervisor can generate recount (transition to round 2)
        $response = $this->actingAs($supervisor)->post('/laporan/opname/generate-recount', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_unassigned_supervisor_cannot_generate_recount()
    {
        $supervisor1 = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);
        $supervisor2 = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-SUPERVISOR-FAIL',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor1->id,
        ]);

        // Unassigned supervisor cannot validate this session
        $response = $this->actingAs($supervisor2)->post('/laporan/opname/generate-recount', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya Admin atau Supervisor yang ditunjuk yang berhak melakukan validasi round!'
        ]);
    }

    public function test_supervisor_cannot_approve_final()
    {
        $supervisor = User::factory()->create(['role' => 'gudang', 'kode_toko' => 'TK_001']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-SUPERVISOR-FINAL',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor->id,
        ]);

        // Supervisor cannot finalize opname (strictly Admin)
        $response = $this->actingAs($supervisor)->post('/laporan/opname/approve-final', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya Admin yang dapat memfinalkan Stock Opname!'
        ]);
    }

    public function test_can_search_items_by_query()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-SEARCH-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        $item1 = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'final_qty' => 0,
        ]);

        $item2 = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '99999999',
            'snapshot_qty' => 5,
            'final_qty' => 0,
        ]);

        // Search by barcode '88888888'
        $response = $this->actingAs($this->admin)->get("/laporan/opname/items-data/{$so->id}?search_query=88888888");
        $response->assertJsonPath('data.0.kode_barang', '88888888');

        // Search by product name 'Voal'
        $response = $this->actingAs($this->admin)->get("/laporan/opname/items-data/{$so->id}?search_query=Voal");
        $response->assertJsonPath('data.0.kode_barang', '88888888');
    }
}
