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
        Schema::table('rekening_clients', function (Blueprint $table) {
            $table->string('recipient_type', 30)->default('INDIVIDUAL')->after('routing_value');
            $table->string('relationship', 30)->default('CUSTOMER')->after('recipient_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekening_clients', function (Blueprint $table) {
            $table->dropColumn(['recipient_type', 'relationship']);
        });
    }
};
