<?php

use App\Models\DataBarang;
use App\Models\Pembayaran;
use App\Models\RiwayatRevisiPenjualan;
use App\Models\StockToko;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    Model::unguard();

    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->kasir = User::factory()->create(['role' => 'kasir']);

    $permId = DB::table('permissions')->insertGetId([
        'name' => 'proses_transaksi',
        'display_name' => 'Proses Transaksi',
        'module' => 'Transaksi',
    ]);

    DB::table('role_permissions')->insert([
        'role' => 'admin',
        'permission_id' => $permId,
    ]);

    $this->toko = Toko::create([
        'kode' => 'T01',
        'nama_toko' => 'Toko Pusat',
    ]);

    $this->barangSalah = DataBarang::create([
        'kode' => 'BRG-SALAH',
        'jenis_barang' => 'Jen',
        'nama_barang' => 'Barang Salah',
        'harga_beli' => '50.000',
        'harga_jual' => '100.000',
        'harga_grosir' => '90.000',
    ]);

    $this->barangBenar = DataBarang::create([
        'kode' => 'BRG-BENAR',
        'jenis_barang' => 'Jen',
        'nama_barang' => 'Barang Benar',
        'harga_beli' => '60.000',
        'harga_jual' => '120.000',
        'harga_grosir' => '110.000',
    ]);

    StockToko::create([
        'kode_input' => 'INP01',
        'kode_toko' => 'T01',
        'kode_barang' => 'BRG-SALAH',
        'nama_barang' => 'Barang Salah',
        'supplier' => 'Sup',
        'jumlah' => 10,
        'terjual' => 2, // 2 terjual
    ]);

    StockToko::create([
        'kode_input' => 'INP02',
        'kode_toko' => 'T01',
        'kode_barang' => 'BRG-BENAR',
        'nama_barang' => 'Barang Benar',
        'supplier' => 'Sup',
        'jumlah' => 10,
        'terjual' => 0,
    ]);

    $this->pembayaran = Pembayaran::create([
        'kode_invoice' => 'INV-001',
        'user_id' => $this->kasir->id,
        'user_name' => $this->kasir->name,
        'total_harga' => 200000,
        'pembayaran' => 250000,
        'kembalian' => 50000,
        'created_at' => now(),
    ]);

    $this->transaksi = Transaksi::create([
        'kode_invoice' => 'INV-001',
        'kode_toko' => 'T01',
        'kode_barang' => 'BRG-SALAH',
        'nama_barang' => 'Barang Salah',
        'metode' => 'umum',
        'jumlah' => 2,
        'harga' => 100000,
        'harga_beli' => 50000,
        'harga_total' => 200000,
        'created_at' => now(),
    ]);
});

it('can search invoice', function () {
    $response = $this->actingAs($this->admin)->getJson(route('revisi.cari', ['kode_invoice' => 'INV-001']));

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('pembayaran.kode_invoice', 'INV-001');
});

it('can search replacement items with store filter', function () {
    // Buat barang ketiga yang tidak ada stoknya di T01
    DataBarang::create([
        'kode' => 'BRG-LAIN',
        'jenis_barang' => 'Jen',
        'nama_barang' => 'Barang Lain',
        'harga_beli' => '60.000',
        'harga_jual' => '120.000',
        'harga_grosir' => '110.000',
    ]);

    // Cari barang dengan kode_toko = T01, "Barang Lain" tidak boleh muncul
    $response = $this->actingAs($this->admin)->getJson(route('revisi.cari_barang', [
        'query' => 'Barang',
        'kode_toko' => 'T01',
    ]));

    $response->assertStatus(200);
    $data = $response->json();

    // Harus ada 2 barang saja ("Barang Salah", "Barang Benar") karena "Barang Lain" tidak punya StockToko di T01
    expect(count($data))->toBe(2);

    $kodes = collect($data)->pluck('kode')->all();
    expect($kodes)->toContain('BRG-SALAH');
    expect($kodes)->toContain('BRG-BENAR');
    expect($kodes)->not->toContain('BRG-LAIN');
});

it('can render index page with riwayats pagination', function () {
    // Buat riwayat dummy
    RiwayatRevisiPenjualan::create([
        'kode_invoice' => 'INV-001',
        'kode_toko' => 'T01',
        'transaksi_id' => $this->transaksi->id,
        'user_id' => $this->admin->id,
        'barang_lama_kode' => 'BRG-SALAH',
        'barang_lama_nama' => 'Barang Salah',
        'harga_lama' => 100000,
        'barang_baru_kode' => 'BRG-BENAR',
        'barang_baru_nama' => 'Barang Benar',
        'harga_baru' => 120000,
        'selisih_harga' => 20000,
        'alasan' => 'Test',
    ]);

    $response = $this->actingAs($this->admin)->get(route('revisi.index'));

    $response->assertStatus(200)
        ->assertViewHas('riwayats')
        ->assertSee('Daftar Riwayat Revisi Penjualan')
        ->assertSee('INV-001');
});

