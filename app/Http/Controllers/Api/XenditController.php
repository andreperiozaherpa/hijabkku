<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingTransaction;
use App\Models\StockToko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class XenditController extends Controller
{
    /**
     * Create a Xendit Invoice for POS payment checkout.
     */
    public function createInvoice(Request $request)
    {
        $invoice = $request->invoice;
        $total_harga = $request->total_harga;
        $method = $request->pembayaran;
        $user = Auth::user();
        $data = $request->data; // cart array

        if (!$data || !is_array($data) || count($data) === 0) {
            return response()->json(['icon' => 'error', 'cek_data' => 'Keranjang kosong!']);
        }

        // Verify stock available before creating invoice to prevent paying for out of stock items
        $productCodes = array_column($data, 'nomor_paket');
        $stocks = StockToko::where('kode_toko', $user->kode_toko)
            ->whereIn('kode_barang', $productCodes)
            ->get()
            ->keyBy('kode_barang');

        foreach ($data as $d) {
            $stock = $stocks->get($d['nomor_paket']);
            if (!$stock) {
                return response()->json(['icon' => 'error', 'cek_data' => 'Barang ' . $d['nama_barang'] . ' tidak terdaftar di toko ini!']);
            }
            $available = $stock->jumlah - $stock->terjual;
            if ($available < intval($d['jumlah_barang'])) {
                return response()->json(['icon' => 'error', 'cek_data' => 'Stok barang ' . $d['nama_barang'] . ' tidak mencukupi!']);
            }
        }

        $grand_total = $total_harga;
        $fee = 0;
        $payment_methods = [];

        if ($method === 'QRIS') {
            $qrisRate = 0.007; // 0.7% flat
            $grand_total = ceil($total_harga / (1 - $qrisRate));
            $fee = $grand_total - $total_harga;
            $payment_methods = ["QRIS"];
        } elseif ($method === 'VA') {
            $vaFeeFlat = 4500;
            $ppnRate = 0.12; // 12% PPN
            $fee = $vaFeeFlat + ($vaFeeFlat * $ppnRate);
            $grand_total = $total_harga + $fee;
            $payment_methods = ["BCA", "BNI", "BSI", "BRI", "MANDIRI", "PERMATA"];
        } elseif ($method === 'EWALLET') {
            $ewalletRate = 0.015; // 1.5%
            $ppnRateOfFee = 0.11; // 11% PPN on top of fee
            $effectiveRate = $ewalletRate * (1 + $ppnRateOfFee); // 1.665%
            $grand_total = ceil($total_harga / (1 - $effectiveRate));
            $fee = $grand_total - $total_harga;
            $payment_methods = ["OVO", "DANA", "LINKAJA", "SHOPEEPAY"];
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid payment method']);
        }

        $pending = PendingTransaction::where('kode_invoice', $invoice)->first();
        if ($pending) {
            return response()->json([
                'success' => true,
                'checkout_url' => $pending->checkout_url,
                'invoice' => $invoice,
                'grand_total' => $grand_total,
                'fee' => $fee,
                'payment_method' => $method
            ]);
        }

        $secret_key = env('XENDIT_SECRET_KEY');
        if (!$secret_key) {
            return response()->json(['success' => false, 'message' => 'Xendit Secret Key is not configured.']);
        }

        $response = Http::withBasicAuth($secret_key, '')
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $invoice,
                'amount' => $grand_total,
                'payer_email' => 'customer@hijabkku.com',
                'description' => 'Pembayaran Tagihan ' . $invoice,
                'payment_methods' => $payment_methods,
                'currency' => 'IDR',
                'success_redirect_url' => url('/transaksi/penjualan?payment_status=success&invoice=' . $invoice),
                'failure_redirect_url' => url('/transaksi/penjualan?payment_status=failure&invoice=' . $invoice),
            ]);

        if ($response->successful()) {
            $xenditData = $response->json();
            
            PendingTransaction::create([
                'kode_invoice' => $invoice,
                'kode_toko' => $user->kode_toko,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'total_harga' => $total_harga,
                'fee' => $fee,
                'grand_total' => $grand_total,
                'payment_method' => $method,
                'xendit_id' => $xenditData['id'],
                'checkout_url' => $xenditData['invoice_url'],
                'cart_payload' => json_encode($data),
                'status' => 'PENDING'
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $xenditData['invoice_url'],
                'invoice' => $invoice,
                'grand_total' => $grand_total,
                'fee' => $fee,
                'payment_method' => $method
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Gagal membuat tagihan Xendit. ' . $response->body()
        ]);
    }
}
