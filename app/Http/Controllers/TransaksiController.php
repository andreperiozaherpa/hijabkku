<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Pembayaran;
use App\Models\SesiKasir;
use App\Models\StockOpname;
use App\Models\StockOpnameAudit;
use App\Models\StockOpnameItem;
use App\Models\StockToko;
use App\Models\SystemSetting;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\FirebaseService;
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
    public function index(Request $request)
    {
        $user = Auth::user();
        $data_toko = $user->toko;
        if ($user->role === 'admin' && $request->filled('kode_toko')) {
            $toko = Toko::where('kode', $request->kode_toko)->first();
            if ($toko) {
                $data_toko = $toko;
            }
        }
        $all_tokos = [];
        if ($user->role === 'admin') {
            $all_tokos = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
        }
        $fitur_sesi_kasir = SystemSetting::getByKey('fitur_sesi_kasir', 'true') === 'true';
        $active_session = null;
        $pending_session = null;
        $rejected_session = null;
        $today_closed = false;

        $closed_session_today = null;
        if ($fitur_sesi_kasir) {
            // Per-user scope: find the current user's own active session
            $active_session = SesiKasir::where('kode_toko', $data_toko->kode)
                ->where('dibuka_oleh', $user->id)
                ->where('status', 'buka')
                ->where('is_user_scoped', true)
                ->first();

            // Check if THIS user closed a session today
            $today_closed = SesiKasir::where('kode_toko', $data_toko->kode)
                ->where('dibuka_oleh', $user->id)
                ->where('is_user_scoped', true)
                ->where(function ($query) {
                    $query->where('status', 'tutup')
                        ->orWhere('status', 'pending_reopen');
                })
                ->whereDate('waktu_tutup', today())
                ->exists();

            if ($today_closed) {
                $closed_session_today = SesiKasir::where('kode_toko', $data_toko->kode)
                    ->where('dibuka_oleh', $user->id)
                    ->where('is_user_scoped', true)
                    ->where(function ($query) {
                        $query->where('status', 'tutup')
                            ->orWhere('status', 'pending_reopen');
                    })
                    ->whereDate('waktu_tutup', today())
                    ->latest()
                    ->first();
            }

            if (! $active_session) {
                $pending_session = SesiKasir::where('kode_toko', $data_toko->kode)
                    ->where('dibuka_oleh', $user->id)
                    ->where('is_user_scoped', true)
                    ->where('status', 'pending_reopen')
                    ->first();
                $rejected_session = SesiKasir::where('kode_toko', $data_toko->kode)
                    ->where('dibuka_oleh', $user->id)
                    ->where('is_user_scoped', true)
                    ->where('status', 'tutup')
                    ->whereDate('waktu_tutup', today())
                    ->where('catatan', 'like', '%Ditolak%')
                    ->latest()
                    ->first();
            }
        }

        $xenditSimulationMode = SystemSetting::getByKey('xendit_simulation_mode', 'false');

        return view('transaksi.penjualan', [
            'data_toko' => $data_toko,
            'all_tokos' => $all_tokos,
            'active_session' => $active_session,
            'fitur_sesi_kasir' => $fitur_sesi_kasir,
            'today_closed' => $today_closed,
            'pending_session' => $pending_session,
            'rejected_session' => $rejected_session,
            'closed_session_today' => $closed_session_today,
            'xenditSimulationMode' => $xenditSimulationMode,
        ]);
    }

    public function index_daftar()
    {
        if (Auth::user()->role == 'admin') {
            $data_toko = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
            $data_kasir = User::where('status', 'on')
                ->whereIn('role', ['admin', 'kasir'])
                ->get();
        } else {
            $data_toko = Toko::where('kode', Auth::user()->kode_toko)
                ->whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])
                ->get();
            $data_kasir = User::where('id', Auth::user()->id)
                ->where('status', 'on')
                ->get();
        }

        return view('transaksi.daftar', [
            'toko' => $data_toko,
            'kasir' => $data_kasir,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $kode_toko = $user->kode_toko;
        if ($user->role === 'admin' && $request->filled('kode_toko')) {
            $kode_toko = $request->kode_toko;
        }

        $key1 = $request->key1;
        $key2 = $request->key2;
        $param = $request->param;
        if ($param == 'all') {
            $stock_toko = StockToko::leftJoin('data_barangs', 'stock_tokos.kode_barang', '=', 'data_barangs.kode')
                ->select(
                    'stock_tokos.*',
                    DB::raw('COALESCE(data_barangs.nama_barang, stock_tokos.nama_barang) as nama_barang'),
                    'data_barangs.jenis_barang',
                    'data_barangs.harga_beli',
                    'data_barangs.harga_jual',
                    'data_barangs.harga_grosir'
                )
                ->where('kode_toko', $kode_toko)
                ->whereColumn('jumlah', '!=', 'terjual')
                ->orderByDesc('kode_toko')
                ->get();
        } else {
            $stock_toko = StockToko::leftJoin('data_barangs', 'stock_tokos.kode_barang', '=', 'data_barangs.kode')
                ->select(
                    'stock_tokos.*',
                    DB::raw('COALESCE(data_barangs.nama_barang, stock_tokos.nama_barang) as nama_barang'),
                    'data_barangs.jenis_barang',
                    'data_barangs.harga_beli',
                    'data_barangs.harga_jual',
                    'data_barangs.harga_grosir'
                )
                ->where(function ($q) use ($key1, $key2) {
                    $q->where(function ($sq) use ($key1, $key2) {
                        $sq->where('stock_tokos.nama_barang', 'like', '%'.$key1.'%')
                            ->where('stock_tokos.nama_barang', 'like', '%'.$key2.'%');
                    })->orWhere(function ($sq) use ($key1, $key2) {
                        $sq->where('data_barangs.nama_barang', 'like', '%'.$key1.'%')
                            ->where('data_barangs.nama_barang', 'like', '%'.$key2.'%');
                    });
                })
                ->where('kode_toko', $kode_toko)
                ->whereColumn('jumlah', '!=', 'terjual')
                ->orderByDesc('kode_toko')
                ->get();
        }

        $hideStock = false;
        if ($user->role !== 'admin') {
            $hideStock = StockOpname::where('kode_toko', $kode_toko)
                ->whereIn('status', ['Draft', 'Counting', 'Recount', 'Review'])
                ->exists();
        }

        return response()->json([
            'stock' => $stock_toko,
            'hide_stock' => $hideStock,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $invoice = $request->invoice;
        $total_harga = $request->total_harga;
        $pembayaran = $request->pembayaran;
        $kembali = $request->kembali;
        $user = Auth::user();
        $kode_toko = $user->kode_toko;
        if ($user->role === 'admin' && $request->filled('kode_toko')) {
            $kode_toko = $request->kode_toko;
        }

        $fitur_sesi_kasir = SystemSetting::getByKey('fitur_sesi_kasir', 'true') === 'true';
        $active_session = null;

        if ($fitur_sesi_kasir) {
            // Per-user scope: the cashier must have opened their OWN session
            $active_session = SesiKasir::where('kode_toko', $kode_toko)
                ->where('dibuka_oleh', $user->id)
                ->where('status', 'buka')
                ->where('is_user_scoped', true)
                ->first();

            if (! $active_session) {
                return response()->json([
                    'icon' => 'error',
                    'cek_data' => 'Anda belum membuka sesi kasir! Silakan buka sesi terlebih dahulu.',
                ]);
            }
        }

        $data = $request->data;
        $cek_transaksi = Pembayaran::where('kode_invoice', $invoice)->count();

        $now = Carbon::now('Asia/Jakarta');
        $time = $now->format('H:i');

        if ($time >= '07:30' && $time <= '17:01') {
            $shif = 1;
        } elseif ($time >= '17:00' && $time <= '23:59') {
            $shif = 2;
        } else {
            $shif = 0;
        }

        if ($user->shift == $shif || $user->shift == 0) {
            if ($cek_transaksi <= 0) {
                // Eager load all required DataBarang models in 1 query outside the loop (O(1))
                $productCodes = array_column($data, 'nomor_paket');
                $dataBarangs = DataBarang::whereIn('kode', $productCodes)->get()->keyBy('kode');

                // Bulk prefetch StockToko models to validate available stocks (O(1))
                $stocks = StockToko::where('kode_toko', $kode_toko)
                    ->whereIn('kode_barang', $productCodes)
                    ->get()
                    ->keyBy('kode_barang');

                // Validate all items' stock first to prevent race conditions atomically
                foreach ($data as $d) {
                    $stock = $stocks->get($d['nomor_paket']);
                    if (! $stock) {
                        return response()->json([
                            'icon' => 'error',
                            'cek_data' => 'Barang '.$d['nama_barang'].' tidak terdaftar di toko ini!',
                        ]);
                    }
                    $available = $stock->jumlah - $stock->terjual;
                    if ($available < intval($d['jumlah_barang'])) {
                        return response()->json([
                            'icon' => 'error',
                            'cek_data' => 'Stok barang '.$d['nama_barang'].' tidak mencukupi! Sisa stok saat ini: '.$available,
                        ]);
                    }
                }

                foreach ($data as $d) {
                    $dataBarang = $dataBarangs->get($d['nomor_paket']);
                    $hargaBeliMentah = $dataBarang ? $dataBarang->harga_beli : 0;

                    $arr = [
                        'kode_invoice' => $invoice,
                        'kode_toko' => $kode_toko,
                        'kode_barang' => $d['nomor_paket'],
                        'nama_barang' => $d['nama_barang'],
                        'metode' => $d['method'],
                        'jumlah' => $d['jumlah_barang'],
                        'harga' => $d['harga_item'],
                        'harga_beli' => str_replace('.', '', $hargaBeliMentah),
                        'harga_total' => $d['harga_jual'],
                    ];
                    Transaksi::create($arr);

                    // Use atomic increment query to avoid concurrency race conditions and separate select query (O(1) instead of N+1)
                    StockToko::where('kode_toko', $kode_toko)
                        ->where('kode_barang', $d['nomor_paket'])
                        ->increment('terjual', $d['jumlah_barang']);
                }
                $data_pembayaran = [
                    'kode_invoice' => $invoice,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'total_harga' => $total_harga,
                    'pembayaran' => $pembayaran,
                    'kembalian' => $kembali,
                    'metode_pembayaran' => 'TUNAI',
                    'sesi_kasir_id' => $active_session ? $active_session->id : null,
                ];
                Pembayaran::create($data_pembayaran);

                // Trigger real-time updates via Firebase
                FirebaseService::triggerUpdate('updates/sales', ['toko' => $kode_toko]);

                // Also trigger updates and auto-adjust counted quantities for any active stock opname session in this shop
                $activeSessions = StockOpname::where('kode_toko', $kode_toko)
                    ->whereIn('status', ['Counting', 'Recount'])
                    ->get();

                foreach ($activeSessions as $session) {
                    // Update Firebase trigger for real-time UI refresh
                    FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

                    // Real-Time Adjusting Physical Count by Sales
                    foreach ($data as $d) {
                        $soItem = StockOpnameItem::where('stock_opname_id', $session->id)
                            ->where('kode_barang', $d['nomor_paket'])
                            ->first();

                        if ($soItem) {
                            $soldQty = intval($d['jumlah_barang']);

                            // Determine the current active round
                            $activeRound = 1;
                            if ($session->status === 'Recount') {
                                $hasRound3 = StockOpnameItem::where('stock_opname_id', $session->id)
                                    ->whereNotNull('round_3_qty')
                                    ->exists();
                                $activeRound = $hasRound3 ? 3 : 2;
                            }

                            // Check if the current round has been counted (is not null)
                            $qtyCol = 'round_'.$activeRound.'_qty';
                            if ($soItem->$qtyCol !== null) {
                                $qtyBefore = $soItem->$qtyCol;
                                // Deduct sold quantity, ensuring it doesn't go below 0
                                $soItem->$qtyCol = max(0, $soItem->$qtyCol - $soldQty);

                                // Also update final_qty if it's currently populated
                                if ($soItem->final_qty !== null) {
                                    $soItem->final_qty = max(0, $soItem->final_qty - $soldQty);
                                }

                                // Fetch product purchase price to recalculate difference_value
                                $barang = DataBarang::where('kode', $soItem->kode_barang)->first();
                                $harga_beli = $barang ? floatval(str_replace('.', '', $barang->harga_beli)) : 0;

                                // Recalculate difference and difference_value
                                $sales = DB::table('transaksis')
                                    ->where('kode_toko', $session->kode_toko)
                                    ->where('kode_barang', $soItem->kode_barang)
                                    ->where('created_at', '>=', $session->created_at)
                                    ->sum('jumlah');

                                $adjustedSnapshot = max(0, $soItem->snapshot_qty - $sales);
                                $soItem->difference = $soItem->final_qty - $adjustedSnapshot;
                                $soItem->difference_value = $soItem->difference * $harga_beli;

                                $soItem->save();

                                // Record in audit logs so there is a clear trail of this automated adjustment!
                                StockOpnameAudit::create([
                                    'stock_opname_id' => $session->id,
                                    'stock_opname_item_id' => $soItem->id,
                                    'user_id' => $user->id, // The cashier who did the POS transaction
                                    'round' => $activeRound,
                                    'qty_before' => $qtyBefore,
                                    'qty_after' => $soItem->$qtyCol,
                                    'action' => 'POS Sale Auto-Deduct',
                                ]);
                            }
                        }
                    }
                }

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
            'cek_data' => $cek_data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $query = Pembayaran::query()->with(['transaksis.toko']);

        if ($user->role != 'admin') {
            $query->where('user_id', $user->id);
        } else {
            if ($request->filled('kasir') && $request->kasir !== 'semua') {
                $query->where('user_id', $request->kasir);
            }
        }

        if ($request->filled('date') && $request->filled('param')) {
            $date = $request->date;
            try {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $date);
            } catch (\Exception $e) {
                try {
                    $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
                } catch (\Exception $ex) {
                    $carbonDate = null;
                }
            }

            if ($carbonDate) {
                $param = $request->param;
                if ($param == 'hari') {
                    $query->whereDate('created_at', $carbonDate->format('Y-m-d'));
                } elseif ($param == 'bulan') {
                    $query->whereYear('created_at', $carbonDate->year)
                        ->whereMonth('created_at', $carbonDate->month);
                } elseif ($param == 'tahun') {
                    $query->whereYear('created_at', $carbonDate->year);
                }
            }
        }

        if ($request->filled('toko') && $request->toko !== 'semua') {
            $toko = $request->toko;
            $query->whereHas('transaksis', function ($q) use ($toko) {
                $q->where('kode_toko', $toko);
            });
        }

        $data = $query->orderBy('id', 'desc');

        return DataTables()->of($data)
            ->addColumn('tanggal', function ($row) {
                $tanggal = Carbon::parse($row->created_at)->locale('id');
                $tanggal->settings(['formatFunction' => 'translatedFormat']);

                return $tanggal->format('l, d M Y, H:i');
            })
            ->addColumn('toko', function ($row) {
                $firstItem = $row->transaksis->first();

                return $firstItem && $firstItem->toko ? $firstItem->toko->nama_toko : '-';
            })
            ->addColumn('metode', function ($row) {
                $uniqueMetodes = $row->transaksis->pluck('metode')->unique()->filter()->toArray();
                if (empty($uniqueMetodes)) {
                    $uniqueMetodes = ['umum'];
                }
                $badges = [];
                foreach ($uniqueMetodes as $metode) {
                    if ($metode === 'grosir') {
                        $badges[] = '<span class="badge bg-outline-success text-success text-uppercase">Grosir</span>';
                    } else {
                        $badges[] = '<span class="badge bg-outline-primary text-primary text-uppercase">Umum</span>';
                    }
                }

                return implode(' ', $badges);
            })
            ->addColumn('metode_pembayaran', function ($row) {
                $method = $row->metode_pembayaran ?? 'TUNAI';
                $colorMap = [
                    'TUNAI' => 'secondary',
                    'QRIS' => 'info',
                    'VA' => 'primary',
                    'EWALLET' => 'warning',
                ];
                $color = $colorMap[$method] ?? 'secondary';
                $label = match ($method) {
                    'TUNAI' => 'Tunai',
                    'QRIS' => 'QRIS',
                    'VA' => 'Virtual Account',
                    'EWALLET' => 'E-Wallet',
                    default => $method,
                };

                return '<span class="badge bg-'.$color.' text-uppercase">'.$label.'</span>';
            })
            ->addColumn('total_rupiah', function ($row) {
                return 'Rp. '.number_format($row->total_harga, 0, ',', '.');
            })
            ->addColumn('aksi', function ($row) {
                $eyeIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                </svg>';

                return '<button data-invoice="'.$row->kode_invoice.'" type="button" class="detailTransaksi btn btn-sm btn-icon btn-icon-only btn-outline-primary me-1" title="Detail Transaksi">'.$eyeIcon.'</button>';
            })
            ->rawColumns(['metode', 'metode_pembayaran', 'aksi'])
            ->make(true);
    }

    public function show_detail(Request $request)
    {
        $invoice = $request->invoice;
        $data_perinvoice = Pembayaran::with(['transaksis.toko'])->where('kode_invoice', $invoice)->first();

        if (! $data_perinvoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        $data_perbarang = $data_perinvoice->transaksis;
        $tanggal = Carbon::parse($data_perinvoice->created_at)->locale('id');
        $tanggal->settings(['formatFunction' => 'translatedFormat']);

        $firstItem = $data_perbarang->first();
        $tokoName = $firstItem && $firstItem->toko ? $firstItem->toko->nama_toko : '-';
        $uniqueMetodes = $data_perbarang->pluck('metode')->unique()->filter()->toArray();
        if (empty($uniqueMetodes)) {
            $uniqueMetodes = ['umum'];
        }
        $metode = implode(' / ', array_map('strtoupper', $uniqueMetodes));

        return response()->json([
            'data' => $data_perbarang,
            'total_harga' => $data_perinvoice->total_harga,
            'pembayaran' => $data_perinvoice->pembayaran,
            'kembalian' => $data_perinvoice->kembalian,
            'username' => $data_perinvoice->user_name,
            'tanggal' => $tanggal->format('l, d M Y, a H:i'),
            'metode' => $metode,
            'metode_pembayaran' => $data_perinvoice->metode_pembayaran ?? 'TUNAI',
            'toko' => $tokoName,
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
            // code...
            $toko = null;
            $tokos = 'Semua';
        } else {
            $toko = $request->toko;
            $tokos = Toko::where('kode', $toko)->first();
        }

        if ($format == 'Hari') {
            // code...
            if ($users == 'semua') {
                // code...
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->where('transaksis.kode_toko', 'LIKE', '%'.$toko.'%')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->whereDate('transaksis.created_at', $tahun.'-'.$bulan.'-'.$hari)
                    ->groupBy('pembayarans.kode_invoice')
                    ->get();
            } else {
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->where('transaksis.kode_toko', 'LIKE', '%'.$toko.'%')
                    ->where('pembayarans.user_id', $users)
                    ->whereDate('transaksis.created_at', $tahun.'-'.$bulan.'-'.$hari)
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
            // code...
            if ($users == 'semua') {
                // code...
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->where('transaksis.kode_toko', 'LIKE', '%'.$toko.'%')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->whereMonth('pembayarans.created_at', $bulan)
                    ->whereYear('pembayarans.created_at', $tahun)
                    ->groupBy('pembayarans.kode_invoice')
                    ->get();
            } else {
                $transaksi = Pembayaran::select('pembayarans.*', 'transaksis.kode_toko')
                    ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                    ->where('transaksis.kode_toko', 'LIKE', '%'.$toko.'%')
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
            // code...
            if ($toko == 'semua') {
                // code...
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
                    // code...
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
            // code...
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
            // code...
            $dates = $data_tahun.'-'.$data_bulan.'-'.$data_hari;
        } elseif ($data_format == 'Bulan') {
            // code...
            $dates = $data_tahun.'-'.$data_bulan;
        } elseif ($data_format == 'Tahun') {
            // code...
            $dates = $data_tahun;
        }

        if ($request->dataToko == 'semua') {
            // code...
            $toko = 'null';
        } else {
            // code...
            $toko = $data_toko;
        }

        if ($data_users == 'semua') {
            // code...
            $bruto = Transaksi::select(
                DB::raw('SUM(transaksis.harga_total) as harga_totals'),
            )
                ->where('kode_toko', 'LIKE', '%'.$toko.'%')
                ->whereDate('transaksis.created_at', 'LIKE', '%'.$dates.'%')
                ->first();

            $keuntungan = Transaksi::select(
                DB::raw('sum( transaksis.harga_total-(transaksis.harga_beli * jumlah) ) as harga_beli_totals'),
            )
                ->where('kode_toko', 'LIKE', '%'.$toko.'%')
                ->whereDate('transaksis.created_at', 'LIKE', '%'.$dates.'%')
                ->first();
        } else {
            // code...
            $bruto = Pembayaran::select(
                'pembayarans.*',
                'transaksis.kode_toko',
                DB::raw('SUM(transaksis.harga_total) as harga_totals'),
            )
                ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                ->where('transaksis.kode_toko', 'LIKE', '%'.$toko.'%')
                ->where('pembayarans.user_id', $data_users)
                ->whereDate('transaksis.created_at', 'LIKE', '%'.$dates.'%')
                ->first();

            $keuntungan = Pembayaran::select(
                'pembayarans.*',
                'transaksis.kode_toko',
                DB::raw('sum( transaksis.harga_total-(transaksis.harga_beli * jumlah) ) as harga_beli_totals'),
            )
                ->join('transaksis', 'transaksis.kode_invoice', '=', 'pembayarans.kode_invoice')
                ->where('transaksis.kode_toko', 'LIKE', '%'.$toko.'%')
                ->where('pembayarans.user_id', $data_users)
                ->whereDate('transaksis.created_at', 'LIKE', '%'.$dates.'%')
                ->first();
        }

        return response()->json([
            'bruto' => $bruto,
            'keuntungan' => $keuntungan,
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
