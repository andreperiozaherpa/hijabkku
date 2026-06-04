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
        Schema::create('pesanan_pickups', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice')->unique();
            $table->string('kode_toko');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->enum('status_pengambilan', ['Belum Diambil', 'Sudah Diambil'])->default('Belum Diambil');
            $table->timestamps();

            $table->index(['kode_toko', 'status_pengambilan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_pickups');
    }
};
