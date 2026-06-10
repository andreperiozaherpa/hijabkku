<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Pembayaran;
use App\Models\RiwayatRevisiPenjualan;
use App\Models\StockToko;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevisiPenjualanController extends Controller
{
    public function index()
    {
        $query = RiwayatRevisiPenjualan::with('user');

        if (Auth::user()->role !== 'admin') {
            $query->where('kode_toko', Auth::user()->kode_toko);
        }

        $riwayats = $query->latest()->paginate(10);

        return view('transaksi.revisi.index', compact('riwayats'));
    }

    public function cariInvoice(Request $request)
    {
        $kode_invoice = $request->kode_invoice;
        if (! $kode_invoice) {
            return response()->json(['success' => false, 'message' => 'Kode invoice tidak boleh kosong']);
        }

        $pembayaran = Pembayaran::where('kode_invoice', $kode_invoice)->first();
        if (! $pembayaran) {
            return response()->json(['success' => false, 'message' => 'Invoice tidak ditemukan']);
        }

        // Cek batasan waktu (hanya bulan berjalan)
        if ($pembayaran->created_at->format('Y-m') !== now()->format('Y-m')) {
            return response()->json(['success' => false, 'message' => 'Revisi hanya dapat dilakukan untuk transaksi pada bulan berjalan ('.now()->format('F Y').')']);
        }

        $transaksis = Transaksi::where('kode_invoice', $kode_invoice)->get();
        if ($transaksis->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Invoice tidak ditemukan']);
        }

        // Cek jika bukan admin, batasi toko
        if (Auth::user()->role !== 'admin' && $transaksis->first()->kode_toko !== Auth::user()->kode_toko) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak akses untuk merevisi transaksi dari toko lain']);
        }

        return response()->json([
            'success' => true,
            'pembayaran' => $pembayaran,
            'transaksis' => $transaksis,
        ]);
    }

    public function cariBarang(Request $request)
    {
        $query = $request->get('query');
        if (! $query) {
            return response()->json([]);
        }

        $kode_toko = $request->get('kode_toko');
        if (Auth::user()->role !== 'admin') {
            $kode_toko = Auth::user()->kode_toko;
        }

        $queryBuilder = DataBarang::query();

        if ($kode_toko) {
            $queryBuilder->whereIn('kode', function ($subQuery) use ($kode_toko) {
                $subQuery->select('kode_barang')
                    ->from('stock_tokos')
                    ->where('kode_toko', $kode_toko);
            });
        }

        $barangs = $queryBuilder->where(function ($q) use ($query) {
            $q->where('nama_barang', 'like', "%{$query}%")
                ->orWhere('kode', 'like', "%{$query}%");
        })
            ->limit(20)
            ->get();

        return response()->json($barangs);
    }

    public function prosesRevisi(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'barang_baru_kode' => 'required|exists:data_barangs,kode',
            'metode_harga_baru' => 'required|in:umum,grosir',
            'pembayaran_baru' => 'required|numeric|min:0',
            'alasan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $transaksi = Transaksi::lockForUpdate()->findOrFail($request->transaksi_id);
                $pembayaran = Pembayaran::lockForUpdate()->where('kode_invoice', $transaksi->kode_invoice)->firstOrFail();

                if (Auth::user()->role !== 'admin' && $transaksi->kode_toko !== Auth::user()->kode_toko) {
                    throw new \Exception('Anda tidak memiliki hak akses untuk merevisi transaksi dari toko lain.');
                }

                if ($transaksi->created_at->format('Y-m') !== now()->format('Y-m')) {
                    throw new \Exception('Revisi hanya dapat dilakukan untuk transaksi pada bulan berjalan.');
                }

                if ($transaksi->kode_barang === $request->barang_baru_kode) {
                    throw new \Exception('Barang pengganti tidak boleh sama dengan barang sebelumnya.');
                }

                $barang_baru = DataBarang::where('kode', $request->barang_baru_kode)->firstOrFail();

                // 1. Update Stok Toko (Barang Lama)
                $stockLama = StockToko::lockForUpdate()
                    ->where('kode_toko', $transaksi->kode_toko)
                    ->where('kode_barang', $transaksi->kode_barang)
                    ->first();

                if ($stockLama) {
                    // Kembalikan ke gudang (kurangi terjual)
                    $stockLama->terjual = max(0, $stockLama->terjual - $transaksi->jumlah);
                    $stockLama->save();
                }

                // 2. Update Stok Toko (Barang Baru)
                $stockBaru = StockToko::lockForUpdate()
                    ->where('kode_toko', $transaksi->kode_toko)
                    ->where('kode_barang', $barang_baru->kode)
                    ->first();

                if ($stockBaru) {
                    $stockBaru->terjual += $transaksi->jumlah;
                    $stockBaru->save();
                } else {
                    // Jika stok barang baru belum pernah diinput di toko tersebut
                    StockToko::create([
                        'kode_input' => 'REV-'.$transaksi->kode_invoice,
                        'kode_toko' => $transaksi->kode_toko,
                        'kode_barang' => $barang_baru->kode,
                        'nama_barang' => $barang_baru->nama_barang,
                        'supplier' => '-',
                        'jumlah' => 0,
                        'terjual' => $transaksi->jumlah,
                    ]);
                }

                // 3. Hitung Harga Baru
                $metode_baru = $request->metode_harga_baru;
                $harga_baru_satuan = ($metode_baru === 'grosir')
                    ? floatval(str_replace('.', '', $barang_baru->harga_grosir))
                    : floatval(str_replace('.', '', $barang_baru->harga_jual));

                $harga_beli_baru = floatval(str_replace('.', '', $barang_baru->harga_beli));
                $harga_total_baru = $harga_baru_satuan * $transaksi->jumlah;

                $selisih_harga_total = $harga_total_baru - $transaksi->harga_total;

                // 4. Update Pembayaran
                $pembayaran->total_harga += $selisih_harga_total;
                $pembayaran->pembayaran = floatval($request->pembayaran_baru);
                $pembayaran->kembalian = $pembayaran->pembayaran - $pembayaran->total_harga;
                $pembayaran->save();

                // 5. Catat Riwayat
                RiwayatRevisiPenjualan::create([
                    'kode_invoice' => $transaksi->kode_invoice,
                    'kode_toko' => $transaksi->kode_toko,
                    'transaksi_id' => $transaksi->id,
                    'user_id' => Auth::id(),
                    'barang_lama_kode' => $transaksi->kode_barang,
                    'barang_lama_nama' => $transaksi->nama_barang,
                    'harga_lama' => $transaksi->harga,
                    'barang_baru_kode' => $barang_baru->kode,
                    'barang_baru_nama' => $barang_baru->nama_barang,
                    'harga_baru' => $harga_baru_satuan,
                    'selisih_harga' => $selisih_harga_total,
                    'alasan' => $request->alasan,
                ]);

                // 6. Update Transaksi
                $transaksi->kode_barang = $barang_baru->kode;
                $transaksi->nama_barang = $barang_baru->nama_barang;
                $transaksi->metode = $metode_baru;
                $transaksi->harga = $harga_baru_satuan;
                $transaksi->harga_beli = $harga_beli_baru;
                $transaksi->harga_total = $harga_total_baru;
                $transaksi->save();
            });

            return response()->json(['success' => true, 'message' => 'Revisi penjualan berhasil diproses!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
