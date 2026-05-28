<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'kode_invoice', 'kode_invoice');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'kode_toko', 'kode');
    }

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'kode_barang', 'kode');
    }
}
