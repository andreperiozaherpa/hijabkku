<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\StockInOut;
use App\Models\StockToko;
use App\Models\Supplier;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use function Pest\Laravel\delete;

class StockInOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $barang = DataBarang::get();
        $supplier = Supplier::get();
        $toko = Toko::get();
        return view('stock.stock_in_out', ['barang' => $barang, 'supplier' => $supplier, 'toko' => $toko]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $kodeBarang = $request->kodeBarang;
        $kodeSupplier = $request->kodeSupplier;
        $kode_input = $request->kode_input;
        $jumlah = $request->jumlah;
        // dd($kode_input);
        $namaBarang = DataBarang::where('kode', $kodeBarang)->first();
        $namaSupplier = Supplier::where('kode', $kodeSupplier)->first();
        // Cek apakah barang sudah ada di stock toko
        $cek_kode_input = StockInOut::where('kode_input', $kode_input)->first();

        if ($cek_kode_input) {
            $icon = 'error';
            $title = 'Gagal';
            $text = 'Kode Input Sudah Ada';
            return response()->json([
                'icon' => $icon,
                'title' => $title,
                'text' => $text
            ]);
        } else {
            $data = new StockInOut();
            $data->kode_input = $kode_input;
            $data->kode_barang = $kodeBarang;
            $data->kode_supplier = $kodeSupplier;
            $data->barang = $namaBarang['nama_barang'];
            $data->supplier = $namaSupplier['nama_supplier'];
            $data->jumlah_masuk = $jumlah;
            $data->save();

            $icon = 'success';
            $title = 'Sukses';
            $text = 'Data Barang Baru Berhasil Disimpan';

            return response()->json([
                'icon' => $icon,
                'title' => $title,
                'text' => $text
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        $data = StockInOut::orderByDesc('id')->get();
        return DataTables()->of($data)
            ->addColumn('aksi', function ($data) {
                $group = '<button data-kode="' . $data->id . '" type="button" class="edit btn btn-quaternary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
          </svg></button>';
                $group .= '<button data-kode="' . $data->id . '" type="button" class="destroy btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
          </svg></button>';
                return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
            })
            ->addColumn('tanggal', function ($data) {
                setlocale(LC_ALL, 'IND');
                $tanggal =  Carbon::parse($data->created_at)->formatLocalized('%A %d %B %Y');;
                return $tanggal;
            })
            ->rawColumns(['aksi', 'tanggal'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $id = $request->kode;
        $data = StockInOut::find($id);
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $id = $request->kode;
        $kodeBarang = $request->kodeBarang;
        $kodeSupplier = $request->kodeSupplier;
        $jumlah = $request->jumlah;
        $namaBarang = DataBarang::where('kode', $kodeBarang)->first();
        $namaSupplier = Supplier::where('kode', $kodeSupplier)->first();

        StockInOut::where('id', $id)->update([
            'kode_barang' => $kodeBarang,
            'kode_supplier' => $kodeSupplier,
            'barang' => $namaBarang['nama_barang'],
            'supplier' => $namaSupplier['nama_supplier'],
            'jumlah_masuk' =>  $jumlah,
        ]);

        $icon = 'success';
        $title = 'Sukses';
        $text = 'Data Stock Berhasil Diubah';

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->kode;
        $data = StockInOut::find($id);
        $arrs = explode('|', $data->keterangan);

        foreach ($arrs as $ar) {
            # code...
            $dt = explode(':', $ar);
            $datas[] = [
                'kode' => $dt[0],
                'jumlah' => $dt[1]
            ];
        }
        foreach ($datas as $value) {
            # code...
            // $arrays[$key['kode']] = $value;
            $arrays[$value['kode']][] =  $value['jumlah'];
        }
        foreach ($arrays as $key => $val) {
            # code...
            $sum[$key] = array_sum($val);
        }
        foreach ($sum as $s => $v) {
            # code...
            $stock_sekarang =  StockToko::where('kode_toko', $s)->where('kode_barang', $data->kode_barang)->first()->jumlah;
            $stock_kurang =  StockToko::where('kode_toko', $s)->where('kode_barang', $data->kode_barang)->update([
                'jumlah' => $stock_sekarang - $v
            ]);
        }
        $data->delete();
        $icon = 'success';
        $title = 'Sukses';
        $text = 'Jenis Barang Berhasil Dihapus';

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }
}
