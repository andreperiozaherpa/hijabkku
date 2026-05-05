<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Pembayaran;
use App\Models\StockToko;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $data_toko = Toko::where('kode', $user->kode_toko)->first();
        $stock_toko = StockToko::where('kode_toko', $user->kode_toko)->whereColumn('jumlah', '!=', 'terjual')->get();
        return view('transaksi.penjualan', [
            'data_toko' => $data_toko,
            'stock' => $stock_toko
        ]);
    }

    public function index_daftar()
    {
        //
        if (Auth::user()->role == 'admin') {
            # code...
            $data_toko = Toko::get();
            $data_kasir = User::where('role', 'admin')
                ->orWhere('role', 'kasir')
                ->get();
        } else {
            # code...
            $data_toko = Toko::where('kode', Auth::user()->kode_toko)->get();
            $data_kasir = User::where('id', Auth::user()->id)
                ->get();
        }
        return view('transaksi.daftar', [
            'toko' =>  $data_toko,
            'kasir' =>  $data_kasir
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $user = Auth::user();
        $key1 = $request->key1;
        $key2 = $request->key2;
        $param = $request->param;
        if ($param == 'all') {
            # code...
            // $stock_toko = StockToko::where('kode_toko', $user->kode_toko)->whereColumn('jumlah', '!=', 'terjual')->get();
            $stock_toko = StockToko::leftJoin('data_barangs', 'stock_tokos.kode_barang', '=', 'data_barangs.kode')
                ->select('stock_tokos.*', 'data_barangs.jenis_barang', 'data_barangs.harga_beli', 'data_barangs.harga_jual', 'data_barangs.harga_grosir')
                ->where('kode_toko', $user->kode_toko)
                // ->where('kode_barang', 'DB-06wj')
                ->whereColumn('jumlah', '!=', 'terjual')
                ->groupBy('kode_barang', 'kode_toko')
                ->orderByDesc('kode_toko')
                ->get();
        } else {
            // $stock_toko = StockToko::where('nama_barang', 'like', '%' . $key . '%')->where('kode_toko', $user->kode_toko)->whereColumn('jumlah', '!=', 'terjual')->get();
            $stock_toko = StockToko::leftJoin('data_barangs', 'stock_tokos.kode_barang', '=', 'data_barangs.kode')
                ->select('stock_tokos.*', 'data_barangs.jenis_barang', 'data_barangs.harga_beli', 'data_barangs.harga_jual', 'data_barangs.harga_grosir')
                ->where('stock_tokos.nama_barang', 'like', '%' . $key1 . '%')
                ->where('stock_tokos.nama_barang', 'like', '%' . $key2 . '%')
                ->where('kode_toko', $user->kode_toko)
                ->whereColumn('jumlah', '!=', 'terjual')
                ->groupBy('kode_barang', 'kode_toko')
                ->orderByDesc('kode_toko')
                ->get();
        }
        // dd($stock_toko);
        return response()->json([
            'stock' => $stock_toko
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $invoice = $request->invoice;
        $total_harga = $request->total_harga;
        $pembayaran = $request->pembayaran;
        $kembali = $request->kembali;
        $user = Auth::user();
        $data = $request->data;
        $cek_transaksi = Pembayaran::where('kode_invoice', $invoice)->count();

        $now   = Carbon::now('Asia/Jakarta');
        $time  = $now->format('H:i');

        if ($time >= '07:30' && $time <= '17:01') {
            # code...
            $shif = 1;
        } elseif ($time >= '17:00' && $time <= '23:59') {
            # code...
            $shif = 2;
        } else {
            $shif = 0;
        }

        if ($user->shift == $shif || $user->shift == 0) {
            if ($cek_transaksi <= 0) {
                # code...
                foreach ($data as $d) {
                    $dataBarang = DataBarang::where('kode', $d['nomor_paket'])->first();
                    $arr = [
                        'kode_invoice' => $invoice,
                        'kode_toko' => $user->kode_toko,
                        'kode_barang' => $d['nomor_paket'],
                        'nama_barang' => $d['nama_barang'],
                        'metode' => $d['method'],
                        'jumlah' => $d['jumlah_barang'],
                        'harga' => $d['harga_item'],
                        'harga_beli' => str_replace(".", "", $dataBarang->harga_beli),
                        'harga_total' => $d['harga_jual'],
                    ];
                    Transaksi::create($arr);
                    $getstock = StockToko::where('kode_toko', $user->kode_toko)->where('kode_barang', $d['nomor_paket'])->get();
                    $stock_terjual = $getstock[0]['terjual'] + $d['jumlah_barang'];
                    // dd($total_stock);
                    StockToko::where('kode_toko', $user->kode_toko)->where('kode_barang', $d['nomor_paket'])->update([
                        'terjual' => $stock_terjual
                    ]);
                }
                $data_pembayaran = [
                    'kode_invoice' => $invoice,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'total_harga' => $total_harga,
                    'pembayaran' => $pembayaran,
                    'kembalian' => $kembali
                ];
                Pembayaran::create($data_pembayaran);
                $icon = 'success';
                $cek_data = 'Pembayaran diterima';
            } else {
                $icon = 'error';
                $cek_data = 'Pembayaran Gagal';
            }
        } else {
            $icon = 'error';
            $cek_data = 'Shift User Telah Selesai';
        }

        return response()->json([
            'icon' => $icon,
            'cek_data' => $cek_data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        if (Auth::user()->role == 'admin') {
            # code...
            $data = Pembayaran::orderBy('id', 'desc')->get();
        } else {
            $data = Pembayaran::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }
        // return DataTables()->of($data)
        //     ->addColumn('Keterangan', function ($data) {
        //         $tanggal = Carbon::parse($data->created_at)->locale('id');
        //         $tanggal->settings(['formatFunction' => 'translatedFormat']);

        //         $keterangan = '
        //             <table class="table table-borderless" style="width:100%">
        //                 <tbody>
        //                     <tr>
        //                         <td style="width:20%">Invoice</td>
        //                         <td style="width:10%">:</td>
        //                         <td style="width:70%">' . $data->kode_invoice . '</td>
        //                     </tr>
        //                     <tr>
        //                         <td>Kasir</td>
        //                         <td>:</td>
        //                         <td>' . $data->user_name . '</td>
        //                     </tr>
        //                     <tr>
        //                         <td>Tanggal</td>
        //                         <td>:</td>
        //                         <td>' . $tanggal->format('l, d M Y, h:i:s') . '</td>
        //                     </tr>
        //                     <tr>
        //                         <td>Totak</td>
        //                         <td>:</td>
        //                         <td>Rp. ' .  number_format($data->total_harga, 0, ',', '.') . '</td>
        //                     </tr>
        //                 </tbody>
        //             </table>
        //         ';
        //         return $keterangan;
        //     })
        //     ->addColumn('aksi', function ($data) {
        //         $group = '<button data-invoice="' . $data->kode_invoice . '" type="button" class="detailTransaksi btn btn-quaternary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
        //         <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
        //         <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
        //       </svg></button>';
        //         return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
        //     })
        //     ->rawColumns(['aksi', 'Keterangan'])
        //     ->make(true);

        return DataTables()->of($data)
            ->addColumn('Keterangan', function ($data) {
                $tanggal = Carbon::parse($data->created_at)->locale('id');
                $tanggal->settings(['formatFunction' => 'translatedFormat']);

                $keterangan = '
                    <table class="table table-borderless" style="width:100%">
                        <tbody>
                            <tr>
                                <td style="width:20%">Invoice</td>
                                <td style="width:10%">:</td>
                                <td style="width:70%">' . $data->kode_invoice . '</td>
                            </tr>
                            <tr>
                                <td>Kasir</td>
                                <td>:</td>
                                <td>' . $data->user_name . '</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>' . $tanggal->format('l, d M Y, h:i:s') . '</td>
                            </tr>
                            <tr>
                                <td>Totak</td>
                                <td>:</td>
                                <td>Rp. ' .  number_format($data->total_harga, 0, ',', '.') . '</td>
                            </tr>
                        </tbody>
                    </table>
                ';
                return $keterangan;
            })
            ->addColumn('aksi', function ($data) {
                $group = '<button data-invoice="' . $data->kode_invoice . '" type="button" class="detailTransaksi btn btn-quaternary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
              </svg></button>';
                return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
            })
            ->rawColumns(['aksi', 'Keterangan'])
            ->make(true);
    }

    public function show_detail(Request $request)
    {
        //
        $invoice = $request->invoice;
        $data_perinvoice = Pembayaran::where('kode_invoice', $invoice)->first();
        $data_perbarang = Transaksi::where('kode_invoice', $invoice)->get();
        $tanggal = Carbon::parse($data_perinvoice->created_at)->locale('id');
        $tanggal->settings(['formatFunction' => 'translatedFormat']);
        $toko = Toko::where('kode', $data_perbarang[0]->kode_toko)->first();
        return response()->json([
            'data' => $data_perbarang,
            'total_harga' => $data_perinvoice->total_harga,
            'pembayaran' => $data_perinvoice->pembayaran,
            'kembalian' => $data_perinvoice->kembalian,
            'username' => $data_perinvoice->user_name,
            'tanggal' => $tanggal->format('l, d M Y, a H:i'),
            'metode' => $data_perbarang[0]->metode,
            'toko' => $toko->nama_toko,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $get_tanggal = $request->tanggal;
        $format = $request->format;
        $users = $request->users;
        $tanggal = Carbon::parse($get_tanggal)->locale('id');
        $tanggal->settings(['formatFunction' => 'translatedFormat']);
        $hari = $tanggal->format('d');
        $bulan = $tanggal->format('m');
        $tahun = $tanggal->format('Y');
        if ($request->toko == 'semua') {
            # code...
            $toko = null;
            $tokos = 'Semua';
        } else {
            $toko = $request->toko;
            $tokos = Toko::where('kode', $toko)->first();
        }

        if ($format == 'Hari') {
            # code...
            if ($users == 'semua') {
                # code...
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->where('transaksis.kode_toko', 'LIKE', '%' . $toko . '%')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->whereDate('transaksis.created_at', $tahun . '-' . $bulan . '-' . $hari)
                    ->groupBy('pembayarans.kode_invoice')
                    ->get();
            } else {
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->where('transaksis.kode_toko', 'LIKE', '%' . $toko . '%')
                    ->where('pembayarans.user_id', $users)
                    ->whereDate('transaksis.created_at', $tahun . '-' . $bulan . '-' . $hari)
                    ->groupBy('pembayarans.kode_invoice')
                    ->get();
            }
            return view('transaksi.rekap', [
                'data' => $transaksi,
                'users' => $users,
                'tahun' => $tahun,
                'bulan' => $tanggal->format('m'),
                'hari' => $tanggal->format('d'),
                'toko' => $toko,
                'tokos' => $tokos['nama_toko'],
                'bulans' => $tanggal->format('M'),
            ]);
        } elseif ($format == 'Bulan') {
            # code...
            if ($users == 'semua') {
                # code...
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->where('transaksis.kode_toko', 'LIKE', '%' . $toko . '%')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->whereMonth('pembayarans.created_at', $bulan)
                    ->whereYear('pembayarans.created_at', $tahun)
                    ->groupBy('pembayarans.kode_invoice')
                    ->get();
            } else {
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->where('transaksis.kode_toko', 'LIKE', '%' . $toko . '%')
                    ->where('pembayarans.user_id', $users)
                    ->whereMonth('pembayarans.created_at', $bulan)
                    ->whereYear('pembayarans.created_at', $tahun)
                    ->groupBy('pembayarans.kode_invoice')
                    ->get();
            }

            return view('transaksi.rekap', [
                'data' => $transaksi,
                'users' => $users,
                'tahun' => $tahun,
                'bulan' => $tanggal->format('m'),
                'hari' => $tanggal->format('d'),
                'toko' => $toko,
                'tokos' => $tokos['nama_toko'],
                'bulans' => $tanggal->format('M'),
            ]);
        } elseif ($format == 'Tahun') {
            # code...
            if ($toko == 'semua') {
                # code...
                $transaksi = DataBarang::select(
                    'pembayarans.user_name',
                    'data_barangs.nama_barang',
                    'transaksis.harga',
                    'transaksis.harga_beli',
                    DB::raw('SUM(transaksis.jumlah) as total_jumlah'),
                )
                    ->join('transaksis', 'transaksis.nama_barang', '=', 'data_barangs.nama_barang')
                    ->join('pembayarans', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->whereYear('transaksis.created_at', $tahun)
                    ->groupBy('transaksis.nama_barang', 'transaksis.harga', 'pembayarans.user_name')
                    ->get();
            } else {
                if ($users == 'semua') {
                    # code...
                    $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                        ->where('transaksis.kode_toko', $toko)
                        ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                        ->whereYear('pembayarans.created_at', $tahun)
                        ->groupBy('pembayarans.kode_invoice')
                        ->get();
                } else {
                    $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                        ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                        ->where('transaksis.kode_toko', $toko)
                        ->where('pembayarans.user_id', $users)
                        ->whereYear('pembayarans.created_at', $tahun)
                        ->groupBy('pembayarans.kode_invoice')
                        ->get();
                }
                return view('transaksi.rekap', [
                    'data' => $transaksi,
                    'users' => $users,
                    'tahun' => $tahun,
                    'bulan' => $tanggal->format('m'),
                    'hari' => $tanggal->format('d'),
                    'toko' => $toko,
                    'tokos' => $tokos['nama_toko'],
                    'bulans' => $tanggal->format('M'),
                ]);
            }
        } else {
            # code...
            $transaksi = 'tidak ada';
        }
        return response()->json([
            'role' => Auth::user()->role,
            'data' => $transaksi,
        ]);
    }

    public function neraca(Request $request)
    {
        $data_toko = $request->dataToko;
        $data_hari = $request->dataHari;
        $data_bulan = $request->dataBulan;
        $data_tahun = $request->dataTahun;
        $data_users = $request->dataUser;
        $data_format = $request->dataFormat;

        if ($data_format == 'Hari') {
            # code...
            $dates = $data_tahun . '-' . $data_bulan . '-' . $data_hari;
        } elseif ($data_format == 'Bulan') {
            # code...
            $dates = $data_tahun . '-' . $data_bulan;
        } elseif ($data_format == 'Tahun') {
            # code...
            $dates = $data_tahun;
        }

        if ($request->dataToko == 'semua') {
            # code...
            $toko = 'null';
        } else {
            # code...
            $toko = $data_toko;
        }

        if ($data_users == 'semua') {
            # code...
            $bruto = Transaksi::select(
                DB::raw('SUM(transaksis.harga_total) as harga_totals'),
            )
                ->where('kode_toko', 'LIKE', '%' . $toko . '%')
                ->whereDate('transaksis.created_at', 'LIKE', '%' . $dates . '%')
                ->first();

            $keuntungan = Transaksi::select(
                DB::raw('sum( transaksis.harga_total-(transaksis.harga_beli * jumlah) ) as harga_beli_totals'),
            )
                ->where('kode_toko', 'LIKE', '%' . $toko . '%')
                ->whereDate('transaksis.created_at', 'LIKE', '%' . $dates . '%')
                ->first();
        } else {
            # code...
            $bruto = Pembayaran::select(
                'pembayarans.*',
                'transaksis.kode_toko',
                DB::raw('SUM(transaksis.harga_total) as harga_totals'),
            )
                ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                ->where('transaksis.kode_toko', 'LIKE', '%' . $toko . '%')
                ->where('pembayarans.user_id', $data_users)
                ->whereDate('transaksis.created_at', 'LIKE', '%' . $dates . '%')
                ->first();

            $keuntungan = Pembayaran::select(
                'pembayarans.*',
                'transaksis.kode_toko',
                DB::raw('sum( transaksis.harga_total-(transaksis.harga_beli * jumlah) ) as harga_beli_totals'),
            )
                ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                ->where('transaksis.kode_toko', 'LIKE', '%' . $toko . '%')
                ->where('pembayarans.user_id', $data_users)
                ->whereDate('transaksis.created_at', 'LIKE', '%' . $dates . '%')
                ->first();
        }
        return response()->json([
            'bruto' => $bruto,
            'keuntungan' => $keuntungan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
