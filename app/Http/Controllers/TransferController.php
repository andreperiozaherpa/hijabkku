<?php

namespace App\Http\Controllers;

use App\Models\RekeningClient;
use App\Models\Transfer;
use App\Services\XenditPayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TransferController extends Controller
{
    protected XenditPayoutService $payoutService;

    public function __construct(XenditPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index()
    {
        $saldo = $this->payoutService->getBalance();
        $rekeningClients = RekeningClient::orderBy('nama_client')->get();

        return view('transfer.index', compact('saldo', 'rekeningClients'));
    }

    public function show()
    {
        $data = Transfer::query()
            ->with('rekeningClient')
            ->orderBy('created_at', 'desc');

        return DataTables()->of($data)
            ->addIndexColumn()
            ->editColumn('amount', function ($data) {
                return 'Rp '.number_format($data->amount, 0, ',', '.');
            })
            ->editColumn('status', function ($data) {
                $badges = [
                    'PENDING' => 'primary',
                    'ACCEPTED' => 'info',
                    'PROCESSING' => 'warning',
                    'SUCCEEDED' => 'success',
                    'FAILED' => 'danger',
                    'REVERSED' => 'danger',
                    'REJECTED' => 'danger',
                    'CANCELLED' => 'secondary',
                ];

                $class = $badges[$data->status] ?? 'secondary';

                return '<span class="badge bg-'.$class.'">'.$data->status.'</span>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d M Y H:i');
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rekening_client_id' => 'required|exists:rekening_clients,id',
            'amount' => 'required|numeric|min:10000|max:1000000000',
            'description' => 'nullable|string|max:100',
            'transfer_pin' => 'required|string|digits:6',
        ], [
            'transfer_pin.required' => 'PIN validasi transfer wajib diisi.',
            'transfer_pin.digits' => 'PIN harus terdiri dari 6 digit angka.',
        ]);

        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'icon' => 'error',
                'title' => 'Tidak Diizinkan',
                'text' => 'Hanya akun admin yang dapat melakukan transfer dana.',
            ], 403);
        }

        if (empty($user->transfer_pin) || ! Hash::check($validated['transfer_pin'], $user->transfer_pin)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'PIN Salah',
                'text' => 'PIN validasi transfer tidak sesuai. Hubungi admin untuk mengatur PIN Anda.',
            ], 422);
        }

        $rekening = RekeningClient::findOrFail($validated['rekening_client_id']);
        $amount = (int) round($validated['amount']);

        // Cegah double-submit: tolak jika sudah ada transfer dengan tujuan & nominal yang sama
        // dalam 2 menit terakhir yang masih belum final (PENDING/ACCEPTED/PROCESSING).
        $duplicate = Transfer::where('rekening_client_id', $rekening->id)
            ->where('amount', $amount)
            ->where('created_by', $user->id)
            ->whereIn('status', ['PENDING', 'ACCEPTED', 'PROCESSING'])
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($duplicate) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Transfer Berulang',
                'text' => 'Transfer dengan tujuan dan nominal yang sama masih diproses. Tunggu beberapa saat sebelum mencoba lagi.',
            ], 422);
        }

        // Serialisasi pembuatan payout agar cek saldo + pengiriman bersifat atomik
        // (mencegah dua request paralel sama-sama lolos cek saldo / overdraw).
        $lock = Cache::lock('xendit_payout', 30);

        if (! $lock->get()) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Transfer Sedang Diproses',
                'text' => 'Masih ada transfer lain yang sedang diproses. Tunggu beberapa saat lalu coba lagi.',
            ], 429);
        }

        try {
            // Cek saldo sebelum transfer.
            $saldo = $this->payoutService->getBalance();
            if ($saldo['available'] < $amount) {
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Saldo Xendit tidak mencukupi. Saldo tersedia: Rp '.number_format($saldo['available'], 0, ',', '.'),
                ], 422);
            }

            $kodeTransfer = 'TRF-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));

            $channelType = $rekening->channel_type ?? 'BANK';
            $isEwallet = $channelType === 'EWALLET';

            $transfer = Transfer::create([
                'kode_transfer' => $kodeTransfer,
                'rekening_client_id' => $rekening->id,
                'nama_client' => $rekening->nama_client,
                'bank_code' => $rekening->bank_code,
                'bank_name' => $rekening->bank_name,
                'account_number' => $rekening->account_number,
                'account_holder_name' => $rekening->account_holder_name,
                'routing_type' => $isEwallet ? 'WALLET' : ($rekening->routing_type ?? 'SWIFT'),
                'routing_value' => $isEwallet ? 'ID_'.$rekening->bank_code : $rekening->routing_value,
                'recipient_type' => $rekening->recipient_type ?? 'INDIVIDUAL',
                'relationship' => $rekening->relationship ?? 'CUSTOMER',
                'amount' => $amount,
                'description' => $validated['description'] ?? null,
                'status' => 'PENDING',
                'source_of_fund' => 'BUSINESS_REVENUE',
                'purpose_code' => 'OTHER',
                'created_by' => Auth::id(),
            ]);

            $result = $this->payoutService->createPayout([
                'reference_id' => $kodeTransfer,
                'bank_code' => $rekening->bank_code,
                'account_holder_name' => $rekening->account_holder_name,
                'account_number' => $rekening->account_number,
                'amount' => $amount,
                'description' => $validated['description'] ?? null,
                'recipient_type' => $rekening->recipient_type ?? 'INDIVIDUAL',
                'relationship' => $rekening->relationship ?? 'CUSTOMER',
                'channel_type' => $channelType,
                'city' => $rekening->city,
                'street_line_1' => $rekening->street_line_1,
            ]);

            $transfer->update([
                'xendit_payout_id' => $result['xendit_payout_id'],
                'status' => $result['status'],
                'failure_message' => $result['error'],
            ]);

            if ($result['status'] === 'FAILED') {
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Transfer Gagal',
                    'text' => $result['error'] ?? 'Terjadi kesalahan pada proses transfer.',
                ], 422);
            }

            return response()->json([
                'icon' => 'success',
                'title' => 'Sukses',
                'text' => 'Transfer '.$kodeTransfer.' berhasil dikirim ke Xendit. Status: '.$result['status'],
            ]);
        } finally {
            $lock->release();
        }
    }

    public function verifyPin(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|digits:6',
        ], [
            'pin.required' => 'PIN wajib diisi.',
            'pin.digits' => 'PIN harus terdiri dari 6 digit angka.',
        ]);

        $user = $request->user();

        if (empty($user->transfer_pin)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'PIN Belum Diatur',
                'text' => 'PIN validasi belum diatur untuk akun Anda. Hubungi admin untuk mengatur PIN.',
            ], 422);
        }

        if (! Hash::check($validated['pin'], $user->transfer_pin)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'PIN Salah',
                'text' => 'PIN yang Anda masukkan tidak sesuai.',
            ], 422);
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'PIN valid.',
        ]);
    }

    public function saldo()
    {
        $saldo = $this->payoutService->getBalance();

        return response()->json([
            'icon' => 'success',
            'available' => $saldo['available'],
            'saldo' => 'Rp '.number_format($saldo['available'], 0, ',', '.'),
        ]);
    }
}
