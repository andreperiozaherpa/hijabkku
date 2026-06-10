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
            $table->index(['kode_toko', 'metode', 'created_at'], 'transaksis_toko_metode_date_idx');
            $table->index(['metode', 'created_at'], 'transaksis_metode_date_idx');
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'pembayarans_user_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex('transaksis_toko_metode_date_idx');
            $table->dropIndex('transaksis_metode_date_idx');
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex('pembayarans_user_date_idx');
        });
    }
};
