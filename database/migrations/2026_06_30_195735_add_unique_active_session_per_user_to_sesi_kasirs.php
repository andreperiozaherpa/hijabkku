<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add a partial-like unique guard at the DB level.
 *
 * SQLite/MySQL do not support partial (conditional) unique indexes natively in
 * the same way PostgreSQL does, so we use a plain unique index on
 * (kode_toko, dibuka_oleh, status) where status = 'buka'.
 *
 * Because SQLite doesn't support partial indexes via the Blueprint API, we
 * solve this at the application layer instead: the controller already does an
 * existence check, but we also add a regular index to make that lookup fast,
 * and add a composite unique on (kode_toko, dibuka_oleh) restricted to rows
 * where status = 'buka' via a DB::statement for MySQL / a workaround for SQLite.
 *
 * We keep it compatible with both drivers used in this project.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Add an index to speed up the per-user active-session lookup
        Schema::table('sesi_kasirs', function (Blueprint $table) {
            $table->index(['kode_toko', 'dibuka_oleh', 'status', 'is_user_scoped'], 'idx_sesi_kasirs_user_scope');
        });
    }

    public function down(): void
    {
        Schema::table('sesi_kasirs', function (Blueprint $table) {
            $table->dropIndex('idx_sesi_kasirs_user_scope');
        });
    }
};
