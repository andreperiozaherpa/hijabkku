<?php

namespace App\Services;

use App\Models\PendingTransaction;
use App\Models\Pembayaran;
use App\Models\DataBarang;
use App\Models\StockToko;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class XenditWebhookService
{
    /**
     * Handle the paid invoice callback event.
     *
     * @param array $payload
     * @return bool
     */
    public function handleInvoicePaid(array $payload): bool
    {
        $invoiceCode = $payload['external_id'];
        
        $pending = PendingTransaction::where('kode_invoice', $invoiceCode)->first();
        if (!$pending) {
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

            $cart = json_decode($pending->cart_payload, true);
            $this->processSale($pending, $cart);

            DB::commit();

            FirebaseService::triggerUpdate('updates/payment_success_' . $invoiceCode, [
                'status' => 'PAID',
                'timestamp' => time()
            ]);

            FirebaseService::triggerUpdate('updates/sales', ['toko' => $pending->kode_toko, 'timestamp' => time()]);
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle the expired invoice callback event.
     *
     * @param array $payload
     * @return bool
     */
    public function handleInvoiceExpired(array $payload): bool
    {
        $invoiceCode = $payload['external_id'];
        
        $pending = PendingTransaction::where('kode_invoice', $invoiceCode)->first();
        if (!$pending) {
            Log::warning("Xendit Webhook: Pending transaction not found for expired invoice {$invoiceCode}");
            return false;
        }

        if ($pending->status === 'EXPIRED' || $pending->status === 'PAID') {
            return true;
        }

        $pending->status = 'EXPIRED';
        $pending->save();

        FirebaseService::triggerUpdate('updates/payment_success_' . $invoiceCode, [
            'status' => 'EXPIRED',
            'timestamp' => time()
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

        foreach ($cart as $d) {
            $dataBarang = $dataBarangs->get($d['nomor_paket']);
            $hargaBeliMentah = $dataBarang ? $dataBarang->harga_beli : 0;
            $stock = $stocks->get($d['nomor_paket']);
            
            if ($stock) {
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
                'harga_beli' => str_replace(".", "", $hargaBeliMentah),
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
        ]);
    }
}
