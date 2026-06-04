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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index('kode_invoice', 'pembayarans_kode_invoice_idx');
            $table->index('user_id', 'pembayarans_user_id_idx');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->index('kode_invoice', 'transaksis_kode_invoice_idx');
            $table->index('created_at', 'transaksis_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex('pembayarans_kode_invoice_idx');
            $table->dropIndex('pembayarans_user_id_idx');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex('transaksis_kode_invoice_idx');
            $table->dropIndex('transaksis_created_at_idx');
        });
    }
};
