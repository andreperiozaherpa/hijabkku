<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Toko;
use App\Models\DataBarang;
use App\Models\Supplier;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\StockToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $tokos = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();

        $metrics = [
            'total_users' => User::count(),
            'total_tokos' => $tokos->count(),
            'total_barangs' => DataBarang::count(),
            'total_suppliers' => Supplier::count(),
        ];

        // 1. Core Sales Data (Total Omzet Overall)
        $yearlySales = Pembayaran::select(DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total_harga) as total'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year', 'asc')
            ->get();

        $monthlySales = Pembayaran::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total_harga) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        $dailySales = Pembayaran::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_harga) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        // 2. Sales per Store/Toko Data (Omzet per Toko)

        // Yearly Sales per Toko
        $storeYearlyRaw = Transaksi::select('kode_toko', DB::raw('YEAR(created_at) as year'), DB::raw('SUM(harga_total) as total'))
            ->groupBy('kode_toko', DB::raw('YEAR(created_at)'))
            ->get();

        $yearlyLabels = $yearlySales->pluck('year')->toArray();
        if (empty($yearlyLabels)) {
            $yearlyLabels = $storeYearlyRaw->pluck('year')->unique()->sort()->values()->toArray();
        }

        $storeYearlyDatasets = [];
        foreach ($tokos as $toko) {
            $data = [];
            foreach ($yearlyLabels as $year) {
                $val = $storeYearlyRaw->where('kode_toko', $toko->kode)->where('year', $year)->first();
                $data[] = $val ? (int)$val->total : 0;
            }
            $storeYearlyDatasets[] = [
                'label' => $toko->nama_toko,
                'data' => $data,
            ];
        }

        // Monthly Sales per Toko (last 12 months)
        $activeMonths = Transaksi::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->pluck('month')
            ->reverse()
            ->values()
            ->toArray();

        $storeMonthlyRaw = Transaksi::select('kode_toko', DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(harga_total) as total'))
            ->whereIn(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $activeMonths)
            ->groupBy('kode_toko', DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        $storeMonthlyDatasets = [];
        foreach ($tokos as $toko) {
            $data = [];
            foreach ($activeMonths as $month) {
                $val = $storeMonthlyRaw->where('kode_toko', $toko->kode)->where('month', $month)->first();
                $data[] = $val ? (int)$val->total : 0;
            }
            $storeMonthlyDatasets[] = [
                'label' => $toko->nama_toko,
                'data' => $data,
            ];
        }

        // Daily Sales per Toko (last 30 active days)
        $activeDays = Transaksi::select(DB::raw('DATE(created_at) as date'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->pluck('date')
            ->reverse()
            ->values()
            ->toArray();

        $storeDailyRaw = Transaksi::select('kode_toko', DB::raw('DATE(created_at) as date'), DB::raw('SUM(harga_total) as total'))
            ->whereIn(DB::raw('DATE(created_at)'), $activeDays)
            ->groupBy('kode_toko', DB::raw('DATE(created_at)'))
            ->get();

        $storeDailyDatasets = [];
        foreach ($tokos as $toko) {
            $data = [];
            foreach ($activeDays as $date) {
                $val = $storeDailyRaw->where('kode_toko', $toko->kode)->where('date', $date)->first();
                $data[] = $val ? (int)$val->total : 0;
            }
            $storeDailyDatasets[] = [
                'label' => $toko->nama_toko,
                'data' => $data,
            ];
        }

        // 3. Best Sellers (Top 20)
        $bestSellers = Transaksi::select('kode_barang', 'nama_barang', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('kode_barang', 'nama_barang')
            ->orderBy('total_terjual', 'desc')
            ->limit(20)
            ->get();

        // 4. Oldest Stock Items (Top 20 oldest items in stock)
        $oldestStocks = StockToko::select('kode_barang', 'nama_barang', 'jumlah', 'created_at')
            ->where('jumlah', '>', 0)
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        $role = auth()->user()->role;
        if (!in_array($role, ['admin', 'kasir', 'gudang'])) {
            $role = 'gudang';
        }

        return view('dashboard.' . $role, compact(
            'metrics', 
            'yearlySales', 
            'monthlySales', 
            'dailySales', 
            'bestSellers', 
            'oldestStocks',
            'tokos',
            'yearlyLabels',
            'storeYearlyDatasets',
            'activeMonths',
            'storeMonthlyDatasets',
            'activeDays',
            'storeDailyDatasets'
        ));
    }
}
