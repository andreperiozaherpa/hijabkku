<?php

namespace App\Services;

use App\Models\Transfer;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditPayoutService
{
    /**
     * Base URL API Xendit.
     */
    protected string $baseUrl = 'https://api.xendit.co';

    /**
     * Versi API Payout v3.
     */
    protected string $apiVersion = '2025-09-01';

    protected function secretKey(): ?string
    {
        return config('services.xendit.secret_key') ?: env('XENDIT_SECRET_KEY');
    }

    protected function http(): PendingRequest
    {
        return Http::withBasicAuth($this->secretKey(), '')
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'api-version' => $this->apiVersion,
            ]);
    }

    /**
     * Ambil saldo cash (IDR) di Xendit.
     *
     * @return array{available: int, balance: int}
     */
    public function getBalance(): array
    {
        try {
            $response = $this->http()->get('/balance', [
                'account_type' => 'CASH',
                'currency' => 'IDR',
            ]);
        } catch (ConnectionException $e) {
            Log::error('Xendit Payout: gagal terhubung saat mengambil saldo', ['error' => $e->getMessage()]);

            return ['available' => 0, 'balance' => 0];
        }

        if ($response->failed()) {
            Log::error('Xendit Payout: gagal mengambil saldo', ['body' => $response->body()]);

            return ['available' => 0, 'balance' => 0];
        }

        $balance = (int) ($response->json('balance') ?? 0);

        return ['available' => $balance, 'balance' => $balance];
    }

    /**
     * Peta kode bank (BCA, MANDIRI, dst.) ke SWIFT/BIC code untuk payout domestik IDR.
     *
     * @return array<string, string>
     */
    protected function bankBicCodes(): array
    {
        return [
            'BCA' => 'CENAIDJA',
            'BNI' => 'BNINIDJA',
            'BRI' => 'BRINIDJA',
            'MANDIRI' => 'BMRIIDJA',
            'PERMATA' => 'BBBAIDJA',
            'BSI' => 'BSMDIDJA',
            'CIMB' => 'BNIAIDJA',
            'DANAMON' => 'BDINIDJA',
            'MAYBANK' => 'IBBKIDJA',
            'OCBC' => 'NISPIDJA',
            'UOB' => 'BBIJIDJA',
            'PANIN' => 'PINBIDJA',
            'BTN' => 'BTANIDJA',
        ];
    }

    /**
     * Peta kode e-wallet (DANA, GOPAY, dst.) ke routing value channel Xendit.
     *
     * @return array<string, string>
     */
    protected function ewalletRoutingCodes(): array
    {
        return [
            'DANA' => 'ID_DANA',
            'GOPAY' => 'ID_GOPAY',
            'OVO' => 'ID_OVO',
            'LINKAJA' => 'ID_LINKAJA',
            'SHOPEEPAY' => 'ID_SHOPEEPAY',
        ];
    }

    /**
     * Buat payout / transfer ke rekening atau e-wallet client.
     *
     * @param  array  $data  harus berisi: reference_id, bank_code, account_holder_name,
     *                       account_number, amount, description,
     *                       recipient_type, relationship, city, street_line_1,
     *                       channel_type (BANK / EWALLET)
     * @return array{xendit_payout_id: string|null, status: string|null, error: string|null}
     */
    public function createPayout(array $data): array
    {
        $recipientType = $data['recipient_type'] ?? 'INDIVIDUAL';
        $channelType = strtoupper((string) ($data['channel_type'] ?? 'BANK'));
        $bankCode = strtoupper((string) ($data['bank_code'] ?? ''));
        $bicCodes = $this->bankBicCodes();
        $ewalletCodes = $this->ewalletRoutingCodes();

        $isEwallet = $channelType === 'EWALLET';
        $accountDetails = [
            'currency' => 'IDR',
            'account_country' => 'ID',
            'account_holder_name' => $data['account_holder_name'],
            'account_number' => $data['account_number'],
        ];

        if ($isEwallet) {
            $accountDetails['routing_type_1'] = 'WALLET';
            $accountDetails['routing_value_1'] = $ewalletCodes[$bankCode] ?? $bankCode;
        } else {
            $accountDetails['routing_type_1'] = 'SWIFT';
            $accountDetails['routing_value_1'] = $bicCodes[$bankCode] ?? $bankCode;
        }

        $recipient = [
            'type' => $recipientType,
            'relationship' => $data['relationship'] ?? 'CUSTOMER',
            'account_details' => $accountDetails,
            'address' => [
                'country' => 'ID',
                'city' => $data['city'] ?? 'Jakarta',
                'street_line_1' => $data['street_line_1'] ?? 'Jl. Merdeka No. 1',
            ],
        ];

        if ($isEwallet) {
            $recipient['details'] = [
                'personal_mobile_number' => $data['account_number'],
            ];
        }

        if ($recipientType === 'BUSINESS') {
            $recipient['business_name'] = $data['account_holder_name'];
        } else {
            [$givenName, $surname] = $this->splitFullName($data['account_holder_name']);
            $recipient['given_name'] = $givenName;
            $recipient['surname'] = $surname;
        }

        $payload = [
            'reference_id' => $data['reference_id'],
            'recipient' => $recipient,
            'payout_details' => [
                'source_currency' => 'IDR',
                'destination_currency' => 'IDR',
                'destination_amount' => (int) $data['amount'],
            ],
            'source_of_fund' => $data['source_of_fund'] ?? 'BUSINESS_REVENUE',
            'purpose_code' => $data['purpose_code'] ?? 'OTHER',
            'description' => substr((string) ($data['description'] ?? 'Transfer Dana Hijabkku'), 0, 100),
        ];

        try {
            $response = $this->http()
                ->withHeaders(['idempotency-key' => $data['reference_id']])
                ->post('/v3/payouts', $payload);
        } catch (ConnectionException $e) {
            Log::error('Xendit Payout: koneksi gagal saat membuat payout', [
                'reference_id' => $data['reference_id'],
                'error' => $e->getMessage(),
            ]);

            return [
                'xendit_payout_id' => null,
                'status' => 'FAILED',
                'error' => 'Gagal terhubung ke Xendit. Periksa koneksi dan coba lagi.',
            ];
        }

        if ($response->failed()) {
            $body = $response->json();
            $error = $body['message'] ?? $response->body();

            Log::error('Xendit Payout: gagal membuat payout', [
                'reference_id' => $data['reference_id'],
                'body' => $body,
            ]);

            return [
                'xendit_payout_id' => null,
                'status' => 'FAILED',
                'error' => $error,
            ];
        }

        return [
            'xendit_payout_id' => $response->json('payout_id'),
            'status' => $response->json('status') ?? 'ACCEPTED',
            'error' => null,
        ];
    }

    /**
     * Tangani webhook payout dari Xendit.
     *
     * @param  array  $payload  payload webhook
     */
    public function handleWebhook(array $payload): bool
    {
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        $referenceId = $data['reference_id'] ?? null;
        if (! $referenceId) {
            Log::warning('Xendit Payout: webhook tanpa reference_id');

            return false;
        }

        $transfer = Transfer::where('kode_transfer', $referenceId)->first();
        if (! $transfer) {
            Log::warning('Xendit Payout: transfer tidak ditemukan untuk webhook', ['reference_id' => $referenceId]);

            return false;
        }

        $statusMap = [
            'v3_payout.succeeded' => 'SUCCEEDED',
            'v3_payout.failed' => 'FAILED',
            'v3_payout.reversed' => 'REVERSED',
            'v3_payout.rejected' => 'REJECTED',
            'v3_payout.pending_compliance' => 'PROCESSING',
        ];

        $newStatus = $statusMap[$event] ?? null;
        if (! $newStatus) {
            Log::info('Xendit Payout: event webhook tidak dikenali', ['event' => $event]);

            return false;
        }

        // Idempotensi: jangan ubah status final yang sudah ada.
        if (in_array($transfer->status, ['SUCCEEDED', 'REVERSED', 'REJECTED', 'FAILED']) &&
            in_array($newStatus, ['SUCCEEDED', 'REVERSED', 'REJECTED', 'FAILED']) &&
            $transfer->status !== $newStatus
        ) {
            Log::warning('Xendit Payout: coba timpa status final transfer', [
                'kode_transfer' => $referenceId,
                'dari' => $transfer->status,
                'ke' => $newStatus,
            ]);

            return false;
        }

        $transfer->status = $newStatus;
        $transfer->xendit_payout_id = $data['payout_id'] ?? $transfer->xendit_payout_id;

        if ($newStatus === 'FAILED' || $newStatus === 'REJECTED') {
            $transfer->failure_code = $data['failure_code'] ?? null;
            $transfer->failure_message = $data['description'] ?? null;
        }

        $transfer->save();

        Log::info('Xendit Payout: status transfer diperbarui', [
            'kode_transfer' => $referenceId,
            'status' => $newStatus,
        ]);

        return true;
    }

    /**
     * Ambil detail payout dari Xendit berdasarkan id.
     *
     * @return array<string, mixed>
     */
    public function getPayout(string $payoutId): array
    {
        $response = $this->http()->get('/v3/payouts/'.$payoutId);

        if ($response->failed()) {
            Log::error('Xendit Payout: gagal mengambil detail payout', ['body' => $response->body()]);

            return [];
        }

        return $response->json() ?? [];
    }

    /**
     * Pecah nama lengkap menjadi given name dan surname.
     *
     * @return array{0: string, 1: string}
     */
    protected function splitFullName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName));

        if (! $parts || count($parts) === 0) {
            return [$fullName, ''];
        }

        $givenName = array_shift($parts);

        return [$givenName, implode(' ', $parts)];
    }
}
