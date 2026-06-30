<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds `user_scope` flag to sesi_kasirs so that each cashier has their own
     * independent session per day (shift-based). Previously the session was
     * scoped only by toko, meaning one toko = one global session per day.
     * After this migration: one toko + one user = one session per day.
     *
     * The `is_user_scoped` column acts as a feature-flag so existing sessions
     * (scoped by toko) remain untouched. New sessions created after this
     * migration will have is_user_scoped = true.
     */
    public function up(): void
    {
        Schema::table('sesi_kasirs', function (Blueprint $table) {
            // Flag: whether this session uses per-user scoping (shift-based)
            $table->boolean('is_user_scoped')->default(true)->after('status');
        });

        // Mark all existing sessions as NOT user-scoped (legacy, per-toko behaviour)
        DB::table('sesi_kasirs')->update(['is_user_scoped' => false]);
    }

    public function down(): void
    {
        Schema::table('sesi_kasirs', function (Blueprint $table) {
            $table->dropColumn('is_user_scoped');
        });
    }
};
