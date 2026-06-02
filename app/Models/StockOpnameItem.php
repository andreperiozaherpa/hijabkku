<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function session()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_barang', 'kode');
    }

    public function audits()
    {
        return $this->hasMany(StockOpnameAudit::class, 'stock_opname_item_id');
    }
}
