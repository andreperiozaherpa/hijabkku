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
        Schema::create('riwayat_revisi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice');
            $table->string('kode_toko');
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('barang_lama_kode');
            $table->string('barang_lama_nama');
            $table->bigInteger('harga_lama');
            $table->string('barang_baru_kode');
            $table->string('barang_baru_nama');
            $table->bigInteger('harga_baru');
            $table->bigInteger('selisih_harga')->default(0);
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_revisi_penjualans');
    }
};
