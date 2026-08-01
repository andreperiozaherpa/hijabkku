<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferPermissionSeeder extends Seeder
{
    /**
     * Tambahkan permission transfer dana dan berikan ke role admin.
     * Tidak menghapus / menruncate data RBAC yang sudah ada.
     */
    public function run(): void
    {
        DB::table('permissions')->updateOrInsert(
            ['name' => 'kelola_transfer'],
            [
                'display_name' => 'Kelola Transfer Dana',
                'description' => 'Mengizinkan pengguna melakukan transfer dana ke rekening client menggunakan saldo Xendit.',
                'module' => 'Keuangan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $permissionId = DB::table('permissions')->where('name', 'kelola_transfer')->value('id');

        foreach (['admin'] as $role) {
            $exists = DB::table('role_permissions')
                ->where('role', $role)
                ->where('permission_id', $permissionId)
                ->exists();

            if (! $exists) {
                DB::table('role_permissions')->insert([
                    'role' => $role,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }
}
