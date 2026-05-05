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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice');
            $table->string('kode_toko');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->enum('metode', ['grosir', 'umum']);
            $table->bigInteger('jumlah');
            $table->bigInteger('harga');
            $table->bigInteger('harga_beli');
            $table->bigInteger('harga_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
