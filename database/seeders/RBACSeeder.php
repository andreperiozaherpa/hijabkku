<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define list of permissions
        $permissions = [
            [
                'name' => 'lihat_dashboard',
                'display_name' => 'Lihat Dashboard',
                'description' => 'Mengizinkan pengguna melihat ringkasan statistik dan grafik penjualan.',
                'module' => 'Dashboard',
            ],
            [
                'name' => 'proses_transaksi',
                'display_name' => 'Proses Transaksi',
                'description' => 'Mengizinkan pengguna mengakses halaman kasir POS untuk memproses transaksi.',
                'module' => 'Transaksi',
            ],
            [
                'name' => 'lihat_daftar_penjualan',
                'display_name' => 'Lihat Daftar Penjualan',
                'description' => 'Mengizinkan pengguna melihat histori transaksi penjualan, detail invoice, dan neraca.',
                'module' => 'Transaksi',
            ],
            [
                'name' => 'kelola_barang',
                'display_name' => 'Kelola Barang',
                'description' => 'Mengizinkan pengguna mengelola data master barang, merek, ukuran, variasi, dan jenis.',
                'module' => 'Manajemen',
            ],
            [
                'name' => 'kelola_supplier',
                'display_name' => 'Kelola Supplier',
                'description' => 'Mengizinkan pengguna menambah, mengedit, dan menghapus data pemasok (supplier).',
                'module' => 'Manajemen',
            ],
            [
                'name' => 'kelola_stok_inout',
                'display_name' => 'Kelola Stok In-Out',
                'description' => 'Mengizinkan pengguna mencatat log mutasi stok masuk dan keluar gudang.',
                'module' => 'Stok',
            ],
            [
                'name' => 'kelola_stok_toko',
                'display_name' => 'Kelola Stok Toko',
                'description' => 'Mengizinkan pengguna memantau dan memperbarui persediaan stok di masing-masing cabang.',
                'module' => 'Stok',
            ],
            [
                'name' => 'kelola_cabang',
                'display_name' => 'Kelola Toko/Cabang',
                'description' => 'Mengizinkan pengguna menambah dan mengelola informasi cabang toko (warehouse).',
                'module' => 'Manajemen',
            ],
            [
                'name' => 'lihat_laporan_penjualan',
                'display_name' => 'Lihat Laporan Penjualan',
                'description' => 'Mengizinkan pengguna mengakses rekapitulasi laporan penjualan.',
                'module' => 'Laporan',
            ],
            [
                'name' => 'kelola_pengguna',
                'display_name' => 'Kelola Pengguna',
                'description' => 'Mengizinkan pengguna mengelola akun pegawai, perubahan role, dan status aktivasi.',
                'module' => 'Manajemen',
            ],
            [
                'name' => 'lihat_buku_panduan',
                'display_name' => 'Lihat Buku Panduan',
                'description' => 'Mengizinkan pengguna membaca panduan penggunaan aplikasi.',
                'module' => 'Panduan',
            ],
        ];

        // Insert or update permissions
        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $perm['name']],
                [
                    'display_name' => $perm['display_name'],
                    'description' => $perm['description'],
                    'module' => $perm['module'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // Get all inserted permissions from DB
        $dbPerms = DB::table('permissions')->pluck('id', 'name');

        // 2. Define role mappings
        $roleMappings = [
            'admin' => [
                'lihat_dashboard',
                'proses_transaksi',
                'lihat_daftar_penjualan',
                'kelola_barang',
                'kelola_supplier',
                'kelola_stok_inout',
                'kelola_stok_toko',
                'kelola_cabang',
                'lihat_laporan_penjualan',
                'kelola_pengguna',
                'lihat_buku_panduan',
            ],
            'kasir' => [
                'lihat_dashboard',
                'proses_transaksi',
                'lihat_daftar_penjualan',
                'lihat_buku_panduan',
            ],
            'gudang' => [
                'lihat_dashboard',
                'kelola_stok_inout',
                'kelola_stok_toko',
                'lihat_buku_panduan',
            ],
        ];

        // Clear existing role permissions first to avoid primary key constraints
        DB::table('role_permissions')->truncate();

        // Seed role permissions
        foreach ($roleMappings as $role => $perms) {
            foreach ($perms as $permName) {
                if (isset($dbPerms[$permName])) {
                    DB::table('role_permissions')->insert([
                        'role' => $role,
                        'permission_id' => $dbPerms[$permName]
                    ]);
                }
            }
        }
    }
}
