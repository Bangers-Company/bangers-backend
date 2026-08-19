<?php

namespace App\Services;

use App\Models\UserDeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FcmService
{
    protected string $projectId;
    protected string $clientEmail;
    protected string $privateKey;

    public function __construct()
    {
        $this->projectId = (string) config('firebase.project_id', '');
        $this->clientEmail = (string) config('firebase.client_email', '');
        $this->privateKey = (string) config('firebase.private_key', '');
    }

    /**
     * Send an FCM notification payload to a specific token.
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        // Support Expo Push Tokens in dev/Expo environments
        if (str_starts_with($token, 'ExponentPushToken') || str_starts_with($token, 'ExpoPushToken')) {
            return $this->sendToExpo($token, $title, $body, $data);
        }

        if (empty($this->projectId) || empty($this->clientEmail) || empty($this->privateKey)) {
            Log::warning('FCM Push Skipped: Firebase credentials not set in .env');
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('FCM Push Failed: Unable to generate Google OAuth2 Access Token');
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        // Convert data values to strings as required by FCM REST API
        $formattedData = [];
        foreach ($data as $key => $value) {
            $formattedData[(string)$key] = (string)$value;
        }

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $formattedData,
                'android' => [
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'default',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        if ($response->successful()) {
            return true;
        }

        $statusCode = $response->status();
        $responseBody = $response->json();

        Log::error("FCM Send Failed [{$statusCode}]:", $responseBody ?? [$response->body()]);

        // Prune unregistered/expired tokens automatically
        $errorCode = $responseBody['error']['details'][0]['errorCode'] ?? null;
        $status = $responseBody['error']['status'] ?? null;

        if ($errorCode === 'UNREGISTERED' || $status === 'NOT_FOUND') {
            Log::info("Pruning invalid FCM device token: {$token}");
            UserDeviceToken::where('token', $token)->delete();
        }

        return false;
    }

    /**
     * Send push notification via Expo Push API for ExpoPushToken tokens.
     */
    protected function sendToExpo(string $token, string $title, string $body, array $data = []): bool
    {
        $payload = [
            'to' => $token,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'sound' => 'default',
            'channelId' => 'default',
            'priority' => 'high',
        ];

        $response = Http::post('https://exp.host/--/api/v2/push/send', $payload);

        if ($response->successful()) {
            Log::info("Expo Push Sent Successfully to {$token}");
            return true;
        }

        Log::error("Expo Push Send Failed [{$response->status()}]:", $response->json() ?? [$response->body()]);
        return false;
    }

    /**
     * Send FCM notification payload to multiple tokens.
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): int
    {
        $successCount = 0;
        foreach (array_unique($tokens) as $token) {
            if ($this->sendToToken($token, $title, $body, $data)) {
                $successCount++;
            }
        }
        return $successCount;
    }

    /**
     * Generate Google OAuth2 Access Token from Service Account (.env credentials).
     */
    protected function getAccessToken(): ?string
    {
        return Cache::remember('firebase_fcm_access_token', 3300, function () {
            $now = time();
            $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            
            $claimSet = base64_encode(json_encode([
                'iss' => $this->clientEmail,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ]));

            $signatureInput = $header . '.' . $claimSet;
            
            $binarySignature = '';
            $success = openssl_sign($signatureInput, $binarySignature, $this->privateKey, 'SHA256');

            if (!$success) {
                Log::error('FCM Error: Failed to sign OAuth2 JWT token with provided private key.');
                return null;
            }

            $jwt = $signatureInput . '.' . base64_encode($binarySignature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('FCM OAuth2 Token Request Failed:', $response->json() ?? [$response->body()]);
            return null;
        });
    }
}
