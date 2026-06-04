<?php

namespace App\Http\Controllers;

use App\Models\PesananPickup;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use Carbon\Carbon;

class PesananPickupController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $tokos = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
        } else {
            $tokos = Toko::where('kode', $user->kode_toko)->get();
        }

        return view('transaksi.pickup', [
            'tokos' => $tokos,
        ]);
    }

    /**
     * Fetch data for Datatables.
     */
    public function data(Request $request)
    {
        $user = Auth::user();

        $query = DB::table('pesanan_pickups')
            ->leftJoin('tokos', 'pesanan_pickups.kode_toko', '=', 'tokos.kode')
            ->select('pesanan_pickups.*', 'tokos.nama_toko');

        // Filter by user store if user is not admin
        if ($user->role !== 'admin') {
            $query->where('pesanan_pickups.kode_toko', $user->kode_toko);
        } else {
            // Admin can filter by store
            if ($request->filled('kode_toko') && $request->kode_toko !== 'semua') {
                $query->where('pesanan_pickups.kode_toko', $request->kode_toko);
            }
        }

        // Filter by pickup status
        if ($request->filled('status_pengambilan')) {
            $query->where('pesanan_pickups.status_pengambilan', $request->status_pengambilan);
        } else {
            $query->where('pesanan_pickups.status_pengambilan', 'Belum Diambil');
        }

        return DataTables()->of($query)
            ->addColumn('tanggal', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->locale('id')->translatedFormat('l, d M Y, H:i') : '-';
            })
            ->addColumn('items', function ($row) {
                // Return a summary of items
                $items = DB::table('transaksis')
                    ->where('kode_invoice', $row->kode_invoice)
                    ->select('nama_barang', 'jumlah')
                    ->get();
                
                $summary = [];
                foreach ($items as $item) {
                    $summary[] = $item->nama_barang . ' (' . $item->jumlah . 'x)';
                }
                
                return empty($summary) ? '-' : implode(', ', $summary);
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-icon btn-icon-only btn-outline-primary btn-detail me-1" data-invoice="' . $row->kode_invoice . '" title="Detail Pesanan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                </button>';

                if ($row->status_pengambilan === 'Belum Diambil') {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-icon-only btn-outline-success btn-complete" data-id="' . $row->id . '" data-invoice="' . $row->kode_invoice . '" title="Tandai Sudah Diambil">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi', 'items'])
            ->make(true);
    }

    /**
     * Show items under specific pickup order.
     */
    public function showItems($id)
    {
        $user = Auth::user();
        $pickup = PesananPickup::findOrFail($id);

        if ($user->role !== 'admin' && $pickup->kode_toko !== $user->kode_toko) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $items = DB::table('transaksis')
            ->where('kode_invoice', $pickup->kode_invoice)
            ->get();

        $toko = Toko::where('kode', $pickup->kode_toko)->first();

        // Calculate grand total from transaksis total_harga (harga_total)
        $grandTotal = $items->sum('harga_total');

        return response()->json([
            'pickup' => $pickup,
            'items' => $items,
            'toko' => $toko ? $toko->nama_toko : '-',
            'grand_total' => $grandTotal,
            'grand_total_rupiah' => 'Rp. ' . number_format($grandTotal, 0, ',', '.'),
        ]);
    }

    /**
     * Complete the pickup.
     */
    public function complete($id)
    {
        $user = Auth::user();
        $pickup = PesananPickup::findOrFail($id);

        if ($user->role !== 'admin' && $pickup->kode_toko !== $user->kode_toko) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        if ($pickup->status_pengambilan === 'Sudah Diambil') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah diambil sebelumnya.'
            ], 400);
        }

        $pickup->status_pengambilan = 'Sudah Diambil';
        $pickup->save();

        // Trigger updates in Firebase
        FirebaseService::triggerUpdate('updates/sales', [
            'toko' => $pickup->kode_toko,
            'timestamp' => time()
        ]);

        // Trigger auto-adjust or refresh on any active stock opname session in this shop
        $activeSessions = \App\Models\StockOpname::where('kode_toko', $pickup->kode_toko)
            ->whereIn('status', ['Counting', 'Recount'])
            ->get();

        foreach ($activeSessions as $session) {
            FirebaseService::triggerUpdate('updates/opname_session_' . $session->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diserahkan ke pelanggan.'
        ]);
    }
}
