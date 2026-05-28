<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class, 'kode_toko', 'kode');
    }

    public function stockTokos()
    {
        return $this->hasMany(StockToko::class, 'kode_toko', 'kode');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'kode_toko', 'kode');
    }
}
