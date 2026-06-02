<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isAdmin = Auth::user()->role === 'admin';

        if ($isAdmin) {
            $toko = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
        } else {
            $toko = Toko::where('kode', Auth::user()->kode_toko)->get();
        }

        return view('laporan.barang.index', [
            'toko' => $toko,
        ]);
    }

    /**
     * Fetch report summary or server-side DataTable items.
     */
    public function show(Request $request)
    {
        $param = $request->param;
        $date = $request->date;
        $toko = $request->toko;
        $metode = $request->input('metode', 'semua');
        $isAdmin = Auth::user()->role === 'admin';

        // 1. DataTables Server-Side Pagination
        if ($request->has('draw')) {
            $query = DB::table('transaksis')
                ->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice');

            // Apply date filters
            if ($param === 'hari') {
                $query->whereDate('transaksis.created_at', $date);
            } elseif ($param === 'bulan') {
                $parts = explode('-', $date);
                if (count($parts) === 2) {
                    $query->whereYear('transaksis.created_at', $parts[0])
                          ->whereMonth('transaksis.created_at', $parts[1]);
                } else {
                    $query->whereDate('transaksis.created_at', 'like', '%' . $date . '%');
                }
            } elseif ($param === 'tahun') {
                $query->whereYear('transaksis.created_at', $date);
            }

            // Apply toko filters
            if ($toko !== 'semua' && !empty($toko)) {
                $query->where('transaksis.kode_toko', $toko);
            } else {
                $excludedTokoCodes = DB::table('tokos')
                    ->whereIn('nama_toko', ['stock hilang', 'Online Shop'])
                    ->pluck('kode')
                    ->toArray();
                if (!empty($excludedTokoCodes)) {
                    $query->whereNotIn('transaksis.kode_toko', $excludedTokoCodes);
                }
            }

            // Apply role restriction
            if (!$isAdmin) {
                $query->where('pembayarans.user_id', Auth::id());
            }

            // Apply metode filter
            if ($metode !== 'semua' && !empty($metode)) {
                $query->where('transaksis.metode', $metode);
            }

            // Base query for counting unique products after filtering
            $countQuery = clone $query;
            $countQuery->select('transaksis.kode_barang', 'transaksis.nama_barang');

            // Apply search filter (if any)
            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function($q) use ($searchValue) {
                    $q->where('transaksis.nama_barang', 'like', '%' . $searchValue . '%')
                      ->orWhere('transaksis.kode_barang', 'like', '%' . $searchValue . '%');
                });
                $countQuery->where(function($q) use ($searchValue) {
                    $q->where('transaksis.nama_barang', 'like', '%' . $searchValue . '%')
                      ->orWhere('transaksis.kode_barang', 'like', '%' . $searchValue . '%');
                });
            }

            // Get total count using efficient subquery for GROUP BY compatibility
            $totalRecordsSql = "SELECT COUNT(DISTINCT CONCAT(transaksis.kode_barang, '-', transaksis.nama_barang)) as aggregate FROM transaksis JOIN pembayarans ON transaksis.kode_invoice = pembayarans.kode_invoice";
            
            // Build where constraints dynamically for counts
            $baseWhereSql = "";
            $bindings = [];
            // Re-apply conditions on basic sql to get lightning fast count
            // However, using Laravel's subquery count is cleaner and safer:
            $totalRecords = DB::table(DB::raw("({$countQuery->groupBy('transaksis.kode_barang', 'transaksis.nama_barang')->toSql()}) as sub"))
                ->mergeBindings($countQuery)
                ->count();

            $totalFiltered = $totalRecords;

            // Grouping and aggregation
            $query->select(
                'transaksis.kode_barang',
                'transaksis.nama_barang',
                DB::raw('SUM(transaksis.jumlah) as total_terjual'),
                DB::raw('SUM(transaksis.harga_total) as total_omzet'),
                DB::raw('SUM(transaksis.jumlah * transaksis.harga_beli) as total_modal'),
                DB::raw('SUM(transaksis.harga_total) - SUM(transaksis.jumlah * transaksis.harga_beli) as laba'),
                DB::raw('COUNT(DISTINCT transaksis.kode_invoice) as total_transaksi'),
                DB::raw('AVG(transaksis.harga) as avg_harga')
            )->groupBy('transaksis.kode_barang', 'transaksis.nama_barang');

            // Ordering
            $orderColumnIdx = $request->input('order.0.column', 2); // Default to total terjual (qty)
            $orderDir = $request->input('order.0.dir', 'desc');
            $columns = [
                'transaksis.nama_barang',
                'transaksis.kode_barang',
                'total_terjual',
                'total_omzet',
                'avg_harga',
                'total_transaksi',
                'laba'
            ];
            $orderColumn = isset($columns[$orderColumnIdx]) ? $columns[$orderColumnIdx] : 'total_terjual';
            $query->orderBy($orderColumn, $orderDir);

            // Pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 25);
            if ($length != -1) {
                $query->skip($start)->take($length);
            }

            $items = $query->get();

            $data = $items->map(function($item) use ($isAdmin) {
                return [
                    'nama_barang' => $item->nama_barang,
                    'kode_barang' => $item->kode_barang,
                    'total_terjual' => (int)$item->total_terjual,
                    'total_omzet' => (float)$item->total_omzet,
                    'avg_harga' => (float)$item->avg_harga,
                    'total_transaksi' => (int)$item->total_transaksi,
                    // Hide profit/modal fields for non-admin
                    'laba' => $isAdmin ? (float)$item->laba : 0,
                    'total_modal' => $isAdmin ? (float)$item->total_modal : 0
                ];
            });

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $data
            ]);
        }

        // 2. Summary Request (KPI & Chart)
        if (in_array($param, ['hari', 'bulan', 'tahun'])) {
            $baseQuery = DB::table('transaksis')
                ->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice');

            // Apply date filters
            if ($param === 'hari') {
                $baseQuery->whereDate('transaksis.created_at', $date);
            } elseif ($param === 'bulan') {
                $parts = explode('-', $date);
                if (count($parts) === 2) {
                    $baseQuery->whereYear('transaksis.created_at', $parts[0])
                              ->whereMonth('transaksis.created_at', $parts[1]);
                } else {
                    $baseQuery->whereDate('transaksis.created_at', 'like', '%' . $date . '%');
                }
            } elseif ($param === 'tahun') {
                $baseQuery->whereYear('transaksis.created_at', $date);
            }

            // Apply toko filters
            if ($toko !== 'semua' && !empty($toko)) {
                $baseQuery->where('transaksis.kode_toko', $toko);
            } else {
                $excludedTokoCodes = DB::table('tokos')
                    ->whereIn('nama_toko', ['stock hilang', 'Online Shop'])
                    ->pluck('kode')
                    ->toArray();
                if (!empty($excludedTokoCodes)) {
                    $baseQuery->whereNotIn('transaksis.kode_toko', $excludedTokoCodes);
                }
            }

            // Apply role restriction
            if (!$isAdmin) {
                $baseQuery->where('pembayarans.user_id', Auth::id());
            }

            // Apply metode filter
            if ($metode !== 'semua' && !empty($metode)) {
                $baseQuery->where('transaksis.metode', $metode);
            }

            // Aggregates for KPI cards
            $totalUnit = (int)(clone $baseQuery)->sum('transaksis.jumlah');
            $totalOmzet = (float)(clone $baseQuery)->sum('transaksis.harga_total');
            
            // Laba Kotor (Admin only)
            $totalLaba = 0;
            if ($isAdmin) {
                $totalLaba = (float)(clone $baseQuery)->select(DB::raw('SUM(transaksis.harga_total - (transaksis.jumlah * transaksis.harga_beli)) as total_laba'))->first()->total_laba;
            }

            // Total Jenis Barang
            $totalJenis = (int)(clone $baseQuery)->distinct()->count('transaksis.kode_barang');

            // Top 10 items for visual chart ranking
            $chartQuery = clone $baseQuery;
            $chartItems = $chartQuery->select(
                'transaksis.nama_barang',
                DB::raw('SUM(transaksis.jumlah) as total_terjual'),
                DB::raw('SUM(transaksis.harga_total) as total_omzet')
            )
            ->groupBy('transaksis.kode_barang', 'transaksis.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

            $data = [
                'total' => [
                    'jenis' => $totalJenis,
                    'unit' => $totalUnit,
                    'omzet' => $totalOmzet,
                    'laba' => $totalLaba
                ],
                'chart' => $chartItems
            ];
            $parameter = $param;
        } else {
            return response()->json([
                'message' => 'Parameter tidak valid',
                'icon' => 'error'
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'param' => $parameter
        ]);
    }

    /**
     * Get transaction details of a specific item for popup modal.
     */
    public function detail(Request $request)
    {
        $kode_barang = $request->kode_barang;
        $param = $request->param;
        $date = $request->date;
        $toko = $request->toko;
        $isAdmin = Auth::user()->role === 'admin';

        $query = DB::table('transaksis')
            ->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
            ->join('tokos', 'transaksis.kode_toko', '=', 'tokos.kode')
            ->join('users', 'pembayarans.user_id', '=', 'users.id')
            ->where('transaksis.kode_barang', $kode_barang);

        // Apply date filters
        if ($param === 'hari') {
            $query->whereDate('transaksis.created_at', $date);
        } elseif ($param === 'bulan') {
            $parts = explode('-', $date);
            if (count($parts) === 2) {
                $query->whereYear('transaksis.created_at', $parts[0])
                      ->whereMonth('transaksis.created_at', $parts[1]);
            } else {
                $query->whereDate('transaksis.created_at', 'like', '%' . $date . '%');
            }
        } elseif ($param === 'tahun') {
            $query->whereYear('transaksis.created_at', $date);
        }

        // Apply toko filters
        if ($toko !== 'semua' && !empty($toko)) {
            $query->where('transaksis.kode_toko', $toko);
        } else {
            $excludedTokoCodes = DB::table('tokos')
                ->whereIn('nama_toko', ['stock hilang', 'Online Shop'])
                ->pluck('kode')
                ->toArray();
            if (!empty($excludedTokoCodes)) {
                $query->whereNotIn('transaksis.kode_toko', $excludedTokoCodes);
            }
        }

        // Apply role restriction
        if (!$isAdmin) {
            $query->where('pembayarans.user_id', Auth::id());
        }

        // Get top 50 recent transactions for this item
        $transactions = $query->select(
            'transaksis.created_at as tanggal_transaksi',
            'transaksis.kode_invoice',
            'tokos.nama_toko',
            'users.name as kasir',
            'transaksis.metode',
            'transaksis.jumlah',
            'transaksis.harga',
            'transaksis.harga_total'
        )
        ->orderByDesc('transaksis.created_at')
        ->limit(50)
        ->get();

        return response()->json([
            'data' => $transactions
        ]);
    }
}
