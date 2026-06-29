<x-mail::message>
# Invoice Pembayaran

Terima kasih telah berbelanja di Hijabkku. Pembayaran Anda telah berhasil diproses.

<x-mail::panel>
**Invoice:** {{ $pembayaran->kode_invoice }}<br>
**Tanggal:** {{ $pembayaran->created_at->translatedFormat('d F Y, H:i') }}<br>
**Metode Pembayaran:** {{ $paymentMethodLabel }}
</x-mail::panel>

## Informasi Pelanggan

**Nama:** {{ $pesananPickup?->customer_name ?? '-' }}<br>
**Telepon:** {{ $pesananPickup?->customer_phone ?? '-' }}<br>
@if ($pesananPickup?->customer_email)
**Email:** {{ $pesananPickup->customer_email }}<br>
@endif

## Cabang Pengambilan

**{{ $toko?->nama_toko ?? '-' }}**<br>
@if ($toko?->alamat_toko)
{{ $toko->alamat_toko }}<br>
@endif

## Detail Pesanan

@foreach ($transaksis as $item)
- **{{ $item->nama_barang }}** × {{ $item->jumlah }} — Rp {{ number_format($item->harga_total, 0, ',', '.') }}
@endforeach

---

**Subtotal:** Rp {{ number_format($pembayaran->total_harga, 0, ',', '.') }}<br>
**Biaya ({{ $paymentMethodLabel }}):** Rp {{ number_format($fee, 0, ',', '.') }}<br>
**Total Dibayar:** **Rp {{ number_format($pembayaran->pembayaran, 0, ',', '.') }}**

<x-mail::panel>
**Status Pengambilan:** {{ $pesananPickup?->status_pengambilan ?? '-' }}<br><br>
Tunjukkan email ini kepada petugas saat pengambilan pesanan di cabang toko.
</x-mail::panel>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
