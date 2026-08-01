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
        Schema::create('rekening_clients', function (Blueprint $table) {
            $table->id();
            $table->string('nama_client');
            $table->string('bank_code')->comment('Kode bank tujuan, mis. BCA, BNI, BRI, MANDIRI');
            $table->string('bank_name')->nullable();
            $table->string('account_number');
            $table->string('account_holder_name')->comment('Nama pemilik rekening sesuai bank');
            $table->string('routing_type')->default('SWIFT');
            $table->string('routing_value')->nullable()->comment('SWIFT/BIC code bank tujuan');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['bank_code']);
            $table->index(['account_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_clients');
    }
};
