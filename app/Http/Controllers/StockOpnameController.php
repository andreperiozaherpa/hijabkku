<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\StockOpname;
use App\Models\StockOpnameAudit;
use App\Models\StockOpnameItem;
use App\Models\StockToko;
use App\Models\Toko;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockOpnameController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            $toko = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
        } else {
            $toko = Toko::where('kode', $user->kode_toko)
                ->whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])
                ->get();
        }

        $supervisors = User::where('status', 'on')->get();

        return view('laporan.opname.index', compact('toko', 'supervisors'));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $query = StockOpname::with(['petugas', 'supervisor', 'toko']);

        if ($user->role != 'admin') {
            $query->where('kode_toko', $user->kode_toko);
        } else {
            if ($request->filled('toko') && $request->toko !== 'semua') {
                $query->where('kode_toko', $request->toko);
            }
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date)->format('Y-m-d'));
        }

        $data = $query->orderBy('id', 'desc')->get();

        return DataTables()->of($data)
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->format('d M Y H:i');
            })
            ->addColumn('nama_toko', function ($row) {
                return $row->toko ? $row->toko->nama_toko : '-';
            })
            ->addColumn('nama_petugas', function ($row) {
                return $row->petugas ? $row->petugas->name : '-';
            })
            ->addColumn('nama_supervisor', function ($row) {
                return $row->supervisor ? $row->supervisor->name : '-';
            })
            ->addColumn('status_badge', function ($row) {
                $badges = [
                    'Draft' => 'bg-secondary text-white',
                    'Counting' => 'bg-info text-white',
                    'Recount' => 'bg-warning text-dark',
                    'Review' => 'bg-primary text-white',
                    'Approved' => 'bg-success text-white',
                    'Posted' => 'bg-dark text-white',
                ];
                $badge = $badges[$row->status] ?? 'bg-secondary';

                return '<span class="badge '.$badge.'">'.$row->status.'</span>';
            })
            ->addColumn('aksi', function ($row) {
                $html = '<a href="/laporan/opname/detail/'.$row->id.'" class="btn btn-sm btn-icon btn-icon-only btn-outline-primary me-1" title="Detail Sesi"><i data-acorn-icon="eye"></i></a>';
                if (Auth::user()->role === 'admin' && $row->status === 'Draft') {
                    $html .= '<button type="button" data-id="'.$row->id.'" class="btn btn-sm btn-icon btn-icon-only btn-outline-danger btn-delete-so" title="Hapus Sesi"><i data-acorn-icon="bin"></i></button>';
                }

                return $html;
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_toko' => 'required',
            'supervisor_id' => 'required|exists:users,id',
        ]);

        // Check if there is already an active (un-posted) stock opname session for this toko
        $activeSessionExists = StockOpname::where('kode_toko', $request->kode_toko)
            ->where('status', '!=', 'Posted')
            ->exists();

        if ($activeSessionExists) {
            return response()->json([
                'success' => false,
                'message' => 'Toko ini masih memiliki sesi Stock Opname yang aktif! Selesaikan sesi sebelumnya terlebih dahulu.',
            ]);
        }

        $nomor_so = 'SO-'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(2)));

        $so = StockOpname::create([
            'nomor_so' => $nomor_so,
            'kode_toko' => $request->kode_toko,
            'status' => 'Draft',
            'petugas_id' => Auth::user()->id,
            'supervisor_id' => $request->supervisor_id,
            'notes' => $request->notes,
        ]);

        FirebaseService::triggerUpdate('updates/opname_list');

        return response()->json([
            'success' => true,
            'message' => 'Sesi Stock Opname berhasil dibuat!',
            'redirect' => '/laporan/opname/detail/'.$so->id,
        ]);
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hanya Admin yang berhak menghapus sesi!']);
        }

        $session = StockOpname::findOrFail($id);

        if ($session->status !== 'Draft') {
            return response()->json(['success' => false, 'message' => 'Hanya sesi berstatus Draft yang dapat dihapus!']);
        }

        DB::transaction(function () use ($session) {
            $session->items()->delete();
            $session->audits()->delete();
            $session->delete();
        });

        FirebaseService::triggerUpdate('updates/opname_list');

        return response()->json([
            'success' => true,
            'message' => 'Sesi Stock Opname berhasil dihapus!',
        ]);
    }

    public function detail($id)
    {
        $session = StockOpname::with(['petugas', 'supervisor', 'toko'])->findOrFail($id);

        // Eager load items summaries to build visual card dashboards in a single aggregate query
        $summary = StockOpnameItem::where('stock_opname_id', $id)
            ->selectRaw('
                COUNT(*) as total_sku,
                COUNT(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL THEN 1 END) as counted,
                COUNT(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL AND difference != 0 THEN 1 END) as variance_items,
                SUM(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL THEN difference_value ELSE 0 END) as variance_value,
                SUM(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL AND difference_value > 0 THEN difference_value ELSE 0 END) as variance_plus,
                SUM(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL AND difference_value < 0 THEN difference_value ELSE 0 END) as variance_minus,
                SUM(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL THEN difference_value_jual ELSE 0 END) as variance_value_jual,
                SUM(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL AND difference_value_jual > 0 THEN difference_value_jual ELSE 0 END) as variance_plus_jual,
                SUM(CASE WHEN COALESCE(round_1_qty, round_2_qty, round_3_qty, final_qty) IS NOT NULL AND difference_value_jual < 0 THEN difference_value_jual ELSE 0 END) as variance_minus_jual
            ')
            ->first();

        $total_sku = $summary->total_sku ?? 0;
        $counted = $summary->counted ?? 0;
        $remaining = $total_sku - $counted;
        $variance_items = $summary->variance_items ?? 0;
        $variance_value = $summary->variance_value ?? 0;
        $variance_plus = $summary->variance_plus ?? 0;
        $variance_minus = $summary->variance_minus ?? 0;
        $variance_value_jual = $summary->variance_value_jual ?? 0;
        $variance_plus_jual = $summary->variance_plus_jual ?? 0;
        $variance_minus_jual = $summary->variance_minus_jual ?? 0;

        // Extract categories and racks from snapshot items for partial filtering dropdowns
        $categories = DataBarang::select('jenis_barang')->distinct()->pluck('jenis_barang');
        $racks = StockOpnameItem::where('stock_opname_id', $id)
            ->whereNotNull('rak_lokasi')
            ->select('rak_lokasi')
            ->distinct()
            ->pluck('rak_lokasi');

        // Determine current active round of the session
        $active_round = 1;
        if ($session->status === 'Recount') {
            $hasRound3 = StockOpnameItem::where('stock_opname_id', $id)
                ->whereNotNull('round_3_qty')
                ->exists();
            $active_round = $hasRound3 ? 3 : 2;
        } elseif (in_array($session->status, ['Review', 'Approved', 'Posted'])) {
            $active_round = 'final';
        }

        return view('laporan.opname.detail', compact('session', 'total_sku', 'counted', 'remaining', 'variance_items', 'variance_value', 'variance_plus', 'variance_minus', 'variance_value_jual', 'variance_plus_jual', 'variance_minus_jual', 'categories', 'racks', 'active_round'));
    }

    public function itemsData(Request $request, $id)
    {
        $session = StockOpname::findOrFail($id);
        $waktuMulai = $session->tanggal_mulai ?? $session->created_at;
        $session_kode_toko = $session->kode_toko;

        $query = StockOpnameItem::with('barang')
            ->select('stock_opname_items.*')
            ->selectSub(function ($q) use ($session_kode_toko, $waktuMulai) {
                $q->selectRaw('COALESCE(SUM(transaksis.jumlah), 0)')
                    ->from('transaksis')
                    ->leftJoin('pesanan_pickups', 'transaksis.kode_invoice', '=', 'pesanan_pickups.kode_invoice')
                    ->whereColumn('transaksis.kode_barang', 'stock_opname_items.kode_barang')
                    ->where('transaksis.kode_toko', $session_kode_toko)
                    ->where('transaksis.created_at', '>=', $waktuMulai)
                    ->where(function ($sub) {
                        $sub->whereNull('pesanan_pickups.id')
                            ->orWhere('pesanan_pickups.status_pengambilan', '!=', 'Belum Diambil');
                    });
            }, 'sales_during_opname')
            ->where('stock_opname_items.stock_opname_id', $id);

        // Apply filters
        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereHas('barang', function ($q) use ($category) {
                $q->where('jenis_barang', $category);
            });
        }

        if ($request->filled('rack')) {
            $query->where('rak_lokasi', $request->rack);
        }

        if ($request->filled('variance_only') && $request->variance_only == 'true') {
            $query->where('difference', '!=', 0);
        }

        if ($request->filled('uncounted_only') && $request->uncounted_only == 'true') {
            $query->whereNull('round_1_qty');
        }

        if ($request->filled('search_query')) {
            $search = $request->search_query;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhereHas('barang', function ($qb) use ($search) {
                        $qb->where('nama_barang', 'like', "%{$search}%");
                    });
            });
        }

        return DataTables()->of($query)
            ->addColumn('product_name', function ($row) {
                return $row->barang ? $row->barang->nama_barang : '-';
            })
            ->addColumn('category', function ($row) {
                return $row->barang ? $row->barang->jenis_barang : '-';
            })
            ->addColumn('harga_jual', function ($row) {
                if (! $row->barang) {
                    return '-';
                }
                $harga = floatval(str_replace('.', '', $row->barang->harga_jual));

                return 'Rp. '.number_format($harga, 0, ',', '.');
            })
            ->addColumn('sales_during_opname', function ($row) {
                return $row->sales_during_opname ?? 0;
            })
            ->addColumn('belum_diambil_qty', function ($row) {
                return $row->belum_diambil_qty ?? 0;
            })
            ->addColumn('adjusted_snapshot', function ($row) use ($session_kode_toko) {
                $stock = DB::table('stock_tokos')
                    ->where('kode_toko', $session_kode_toko)
                    ->where('kode_barang', $row->kode_barang)
                    ->first();
                $currentJumlah = $stock ? $stock->jumlah : 0;
                $deltaJumlah = (empty($row->snapshot_jumlah) && $row->snapshot_qty > 0) ? 0 : ($currentJumlah - $row->snapshot_jumlah);

                return max(0, ($row->snapshot_qty + $row->belum_diambil_qty + $deltaJumlah) - ($row->sales_during_opname ?? 0));
            })
            ->addColumn('status_badge', function ($row) {
                $classes = [
                    'Match' => 'bg-outline-success text-success',
                    'Need Recount' => 'bg-outline-warning text-warning',
                    'Reviewed' => 'bg-outline-primary text-primary',
                    'Finalized' => 'bg-outline-dark text-dark',
                ];
                $class = $classes[$row->status] ?? 'bg-outline-secondary';

                return '<span class="badge '.$class.'">'.$row->status.'</span>';
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    public function startCounting(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $session = StockOpname::lockForUpdate()->findOrFail($request->stock_opname_id);

                if ($session->status !== 'Draft') {
                    throw new \Exception('Counting sudah dimulai!');
                }

                $session->status = 'Counting';
                $session->tanggal_mulai = now();
                $session->save();

                // Perform snapshot / freeze stock from stock_tokos table where available stock != 0
                $stocks = StockToko::where('kode_toko', $session->kode_toko)->get();

                // Query all booking (belum diambil) stock at this store
                $bookingQuantities = DB::table('transaksis')
                    ->join('pesanan_pickups', 'transaksis.kode_invoice', '=', 'pesanan_pickups.kode_invoice')
                    ->where('transaksis.kode_toko', $session->kode_toko)
                    ->where('pesanan_pickups.status_pengambilan', 'Belum Diambil')
                    ->groupBy('transaksis.kode_barang')
                    ->select('transaksis.kode_barang', DB::raw('SUM(transaksis.jumlah) as total'))
                    ->pluck('total', 'kode_barang');

                // Eliminate N+1 queries by eager loading all products in a single batch
                $barangsMap = DataBarang::whereIn('kode', $stocks->pluck('kode_barang'))->get()->keyBy('kode');

                $insertData = [];
                foreach ($stocks as $stock) {
                    $availableQty = $stock->jumlah - $stock->terjual;
                    $belumDiambil = intval($bookingQuantities->get($stock->kode_barang, 0));
                    $expectedPhysical = $availableQty + $belumDiambil;

                    if ($expectedPhysical == 0) {
                        continue; // Skip items with 0 total physical expected stock to keep stock opname efficient
                    }

                    $barang = $barangsMap->get($stock->kode_barang);
                    $harga_beli = $barang ? floatval(str_replace('.', '', $barang->harga_beli)) : 0;
                    $harga_jual = $barang ? floatval(str_replace('.', '', $barang->harga_jual)) : 0;

                    $insertData[] = [
                        'stock_opname_id' => $session->id,
                        'kode_barang' => $stock->kode_barang,
                        'snapshot_qty' => $availableQty, // Available stock in store
                        'snapshot_jumlah' => $stock->jumlah, // Save snapshot_jumlah!
                        'belum_diambil_qty' => $belumDiambil,
                        'final_qty' => 0,
                        'difference' => -$expectedPhysical,
                        'difference_value' => -$expectedPhysical * $harga_beli,
                        'difference_value_jual' => -$expectedPhysical * $harga_jual,
                        'status' => 'Match',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Batch insert for performance
                if (count($insertData) > 0) {
                    StockOpnameItem::insert($insertData);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        $session = StockOpname::findOrFail($request->stock_opname_id);
        FirebaseService::triggerUpdate('updates/opname_list');
        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json([
            'success' => true,
            'message' => 'Snapshot Stock Opname berhasil dibuat dan proses counting dimulai!',
        ]);
    }

    public function scanBarcode(Request $request)
    {
        $session = StockOpname::findOrFail($request->stock_opname_id);

        if (! in_array($session->status, ['Counting', 'Recount'])) {
            return response()->json(['success' => false, 'message' => 'Proses scan tidak diizinkan pada status sesi ini!']);
        }

        $round = intval($request->round);
        $barcode = trim($request->barcode);

        $barang = DataBarang::where('kode', $barcode)->first();
        if (! $barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan!']);
        }

        try {
            $result = DB::transaction(function () use ($session, $barcode, $round, $barang) {
                // Pessimistic lock on session row to serialize concurrent requests on the same session
                $lockedSession = StockOpname::lockForUpdate()->findOrFail($session->id);

                if (! in_array($lockedSession->status, ['Counting', 'Recount'])) {
                    throw new \Exception('Proses scan tidak diizinkan pada status sesi ini!');
                }

                $item = StockOpnameItem::where('stock_opname_id', $lockedSession->id)
                    ->where('kode_barang', $barcode)
                    ->lockForUpdate()
                    ->first();

                // If item not exist in snapshot (newly found on the floor), create it dynamically!
                if (! $item) {
                    $stock = DB::table('stock_tokos')
                        ->where('kode_toko', $lockedSession->kode_toko)
                        ->where('kode_barang', $barcode)
                        ->first();

                    try {
                        $item = StockOpnameItem::create([
                            'stock_opname_id' => $lockedSession->id,
                            'kode_barang' => $barcode,
                            'snapshot_qty' => $stock ? ($stock->jumlah - $stock->terjual) : 0,
                            'snapshot_jumlah' => $stock ? $stock->jumlah : 0,
                            'final_qty' => 0,
                            'difference' => 0,
                            'difference_value' => 0,
                            'difference_value_jual' => 0,
                            'status' => 'Match',
                        ]);
                    } catch (QueryException $e) {
                        // Concurrent request created it first, fetch it with lock
                        $item = StockOpnameItem::where('stock_opname_id', $lockedSession->id)
                            ->where('kode_barang', $barcode)
                            ->lockForUpdate()
                            ->firstOrFail();
                    }
                }

                $qtyCol = 'round_'.$round.'_qty';
                $qtyBefore = $item->$qtyCol;
                $qtyAfter = ($qtyBefore ?? 0) + 1;

                $item->$qtyCol = $qtyAfter;

                $waktuMulai = $lockedSession->tanggal_mulai ?? $lockedSession->created_at;

                // Recalculate Final Qty & Variance taking real-time sales into account
                $sales = DB::table('transaksis')
                    ->leftJoin('pesanan_pickups', 'transaksis.kode_invoice', '=', 'pesanan_pickups.kode_invoice')
                    ->where('transaksis.kode_toko', $lockedSession->kode_toko)
                    ->where('transaksis.kode_barang', $barcode)
                    ->where('transaksis.created_at', '>=', $waktuMulai)
                    ->where(function ($sub) {
                        $sub->whereNull('pesanan_pickups.id')
                            ->orWhere('pesanan_pickups.status_pengambilan', '!=', 'Belum Diambil');
                    })
                    ->sum('transaksis.jumlah');

                $stock = DB::table('stock_tokos')
                    ->where('kode_toko', $lockedSession->kode_toko)
                    ->where('kode_barang', $barcode)
                    ->first();
                $currentJumlah = $stock ? $stock->jumlah : 0;
                $deltaJumlah = (empty($item->snapshot_jumlah) && $item->snapshot_qty > 0) ? 0 : ($currentJumlah - $item->snapshot_jumlah);

                $adjustedSnapshot = max(0, ($item->snapshot_qty + $item->belum_diambil_qty + $deltaJumlah) - $sales);

                $item->final_qty = $qtyAfter;
                $item->difference = $item->final_qty - $adjustedSnapshot;
                $item->difference_value = $item->difference * floatval(str_replace('.', '', $barang->harga_beli));
                $item->difference_value_jual = $item->difference * floatval(str_replace('.', '', $barang->harga_jual));
                $item->save();

                // Record audit trail log
                StockOpnameAudit::create([
                    'stock_opname_id' => $lockedSession->id,
                    'stock_opname_item_id' => $item->id,
                    'user_id' => Auth::user()->id,
                    'round' => $round,
                    'qty_before' => $qtyBefore,
                    'qty_after' => $qtyAfter,
                    'action' => 'Scan Barcode',
                ]);

                return $item;
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json([
            'success' => true,
            'message' => $barang->nama_barang.' +1',
            'item' => $result,
        ]);
    }

    public function updateQtyManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $item = StockOpnameItem::findOrFail($request->item_id);
        $session = StockOpname::findOrFail($item->stock_opname_id);

        if (! in_array($session->status, ['Counting', 'Recount', 'Review'])) {
            return response()->json(['success' => false, 'message' => 'Status sesi ini tidak mengizinkan pengubahan kuantitas!']);
        }

        $user = Auth::user();
        if ($session->status === 'Review' && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hanya Admin yang berhak melakukan manual adjustment pada mode Review!']);
        }

        $roundParam = $request->round;
        $qty = intval($request->qty);

        $barang = DataBarang::where('kode', $item->kode_barang)->first();
        $harga_beli = $barang ? floatval(str_replace('.', '', $barang->harga_beli)) : 0;
        $harga_jual = $barang ? floatval(str_replace('.', '', $barang->harga_jual)) : 0;

        try {
            DB::transaction(function () use ($item, $roundParam, $qty, $harga_beli, $harga_jual, $session, $user) {
                // Pessimistic lock on session & item to serialize concurrent manual updates on same item
                $lockedSession = StockOpname::lockForUpdate()->findOrFail($session->id);
                $lockedItem = StockOpnameItem::lockForUpdate()->findOrFail($item->id);

                // Determine column to edit
                if ($lockedSession->status === 'Review' || $roundParam === 'final' || intval($roundParam) === 0) {
                    $qtyCol = 'final_qty';
                    $logRound = 0;
                } else {
                    $qtyCol = 'round_'.intval($roundParam).'_qty';
                    $logRound = intval($roundParam);
                }

                $qtyBefore = $lockedItem->$qtyCol;
                $lockedItem->$qtyCol = $qty;

                $waktuMulai = $lockedSession->tanggal_mulai ?? $lockedSession->created_at;

                $sales = DB::table('transaksis')
                    ->leftJoin('pesanan_pickups', 'transaksis.kode_invoice', '=', 'pesanan_pickups.kode_invoice')
                    ->where('transaksis.kode_toko', $lockedSession->kode_toko)
                    ->where('transaksis.kode_barang', $lockedItem->kode_barang)
                    ->where('transaksis.created_at', '>=', $waktuMulai)
                    ->where(function ($sub) {
                        $sub->whereNull('pesanan_pickups.id')
                            ->orWhere('pesanan_pickups.status_pengambilan', '!=', 'Belum Diambil');
                    })
                    ->sum('transaksis.jumlah');

                $stock = DB::table('stock_tokos')
                    ->where('kode_toko', $lockedSession->kode_toko)
                    ->where('kode_barang', $lockedItem->kode_barang)
                    ->first();
                $currentJumlah = $stock ? $stock->jumlah : 0;
                $deltaJumlah = (empty($lockedItem->snapshot_jumlah) && $lockedItem->snapshot_qty > 0) ? 0 : ($currentJumlah - $lockedItem->snapshot_jumlah);

                $adjustedSnapshot = max(0, ($lockedItem->snapshot_qty + $lockedItem->belum_diambil_qty + $deltaJumlah) - $sales);

                $lockedItem->final_qty = $qty;
                $lockedItem->difference = $lockedItem->final_qty - $adjustedSnapshot;
                $lockedItem->difference_value = $lockedItem->difference * $harga_beli;
                $lockedItem->difference_value_jual = $lockedItem->difference * $harga_jual;

                $lockedItem->save();

                // Record audit trail log
                StockOpnameAudit::create([
                    'stock_opname_id' => $lockedSession->id,
                    'stock_opname_item_id' => $lockedItem->id,
                    'user_id' => $user->id,
                    'round' => $logRound,
                    'qty_before' => $qtyBefore,
                    'qty_after' => $qty,
                    'action' => $lockedSession->status === 'Review' ? 'Review Manual Adjust' : 'Manual Edit',
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui!',
        ]);
    }

    public function generateRecount(Request $request)
    {
        $session = StockOpname::findOrFail($request->stock_opname_id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $session->supervisor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Hanya Admin atau Supervisor yang ditunjuk yang berhak melakukan validasi round!']);
        }

        try {
            $result = DB::transaction(function () use ($session) {
                // Pessimistic lock on the session row
                $lockedSession = StockOpname::lockForUpdate()->findOrFail($session->id);

                if (! in_array($lockedSession->status, ['Counting', 'Recount'])) {
                    throw new \Exception('Sesi harus berstatus Counting atau Recount!');
                }

                $waktuMulai = $lockedSession->tanggal_mulai ?? $lockedSession->created_at;

                // Bulk prefetch sales during opname for this shop
                $salesData = DB::table('transaksis')
                    ->leftJoin('pesanan_pickups', 'transaksis.kode_invoice', '=', 'pesanan_pickups.kode_invoice')
                    ->where('transaksis.kode_toko', $lockedSession->kode_toko)
                    ->where('transaksis.created_at', '>=', $waktuMulai)
                    ->where(function ($sub) {
                        $sub->whereNull('pesanan_pickups.id')
                            ->orWhere('pesanan_pickups.status_pengambilan', '!=', 'Belum Diambil');
                    })
                    ->groupBy('transaksis.kode_barang')
                    ->select('transaksis.kode_barang', DB::raw('SUM(transaksis.jumlah) as total_sales'))
                    ->pluck('total_sales', 'transaksis.kode_barang')
                    ->toArray();

                // Fetch all items to optimize price fetching in bulk
                $allItems = StockOpnameItem::where('stock_opname_id', $lockedSession->id)->get();
                $itemCodes = $allItems->pluck('kode_barang')->toArray();

                // Bulk prefetch current StockToko jumlah for delta calculation
                $currentStocksMap = DB::table('stock_tokos')
                    ->where('kode_toko', $lockedSession->kode_toko)
                    ->whereIn('kode_barang', $itemCodes)
                    ->pluck('jumlah', 'kode_barang')
                    ->toArray();

                // Bulk prefetch purchase prices (harga_beli) from master products
                $prices = DataBarang::whereIn('kode', $itemCodes)
                    ->pluck('harga_beli', 'kode')
                    ->toArray();

                // Bulk prefetch retail prices (harga_jual) from master products
                $pricesJual = DataBarang::whereIn('kode', $itemCodes)
                    ->pluck('harga_jual', 'kode')
                    ->toArray();

                // If the session status is Counting, we are transitioning Round 1 -> Round 2
                if ($lockedSession->status === 'Counting') {
                    $lockedSession->status = 'Recount';
                    $lockedSession->save();

                    foreach ($allItems as $item) {
                        $sales = $salesData[$item->kode_barang] ?? 0;
                        $currentJumlah = $currentStocksMap[$item->kode_barang] ?? 0;
                        $deltaJumlah = (empty($item->snapshot_jumlah) && $item->snapshot_qty > 0) ? 0 : ($currentJumlah - $item->snapshot_jumlah);
                        $adjustedSnapshot = max(0, ($item->snapshot_qty + $item->belum_diambil_qty + $deltaJumlah) - $sales);

                        // Update dynamic fields
                        $item->difference = ($item->round_1_qty ?? 0) - $adjustedSnapshot;
                        $rawPrice = $prices[$item->kode_barang] ?? '0';
                        $harga_beli = floatval(str_replace('.', '', $rawPrice));
                        $rawPriceJual = $pricesJual[$item->kode_barang] ?? '0';
                        $harga_jual = floatval(str_replace('.', '', $rawPriceJual));
                        $item->difference_value = $item->difference * $harga_beli;
                        $item->difference_value_jual = $item->difference * $harga_jual;

                        if ($adjustedSnapshot == ($item->round_1_qty ?? 0)) {
                            $item->status = 'Match';
                        } else {
                            $item->status = 'Need Recount';
                        }
                        $item->round_2_qty = $item->round_1_qty ?? 0; // Carry over Round 1 count so it is not reset!
                        $item->save();
                    }

                    return 'Round 2';
                }

                // If the session status is already Recount, we are transitioning Round 2 -> Round 3
                if ($lockedSession->status === 'Recount') {
                    $hasStillVariance = false;
                    foreach ($allItems as $item) {
                        $sales = $salesData[$item->kode_barang] ?? 0;
                        $currentJumlah = $currentStocksMap[$item->kode_barang] ?? 0;
                        $deltaJumlah = (empty($item->snapshot_jumlah) && $item->snapshot_qty > 0) ? 0 : ($currentJumlah - $item->snapshot_jumlah);
                        $adjustedSnapshot = max(0, ($item->snapshot_qty + $item->belum_diambil_qty + $deltaJumlah) - $sales);

                        $rawPrice = $prices[$item->kode_barang] ?? '0';
                        $harga_beli = floatval(str_replace('.', '', $rawPrice));

                        if ($item->status !== 'Need Recount') {
                            // Carry over matched items
                            $item->round_3_qty = $item->round_2_qty ?? 0;
                            $item->save();

                            continue;
                        }

                        // Process recount items
                        $item->difference = ($item->round_2_qty ?? 0) - $adjustedSnapshot;
                        $rawPrice = $prices[$item->kode_barang] ?? '0';
                        $harga_beli = floatval(str_replace('.', '', $rawPrice));
                        $rawPriceJual = $pricesJual[$item->kode_barang] ?? '0';
                        $harga_jual = floatval(str_replace('.', '', $rawPriceJual));
                        $item->difference_value = $item->difference * $harga_beli;
                        $item->difference_value_jual = $item->difference * $harga_jual;

                        if ($adjustedSnapshot == ($item->round_2_qty ?? 0)) {
                            $item->status = 'Match';
                        } else {
                            $item->status = 'Need Recount';
                            $hasStillVariance = true;
                        }
                        $item->round_3_qty = $item->round_2_qty ?? 0; // Carry over Round 2 count so it is not reset!
                        $item->save();
                    }

                    if (! $hasStillVariance) {
                        return 'none';
                    }

                    return 'Round 3';
                }
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        if ($result === 'none') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan selisih pada Round 2! Tidak perlu menghasilkan Round 3.',
            ]);
        }

        FirebaseService::triggerUpdate('updates/opname_list');
        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Recount untuk '.$result.' berhasil dibuat! Item yang selisih siap dihitung kembali.',
        ]);
    }

    public function approveFinal(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hanya Admin yang dapat memfinalkan Stock Opname!']);
        }

        $session = StockOpname::findOrFail($request->stock_opname_id);

        try {
            DB::transaction(function () use ($session) {
                // Pessimistic lock on session row
                $lockedSession = StockOpname::lockForUpdate()->findOrFail($session->id);

                if (! in_array($lockedSession->status, ['Counting', 'Recount'])) {
                    throw new \Exception('Sesi harus berstatus Counting atau Recount!');
                }

                $lockedSession->status = 'Review';
                $lockedSession->supervisor_id = Auth::user()->id;
                $lockedSession->save();

                $waktuMulai = $lockedSession->tanggal_mulai ?? $lockedSession->created_at;

                // Bulk prefetch sales during opname for this shop
                $salesData = DB::table('transaksis')
                    ->leftJoin('pesanan_pickups', 'transaksis.kode_invoice', '=', 'pesanan_pickups.kode_invoice')
                    ->where('transaksis.kode_toko', $lockedSession->kode_toko)
                    ->where('transaksis.created_at', '>=', $waktuMulai)
                    ->where(function ($sub) {
                        $sub->whereNull('pesanan_pickups.id')
                            ->orWhere('pesanan_pickups.status_pengambilan', '!=', 'Belum Diambil');
                    })
                    ->groupBy('transaksis.kode_barang')
                    ->select('transaksis.kode_barang', DB::raw('SUM(transaksis.jumlah) as total_sales'))
                    ->pluck('total_sales', 'transaksis.kode_barang')
                    ->toArray();

                // Fetch all items to optimize price fetching in bulk
                $items = StockOpnameItem::where('stock_opname_id', $lockedSession->id)->get();
                $itemCodes = $items->pluck('kode_barang')->toArray();

                // Bulk prefetch current StockToko jumlah for delta calculation
                $currentStocksMap = DB::table('stock_tokos')
                    ->where('kode_toko', $lockedSession->kode_toko)
                    ->whereIn('kode_barang', $itemCodes)
                    ->pluck('jumlah', 'kode_barang')
                    ->toArray();

                // Bulk prefetch purchase prices (harga_beli) from master products
                $prices = DataBarang::whereIn('kode', $itemCodes)
                    ->pluck('harga_beli', 'kode')
                    ->toArray();

                // Bulk prefetch retail prices (harga_jual) from master products
                $pricesJual = DataBarang::whereIn('kode', $itemCodes)
                    ->pluck('harga_jual', 'kode')
                    ->toArray();

                foreach ($items as $item) {
                    $rawPrice = $prices[$item->kode_barang] ?? '0';
                    $harga_beli = floatval(str_replace('.', '', $rawPrice));
                    $rawPriceJual = $pricesJual[$item->kode_barang] ?? '0';
                    $harga_jual = floatval(str_replace('.', '', $rawPriceJual));

                    // Sync latest counted quantity across rounds
                    if ($item->round_3_qty !== null) {
                        $item->final_qty = $item->round_3_qty;
                    } elseif ($item->round_2_qty !== null) {
                        $item->final_qty = $item->round_2_qty;
                    } else {
                        $item->final_qty = $item->round_1_qty ?? 0;
                    }

                    $sales = $salesData[$item->kode_barang] ?? 0;
                    $currentJumlah = $currentStocksMap[$item->kode_barang] ?? 0;
                    $deltaJumlah = (empty($item->snapshot_jumlah) && $item->snapshot_qty > 0) ? 0 : ($currentJumlah - $item->snapshot_jumlah);
                    $adjustedSnapshot = max(0, ($item->snapshot_qty + $item->belum_diambil_qty + $deltaJumlah) - $sales);

                    $item->difference = $item->final_qty - $adjustedSnapshot;
                    $item->difference_value = $item->difference * $harga_beli;
                    $item->difference_value_jual = $item->difference * $harga_jual;
                    $item->status = 'Finalized';
                    $item->save();
                }
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        FirebaseService::triggerUpdate('updates/opname_list');
        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json([
            'success' => true,
            'message' => 'Sesi disetujui! Laporan masuk ke tahap Review untuk supervisor dengan kuantitas yang tersinkronisasi.',
        ]);
    }

    public function postAdjustment(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Hanya Admin yang dapat memposting penyesuaian stok!']);
        }

        $session = StockOpname::findOrFail($request->stock_opname_id);

        try {
            DB::transaction(function () use ($session) {
                // Pessimistic lock on the SO session to ensure status transitions are atomic and concurrent scans/posts are serialized
                $lockedSession = StockOpname::lockForUpdate()->findOrFail($session->id);

                if ($lockedSession->status !== 'Review') {
                    throw new \Exception('Sesi harus berstatus Review untuk bisa diposting!');
                }

                $lockedSession->status = 'Posted';
                $lockedSession->tanggal_selesai = now();
                $lockedSession->save();

                $items = StockOpnameItem::where('stock_opname_id', $lockedSession->id)->get();
                $itemCodes = $items->pluck('kode_barang')->toArray();

                // Bulk prefetch product names from master data in case we need to create new stock
                $barangsMap = DataBarang::whereIn('kode', $itemCodes)
                    ->get()
                    ->keyBy('kode');

                foreach ($items as $item) {
                    if ($item->difference == 0) {
                        continue;
                    }

                    // Retrieve stock with lockForUpdate to prevent race conditions & lost updates
                    $stock = StockToko::where('kode_toko', $lockedSession->kode_toko)
                        ->where('kode_barang', $item->kode_barang)
                        ->lockForUpdate()
                        ->first();

                    if ($stock) {
                        $stock->jumlah = $stock->jumlah + $item->difference;
                        $stock->save();
                    } else {
                        // Create new stock if not existed
                        $barang = $barangsMap->get($item->kode_barang);
                        StockToko::create([
                            'kode_input' => 'SO-ADJ-'.$lockedSession->nomor_so,
                            'kode_toko' => $lockedSession->kode_toko,
                            'kode_barang' => $item->kode_barang,
                            'nama_barang' => $barang ? $barang->nama_barang : 'Unknown Item',
                            'jumlah' => $item->final_qty,
                            'terjual' => 0,
                            'supplier' => '-',
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        FirebaseService::triggerUpdate('updates/opname_list');
        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json([
            'success' => true,
            'message' => 'Koreksi stok berhasil diposting ke tabel persediaan!',
        ]);
    }

    public function export($id)
    {
        $session = StockOpname::with(['petugas', 'supervisor', 'toko'])->findOrFail($id);
        $items = StockOpnameItem::with('barang')->where('stock_opname_id', $id)->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Stock_Opname_'.$session->nomor_so.'.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($items, $session) {
            $file = fopen('php://output', 'w');

            // Add session header metadata in CSV
            fputcsv($file, ['Nomor Sesi', $session->nomor_so]);
            fputcsv($file, ['Toko/Cabang', $session->toko ? $session->toko->nama_toko : '-']);
            fputcsv($file, ['Tanggal Mulai', $session->tanggal_mulai]);
            fputcsv($file, ['Status Sesi', $session->status]);
            fputcsv($file, ['Petugas', $session->petugas ? $session->petugas->name : '-']);
            fputcsv($file, []); // blank spacer

            // Table headers
            fputcsv($file, ['Barcode/SKU', 'Nama Barang', 'Kategori', 'Snapshot Qty', 'Round 1 Qty', 'Round 2 Qty', 'Round 3 Qty', 'Final Qty', 'Selisih', 'Nilai Selisih Ecer', 'Nilai Selisih Beli', 'Status']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->kode_barang,
                    $item->barang ? $item->barang->nama_barang : '-',
                    $item->barang ? $item->barang->jenis_barang : '-',
                    $item->snapshot_qty,
                    $item->round_1_qty ?? '-',
                    $item->round_2_qty ?? '-',
                    $item->round_3_qty ?? '-',
                    $item->final_qty,
                    $item->difference,
                    number_format($item->difference_value_jual, 0, ',', '.'),
                    number_format($item->difference_value, 0, ',', '.'),
                    $item->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function auditLogs($id)
    {
        $logs = StockOpnameAudit::with(['user', 'item.barang'])
            ->where('stock_opname_id', $id)
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs->map(function ($log) {
                $barangName = $log->item && $log->item->barang ? $log->item->barang->nama_barang : 'Unknown SKU';

                return [
                    'id' => $log->id,
                    'user_name' => $log->user ? $log->user->name : 'System',
                    'action' => $log->action,
                    'qty_before' => $log->qty_before,
                    'qty_after' => $log->qty_after,
                    'round' => $log->round == 0 ? 'Final' : $log->round,
                    'time' => Carbon::parse($log->created_at)->diffForHumans(),
                    'product_name' => $barangName,
                    'sku' => $log->item ? $log->item->kode_barang : '-',
                ];
            }),
        ]);
    }

    public function searchMasterProducts(Request $request, $id)
    {
        $session = StockOpname::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $session->supervisor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Hanya Admin atau Supervisor yang ditunjuk yang berhak mengakses fitur ini!'], 403);
        }

        $search = trim($request->query('search_query'));

        if (empty($search)) {
            return response()->json([]);
        }

        // Fetch matching master products
        $products = DataBarang::where('kode', 'like', "%{$search}%")
            ->orWhere('nama_barang', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        // Check which products are already added to this session
        $addedCodes = StockOpnameItem::where('stock_opname_id', $id)
            ->pluck('kode_barang')
            ->toArray();

        $results = [];
        foreach ($products as $p) {
            $results[] = [
                'kode' => $p->kode,
                'nama_barang' => $p->nama_barang,
                'jenis_barang' => $p->jenis_barang,
                'harga_jual' => 'Rp. '.number_format(floatval(str_replace('.', '', $p->harga_jual)), 0, ',', '.'),
                'is_added' => in_array($p->kode, $addedCodes),
            ];
        }

        return response()->json($results);
    }

    public function addMasterProduct(Request $request, $id)
    {
        $session = StockOpname::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $session->supervisor_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Hanya Admin atau Supervisor yang ditunjuk yang berhak menambahkan barang manual!'], 403);
        }

        if (! in_array($session->status, ['Counting', 'Recount'])) {
            return response()->json(['success' => false, 'message' => 'Proses tambah barang tidak diizinkan pada status sesi ini!']);
        }

        $kode_barang = trim($request->kode_barang);
        $barang = DataBarang::where('kode', $kode_barang)->first();

        if (! $barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan di data master!']);
        }

        try {
            DB::transaction(function () use ($session, $kode_barang, $barang) {
                // Pessimistic lock on the SO session to ensure status transitions are atomic and concurrent scans on same session are serialized
                $lockedSession = StockOpname::lockForUpdate()->findOrFail($session->id);

                if (! in_array($lockedSession->status, ['Counting', 'Recount'])) {
                    throw new \Exception('Proses tambah barang tidak diizinkan pada status sesi ini!');
                }

                // Check if already exists in this session
                $existing = StockOpnameItem::where('stock_opname_id', $lockedSession->id)
                    ->where('kode_barang', $kode_barang)
                    ->exists();

                if ($existing) {
                    throw new \Exception('Barang sudah ada di dalam list stock opname!');
                }

                // Determine snapshot quantity from stock_tokos table
                $stockToko = StockToko::where('kode_toko', $lockedSession->kode_toko)
                    ->where('kode_barang', $kode_barang)
                    ->first();

                $snapshotQty = 0;
                $snapshotJumlah = 0;
                if ($stockToko) {
                    $snapshotQty = max(0, $stockToko->jumlah - $stockToko->terjual);
                    $snapshotJumlah = $stockToko->jumlah;
                }

                $harga_beli = floatval(str_replace('.', '', $barang->harga_beli));
                $harga_jual = floatval(str_replace('.', '', $barang->harga_jual));

                try {
                    StockOpnameItem::create([
                        'stock_opname_id' => $lockedSession->id,
                        'kode_barang' => $kode_barang,
                        'snapshot_qty' => $snapshotQty,
                        'snapshot_jumlah' => $snapshotJumlah,
                        'final_qty' => 0,
                        'difference' => -$snapshotQty,
                        'difference_value' => -$snapshotQty * $harga_beli,
                        'difference_value_jual' => -$snapshotQty * $harga_jual,
                        'status' => 'Match',
                    ]);
                } catch (QueryException $e) {
                    throw new \Exception('Barang sudah ada di dalam list stock opname!');
                }
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        FirebaseService::triggerUpdate('updates/opname_session_'.$session->id);

        return response()->json(['success' => true, 'message' => 'Barang berhasil ditambahkan ke list stock opname!']);
    }
}
