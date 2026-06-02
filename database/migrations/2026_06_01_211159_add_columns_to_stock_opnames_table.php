<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->string('nomor_so')->unique()->after('id');
            $table->string('kode_toko')->after('nomor_so');
            $table->enum('status', ['Draft', 'Counting', 'Recount', 'Review', 'Approved', 'Posted'])->default('Draft')->after('kode_toko');
            $table->dateTime('tanggal_mulai')->nullable()->after('status');
            $table->dateTime('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->unsignedBigInteger('petugas_id')->nullable()->after('tanggal_selesai');
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('petugas_id');
            $table->text('notes')->nullable()->after('supervisor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_so',
                'kode_toko',
                'status',
                'tanggal_mulai',
                'tanggal_selesai',
                'petugas_id',
                'supervisor_id',
                'notes'
            ]);
        });
    }
};
