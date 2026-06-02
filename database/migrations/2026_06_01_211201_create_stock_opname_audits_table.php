<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->onDelete('cascade');
            $table->foreignId('stock_opname_item_id')->constrained('stock_opname_items')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->integer('round');
            $table->integer('qty_before')->nullable();
            $table->integer('qty_after');
            $table->string('action');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_audits');
    }
};
