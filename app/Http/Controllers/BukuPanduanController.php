<?php

namespace App\Http\Controllers;

use App\Models\bahanBarang;
use App\Models\JenisBarang;
use App\Models\merekBarang;
use App\Models\modelBarang;
use App\Models\packagingBarang;
use App\Models\ukuranBarang;
use App\Models\variasiBarang;
use Illuminate\Http\Request;

class BukuPanduanController extends Controller
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

        // $data_header = [
        //     'jenis',
        //     'merek',
        //     'model',
        //     'bahan',
        //     'variasi',
        //     'ukuran',
        //     'packaging',
        // ];

        // foreach ($data_header as $value) {
        //     // $data = [$value];
        //     $i = 0;
        //     foreach ($jenis as $j) {
        //         $data[$value] = $$value;
        //     }
        // }

        // dd($data);

        // for ($i = 0; $i < 7; $i++) {
        //     # code...
        //     $data_header = [
        //         'jenis',
        //         'merek',
        //         'model',
        //         'bahan',
        //         'variasi',
        //         'ukuran',
        //         'packaging',
        //     ];

        //     if ($data_header[$i] == 'jenis') {
        //         $data[$data_header[$i]] = $jenis;
        //     }
        // }
        // dd($data);
        return view('buku.panduan.index', [
            'jenis' => $jenis,
            'merek' => $merek,
            'model' => $model,
            'bahan' => $bahan,
            'variasi' => $variasi,
            'ukuran' => $ukuran,
            'packaging' => $packaging,
            // 'data' => $data
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
