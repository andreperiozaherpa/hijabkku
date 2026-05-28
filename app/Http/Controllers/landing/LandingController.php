<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\StockToko;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the main welcome landing page.
     */
    public function index()
    {
        return view('landing.welcome');
    }

    /**
     * Display the dynamic products catalog.
     */
    public function catalog(Request $request)
    {
        $tokos = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
        $selectedTokoKode = $request->query('toko');

        if (!$selectedTokoKode && $tokos->isNotEmpty()) {
            $selectedTokoKode = $tokos->first()->kode;
        }

        $stocks = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
        $selectedToko = null;

        if ($selectedTokoKode) {
            $selectedToko = Toko::where('kode', $selectedTokoKode)->first();

            $search = $request->query('search');
            $query = StockToko::whereHas('data_barang')
                ->with('data_barang')
                ->where('kode_toko', $selectedTokoKode)
                ->whereRaw('jumlah > terjual');

            if ($search) {
                $query->whereHas('data_barang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', '%' . $search . '%')
                      ->orWhere('kode_barang', 'like', '%' . $search . '%');
                });
            }

            $stocks = $query->paginate(8)->onEachSide(0)->withQueryString();
        }

        return view('landing.catalog', [
            'tokos' => $tokos,
            'selectedTokoKode' => $selectedTokoKode,
            'selectedToko' => $selectedToko,
            'stocks' => $stocks
        ]);
    }
}
