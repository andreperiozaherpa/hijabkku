<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\XenditPayoutService;
use App\Services\XenditWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    protected $webhookService;

    protected XenditPayoutService $payoutService;

    public function __construct(XenditWebhookService $webhookService, XenditPayoutService $payoutService)
    {
        $this->webhookService = $webhookService;
        $this->payoutService = $payoutService;
    }

    /**
     * Handle incoming Xendit webhook for Payout events.
     */
    public function handlePayout(Request $request)
    {
        $payload = $request->all();

        if (! $this->isValidCallbackToken($request)) {
            Log::warning('Invalid Xendit Payout Webhook Token');

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $processed = $this->payoutService->handleWebhook($payload);

            if ($processed) {
                return response()->json(['message' => 'Success']);
            }

            return response()->json(['message' => 'Event ignored or not processed']);
        } catch (\Exception $e) {
            Log::error('Error handling Xendit Payout webhook', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle incoming Xendit webhook for Invoice Payment events.
     */
    public function handleInvoicePayment(Request $request)
    {
        $payload = $request->all();

        if (! $this->isValidCallbackToken($request)) {
            Log::warning('Invalid Xendit Webhook Token');

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Must have an external ID representing the invoice
        if (! isset($payload['external_id'])) {
            return response()->json(['message' => 'Ignored, no external_id found']);
        }

        try {
            $processed = false;

            if (isset($payload['status'])) {
                if ($payload['status'] === 'PAID' || $payload['status'] === 'SETTLED') {
                    $processed = $this->webhookService->handleInvoicePaid($payload);
                } elseif ($payload['status'] === 'EXPIRED') {
                    $processed = $this->webhookService->handleInvoiceExpired($payload);
                }
            }

            if ($processed) {
                return response()->json(['message' => 'Success']);
            }

            return response()->json(['message' => 'Event ignored or not processed']);
        } catch (\Exception $e) {
            Log::error('Error handling Xendit webhook', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Validasi token callback Xendit menggunakan perbandingan timing-safe.
     * Token wajib terkonfigurasi; tanpa token webhook ditolak.
     */
    protected function isValidCallbackToken(Request $request): bool
    {
        $configuredToken = config('services.xendit.webhook_token');
        $sentToken = $request->header('x-callback-token');

        if (empty($configuredToken) || empty($sentToken)) {
            return false;
        }

        return hash_equals($configuredToken, $sentToken);
    }
}
