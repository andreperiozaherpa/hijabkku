<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\StockToko;
use App\Models\SystemSetting;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class LandingController extends Controller
{
    /**
     * Display the main welcome landing page.
     */
    public function index()
    {
        $featuredProducts = collect();
        if (Schema::hasTable('stock_tokos')) {
            $featuredProducts = StockToko::whereHas('data_barang')
                ->with('data_barang')
                ->whereRaw('jumlah > terjual')
                ->latest('id')
                ->get()
                ->unique('kode_barang')
                ->take(3);
        }

        return view('landing.home', compact('featuredProducts'));
    }

    /**
     * Display the dynamic products catalog.
     */
    public function catalog(Request $request)
    {
        $paymentStatus = $request->query('payment_status');
        $invoiceCode = $request->query('invoice');

        if ($paymentStatus === 'success' && $invoiceCode) {
            return redirect()->route('invoice', $invoiceCode);
        } elseif ($paymentStatus === 'failure') {
            session()->flash('payment_error', 'Pembayaran gagal atau dibatalkan.');
        }

        $tokos = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
        $selectedTokoKode = $request->query('toko');

        if (! $selectedTokoKode && $tokos->isNotEmpty()) {
            $selectedTokoKode = $tokos->first()->kode;
        }

        $stocks = new LengthAwarePaginator([], 0, 8);
        $selectedToko = null;

        if ($selectedTokoKode) {
            $selectedToko = Toko::where('kode', $selectedTokoKode)->first();

            $search = $request->query('search');
            $query = StockToko::whereHas('data_barang')
                ->with('data_barang.fotos')
                ->where('kode_toko', $selectedTokoKode)
                ->whereRaw('jumlah > terjual');

            if ($search) {
                $query->whereHas('data_barang', function ($q) use ($search) {
                    $q->where('nama_barang', 'like', '%'.$search.'%')
                        ->orWhere('kode_barang', 'like', '%'.$search.'%');
                });
            }

            $stocks = $query->paginate(8)->onEachSide(0)->withQueryString();
        }

        $xenditSimulationMode = SystemSetting::getByKey('xendit_simulation_mode', 'false');

        return view('landing.catalog', [
            'tokos' => $tokos,
            'selectedTokoKode' => $selectedTokoKode,
            'selectedToko' => $selectedToko,
            'stocks' => $stocks,
            'xenditSimulationMode' => $xenditSimulationMode,
        ]);
    }

    /**
     * Display the About Us page.
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Display the Contact Us page.
     */
    public function contact()
    {
        return view('landing.contact');
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacyPolicy()
    {
        return view('landing.privacy-policy');
    }

    /**
     * Display the Terms & Conditions page.
     */
    public function terms()
    {
        return view('landing.terms');
    }

    /**
     * Display the Return & Refund Policy page.
     */
    public function refundPolicy()
    {
        return view('landing.refund-policy');
    }
}
