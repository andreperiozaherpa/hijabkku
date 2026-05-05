<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $toko = Toko::get();
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
            # code...
            $data = User::where('kode_toko', $select)->where('status', 'on')->get();
        }
        return response()->json([
            'data' => $data,
            'param' => 'change'
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

        function data_toko($toko, $date, $karyawan)
        {
            $query = DB::table('transaksis')
                ->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                ->join('users', 'pembayarans.user_id', '=', 'users.id')
                ->select(
                    'transaksis.*',
                    'pembayarans.user_id',
                    'users.name as user_name',
                    'transaksis.created_at as tanggal_data'
                );

            // Filter tanggal
            $query->whereDate('transaksis.created_at', 'like', '%' . $date . '%');

            // Filter toko
            if ($toko !== 'semua' && !empty($toko)) {
                $query->where('transaksis.kode_toko', $toko);
            }

            // Filter karyawan
            if ($karyawan !== 'semua' && !empty($karyawan)) {
                $query->where('pembayarans.user_id', $karyawan);
            }

            // Jika bukan admin, batasi berdasarkan user login
            if (Auth::user()->role != 'admin') {
                $query->where('pembayarans.user_id', Auth::id());
            }

            $laporan = $query->get();

            // Total umum & grosir
            $totalUmum = 0;
            $modalUmum = 0;
            $totalGrosir = 0;
            $modalGrosir = 0;

            foreach ($laporan as $item) {
                $total = $item->jumlah * $item->harga;
                $modal = $item->jumlah * $item->harga_beli;

                if ($item->metode === 'umum') {
                    $totalUmum += $total;
                    $modalUmum += $modal;
                } elseif ($item->metode === 'grosir') {
                    $totalGrosir += $total;
                    $modalGrosir += $modal;
                }
            }

            return [
                'laporan' => $laporan,
                'total' => [
                    'umum' => $totalUmum,
                    'modal_umum' => $modalUmum,
                    'grosir' => $totalGrosir,
                    'modal_grosir' => $modalGrosir,
                ]
            ];
        }

        if (in_array($param, ['hari', 'bulan', 'tahun'])) {
            $data = data_toko($toko, $date, $karyawan);
            $parameter = $param;
            $icon = 'success';
        } else {
            return response()->json([
                'message' => 'Parameter tidak valid',
                'icon' => 'error'
            ], 400);
        }

        return response()->json([
            'data' => $data,
            'param' => $parameter,
            'karyawan' => $karyawan
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