it('can process revisi penjualan successfully', function () {
    // Aksi revisi
    $response = $this->actingAs($this->admin)->postJson(route('revisi.proses'), [
        'transaksi_id' => $this->transaksi->id,
        'barang_baru_kode' => 'BRG-BENAR',
        'pembayaran_baru' => 250000,
        'alasan' => 'Salah klik varian',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', true);

    // Verifikasi stok berubah
    $stokSalah = StockToko::where('kode_barang', 'BRG-SALAH')->first();
    expect($stokSalah->terjual)->toBe(0); // 2 - 2 = 0

    $stokBenar = StockToko::where('kode_barang', 'BRG-BENAR')->first();
    expect($stokBenar->terjual)->toBe(2); // 0 + 2 = 2

    // Verifikasi transaksi terupdate
    $this->transaksi->refresh();
    expect($this->transaksi->kode_barang)->toBe('BRG-BENAR');
    expect($this->transaksi->harga)->toBe(120000);
    expect($this->transaksi->harga_total)->toBe(240000);

    // Verifikasi pembayaran terupdate
    $this->pembayaran->refresh();
    expect($this->pembayaran->total_harga)->toBe(240000); // 200k + (240k - 200k)
    expect($this->pembayaran->kembalian)->toBe(10000); // 250k bayar - 240k

    // Verifikasi riwayat tercatat
    $riwayat = RiwayatRevisiPenjualan::first();
    expect($riwayat)->not->toBeNull();
    expect($riwayat->barang_lama_kode)->toBe('BRG-SALAH');
    expect($riwayat->barang_baru_kode)->toBe('BRG-BENAR');
    expect($riwayat->selisih_harga)->toBe(40000); // 240k - 200k
});

it('cannot revise non-current month transaction', function () {
    $this->pembayaran->update(['created_at' => now()->subMonth()]);
    $this->transaksi->update(['created_at' => now()->subMonth()]);

    $response = $this->actingAs($this->admin)->postJson(route('revisi.proses'), [
        'transaksi_id' => $this->transaksi->id,
        'barang_baru_kode' => 'BRG-BENAR',
        'pembayaran_baru' => 250000,
        'alasan' => 'Expired',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Revisi hanya dapat dilakukan untuk transaksi pada bulan berjalan.');
});

it('prevents kasir from accessing revisi penjualan page', function () {
    $response = $this->actingAs($this->kasir)->get(route('revisi.index'));

    // Asumsikan middleware akan mendirect atau membalas 403.
    // Jika auth tidak cukup, biasanya redirect atau abort.
    // Tapi karena role:admin kita mungkin expect Forbidden (403).
    $response->assertStatus(403);
});

it('restricts non-admin users to their own store', function () {
    // Berikan permission ke kasir
    $permId = DB::table('permissions')->where('name', 'proses_transaksi')->value('id');
    DB::table('role_permissions')->insert([
        'role' => 'kasir',
        'permission_id' => $permId,
    ]);

    // Kasir 1 di Toko T01 (Toko yang sama dengan transaksi)
    $kasirToko1 = User::factory()->create(['role' => 'kasir', 'kode_toko' => 'T01']);
    // Kasir 2 di Toko T02 (Toko yang berbeda)
    $kasirToko2 = User::factory()->create(['role' => 'kasir', 'kode_toko' => 'T02']);

    // Kasir 1 cari invoice di tokonya sendiri -> sukses
    $response1 = $this->actingAs($kasirToko1)->getJson(route('revisi.cari', ['kode_invoice' => 'INV-001']));
    $response1->assertStatus(200)->assertJsonPath('success', true);

    // Kasir 2 cari invoice di toko lain -> gagal (akses dibatasi)
    $response2 = $this->actingAs($kasirToko2)->getJson(route('revisi.cari', ['kode_invoice' => 'INV-001']));
    $response2->assertStatus(200)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Anda tidak memiliki hak akses untuk merevisi transaksi dari toko lain');

    // Kasir 2 cari barang pengganti -> kode_toko otomatis dioverwrite ke toko kasir tersebut (T02)
    // Di T02 tidak ada barang apapun di StockToko, jadi pencarian harus 0 hasil
    $response3 = $this->actingAs($kasirToko2)->getJson(route('revisi.cari_barang', [
        'query' => 'Barang',
        'kode_toko' => 'T01', // mencoba bypass
    ]));
    $response3->assertStatus(200)->assertJsonCount(0);

    // Kasir 2 mencoba memproses revisi transaksi di toko T01 -> gagal
    $response4 = $this->actingAs($kasirToko2)->postJson(route('revisi.proses'), [
        'transaksi_id' => $this->transaksi->id,
        'barang_baru_kode' => 'BRG-BENAR',
        'pembayaran_baru' => 250000,
        'alasan' => 'Bypass',
    ]);
    $response4->assertStatus(200)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Anda tidak memiliki hak akses untuk merevisi transaksi dari toko lain.');
});
