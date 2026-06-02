<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    /**
     * Trigger a real-time update event on a Firebase Realtime Database path.
     *
     * @param string $path
     * @param array $data
     * @return bool
     */
    public static function triggerUpdate(string $path, array $data = []): bool
    {
        $dbUrl = env('FIREBASE_DATABASE_URL');
        $secret = env('FIREBASE_DATABASE_SECRET');

        if (empty($dbUrl)) {
            Log::warning('Firebase DB URL is not set in .env');
            return false;
        }

        // Clean path and build REST URL
        $path = ltrim($path, '/');
        $url = "{$dbUrl}/hijabkku/{$path}.json";

        if ($secret) {
            $url .= "?auth={$secret}";
        }

        try {
            $payload = array_merge([
                'timestamp' => now()->timestamp,
            ], $data);

            $response = Http::timeout(3)->put($url, $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('Firebase REST API update failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Firebase REST API exception: ' . $e->getMessage());
        }

        return false;
    }
}
