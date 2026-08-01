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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transfer')->unique()->comment('Reference ID / Idempotency key ke Xendit');
            $table->unsignedBigInteger('rekening_client_id')->nullable();
            $table->string('nama_client');
            $table->string('bank_code');
            $table->string('bank_name')->nullable();
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->string('routing_type')->default('SWIFT');
            $table->string('routing_value')->nullable();
            $table->string('recipient_type')->default('INDIVIDUAL')->comment('INDIVIDUAL / BUSINESS');
            $table->string('relationship')->default('CUSTOMER')->comment('Relasi penerima ke merchant');
            $table->unsignedBigInteger('amount')->comment('Nominal transfer (IDR)');
            $table->string('description')->nullable()->comment('Keterangan yang tampil di statement penerima');
            $table->enum('status', ['PENDING', 'ACCEPTED', 'PROCESSING', 'SUCCEEDED', 'FAILED', 'REVERSED', 'REJECTED', 'CANCELLED'])->default('PENDING');
            $table->string('xendit_payout_id')->nullable();
            $table->string('failure_code')->nullable();
            $table->text('failure_message')->nullable();
            $table->string('source_of_fund')->default('BUSINESS_REVENUE');
            $table->string('purpose_code')->default('OTHER');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['rekening_client_id']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
