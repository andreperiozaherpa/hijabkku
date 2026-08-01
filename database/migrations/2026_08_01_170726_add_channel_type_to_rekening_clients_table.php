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
            $table->string('channel_type', 20)->default('BANK')->comment('BANK / EWALLET')->after('relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekening_clients', function (Blueprint $table) {
            $table->dropColumn('channel_type');
        });
    }
};
