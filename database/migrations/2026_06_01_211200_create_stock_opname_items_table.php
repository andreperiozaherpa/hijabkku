<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->onDelete('cascade');
            $table->string('kode_barang');
            $table->string('rak_lokasi')->nullable();
            $table->integer('snapshot_qty')->default(0);
            $table->integer('round_1_qty')->nullable();
            $table->integer('round_2_qty')->nullable();
            $table->integer('round_3_qty')->nullable();
            $table->integer('final_qty')->default(0);
            $table->integer('difference')->default(0);
            $table->decimal('difference_value', 15, 2)->default(0);
            $table->string('reason')->nullable();
            $table->enum('status', ['Match', 'Need Recount', 'Reviewed', 'Finalized'])->default('Match');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_items');
    }
};
