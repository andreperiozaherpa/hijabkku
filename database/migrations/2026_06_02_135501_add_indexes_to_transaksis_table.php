<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->index(['kode_barang', 'kode_toko', 'created_at'], 'transaksis_barang_toko_date_idx');
            $table->index('kode_toko', 'transaksis_kode_toko_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex('transaksis_barang_toko_date_idx');
            $table->dropIndex('transaksis_kode_toko_idx');
        });
    }
};
