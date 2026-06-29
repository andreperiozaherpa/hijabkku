<?php

namespace App\Services;

use App\Mail\OrderInvoiceMail;
use App\Models\DataBarang;
use App\Models\Pembayaran;
use App\Models\PendingTransaction;
use App\Models\PesananPickup;
use App\Models\StockToko;
use App\Models\SystemSetting;
use App\Models\Toko;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class XenditWebhookService
{
    /**
     * Handle the paid invoice callback event.
     */
    public function handleInvoicePaid(array $payload): bool
    {
        $invoiceCode = $payload['external_id'];

        $pending = PendingTransaction::where('kode_invoice', $invoiceCode)->first();
        if (! $pending) {
            Log::error("Xendit Webhook: Pending transaction not found for invoice {$invoiceCode}");

            return false;
        }

        if ($pending->status === 'PAID') {
            return true;
        }

        DB::beginTransaction();
        try {
            $pending->status = 'PAID';
            $pending->save();

            $payloadData = json_decode($pending->cart_payload, true);
            $cart = isset($payloadData['items']) ? $payloadData['items'] : $payloadData;
            $this->processSale($pending, $cart);

            DB::commit();

            FirebaseService::triggerUpdate('updates/payment_success_'.$invoiceCode, [
                'status' => 'PAID',
                'timestamp' => time(),
            ]);

            FirebaseService::triggerUpdate('updates/sales', ['toko' => $pending->kode_toko, 'timestamp' => time()]);

            // Send invoice email for online (landing page) orders only
            if (isset($payloadData['customer']) && ! empty($payloadData['customer']['email'])) {
                $this->sendInvoiceEmail($pending, $payloadData['customer']);
            }

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook processing failed: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle the expired invoice callback event.
     */
    public function handleInvoiceExpired(array $payload): bool
    {
        $invoiceCode = $payload['external_id'];

        $pending = PendingTransaction::where('kode_invoice', $invoiceCode)->first();
        if (! $pending) {
            Log::warning("Xendit Webhook: Pending transaction not found for expired invoice {$invoiceCode}");

            return false;
        }

        if ($pending->status === 'EXPIRED' || $pending->status === 'PAID') {
            return true;
        }

        $pending->status = 'EXPIRED';
        $pending->save();

        FirebaseService::triggerUpdate('updates/payment_success_'.$invoiceCode, [
            'status' => 'EXPIRED',
            'timestamp' => time(),
        ]);

        return true;
    }

    /**
     * Process sales details, update stock, and insert transactions & payments.
     */
    private function processSale(PendingTransaction $pending, array $cart)
    {
        $productCodes = array_column($cart, 'nomor_paket');
        $dataBarangs = DataBarang::whereIn('kode', $productCodes)->get()->keyBy('kode');
        $stocks = StockToko::where('kode_toko', $pending->kode_toko)
            ->whereIn('kode_barang', $productCodes)
            ->get()
            ->keyBy('kode_barang');

        $isSimulation = SystemSetting::getByKey('xendit_simulation_mode', 'false');

        foreach ($cart as $d) {
            $dataBarang = $dataBarangs->get($d['nomor_paket']);
            $hargaBeliMentah = $dataBarang ? $dataBarang->harga_beli : 0;
            $stock = $stocks->get($d['nomor_paket']);

            if ($stock && $isSimulation !== 'true') {
                $stock->terjual += $d['jumlah_barang'];
                $stock->save();
            }

            DB::table('transaksis')->insert([
                'kode_invoice' => $pending->kode_invoice,
                'kode_toko' => $pending->kode_toko,
                'kode_barang' => $d['nomor_paket'],
                'nama_barang' => $d['nama_barang'],
                'metode' => $d['method'],
                'jumlah' => $d['jumlah_barang'],
                'harga' => $d['harga_item'],
                'harga_beli' => str_replace('.', '', $hargaBeliMentah),
                'harga_total' => $d['harga_jual'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        Pembayaran::create([
            'kode_invoice' => $pending->kode_invoice,
            'user_id' => $pending->user_id,
            'user_name' => $pending->user_name,
            'total_harga' => $pending->total_harga,
            'pembayaran' => $pending->grand_total,
            'kembalian' => 0,
            'metode_pembayaran' => $pending->payment_method ?? 'TUNAI',
        ]);

        // If it's an online checkout (contains customer info), create a pickup order record
        $payloadData = json_decode($pending->cart_payload, true);
        if (isset($payloadData['customer'])) {
            $customer = $payloadData['customer'];
            DB::table('pesanan_pickups')->insert([
                'kode_invoice' => $pending->kode_invoice,
                'kode_toko' => $pending->kode_toko,
                'customer_name' => $customer['name'],
                'customer_email' => $customer['email'] ?? null,
                'customer_phone' => $customer['phone'],
                'status_pengambilan' => 'Belum Diambil',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Send invoice email to customer for online orders.
     */
    private function sendInvoiceEmail(PendingTransaction $pending, array $customer): void
    {
        try {
            $pembayaran = Pembayaran::where('kode_invoice', $pending->kode_invoice)->first();
            $transaksis = Transaksi::where('kode_invoice', $pending->kode_invoice)->get();
            $toko = Toko::where('kode', $pending->kode_toko)->first();
            $pesananPickup = PesananPickup::where('kode_invoice', $pending->kode_invoice)->first();

            if (! $pembayaran || ! $pesananPickup) {
                Log::warning("Invoice email skipped: data not found for invoice {$pending->kode_invoice}");

                return;
            }

            $paymentMethodLabel = match ($pending->payment_method) {
                'QRIS' => 'QRIS (0.7%)',
                'VA' => 'Virtual Account (Fee Rp 5.040)',
                'EWALLET' => 'E-Wallet (1.665%)',
                default => $pending->payment_method,
            };

            Mail::to($customer['email'])->send(
                new OrderInvoiceMail(
                    pembayaran: $pembayaran,
                    transaksis: $transaksis,
                    toko: $toko,
                    pesananPickup: $pesananPickup,
                    paymentMethodLabel: $paymentMethodLabel,
                    fee: $pending->fee,
                )
            );

            Log::info("Invoice email sent for {$pending->kode_invoice} to {$customer['email']}");
        } catch (\Exception $e) {
            Log::error("Failed to send invoice email for {$pending->kode_invoice}: ".$e->getMessage());
        }
    }
}
