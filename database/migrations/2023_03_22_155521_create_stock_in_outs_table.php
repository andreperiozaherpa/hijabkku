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
        Schema::create('stock_in_outs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_input');
            $table->string('kode_barang');
            $table->string('kode_supplier');
            $table->string('barang');
            $table->string('supplier');
            $table->bigInteger('jumlah_masuk');
            $table->bigInteger('jumlah_keluar')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_outs');
    }
};
