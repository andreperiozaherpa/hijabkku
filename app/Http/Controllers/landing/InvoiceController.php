<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PendingTransaction;
use App\Models\PesananPickup;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Services\XenditWebhookService;

class InvoiceController extends Controller
{
    public function show(string $kode)
    {
        $pending = PendingTransaction::where('kode_invoice', $kode)->first();

        // Case 1: PendingTransaction exists and is PENDING → process it (local fallback)
        if ($pending && $pending->status === 'PENDING' && in_array(config('app.env'), ['local', 'testing'])) {
            $webhookService = app(XenditWebhookService::class);
            try {
                $webhookService->handleInvoicePaid([
                    'external_id' => $kode,
                    'status' => 'PAID',
                ]);
                $pending->refresh();
            } catch (\Exception $e) {
                session()->flash('payment_error', 'Gagal memproses pembayaran: '.$e->getMessage());

                return redirect()->route('catalog');
            }
        }

        // Load transaction data (works for both cases: with or without PendingTransaction)
        $pembayaran = Pembayaran::where('kode_invoice', $kode)->first();

        if (! $pembayaran) {
            abort(404, 'Invoice tidak ditemukan.');
        }

        $transaksis = Transaksi::where('kode_invoice', $kode)->get();
        $firstTransaksi = $transaksis->first();
        $toko = $firstTransaksi ? Toko::where('kode', $firstTransaksi->kode_toko)->first() : null;
        $pesananPickup = PesananPickup::where('kode_invoice', $kode)->first();

        // Payment info from PendingTransaction, fallback to Pembayaran data
        $paymentMethod = $pending->payment_method ?? 'QRIS';
        $fee = $pending->fee ?? max(0, $pembayaran->pembayaran - $pembayaran->total_harga);

        $paymentMethodLabel = match ($paymentMethod) {
            'QRIS' => 'QRIS (0.7%)',
            'VA' => 'Virtual Account (Fee Rp 5.040)',
            'EWALLET' => 'E-Wallet (1.665%)',
            default => $paymentMethod,
        };

        return view('landing.invoice', [
            'pembayaran' => $pembayaran,
            'transaksis' => $transaksis,
            'toko' => $toko,
            'pesananPickup' => $pesananPickup,
            'paymentMethod' => $paymentMethod,
            'paymentMethodLabel' => $paymentMethodLabel,
            'fee' => $fee,
        ]);
    }
}
