<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kasir', 'display_name' => 'Kasir', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'gudang', 'display_name' => 'Gudang', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'supervisor', 'display_name' => 'Supervisor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['gudang', 'kasir', 'admin'])->change();
        });

        Schema::dropIfExists('roles');
    }
};
