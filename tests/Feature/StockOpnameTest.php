<?php

namespace Tests\Feature;

use App\Models\DataBarang;
use App\Models\SesiKasir;
use App\Models\StockOpname;
use App\Models\StockOpnameAudit;
use App\Models\StockOpnameItem;
use App\Models\StockToko;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        $this->toko = new Toko;
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
        $this->barang = new DataBarang;
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
        $barang0 = new DataBarang;
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

        StockOpnameAudit::create([
            'stock_opname_id' => $so->id,
            'stock_opname_item_id' => $item->id,
            'user_id' => $this->admin->id,
            'round' => 1,
            'qty_before' => 0,
            'qty_after' => 1,
            'action' => 'Scan Barcode',
        ]);

        $response = $this->actingAs($this->admin)->get('/laporan/opname/audit-logs/'.$so->id);

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
        Transaksi::create([
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
            'message' => 'Toko ini masih memiliki sesi Stock Opname yang aktif! Selesaikan sesi sebelumnya terlebih dahulu.',
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

        $response = $this->actingAs($this->admin)->delete('/laporan/opname/destroy/'.$so->id);

        $response->assertJson([
            'success' => true,
            'message' => 'Sesi Stock Opname berhasil dihapus!',
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

        $response = $this->actingAs($this->admin)->delete('/laporan/opname/destroy/'.$so->id);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya sesi berstatus Draft yang dapat dihapus!',
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
            'message' => 'Hanya Admin atau Supervisor yang ditunjuk yang berhak melakukan validasi round!',
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
            'message' => 'Hanya Admin yang dapat memfinalkan Stock Opname!',
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

    public function test_can_search_master_products()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-SEARCH-MASTER',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get("/laporan/opname/search-master-products/{$so->id}?search_query=88888888");
        $response->assertStatus(200);
        $response->assertJsonFragment(['kode' => '88888888']);
    }

    public function test_can_add_master_product_manually()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-ADD-MASTER-MANUAL',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        // Add '99999999' which is in master data but NOT in this session's items
        $response = $this->actingAs($this->admin)->post("/laporan/opname/add-master-product/{$so->id}", [
            'kode_barang' => '99999999',
        ]);

        $response->assertJson([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke list stock opname!',
        ]);

        $this->assertDatabaseHas('stock_opname_items', [
            'stock_opname_id' => $so->id,
            'kode_barang' => '99999999',
            'snapshot_qty' => 0, // '99999999' has 5 - 5 = 0 available stock
        ]);
    }

    public function test_unauthorized_user_cannot_add_master_product_manually()
    {
        $unauthorizedUser = User::factory()->create(['role' => 'gudang']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-UNAUTH-ADD-MASTER',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($unauthorizedUser)->post("/laporan/opname/add-master-product/{$so->id}", [
            'kode_barang' => '99999999',
        ]);

        $response->assertStatus(403);
    }

    public function test_assigned_supervisor_can_add_master_product_manually()
    {
        $supervisor = User::factory()->create(['role' => 'gudang']);
        $so = StockOpname::create([
            'nomor_so' => 'SO-SUPERVISOR-ADD-MASTER',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $this->admin->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($supervisor)->post("/laporan/opname/add-master-product/{$so->id}", [
            'kode_barang' => '99999999',
        ]);

        $response->assertJson([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke list stock opname!',
        ]);
    }

    public function test_pos_sale_auto_deducts_counted_opname_item()
    {
        // Fake Firebase service
        Http::fake();

        $user = User::factory()->create([
            'role' => 'kasir',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $barang = DataBarang::create([
            'kode' => 'BRG_DEDUCT_TEST',
            'nama_barang' => 'Test Deduct Product',
            'harga_beli' => '10000',
            'harga_jual' => '15000',
            'harga_grosir' => '12000',
            'jenis_barang' => 'Hijab',
        ]);

        $stock = StockToko::create([
            'kode_input' => 'IN-DEDUCT',
            'kode_toko' => 'TK_001',
            'kode_barang' => 'BRG_DEDUCT_TEST',
            'nama_barang' => 'Test Deduct Product',
            'jumlah' => 10,
            'terjual' => 0,
            'supplier' => 'Test Supplier',
        ]);

        // Start active opname session
        $so = StockOpname::create([
            'nomor_so' => 'SO-DEDUCT-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $user->id,
        ]);

        // The staff has already counted this item and recorded 5 pcs
        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => 'BRG_DEDUCT_TEST',
            'snapshot_qty' => 10,
            'round_1_qty' => 5,
            'final_qty' => 5,
        ]);

        // Open a sesi kasir
        SesiKasir::create([
            'kode_toko' => 'TK_001',
            'waktu_buka' => now(),
            'dibuka_oleh' => $user->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
        ]);

        // A POS sale of 2 items occurs
        $response = $this->actingAs($user)->post('/transaksi/penjualan/store', [
            'invoice' => 'INV-DEDUCT-TEST',
            'total_harga' => 30000,
            'pembayaran' => 50000,
            'kembali' => 20000,
            'data' => [
                [
                    'nomor_paket' => 'BRG_DEDUCT_TEST',
                    'nama_barang' => 'Test Deduct Product',
                    'method' => 'umum',
                    'jumlah_barang' => 2,
                    'harga_item' => 15000,
                    'harga_jual' => 30000,
                ],
            ],
        ]);

        $response->assertJson([
            'icon' => 'success',
        ]);

        // Verify that the counted physical quantities are automatically reduced by 2
        $item->refresh();
        $this->assertEquals(3, $item->round_1_qty); // 5 - 2 = 3
        $this->assertEquals(3, $item->final_qty);   // 5 - 2 = 3

        // Verify an audit trail log is created for this auto-deduction
        $this->assertDatabaseHas('stock_opname_audits', [
            'stock_opname_id' => $so->id,
            'stock_opname_item_id' => $item->id,
            'action' => 'POS Sale Auto-Deduct',
            'qty_before' => 5,
            'qty_after' => 3,
        ]);
    }

    public function test_admin_can_edit_qty_in_review_mode()
    {
        Http::fake();

        $admin = User::factory()->create(['role' => 'admin']);

        $barang = DataBarang::create([
            'kode' => 'BRG_REVIEW_EDIT',
            'nama_barang' => 'Test Review Product',
            'harga_beli' => '10000',
            'harga_jual' => '15000',
            'harga_grosir' => '12000',
            'jenis_barang' => 'Hijab',
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-REVIEW-EDIT',
            'kode_toko' => 'TK_001',
            'status' => 'Review',
            'petugas_id' => $admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => 'BRG_REVIEW_EDIT',
            'snapshot_qty' => 10,
            'round_1_qty' => 8,
            'final_qty' => 8,
        ]);

        $response = $this->actingAs($admin)->post('/laporan/opname/update-qty-manual', [
            'item_id' => $item->id,
            'qty' => 12,
            'round' => 'final',
        ]);

        $response->assertJson([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui!',
        ]);

        $item->refresh();
        $this->assertEquals(12, $item->final_qty);

        // Verify log action
        $this->assertDatabaseHas('stock_opname_audits', [
            'stock_opname_id' => $so->id,
            'stock_opname_item_id' => $item->id,
            'action' => 'Review Manual Adjust',
            'qty_before' => 8,
            'qty_after' => 12,
            'round' => 0,
        ]);
    }

    public function test_non_admin_cannot_edit_qty_in_review_mode()
    {
        Http::fake();

        $nonAdmin = User::factory()->create(['role' => 'gudang']);
        $admin = User::factory()->create(['role' => 'admin']);

        $barang = DataBarang::create([
            'kode' => 'BRG_REVIEW_FAIL',
            'nama_barang' => 'Test Fail Product',
            'harga_beli' => '10000',
            'harga_jual' => '15000',
            'harga_grosir' => '12000',
            'jenis_barang' => 'Hijab',
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-REVIEW-FAIL',
            'kode_toko' => 'TK_001',
            'status' => 'Review',
            'petugas_id' => $admin->id,
            'supervisor_id' => $nonAdmin->id, // Even if nonAdmin is the assigned supervisor
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => 'BRG_REVIEW_FAIL',
            'snapshot_qty' => 10,
            'round_1_qty' => 8,
            'final_qty' => 8,
        ]);

        $response = $this->actingAs($nonAdmin)->post('/laporan/opname/update-qty-manual', [
            'item_id' => $item->id,
            'qty' => 12,
            'round' => 'final',
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Hanya Admin yang berhak melakukan manual adjustment pada mode Review!',
        ]);
    }

    public function test_round_3_carry_over_and_variance_filtering()
    {
        $so = StockOpname::create([
            'nomor_so' => 'SO-R3-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Recount',
            'petugas_id' => $this->admin->id,
        ]);

        // Item 1: Had difference in Round 1, status 'Need Recount', not resolved in Round 2
        $item1 = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'round_1_qty' => 8,
            'round_2_qty' => 8,
            'final_qty' => 8,
            'status' => 'Need Recount',
            'difference' => -2,
        ]);

        // Item 2: Matched in Round 2 (difference is 0, status 'Match')
        $item2 = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '99999999',
            'snapshot_qty' => 5,
            'round_1_qty' => 5,
            'round_2_qty' => 5,
            'final_qty' => 5,
            'status' => 'Match',
            'difference' => 0,
        ]);

        // Transition from Round 2 (Recount) to Round 3 (Recount with round_3_qty populated)
        $response = $this->actingAs($this->admin)->post('/laporan/opname/generate-recount', [
            'stock_opname_id' => $so->id,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        // Both items should have round_3_qty carried over from round_2_qty
        $this->assertDatabaseHas('stock_opname_items', [
            'id' => $item1->id,
            'round_3_qty' => 8,
            'status' => 'Need Recount',
        ]);

        $this->assertDatabaseHas('stock_opname_items', [
            'id' => $item2->id,
            'round_3_qty' => 5,
            'status' => 'Match',
        ]);

        // Verify DataTables list returns data correctly and filters by variance
        $dtResponse = $this->actingAs($this->admin)->get('/laporan/opname/items-data/'.$so->id.'?variance_only=true');
        $dtResponse->assertStatus(200);

        $data = $dtResponse->json()['data'];
        $ids = collect($data)->pluck('id')->toArray();

        // item1 has difference != 0, item2 has difference == 0
        $this->assertTrue(in_array($item1->id, $ids));
        $this->assertFalse(in_array($item2->id, $ids));
    }

    public function test_sales_during_opname_uses_tanggal_mulai_instead_of_created_at()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $barang = DataBarang::create([
            'kode' => '77777777',
            'nama_barang' => 'Test Time Reference Item',
            'harga_beli' => '10000',
            'harga_jual' => '15000',
            'harga_grosir' => '12000',
            'jenis_barang' => 'Hijab',
        ]);

        $stock = StockToko::create([
            'kode_input' => 'IN-TIME-REF',
            'kode_toko' => 'TK_001',
            'kode_barang' => '77777777',
            'nama_barang' => 'Test Time Reference Item',
            'jumlah' => 100,
            'terjual' => 0,
            'supplier' => 'Test Supplier',
        ]);

        // Create SO session draft 2 hours ago
        $so = StockOpname::create([
            'nomor_so' => 'SO-TIME-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $admin->id,
            'created_at' => now()->subHours(2),
            'tanggal_mulai' => now()->subHours(1), // Started counting 1 hour ago
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '77777777',
            'snapshot_qty' => 100,
            'round_1_qty' => null,
            'final_qty' => 0,
        ]);

        // Transaction 1: 1.5 hours ago (AFTER session draft created_at, but BEFORE counting started tanggal_mulai)
        Transaksi::create([
            'kode_invoice' => 'INV-PRE-SO',
            'kode_toko' => 'TK_001',
            'kode_barang' => '77777777',
            'nama_barang' => 'Test Time Reference Item',
            'metode' => 'umum',
            'jumlah' => 10,
            'harga' => 15000,
            'harga_beli' => 10000,
            'harga_total' => 150000,
            'created_at' => now()->subMinutes(90),
        ]);

        // Transaction 2: 30 minutes ago (AFTER counting started tanggal_mulai)
        Transaksi::create([
            'kode_invoice' => 'INV-DURING-SO',
            'kode_toko' => 'TK_001',
            'kode_barang' => '77777777',
            'nama_barang' => 'Test Time Reference Item',
            'metode' => 'umum',
            'jumlah' => 5,
            'harga' => 15000,
            'harga_beli' => 10000,
            'harga_total' => 75000,
            'created_at' => now()->subMinutes(30),
        ]);

        // Fetch data via items-data API route
        $response = $this->actingAs($admin)->get('/laporan/opname/items-data/'.$so->id);
        $response->assertStatus(200);

        $data = $response->json()['data'];
        $this->assertCount(1, $data);

        // The sales_during_opname should only count the 5 items from Transaction 2, not the 10 from Transaction 1!
        $this->assertEquals(5, $data[0]['sales_during_opname']);
    }

    public function test_cannot_update_qty_manual_with_negative_value()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'kode_toko' => 'TK_001',
            'shift' => 0,
        ]);

        $so = StockOpname::create([
            'nomor_so' => 'SO-MANUAL-NEG-TEST',
            'kode_toko' => 'TK_001',
            'status' => 'Counting',
            'petugas_id' => $admin->id,
        ]);

        $item = StockOpnameItem::create([
            'stock_opname_id' => $so->id,
            'kode_barang' => '88888888',
            'snapshot_qty' => 10,
            'final_qty' => 0,
        ]);

        $response = $this->actingAs($admin)->post('/laporan/opname/update-qty-manual', [
            'item_id' => $item->id,
            'qty' => -5,
            'round' => 1,
        ]);

        $response->assertJson([
            'success' => false,
        ]);

        $item->refresh();
        $this->assertEquals(null, $item->round_1_qty);
    }
}
