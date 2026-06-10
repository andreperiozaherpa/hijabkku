<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $toko = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();

        return view('laporan.penjualan.index', [
            'toko' => $toko,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $parameter = $request->parameters;
        $select = $request->selected;

        if ($parameter == 'karyawan') {
            // code...
            $data = User::where('kode_toko', $select)->where('status', 'on')->get();
        }

        return response()->json([
            'data' => $data,
            'param' => 'change',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     */
    // public function show(Request $request)
    // {
    //     //
    //     $param = $request->param;
    //     $date = $request->date;
    //     $toko = $request->toko;
    //     $karyawan = $request->karyawan;

    //     function data_toko($toko, $date)
    //     {
    //         if ($toko == 'semua' || empty($toko)) {
    //             if (Auth::user()->role == 'admin') {
    //                 # code...
    //                 $dataTransaki = Transaksi::whereDate('created_at', 'like', '%' . $date . '%')
    //                     ->get();
    //                 $dataPembayaran = Pembayaran::whereDate('created_at', 'like', '%' . $date . '%')
    //                     ->get();
    //             }
    //         } else {
    //             $dataTransaki = Transaksi::where('kode_toko', $toko)->whereDate('created_at', 'like', '%' . $date . '%')
    //                 ->get();
    //             $dataPembayaran = Pembayaran::whereDate('created_at', 'like', '%' . $date . '%')
    //                 ->get();
    //         }
    //         return [
    //             'transaksi' => $dataTransaki,
    //             'pembayaran' => $dataPembayaran,
    //         ];
    //     }

    //     // dd($date);
    //     if ($param == 'hari') {
    //         $data = data_toko($toko, $date);
    //         $parameter = $param;
    //         $icon = 'success';
    //     } else if ($param == 'bulan') {
    //         $data = data_toko($toko, $date);
    //         $parameter = $param;
    //         $icon = 'success';
    //     } else if ($param == 'tahun') {
    //         $data = data_toko($toko, $date);
    //         $parameter = $param;
    //         $icon = 'success';
    //     } else {
    //         $icon = 'error';
    //     }

    //     // dd(count($data['transaksi']));

    //     return response()->json([
    //         'data' => $data,
    //         'param' => $parameter,
    //         'karyawan' => $karyawan
    //     ]);
    // }

    public function show(Request $request)
    {
        $param = $request->param;
        $date = $request->date;
        $toko = $request->toko;
        $karyawan = $request->karyawan;

        // Parse date bounds to enable high-efficiency index range scans (whereBetween) instead of un-indexed functions (whereDate/whereYear)
        $startDate = null;
        $endDate = null;
        if ($param === 'hari') {
            $startDate = $date.' 00:00:00';
            $endDate = $date.' 23:59:59';
        } elseif ($param === 'bulan') {
            try {
                $carbonDate = Carbon::parse($date.'-01');
                $startDate = $carbonDate->startOfMonth()->toDateTimeString();
                $endDate = $carbonDate->endOfMonth()->toDateTimeString();
            } catch (\Exception $e) {
                $startDate = $date.'-01 00:00:00';
                $endDate = $date.'-31 23:59:59';
            }
        } elseif ($param === 'tahun') {
            try {
                $carbonDate = Carbon::parse($date.'-01-01');
                $startDate = $carbonDate->startOfYear()->toDateTimeString();
                $endDate = $carbonDate->endOfYear()->toDateTimeString();
            } catch (\Exception $e) {
                $startDate = $date.'-01-01 00:00:00';
                $endDate = $date.'-12-31 23:59:59';
            }
        }

        // 1. DataTables Server-Side Pagination
        if ($request->has('draw')) {
            $metode = $request->input('metode', 'umum');
            $needsUserFilter = ($karyawan !== 'semua' && ! empty($karyawan)) || Auth::user()->role != 'admin';

            // Get excluded store codes once
            $excludedTokoCodes = DB::table('tokos')
                ->whereIn('nama_toko', ['stock hilang', 'Online Shop'])
                ->pluck('kode')
                ->toArray();

            // Build a lightweight count query (avoid JOIN to pembayarans if not filtering on user_id)
            $countQuery = DB::table('transaksis')
                ->where('transaksis.metode', $metode);

            if ($startDate && $endDate) {
                $countQuery->whereBetween('transaksis.created_at', [$startDate, $endDate]);
            }

            if ($toko !== 'semua' && ! empty($toko)) {
                $countQuery->where('transaksis.kode_toko', $toko);
            } elseif (! empty($excludedTokoCodes)) {
                $countQuery->whereNotIn('transaksis.kode_toko', $excludedTokoCodes);
            }

            if ($needsUserFilter) {
                $countQuery->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice');
                if ($karyawan !== 'semua' && ! empty($karyawan)) {
                    $countQuery->where('pembayarans.user_id', $karyawan);
                }
                if (Auth::user()->role != 'admin') {
                    $countQuery->where('pembayarans.user_id', Auth::id());
                }
            }

            $totalRecords = $countQuery->count();

            // Build query to retrieve rows (includes JOIN to get pembayarans.user_name)
            $query = DB::table('transaksis')
                ->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                ->where('transaksis.metode', $metode);

            if ($startDate && $endDate) {
                $query->whereBetween('transaksis.created_at', [$startDate, $endDate]);
            }

            if ($toko !== 'semua' && ! empty($toko)) {
                $query->where('transaksis.kode_toko', $toko);
            } elseif (! empty($excludedTokoCodes)) {
                $query->whereNotIn('transaksis.kode_toko', $excludedTokoCodes);
            }

            if ($karyawan !== 'semua' && ! empty($karyawan)) {
                $query->where('pembayarans.user_id', $karyawan);
            }
            if (Auth::user()->role != 'admin') {
                $query->where('pembayarans.user_id', Auth::id());
            }

            $searchValue = $request->input('search.value');
            if (! empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('transaksis.kode_invoice', 'like', '%'.$searchValue.'%')
                        ->orWhere('pembayarans.user_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('transaksis.nama_barang', 'like', '%'.$searchValue.'%');
                });
                $totalFiltered = $query->count();
            } else {
                $totalFiltered = $totalRecords; // Avoid executing second count query!
            }

            // Ordering
            $orderColumnIdx = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'desc');
            $columns = ['transaksis.created_at', 'transaksis.kode_invoice', 'pembayarans.user_name', 'transaksis.nama_barang', 'transaksis.metode', 'transaksis.jumlah', 'transaksis.harga', 'transaksis.harga_total'];
            $orderColumn = isset($columns[$orderColumnIdx]) ? $columns[$orderColumnIdx] : 'transaksis.created_at';

            $query->orderBy($orderColumn, $orderDir);

            // Pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 25);

            if ($length != -1) {
                $query->skip($start)->take($length);
            }

            $items = $query->select(
                'transaksis.created_at as tanggal_data',
                'transaksis.kode_invoice',
                'pembayarans.user_name as user_name',
                'transaksis.nama_barang',
                'transaksis.metode',
                'transaksis.jumlah',
                'transaksis.harga',
                'transaksis.harga_total'
            )->get();

            $data = $items->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_data,
                    'kode_invoice' => $item->kode_invoice,
                    'user_name' => $item->user_name,
                    'nama_barang' => $item->nama_barang,
                    'metode' => $item->metode,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->harga,
                    'total' => (float) $item->harga_total,
                ];
            });

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $data,
            ]);
        }

        // 2. Summary Request (KPI & Chart) - Highly Optimized using aggregates!
        if (in_array($param, ['hari', 'bulan', 'tahun'])) {
            $needsUserFilter = ($karyawan !== 'semua' && ! empty($karyawan)) || Auth::user()->role != 'admin';

            // Build base totals query (avoid JOIN to pembayarans if not filtering on user_id)
            $totalsQuery = DB::table('transaksis');

            if ($needsUserFilter) {
                $totalsQuery->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice');
            }

            // Apply date filters using optimized index range scan
            if ($startDate && $endDate) {
                $totalsQuery->whereBetween('transaksis.created_at', [$startDate, $endDate]);
            }

            // Apply toko filters
            if ($toko !== 'semua' && ! empty($toko)) {
                $totalsQuery->where('transaksis.kode_toko', $toko);
            } else {
                $excludedTokoCodes = DB::table('tokos')
                    ->whereIn('nama_toko', ['stock hilang', 'Online Shop'])
                    ->pluck('kode')
                    ->toArray();
                if (! empty($excludedTokoCodes)) {
                    $totalsQuery->whereNotIn('transaksis.kode_toko', $excludedTokoCodes);
                }
            }

            // Apply user filters
            if ($needsUserFilter) {
                if ($karyawan !== 'semua' && ! empty($karyawan)) {
                    $totalsQuery->where('pembayarans.user_id', $karyawan);
                }
                if (Auth::user()->role != 'admin') {
                    $totalsQuery->where('pembayarans.user_id', Auth::id());
                }
            }

            // Aggregate totals directly in DB!
            $aggregated = (clone $totalsQuery)
                ->select(
                    'transaksis.metode',
                    DB::raw('COUNT(transaksis.id) as total_count'),
                    DB::raw('SUM(transaksis.harga_total) as total_omzet'),
                    DB::raw('SUM(transaksis.jumlah * transaksis.harga_beli) as total_modal')
                )
                ->groupBy('transaksis.metode')
                ->get();

            $totalUmum = 0;
            $modalUmum = 0;
            $countUmum = 0;
            $totalGrosir = 0;
            $modalGrosir = 0;
            $countGrosir = 0;

            foreach ($aggregated as $row) {
                if ($row->metode === 'umum') {
                    $totalUmum = (float) $row->total_omzet;
                    $modalUmum = (float) $row->total_modal;
                    $countUmum = (int) $row->total_count;
                } elseif ($row->metode === 'grosir') {
                    $totalGrosir = (float) $row->total_omzet;
                    $modalGrosir = (float) $row->total_modal;
                    $countGrosir = (int) $row->total_count;
                }
            }

            // Fetch lightweight chart data only (omit details to reduce size by 95%!)
            $driver = DB::connection()->getDriverName();
            if ($param === 'hari') {
                $format = $driver === 'sqlite' ? "strftime('%Y-%m-%d %H:00:00', transaksis.created_at)" : "DATE_FORMAT(transaksis.created_at, '%Y-%m-%d %H:00:00')";
            } elseif ($param === 'bulan') {
                $format = $driver === 'sqlite' ? "strftime('%Y-%m-%d 00:00:00', transaksis.created_at)" : "DATE_FORMAT(transaksis.created_at, '%Y-%m-%d 00:00:00')";
            } else { // tahun
                $format = $driver === 'sqlite' ? "strftime('%Y-%m-01 00:00:00', transaksis.created_at)" : "DATE_FORMAT(transaksis.created_at, '%Y-%m-01 00:00:00')";
            }

            $chartItems = $totalsQuery
                ->select(
                    DB::raw("$format as tanggal_data"),
                    'transaksis.metode',
                    DB::raw('SUM(transaksis.harga_total) as harga_total')
                )
                ->groupBy('tanggal_data', 'transaksis.metode')
                ->get();

            $data = [
                'laporan' => $chartItems, // Kept key name as 'laporan' for frontend chart parser compatibility
                'counts' => [
                    'umum' => $countUmum,
                    'grosir' => $countGrosir,
                ],
                'total' => [
                    'umum' => $totalUmum,
                    'modal_umum' => $modalUmum,
                    'grosir' => $totalGrosir,
                    'modal_grosir' => $modalGrosir,
                ],
            ];
            $parameter = $param;
        } else {
            return response()->json([
                'message' => 'Parameter tidak valid',
                'icon' => 'error',
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'param' => $parameter,
            'karyawan' => $karyawan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
