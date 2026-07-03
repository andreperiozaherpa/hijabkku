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

    // ──────────────────────────────────────────────────────────────────────────
    //  Helper: create a kasir user
    // ──────────────────────────────────────────────────────────────────────────

    protected function makeKasir(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'status' => 'on',
            'role' => 'kasir',
            'kode_toko' => 'TK_test',
            'shift' => 0,
        ], $overrides));
    }

    protected function makeAdmin(): User
    {
        return User::factory()->create([
            'status' => 'on',
            'role' => 'admin',
            'kode_toko' => 'TK_test',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret123'),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  POS page access
    // ──────────────────────────────────────────────────────────────────────────

    public function test_cannot_access_pos_without_open_session()
    {
        $kasir = $this->makeKasir();

        $response = $this->actingAs($kasir)->get('/transaksi/penjualan');
        $response->assertStatus(200);
        $response->assertSee('Belum Buka');
        $response->assertSee('id="modalBukaKasir"', false);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Open session
    // ──────────────────────────────────────────────────────────────────────────

    public function test_can_open_sesi_kasir()
    {
        $kasir = $this->makeKasir();

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
            'is_user_scoped' => true,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Two cashiers can open simultaneous sessions in the same toko
    // ──────────────────────────────────────────────────────────────────────────

    public function test_two_cashiers_can_have_independent_sessions_in_same_toko()
    {
        $kasir1 = $this->makeKasir();
        $kasir2 = $this->makeKasir();

        // Kasir 1 opens their session
        $this->actingAs($kasir1)->post('/transaksi/penjualan/sesi-kasir/buka', [
            'saldo_awal' => 100000,
        ])->assertStatus(200)->assertJson(['icon' => 'success']);

        // Kasir 2 can also open their OWN session simultaneously
        $this->actingAs($kasir2)->post('/transaksi/penjualan/sesi-kasir/buka', [
            'saldo_awal' => 150000,
        ])->assertStatus(200)->assertJson(['icon' => 'success']);

        // Both sessions should exist
        $this->assertDatabaseHas('sesi_kasirs', [
            'dibuka_oleh' => $kasir1->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
        ]);
        $this->assertDatabaseHas('sesi_kasirs', [
            'dibuka_oleh' => $kasir2->id,
            'saldo_awal' => 150000,
            'status' => 'buka',
        ]);
        $this->assertEquals(2, SesiKasir::count());
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Duplicate session guard: same user cannot open two sessions
    // ──────────────────────────────────────────────────────────────────────────

    public function test_same_cashier_cannot_open_duplicate_active_session()
    {
        $kasir = $this->makeKasir();

        SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now(),
            'dibuka_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
            'is_user_scoped' => true,
        ]);

        $response = $this->actingAs($kasir)->post('/transaksi/penjualan/sesi-kasir/buka', [
            'saldo_awal' => 200000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'icon' => 'error',
            'cek_data' => 'Anda sudah memiliki sesi kasir yang sedang aktif!',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Summary and close: each cashier closes their own session
    // ──────────────────────────────────────────────────────────────────────────

    public function test_can_get_summary_and_close_sesi_kasir()
    {
        $kasir = $this->makeKasir();

        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now(),
            'dibuka_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
            'is_user_scoped' => true,
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

    // ──────────────────────────────────────────────────────────────────────────
    //  Cashier B cannot close Cashier A's session
    // ──────────────────────────────────────────────────────────────────────────

    public function test_cashier_cannot_close_another_cashiers_session()
    {
        $kasir1 = $this->makeKasir();
        $kasir2 = $this->makeKasir();

        // Kasir 1 opens a session
        SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now(),
            'dibuka_oleh' => $kasir1->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
            'is_user_scoped' => true,
        ]);

        // Kasir 2 tries to close — but has no session of their own
        $response = $this->actingAs($kasir2)->post('/transaksi/penjualan/sesi-kasir/tutup', [
            'saldo_akhir_aktual' => 100000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'icon' => 'error',
            'cek_data' => 'Tidak ada sesi kasir aktif milik Anda yang dapat ditutup!',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Feature toggle
    // ──────────────────────────────────────────────────────────────────────────

    public function test_feature_sesi_kasir_can_be_disabled()
    {
        SystemSetting::setByKey('fitur_sesi_kasir', 'false');

        $kasir = $this->makeKasir();

        $response = $this->actingAs($kasir)->get('/transaksi/penjualan');
        $response->assertStatus(200);
        $response->assertSee('Nonaktif');
        $response->assertDontSee('id="modalBukaKasir"', false);

        SystemSetting::setByKey('fitur_sesi_kasir', 'true');
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Per-user: cannot reopen own session closed today without admin approval
    // ──────────────────────────────────────────────────────────────────────────

    public function test_cannot_reopen_own_session_closed_today_without_admin_approval()
    {
        $kasir = $this->makeKasir();

        // This kasir already closed their session today
        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now()->subHours(2),
            'waktu_tutup' => now(),
            'dibuka_oleh' => $kasir->id,
            'ditutup_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'tutup',
            'is_user_scoped' => true,
        ]);

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
            'catatan' => 'Pengajuan buka kembali: Minta buka sesi kembali.',
        ]);

        $this->assertEquals(1, SesiKasir::count());
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Admin approve
    // ──────────────────────────────────────────────────────────────────────────

    public function test_can_reopen_session_closed_today_with_admin_approval()
    {
        $kasir = $this->makeKasir();
        $admin = $this->makeAdmin();

        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now()->subHours(2),
            'waktu_tutup' => now(),
            'dibuka_oleh' => $kasir->id,
            'ditutup_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'pending_reopen',
            'is_user_scoped' => true,
            'catatan' => 'Minta buka sesi.',
        ]);

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
            'saldo_awal' => 100000,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Admin reject
    // ──────────────────────────────────────────────────────────────────────────

    public function test_can_reject_session_closed_today_by_admin()
    {
        $kasir = $this->makeKasir();
        $admin = $this->makeAdmin();

        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => now()->subHours(2),
            'waktu_tutup' => now(),
            'dibuka_oleh' => $kasir->id,
            'ditutup_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'pending_reopen',
            'is_user_scoped' => true,
            'catatan' => 'Minta buka sesi.',
        ]);

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
            'status' => 'tutup',
        ]);
    }

    public function test_waktu_tutup_adjusted_when_closed_on_different_day()
    {
        $kasir = $this->makeKasir();

        // Opened 2 days ago at 08:30:15
        $waktuBuka = now()->subDays(2)->setTime(8, 30, 15);

        $session = SesiKasir::create([
            'kode_toko' => 'TK_test',
            'waktu_buka' => $waktuBuka,
            'dibuka_oleh' => $kasir->id,
            'saldo_awal' => 100000,
            'status' => 'buka',
            'is_user_scoped' => true,
        ]);

        $response = $this->actingAs($kasir)->post('/transaksi/penjualan/sesi-kasir/tutup', [
            'saldo_akhir_aktual' => 100000,
            'catatan' => 'Lupa tutup sesi',
        ]);

        $response->assertStatus(200);

        $session->refresh();

        $this->assertEquals($waktuBuka->format('Y-m-d'), $session->waktu_tutup->format('Y-m-d'));
        $this->assertEquals(now()->hour, $session->waktu_tutup->hour);
        $this->assertEquals(now()->minute, $session->waktu_tutup->minute);
    }
}
