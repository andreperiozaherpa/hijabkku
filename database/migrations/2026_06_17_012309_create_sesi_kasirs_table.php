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
        Schema::create('sesi_kasirs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_toko');
            $table->timestamp('waktu_buka');
            $table->unsignedBigInteger('dibuka_oleh');
            $table->bigInteger('saldo_awal');
            $table->timestamp('waktu_tutup')->nullable();
            $table->unsignedBigInteger('ditutup_oleh')->nullable();
            $table->bigInteger('total_penjualan')->nullable();
            $table->bigInteger('saldo_akhir_sistem')->nullable();
            $table->bigInteger('saldo_akhir_aktual')->nullable();
            $table->bigInteger('selisih')->nullable();
            $table->string('status')->default('buka');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_kasirs');
    }
};
