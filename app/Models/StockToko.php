<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockToko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function data_barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_barang', 'kode');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'kode_toko', 'kode');
    }
}
