<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatRevisiPenjualan extends Model
{
    protected $fillable = [
        'kode_invoice',
        'kode_toko',
        'transaksi_id',
        'user_id',
        'barang_lama_kode',
        'barang_lama_nama',
        'harga_lama',
        'barang_baru_kode',
        'barang_baru_nama',
        'harga_baru',
        'selisih_harga',
        'alasan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
