<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\SesiKasir;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiKasirController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    //  Helper: resolve the scoped "active session" for the authenticated user.
    //  Per-user scope:  sessions owned by THIS user + this toko that are open.
    //  Legacy scope:    any open session for the toko (admin fallback).
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Return the caller's own active session for a given toko.
     * Non-admin users are always bound to their own session (per-user scope).
     * Admins can optionally view any toko's sessions but open/close their own.
     */
    private function getUserActiveSession(int $userId, string $kodeToko): ?SesiKasir
    {
        return SesiKasir::where('kode_toko', $kodeToko)
            ->where('dibuka_oleh', $userId)
            ->where('status', 'buka')
            ->where('is_user_scoped', true)
            ->first();
    }

    /**
     * Check whether THIS user already closed a session today in this toko.
     */
    private function getUserClosedSessionToday(int $userId, string $kodeToko): ?SesiKasir
    {
        return SesiKasir::where('kode_toko', $kodeToko)
            ->where('dibuka_oleh', $userId)
            ->where('is_user_scoped', true)
            ->where(function ($q) {
                $q->where('status', 'tutup')
                    ->orWhere('status', 'pending_reopen');
            })
            ->whereDate('waktu_tutup', today())
            ->latest()
            ->first();
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Laporan / index: show all sessions (admin) or own sessions (kasir)
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            $toko = Toko::whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])->get();
            $pending_approvals = SesiKasir::with(['dibukaOleh', 'toko'])
                ->where('status', 'pending_reopen')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $toko = Toko::where('kode', $user->kode_toko)
                ->whereNotIn('nama_toko', ['stock hilang', 'Online Shop'])
                ->get();
            $pending_approvals = collect();
        }

        return view('laporan.sesi_kasir.index', compact('toko', 'pending_approvals'));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $query = SesiKasir::with(['dibukaOleh', 'ditutupOleh', 'toko']);

        if ($user->role != 'admin') {
            $query->where('kode_toko', $user->kode_toko)
                ->where('dibuka_oleh', $user->id); // kasir hanya lihat sesinya sendiri
        } else {
            if ($request->filled('toko') && $request->toko !== 'semua') {
                $query->where('kode_toko', $request->toko);
            }
            // Admin can filter by kasir
            if ($request->filled('kasir') && $request->kasir !== 'semua') {
                $query->where('dibuka_oleh', $request->kasir);
            }
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('waktu_buka', Carbon::parse($request->date)->format('Y-m-d'));
        }

        $data = $query->orderBy('id', 'desc')->get();

        return DataTables()->of($data)
            ->addColumn('tgl_buka', function ($row) {
                return $row->waktu_buka->format('d M Y H:i');
            })
            ->addColumn('tgl_tutup', function ($row) {
                return $row->waktu_tutup ? $row->waktu_tutup->format('d M Y H:i') : '-';
            })
            ->addColumn('nama_toko', function ($row) {
                return $row->toko ? $row->toko->nama_toko : '-';
            })
            ->addColumn('kasir_buka', function ($row) {
                return $row->dibukaOleh ? $row->dibukaOleh->name : '-';
            })
            ->addColumn('kasir_tutup', function ($row) {
                return $row->ditutupOleh ? $row->ditutupOleh->name : '-';
            })
            ->addColumn('formatted_saldo_awal', function ($row) {
                return 'Rp. '.number_format($row->saldo_awal, 0, ',', '.');
            })
            ->addColumn('formatted_total_penjualan', function ($row) {
                return $row->total_penjualan !== null ? 'Rp. '.number_format($row->total_penjualan, 0, ',', '.') : '-';
            })
            ->addColumn('formatted_saldo_akhir_sistem', function ($row) {
                return $row->saldo_akhir_sistem !== null ? 'Rp. '.number_format($row->saldo_akhir_sistem, 0, ',', '.') : '-';
            })
            ->addColumn('formatted_saldo_akhir_aktual', function ($row) {
                return $row->saldo_akhir_aktual !== null ? 'Rp. '.number_format($row->saldo_akhir_aktual, 0, ',', '.') : '-';
            })
            ->addColumn('formatted_selisih', function ($row) {
                if ($row->selisih === null) {
                    return '-';
                }
                $formatted = 'Rp. '.number_format(abs($row->selisih), 0, ',', '.');
                if ($row->selisih > 0) {
                    return '<span class="text-success">+'.$formatted.'</span>';
                } elseif ($row->selisih < 0) {
                    return '<span class="text-danger">-'.$formatted.'</span>';
                }

                return '<span class="text-muted">'.$formatted.'</span>';
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status === 'buka') {
                    return '<span class="badge bg-outline-success text-success">Buka</span>';
                }
                if ($row->status === 'pending_reopen') {
                    return '<span class="badge bg-outline-warning text-warning">Pending Reopen</span>';
                }
                if ($row->status === 'rejected') {
                    return '<span class="badge bg-outline-danger text-danger">Ditolak</span>';
                }

                return '<span class="badge bg-outline-secondary text-secondary">Tutup</span>';
            })
            ->rawColumns(['formatted_selisih', 'status_badge'])
            ->make(true);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Buka: open a new session for the authenticated user
    //  Rule: each user can only have ONE active session per toko per day.
    // ──────────────────────────────────────────────────────────────────────────

    public function buka(Request $request)
    {
        $user = Auth::user();
        $kode_toko = $user->kode_toko;

        if ($user->role === 'admin' && $request->filled('kode_toko')) {
            $kode_toko = $request->kode_toko;
        }

        // 1. Guard: this user already has an open session in this toko
        $existingUserSession = $this->getUserActiveSession($user->id, $kode_toko);
        if ($existingUserSession) {
            return response()->json([
                'icon' => 'error',
                'cek_data' => 'Anda sudah memiliki sesi kasir yang sedang aktif!',
            ], 400);
        }

        // 2. Guard: this user already closed a session today — needs approval to reopen
        $userClosedToday = $this->getUserClosedSessionToday($user->id, $kode_toko);

        if ($userClosedToday) {
            if ($user->role !== 'admin') {
                // Check if already has a pending request
                if ($userClosedToday->status === 'pending_reopen') {
                    return response()->json([
                        'icon' => 'warning',
                        'require_approval' => true,
                        'cek_data' => 'Pengajuan pembukaan kembali sesi sebelumnya masih menunggu persetujuan Admin.',
                    ]);
                }

                // Set this session to pending_reopen
                $userClosedToday->update([
                    'status' => 'pending_reopen',
                    'catatan' => 'Pengajuan buka kembali: '.($request->catatan ?: 'Minta buka sesi kembali.'),
                ]);

                return response()->json([
                    'icon' => 'success',
                    'require_approval' => true,
                    'cek_data' => 'Pengajuan pembukaan kembali sesi kasir telah dikirim ke Admin. Silakan tunggu persetujuan.',
                ]);
            } else {
                // Admin reopens their own session directly
                $userClosedToday->update([
                    'status' => 'buka',
                    'waktu_tutup' => null,
                    'ditutup_oleh' => null,
                    'total_penjualan' => null,
                    'saldo_akhir_sistem' => null,
                    'saldo_akhir_aktual' => null,
                    'selisih' => null,
                    'catatan' => 'Dibuka kembali oleh Admin '.$user->name.' pada '.now()->toDateTimeString(),
                ]);

                return response()->json([
                    'icon' => 'success',
                    'cek_data' => 'Sesi kasir berhasil dibuka kembali!',
                ]);
            }
        }

        // 3. Create a brand-new per-user session
        $request->validate([
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        SesiKasir::create([
            'kode_toko' => $kode_toko,
            'waktu_buka' => now(),
            'dibuka_oleh' => $user->id,
            'saldo_awal' => $request->saldo_awal,
            'status' => 'buka',
            'is_user_scoped' => true,
            'catatan' => null,
        ]);

        return response()->json([
            'icon' => 'success',
            'cek_data' => 'Sesi kasir berhasil dibuka!',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Summary: fetch the authenticated user's own active session summary
    // ──────────────────────────────────────────────────────────────────────────

    public function summary(Request $request)
    {
        $user = Auth::user();
        $kode_toko = $user->kode_toko;

        if ($user->role === 'admin' && $request->filled('kode_toko')) {
            $kode_toko = $request->kode_toko;
        }

        // Per-user scope: find this user's own open session
        $session = $this->getUserActiveSession($user->id, $kode_toko);

        if (! $session) {
            return response()->json(['error' => 'Sesi aktif tidak ditemukan.'], 404);
        }

        $total_penjualan = Pembayaran::where('sesi_kasir_id', $session->id)->sum('total_harga');
        $saldo_akhir_sistem = $session->saldo_awal + $total_penjualan;

        return response()->json([
            'saldo_awal' => $session->saldo_awal,
            'total_penjualan' => $total_penjualan,
            'saldo_akhir_sistem' => $saldo_akhir_sistem,
            'waktu_buka' => $session->waktu_buka->translatedFormat('d F Y H:i:s'),
            'dibuka_oleh' => $session->dibukaOleh->name ?? 'System',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Tutup: close the authenticated user's own active session
    // ──────────────────────────────────────────────────────────────────────────

    public function tutup(Request $request)
    {
        $request->validate([
            'saldo_akhir_aktual' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $kode_toko = $user->kode_toko;

        if ($user->role === 'admin' && $request->filled('kode_toko')) {
            $kode_toko = $request->kode_toko;
        }

        // Per-user scope: only close THIS user's own session
        $session = $this->getUserActiveSession($user->id, $kode_toko);

        if (! $session) {
            return response()->json([
                'icon' => 'error',
                'cek_data' => 'Tidak ada sesi kasir aktif milik Anda yang dapat ditutup!',
            ], 400);
        }

        $total_penjualan = Pembayaran::where('sesi_kasir_id', $session->id)->sum('total_harga');
        $saldo_akhir_sistem = $session->saldo_awal + $total_penjualan;
        $saldo_akhir_aktual = $request->saldo_akhir_aktual;
        $selisih = $saldo_akhir_aktual - $saldo_akhir_sistem;

        $session->update([
            'waktu_tutup' => now(),
            'ditutup_oleh' => $user->id,
            'total_penjualan' => $total_penjualan,
            'saldo_akhir_sistem' => $saldo_akhir_sistem,
            'saldo_akhir_aktual' => $saldo_akhir_aktual,
            'selisih' => $selisih,
            'status' => 'tutup',
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'icon' => 'success',
            'cek_data' => 'Sesi kasir berhasil ditutup!',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    //  Approve / Reject (admin only)
    // ──────────────────────────────────────────────────────────────────────────

    public function approve(Request $request, $id)
    {
        $session = SesiKasir::findOrFail($id);
        if ($session->status !== 'pending_reopen') {
            return response()->json([
                'icon' => 'error',
                'cek_data' => 'Sesi kasir ini tidak dalam status menunggu persetujuan.',
            ], 400);
        }

        $user = Auth::user();
        $session->update([
            'status' => 'buka',
            'waktu_tutup' => null,
            'ditutup_oleh' => null,
            'total_penjualan' => null,
            'saldo_akhir_sistem' => null,
            'saldo_akhir_aktual' => null,
            'selisih' => null,
            'catatan' => 'Disetujui oleh Admin: '.$user->name.' pada '.now()->toDateTimeString().($session->catatan ? ' ('.$session->catatan.')' : ''),
        ]);

        return response()->json([
            'icon' => 'success',
            'cek_data' => 'Sesi kasir berhasil disetujui dan dibuka kembali!',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $session = SesiKasir::findOrFail($id);
        if ($session->status !== 'pending_reopen') {
            return response()->json([
                'icon' => 'error',
                'cek_data' => 'Sesi kasir ini tidak dalam status menunggu persetujuan.',
            ], 400);
        }

        $user = Auth::user();
        $reason = $request->input('alasan') ?: 'Ditolak oleh Admin.';
        $session->update([
            'status' => 'tutup',
            'catatan' => 'Ditolak oleh Admin: '.$user->name.' pada '.now()->toDateTimeString().' (Alasan: '.$reason.') [Catatan kasir: '.$session->catatan.']',
        ]);

        return response()->json([
            'icon' => 'success',
            'cek_data' => 'Pengajuan sesi kasir berhasil ditolak.',
        ]);
    }
}
