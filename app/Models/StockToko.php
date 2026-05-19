<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockToko extends Model
{
    use HasFactory;

    public function data_barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_barang', 'kode');
    }
}
