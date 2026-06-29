<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stockTokos()
    {
        return $this->hasMany(StockToko::class, 'kode_barang', 'kode');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'kode_barang', 'kode');
    }

    public function stockInOuts()
    {
        return $this->hasMany(StockInOut::class, 'kode_barang', 'kode');
    }

    public function fotos()
    {
        return $this->hasMany(FotoBarang::class, 'data_barang_id');
    }
}
