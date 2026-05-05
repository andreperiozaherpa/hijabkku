<?php

namespace App\Http\Controllers;

use App\Models\merekBarang;
use Illuminate\Http\Request;

class MerekBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('barang.kategori');
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
        $jenis = $request->jenis;

        $data = new merekBarang();
        $data->kode = $kode;
        $data->jenis = $jenis;
        $data->save();

        $icon = 'success';
        $title = 'Sukses';
        $text = 'Data Jenis Barang Berhasil Disimpan';

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
        $data = merekBarang::get();
        return DataTables()->of($data)
            ->addColumn('aksi', function ($data) {
                $group = '<button data-kode="' . $data->kode . '" type="button" class="destroy btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
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
    public function edit(merekBarang $merekBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, merekBarang $merekBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $kode = $request->kode;
        merekBarang::where('kode', $kode)->delete();

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
