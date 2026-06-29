<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PendingTransaction;
use App\Models\StockToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LandingCheckoutController extends Controller
{
    /**
     * Create a Xendit Invoice for Landing Page checkout.
     */
    public function createInvoice(Request $request)
    {
        $rules = [
            'kode_toko' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => ['nullable', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'max:255'],
            'payment_method' => 'required|string|in:QRIS,VA,EWALLET',
            'cart' => 'required|array|min:1',
            'cart.*.kode_barang' => 'required|string',
            'cart.*.jumlah' => 'required|integer|min:1',
        ];

        $recaptchaSecret = config('services.recaptcha.secret_key');
        if (! empty($recaptchaSecret)) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $request->validate($rules, [
            'customer_email.regex' => 'Format email tidak valid (Top-Level Domain harus minimal 2 karakter, contoh: .com, .co.id).',
            'customer_email.email' => 'Format email tidak valid.',
        ]);

        if (! empty($recaptchaSecret)) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip(),
            ]);

            if (! $verifyResponse->successful() || ! $verifyResponse->json('success')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi!',
                ], 422);
            }
        }

        $kode_toko = $request->kode_toko;
        $customer_name = $request->customer_name;
        $customer_phone = $request->customer_phone;
        $customer_email = $request->customer_email;
        $method = $request->payment_method;
        $cart = $request->cart;

        // Verify stock available before creating invoice
        $productCodes = array_column($cart, 'kode_barang');
        $stocks = StockToko::with('data_barang')
            ->where('kode_toko', $kode_toko)
            ->whereIn('kode_barang', $productCodes)
            ->get()
            ->keyBy('kode_barang');

        $total_harga = 0;
        $formattedCartData = [];

        foreach ($cart as $item) {
            $stock = $stocks->get($item['kode_barang']);
            if (! $stock || ! $stock->data_barang) {
                return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan di cabang toko ini!'], 422);
            }

            $available = $stock->jumlah - $stock->terjual;
            if ($available < intval($item['jumlah'])) {
                return response()->json(['success' => false, 'message' => 'Stok untuk barang '.$stock->data_barang->nama_barang.' tidak mencukupi!'], 422);
            }

            $harga_jual = intval(str_replace('.', '', $stock->data_barang->harga_jual));
            $total_item_harga = $harga_jual * intval($item['jumlah']);
            $total_harga += $total_item_harga;

            $formattedCartData[] = [
                'nomor_paket' => $stock->kode_barang,
                'nama_barang' => $stock->data_barang->nama_barang,
                'jumlah_barang' => $item['jumlah'],
                'harga_item' => $harga_jual,
                'harga_jual' => $total_item_harga,
                'method' => 'umum',
            ];
        }

        $grand_total = $total_harga;
        $fee = 0;
        $payment_methods = [];

        if ($method === 'QRIS') {
            $qrisRate = 0.007; // 0.7% flat
            $grand_total = ceil($total_harga / (1 - $qrisRate));
            $fee = $grand_total - $total_harga;
            $payment_methods = ['QRIS'];
        } elseif ($method === 'VA') {
            $vaFeeFlat = 4500;
            $ppnRate = 0.12; // 12% PPN
            $fee = $vaFeeFlat + ($vaFeeFlat * $ppnRate);
            $grand_total = $total_harga + $fee;
            $payment_methods = ['BCA', 'BNI', 'BSI', 'BRI', 'MANDIRI', 'PERMATA'];
        } elseif ($method === 'EWALLET') {
            $ewalletRate = 0.015; // 1.5%
            $ppnRateOfFee = 0.11; // 11% PPN on top of fee
            $effectiveRate = $ewalletRate * (1 + $ppnRateOfFee); // 1.665%
            $grand_total = ceil($total_harga / (1 - $effectiveRate));
            $fee = $grand_total - $total_harga;
            $payment_methods = ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY'];
        }

        $invoice = 'INV-OL-'.date('YmdHis').'-'.strtoupper(bin2hex(random_bytes(2)));

        $secret_key = env('XENDIT_SECRET_KEY');
        if (! $secret_key) {
            return response()->json(['success' => false, 'message' => 'Xendit Secret Key is not configured.'], 500);
        }

        $payload = [
            'external_id' => $invoice,
            'amount' => $grand_total,
            'description' => 'Pembayaran Pesanan Online '.$invoice,
            'payment_methods' => $payment_methods,
            'currency' => 'IDR',
            'success_redirect_url' => request()->schemeAndHttpHost().'/invoice/'.$invoice,
            'failure_redirect_url' => request()->schemeAndHttpHost().'/catalog?payment_status=failure&invoice='.$invoice,
        ];

        if ($customer_email) {
            $payload['payer_email'] = $customer_email;
        }

        $response = Http::withBasicAuth($secret_key, '')
            ->post('https://api.xendit.co/v2/invoices', $payload);

        if ($response->successful()) {
            $xenditData = $response->json();

            PendingTransaction::create([
                'kode_invoice' => $invoice,
                'kode_toko' => $kode_toko,
                'user_id' => 0,
                'user_name' => 'Guest ('.$customer_name.')',
                'total_harga' => $total_harga,
                'fee' => $fee,
                'grand_total' => $grand_total,
                'payment_method' => $method,
                'xendit_id' => $xenditData['id'],
                'checkout_url' => $xenditData['invoice_url'],
                'cart_payload' => json_encode([
                    'items' => $formattedCartData,
                    'customer' => [
                        'name' => $customer_name,
                        'email' => $customer_email,
                        'phone' => $customer_phone,
                    ],
                ]),
                'status' => 'PENDING',
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $xenditData['invoice_url'],
                'invoice' => $invoice,
                'grand_total' => $grand_total,
                'fee' => $fee,
                'payment_method' => $method,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat tagihan Xendit. '.$response->body(),
        ], 400);
    }
}
