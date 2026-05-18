<?php

namespace App\Http\Controllers;

use App\Models\bahanBarang;
use App\Models\DataBarang;
use App\Models\JenisBarang;
use App\Models\merekBarang;
use App\Models\modelBarang;
use App\Models\packagingBarang;
use App\Models\StockInOut;
use App\Models\StockToko;
use App\Models\ukuranBarang;
use App\Models\variasiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class DataBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $jenis = JenisBarang::get();
        $merek = merekBarang::get();
        $model = modelBarang::get();
        $bahan = bahanBarang::get();
        $variasi = variasiBarang::get();
        $ukuran = ukuranBarang::get();
        $packaging = packagingBarang::get();

        return view('barang.barang', [
            'jenis' => $jenis,
            'merek' => $merek,
            'model' => $model,
            'bahan' => $bahan,
            'variasi' => $variasi,
            'ukuran' => $ukuran,
            'packaging' => $packaging,
        ]);
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
        $kode = $request->kode;
        $jenis_barang = $request->jenis_barang;
        $nama_barang = $request->nama_barang;
        $harga_beli = $request->harga_beli;
        $harga_jual = $request->harga_jual;
        $harga_grosir = $request->harga_grosir;

        $querybarang = DataBarang::where('kode', $kode)->count();
        if ($querybarang <= 0) {
            # code...
            $data = new DataBarang();
            $data->kode = $kode;
            $data->jenis_barang = $jenis_barang;
            $data->nama_barang = $nama_barang;
            $data->harga_beli = $harga_beli;
            $data->harga_jual = $harga_jual;
            $data->harga_grosir = $harga_grosir;
            $data->save();

            $icon = 'success';
            $title = 'Sukses';
            $text = 'Data Barang Baru Berhasil Disimpan';
        } else {
            $icon = 'error';
            $title = 'Error';
            $text = 'Data Barang Baru Sudah Ada';
        }

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
        // Default start to 0 and length to 20 if they are not provided, ensuring pagination is always enforced
        if (!$request->has('start')) {
            $request->merge(['start' => 0]);
        }
        if (!$request->has('length')) {
            $request->merge(['length' => 20]);
        }

        $data = DataBarang::query()->orderByDesc('id');
        return DataTables()->of($data)
            ->addColumn('aksi', function ($data) {
                $group = '<button data-kode="' . $data->kode . '" data-nama="' . $data->nama_barang . '" type="button" class="edit btn btn-quaternary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>
                    </button>';
                $group .= '<button data-kode="' . $data->kode . '" type="button" class="destroy btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
              </svg></button>';

                return '<div class="btn-group" role="group" aria-label="Basic example">' . $group . ' </div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $kode = $request->kode;

        $data = DataBarang::where('kode', $kode)->get();

        return response()->json([
            'data' => $data[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $kode = $request->kode;
        $jenis_barang = $request->jenis_barang;
        $nama_barang = $request->nama_barang;
        $harga_beli = $request->harga_beli;
        $harga_jual = $request->harga_jual;
        $harga_grosir = $request->harga_grosir;

        DataBarang::where('kode', $kode)->update([
            'jenis_barang' => $jenis_barang,
            'nama_barang' => $nama_barang,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
            'harga_grosir' => $harga_grosir
        ]);

        StockToko::where('kode_barang', $kode)->update([
            'nama_barang' => $nama_barang
        ]);

        StockInOut::where('kode_barang', $kode)->update([
            'barang' => $nama_barang,
        ]);

        $icon = 'success';
        $title = 'Sukses';
        $text = 'Data Barang Berhasil Diubah';

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
        $kode = $request->kode;
        DataBarang::where('kode', $kode)->delete();

        $icon = 'success';
        $title = 'Sukses';
        $text = 'Barang Berhasil Dihapus';

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }
}
