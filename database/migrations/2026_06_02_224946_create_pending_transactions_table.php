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
        Schema::create('pending_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice')->unique();
            $table->string('kode_toko');
            $table->bigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->bigInteger('total_harga');
            $table->bigInteger('fee')->default(0);
            $table->bigInteger('grand_total');
            $table->string('payment_method'); // QRIS, VA, EWALLET
            $table->string('xendit_id')->nullable(); // Invoice ID from Xendit
            $table->string('checkout_url')->nullable(); // Checkout URL (if needed)
            $table->text('qr_string')->nullable(); // Custom QR String
            $table->string('va_number')->nullable(); // Custom VA Number
            $table->string('status')->default('PENDING'); // PENDING, PAID, FAILED, EXPIRED
            $table->longText('cart_payload'); // JSON data of cart items
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_transactions');
    }
};
