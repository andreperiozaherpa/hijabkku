<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\XenditWebhookService;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    protected $webhookService;

    public function __construct(XenditWebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    /**
     * Handle incoming Xendit webhook for Invoice Payment events.
     */
    public function handleInvoicePayment(Request $request)
    {
        $payload = $request->all();
        
        $xenditToken = $request->header('x-callback-token');
        if (env('XENDIT_WEBHOOK_TOKEN') && $xenditToken !== env('XENDIT_WEBHOOK_TOKEN')) {
            Log::warning('Invalid Xendit Webhook Token');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Must have an external ID representing the invoice
        if (!isset($payload['external_id'])) {
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
            return response()->json(['message' => 'Internal server error', 'error' => $e->getMessage()], 500);
        }
    }
}
