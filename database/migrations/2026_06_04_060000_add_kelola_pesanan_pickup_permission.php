<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Insert permission
        $permissionId = DB::table('permissions')->insertGetId([
            'name' => 'kelola_pesanan_pickup',
            'display_name' => 'Kelola Pesanan Pickup',
            'description' => 'Mengizinkan pengguna melihat daftar pesanan pickup online dan memproses pengambilannya.',
            'module' => 'Transaksi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Map permission to admin and kasir roles
        DB::table('role_permissions')->insert([
            [
                'role' => 'admin',
                'permission_id' => $permissionId,
            ],
            [
                'role' => 'kasir',
                'permission_id' => $permissionId,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = DB::table('permissions')->where('name', 'kelola_pesanan_pickup')->first();
        if ($permission) {
            DB::table('role_permissions')->where('permission_id', $permission->id)->delete();
            DB::table('permissions')->where('id', $permission->id)->delete();
        }
    }
};
