<?php

use App\Models\DataBarang;
use App\Models\StockOpnameItem;
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
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->decimal('difference_value_jual', 15, 2)->default(0)->after('difference_value');
        });

        // Backfill difference_value_jual for existing rows using harga_jual from master products
        $barangs = DataBarang::pluck('harga_jual', 'kode');

        StockOpnameItem::where('difference', '!=', 0)
            ->whereIn('kode_barang', $barangs->keys())
            ->chunkById(500, function ($items) use ($barangs) {
                foreach ($items as $item) {
                    $harga_jual = floatval(str_replace('.', '', $barangs[$item->kode_barang] ?? '0'));
                    $item->difference_value_jual = $item->difference * $harga_jual;
                    $item->save();
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->dropColumn('difference_value_jual');
        });
    }
};
