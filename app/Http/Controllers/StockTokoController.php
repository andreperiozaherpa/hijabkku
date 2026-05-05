<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\StockInOut;
use App\Models\StockToko;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockTokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('stock.stock_toko');
    }

    public function index_detail(Request $request)
    {
        //
        $kode = $request->segment(5);
        $nama_toko = Toko::where('kode', $kode)->first();
        $toko = Toko::where('kode', '!=', $kode)->get();
        // $barang = StockInOut::whereColumn('jumlah_masuk', '!=', 'jumlah_keluar')->get();
        $stocks = StockToko::where('kode_toko', $kode)->get();
       
        $total_uang[]='';
        foreach ($stocks as  $stock) {
            $total_aset = $stock['jumlah'] - $stock['terjual'];
            $barang = DataBarang::where('kode', $stock->kode_barang)->first();
            $total_uang = str_replace('.', '', $barang['harga_beli'] ?? 0) *  $total_aset;
        }
        // $total_aset = $stock['jumlah'] - $stock['terjual'];
        // dd($total_uang);
        
        if(is_array($total_uang)){
            $total_nilai_uang=number_format(array_sum($total_uang), 0, ",", ".");
        }else{
             $total_nilai_uang=0;
        }

        return view('stock.stock_toko_detail', [
            'kode' => $kode,
            'toko' => $toko,
            'nama_toko' => $nama_toko['nama_toko'],
            'total_aset' =>$total_nilai_uang,
            // 'barang' => $barang
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $id_stock_in = $request->id;
        $params = $request->params;

        if ($params == 'sisa') {
            if ($id_stock_in == null) {
                # code...
            } else {
                $data_stock_sisa =  StockInOut::where('id', $id_stock_in)->first();
                $sisa = $data_stock_sisa['jumlah_masuk'] - $data_stock_sisa['jumlah_keluar'];
                $barang = StockInOut::whereColumn('jumlah_masuk', '!=', 'jumlah_keluar')->get();
                return response()->json([
                    'sisa' => $sisa,
                    'kode_barang' => $data_stock_sisa['kode_barang'],
                    'res' => 'create'
                ]);
            }
        } else {
            $barang = StockInOut::whereColumn('jumlah_masuk', '!=', 'jumlah_keluar')->get();
            return response()->json([
                'data' => $barang
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $id = $request->namaBarang;
        $kode_barang = $request->kode_barang;
        $kodeToko = $request->kodeToko;
        $jumlah = $request->jumlah;
        $stock_in_out = StockInOut::where('id', $id)->first();
        $stock = StockToko::where('kode_barang', $kode_barang)->where('kode_toko', $kodeToko)->first();
        $stock_toko = StockToko::where('kode_barang', $kode_barang)->where('kode_toko', $kodeToko)->count();

        // $ceks = StockInOut::where('id', $id)->first()->keterangan;
        // $arrs = explode('|', $ceks);
        // foreach ($arrs as $keys) {
        //     # code...
        //     $dta[] = explode(':', $keys);
        // }
        // dd($dta);

        if ($stock_toko <= 0) {
            # code...
            $data = new StockToko();
            $data->kode_toko = $kodeToko;
            $data->kode_barang = $stock_in_out['kode_barang'];
            $data->supplier = $stock_in_out['supplier'];
            $data->nama_barang = $stock_in_out['barang'];
            $data->jumlah = $jumlah;
            $data->save();

            $stocks = StockInOut::where('id', $id)->first();
            if ($stocks->keterangan == null || $stocks->keterangan == '') {
                # code...
                StockInOut::where('id', $id)->update([
                    'keterangan' =>  $kodeToko . ':' . $jumlah,
                ]);
            } else {
                StockInOut::where('id', $id)->update([
                    'keterangan' => $kodeToko . ':' . $jumlah . '|' . $stocks->keterangan,
                ]);
            }
        } else {
            StockToko::where('kode_barang', $kode_barang)->where('kode_toko', $kodeToko)->update([
                'jumlah' => $jumlah + $stock['jumlah']
            ]);

            $stocks = StockInOut::where('id', $id)->first();
            StockInOut::where('id', $id)->update([
                'keterangan' => $kodeToko . ':' . $jumlah . '|' . $stocks->keterangan,
            ]);
        }

        $pengurangan = $stock_in_out['jumlah_keluar'] + $jumlah;
        StockInOut::where('id', $id)->update([
            'jumlah_keluar' => $pengurangan
        ]);


        $icon = 'success';
        $title = 'Sukses';
        $text = 'Stock Toko Berhasil Ditambahkan';

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        $data = Toko::get();
        return DataTables()->of($data)
            ->addColumn('aksi', function ($data) {
                $group = '<a href="/manajemen/stock/toko/index/' . $data->kode . '" class="btn btn-quaternary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
              </svg></a>';
                return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_detail(Request $request)
    {
        $kode_toko = $request->segment(5);
        $data = StockToko::orderByDesc('updated_at')->where('kode_toko', $kode_toko)->get();
        return DataTables()->of($data)
            ->addColumn('aksi', function ($data) {
                $group = '<button data-kode="' . $data->id . '" type="button" class="exchange btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
              </svg></button>';
                return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
            })
            ->addColumn('sisa', function ($data) {
                $stock = StockToko::where('id', $data->id)->first();
                $total = $stock['jumlah'] - $stock['terjual'];
                return $total;
            })
            ->addColumn('total_uang', function ($data) {
                $stock = StockToko::where('id', $data->id)->first();
                $total = $stock['jumlah'] - $stock['terjual'];

                $barang = DataBarang::where('kode', $stock->kode_barang)->first();
                $total_uang = str_replace('.', '', $barang['harga_jual'] ?? 0) *  $total;
                // dd($total_uang);
                return number_format($total_uang, 0, ",", ".");
            })
            ->addColumn('total_uang_grosir', function ($data) {
                $stock = StockToko::where('id', $data->id)->first();
                $total = $stock['jumlah'] - $stock['terjual'];

                $barang = DataBarang::where('kode', $stock->kode_barang)->first();
                $total_uang = str_replace('.', '', $barang['harga_grosir'] ?? 0) *  $total;
                // dd($total_uang);
                return number_format($total_uang, 0, ",", ".");
            })
            ->rawColumns(['aksi', 'sisa', 'total_uang', 'total_uang_grosir'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $id = $request->id;
        $data = StockToko::where('id', $id)->first();
        return response()->json([
            'data' => $data,
            'res' => 'exchanges'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $namaBarangExchange = $request->namaBarangExchange;
        $kodeBarang = $request->kodeBarang;
        $id_in = $request->idIn;
        $namaSupplierExchange = $request->namaSupplierExchange;
        $kodeToko = $request->namaToko;
        $kodeTokoOld = $request->kodeTokoOld;
        $sisaExchange = $request->sisaExchange;
        $kirimExchange = $request->kirimExchange;
        $jumlahOld = $request->jumlahOld;

        $stock_barang = StockToko::where('kode_toko', $kodeToko)->where('kode_barang', $kodeBarang)->count();
        if ($stock_barang <= 0) {
            $data = new StockToko();
            $data->kode_toko = $kodeToko;
            $data->kode_barang = $kodeBarang;
            $data->supplier = $namaSupplierExchange;
            $data->nama_barang = $namaBarangExchange;
            $data->jumlah = $kirimExchange;
            $data->save();
            // $jumlahLama = $jumlahOld - $kirimExchange;
        } else {
            $jumlahUpdate = StockToko::where('kode_toko', $kodeToko)->where('kode_barang', $kodeBarang)->first();
            $total = $jumlahUpdate['jumlah'] + $kirimExchange;

            StockToko::where('kode_toko', $kodeToko)->where('kode_barang', $kodeBarang)->update([
                'jumlah' => $total
            ]);
        }
        $jumlahLama = $jumlahOld - $kirimExchange;
        StockToko::where('kode_toko', $kodeTokoOld)->where('kode_barang', $kodeBarang)->update([
            'jumlah' => $jumlahLama
        ]);

        $icon = 'success';
        $title = 'Sukses';
        $text = 'Stock Toko Berhasil DiOper Ke ' . $kodeToko;

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockToko $stockToko)
    {
        //
    }
}
