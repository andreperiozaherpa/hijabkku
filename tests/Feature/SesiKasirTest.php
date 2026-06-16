<?php

namespace Tests\Feature;

use App\Models\Pembayaran;
use App\Models\SesiKasir;
use App\Models\SystemSetting;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SesiKasirTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RBACSeeder::class);

        $toko = new Toko;
        $toko->kode = 'TK_test';
        $toko->nama_toko = 'Test Toko';
        $toko->save();
    }

    public function test_cannot_access_pos_without_open_session()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($kasir)->get('/transaksi/penjualan');
        $response->assertStatus(200);
        $response->assertSee('Belum Buka');
        $response->assertSee('id="modalBukaKasir"', false);
    }

    public function test_can_open_sesi_kasir()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $response = $this->actingAs($kasir)->post('/transaksi/penjualan/sesi-kasir/buka', [
            'saldo_awal' => 100000,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'icon' => 'success',
            'cek_data' => 'Sesi kasir berhasil dibuka!',
        ]);

        $this->assertDatabaseHas('sesi_kasirs', [
            'kode_toko' => 'TK_test',
            'saldo_awal' => 100000,
            'status' => 'buka',
            'dibuka_oleh' => $kasir->id,
        ]);
    }

    public function test_cannot_open_duplicate_active_sesi_kasir()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now(),
            'dibuka_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
        ]);

        $response = $this->actingAs($kasir)->post('/transaksi/penjualan/sesi-kasir/buka', [
            'saldo_awal' => 200000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'icon' => 'error',
            'cek_data' => 'Sesi kasir untuk toko ini sudah dibuka!',
        ]);
    }

    public function test_can_get_summary_and_close_sesi_kasir()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now(),
            'dibuka_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
        ]);

        // Add some payments linked to the session
        Pembayaran::create([
            'kode_invoice' => 'INV-001',
            'user_id' => $kasir->id,
            'user_name' => $kasir->name,
            'total_harga' => 150000,
            'pembayaran' => 200000,
            'kembalian' => 50000,
            'sesi_kasir_id' => $session->id,
        ]);

        // Get summary
        $response = $this->actingAs($kasir)->get('/transaksi/penjualan/sesi-kasir/summary');
        $response->assertStatus(200);
        $response->assertJson([
            'saldo_awal' => 100000,
            'total_penjualan' => 150000,
            'saldo_akhir_sistem' => 250000,
        ]);

        // Close session with 255000 actual cash (5000 surplus)
        $closeResponse = $this->actingAs($kasir)->post('/transaksi/penjualan/sesi-kasir/tutup', [
            'saldo_akhir_aktual' => 255000,
            'catatan' => 'Surplus Rp 5.000',
        ]);

        $closeResponse->assertStatus(200);
        $closeResponse->assertJson([
            'icon' => 'success',
            'cek_data' => 'Sesi kasir berhasil ditutup!',
        ]);

        $this->assertDatabaseHas('sesi_kasirs', [
            'id' => $session->id,
            'status' => 'tutup',
            'total_penjualan' => 150000,
            'saldo_akhir_sistem' => 250000,
            'saldo_akhir_aktual' => 255000,
            'selisih' => 5000,
            'catatan' => 'Surplus Rp 5.000',
            'ditutup_oleh' => $kasir->id,
        ]);
    }

    public function test_feature_sesi_kasir_can_be_disabled()
    {
        // Disable feature
        SystemSetting::setByKey('fitur_sesi_kasir', 'false');

        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // Access POS: should not see active session or open modal
        $response = $this->actingAs($kasir)->get('/transaksi/penjualan');
        $response->assertStatus(200);
        $response->assertSee('Nonaktif');
        $response->assertDontSee('id="modalBukaKasir"', false);

        // Can store transaction without active session
        $transResponse = $this->actingAs($kasir)->post('/transaksi/penjualan/store', [
            'invoice' => 'TRHJ_test1234',
            'total_harga' => 100000,
            'pembayaran' => 100000,
            'kembali' => 0,
            'data' => [
                [
                    'nomor_paket' => 'BARANG001',
                    'nama_barang' => 'Test Barang',
                    'method' => 'Ecer',
                    'jumlah_barang' => 1,
                    'harga_item' => 100000,
                    'harga_jual' => 100000,
                ],
            ],
        ]);

        // Re-enable for subsequent tests
        SystemSetting::setByKey('fitur_sesi_kasir', 'true');
    }

    public function test_cannot_reopen_session_closed_today_without_admin_approval()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        // Close a session today
        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now()->subHours(2),
            'waktu_tutup' => now(),
            'dibuka_oleh' => $kasir->id,
            'ditutup_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'tutup',
        ]);

        // Attempt to open another session (should update the existing session to pending_reopen)
        $response = $this->actingAs($kasir)->post('/transaksi/penjualan/sesi-kasir/buka', [
            'catatan' => 'Minta buka sesi kembali.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'icon' => 'success',
            'require_approval' => true,
            'cek_data' => 'Pengajuan pembukaan kembali sesi kasir telah dikirim ke Admin. Silakan tunggu persetujuan.',
        ]);

        $this->assertDatabaseHas('sesi_kasirs', [
            'id' => $session->id,
            'status' => 'pending_reopen',
            'saldo_awal' => 100000,
            'catatan' => 'Pengajuan buka kembali: Minta buka sesi kembali.',
        ]);

        // Ensure no duplicate session row was created
        $this->assertEquals(1, SesiKasir::count());
    }

    public function test_can_reopen_session_closed_today_with_admin_approval()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret123'),
        ]);

        // Close a session today
        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now()->subHours(2),
            'waktu_tutup' => now(),
            'dibuka_oleh' => $kasir->id,
            'ditutup_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'tutup',
        ]);

        // Set status to pending_reopen
        $session->update([
            'status' => 'pending_reopen',
            'catatan' => 'Minta buka sesi.',
        ]);

        // Admin approves the request
        $response = $this->actingAs($admin)->post("/laporan/sesi-kasir/approve/{$session->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'icon' => 'success',
            'cek_data' => 'Sesi kasir berhasil disetujui dan dibuka kembali!',
        ]);

        $this->assertDatabaseHas('sesi_kasirs', [
            'id' => $session->id,
            'status' => 'buka',
            'waktu_tutup' => null,
            'ditutup_oleh' => null,
            'saldo_awal' => 100000, // Keadaan modal awal / uang kembalian masih sama
        ]);
    }

    public function test_can_reject_session_closed_today_by_admin()
    {
        $kasir = User::factory()->create([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ]);

        $admin = User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret123'),
        ]);

        // Close a session today
        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now()->subHours(2),
            'waktu_tutup' => now(),
            'dibuka_oleh' => $kasir->id,
            'ditutup_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'tutup',
        ]);

        // Set status to pending_reopen
        $session->update([
            'status' => 'pending_reopen',
            'catatan' => 'Minta buka sesi.',
        ]);

        // Admin rejects the request
        $response = $this->actingAs($admin)->post("/laporan/sesi-kasir/reject/{$session->id}", [
            'alasan' => 'Saldo awal tidak valid.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'icon' => 'success',
            'cek_data' => 'Pengajuan sesi kasir berhasil ditolak.',
        ]);

        $this->assertDatabaseHas('sesi_kasirs', [
            'id' => $session->id,
            'status' => 'tutup', // back to tutup status
        ]);
    }
}
