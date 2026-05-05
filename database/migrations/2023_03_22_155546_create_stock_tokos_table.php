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
        Schema::create('stock_tokos', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('id_stock_in');
            $table->string('kode_input');
            $table->string('kode_toko');
            $table->string('kode_barang');
            $table->string('supplier');
            $table->string('nama_barang');
            $table->bigInteger('jumlah');
            $table->bigInteger('terjual')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_tokos');
    }
};
