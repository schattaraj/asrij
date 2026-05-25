<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Firebase Cloud Messaging — HTTP v1 sender.
 *
 * Sends a single notification to one or many device tokens by looping
 * messages:send (FCM v1 has no multicast endpoint).
 *
 * Setup:
 *   - Place service-account JSON in storage/app/firebase/credentials.json
 *   - .env: FIREBASE_PROJECT_ID=your-project-id
 *           FIREBASE_CREDENTIALS=/absolute/path/to/credentials.json   (optional)
 */
class FcmService
{
    private const OAUTH_SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';
    private const TOKEN_URL   = 'https://oauth2.googleapis.com/token';
    private const FCM_BASE    = 'https://fcm.googleapis.com/v1/projects';
    private const CACHE_KEY   = 'fcm_v1_access_token';

    /**
     * Send a notification to many device tokens.
     *
     * @param  array<int,string> $tokens
     * @param  string $title
     * @param  string $body
     * @param  array  $data   custom data payload (string values recommended)
     * @return array{success:int, failure:int, invalid_tokens:array<int,string>}
     */
    public function sendToMany(array $tokens, string $title, string $body, array $data = []): array
    {
        $tokens = array_values(array_unique(array_filter($tokens)));

        $result = ['success' => 0, 'failure' => 0, 'invalid_tokens' => []];

        if (empty($tokens)) {
            return $result;
        }

        $projectId   = config('services.fcm.project_id');
        $accessToken = $this->getAccessToken();

        if (!$projectId || !$accessToken) {
            Log::warning('FCM not configured: missing project_id or access token.');
            $result['failure'] = count($tokens);
            return $result;
        }

        $endpoint = self::FCM_BASE . "/{$projectId}/messages:send";

        foreach ($tokens as $token) {
            try {
                $payload = $this->buildMessage($token, $title, $body, $data);

                $res = Http::withToken($accessToken)
                    ->acceptJson()
                    ->timeout(8)
                    ->post($endpoint, $payload);

                if ($res->successful()) {
                    $result['success']++;
                } else {
                    $result['failure']++;
                    $errorCode = data_get($res->json(), 'error.details.0.errorCode')
                              ?? data_get($res->json(), 'error.status');

                    if (in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT', 'NOT_FOUND'], true)) {
                        $result['invalid_tokens'][] = $token;
                    }

                    Log::warning('FCM send failed', [
                        'token_short' => substr($token, 0, 12) . '...',
                        'status'      => $res->status(),
                        'body'        => $res->json(),
                    ]);
                }
            } catch (\Throwable $e) {
                $result['failure']++;
                Log::warning('FCM exception: ' . $e->getMessage());
            }
        }

        return $result;
    }

    /**
     * Send to a single device token.
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        return $this->sendToMany([$token], $title, $body, $data);
    }

    /**
     * Build a v1 message payload.
     */
    private function buildMessage(string $token, string $title, string $body, array $data): array
    {
        // FCM v1 requires data values to be strings.
        $stringData = array_map(static fn ($v) => is_scalar($v) ? (string) $v : json_encode($v), $data);

        return [
            'message' => [
                'token'        => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data'    => $stringData,
                'android' => [
                    'priority'     => 'HIGH',
                    'notification' => [
                        'channel_id' => 'asrij_default',
                        'sound'      => 'default',
                    ],
                ],
                'apns' => [
                    'headers' => ['apns-priority' => '10'],
                    'payload' => ['aps' => ['sound' => 'default', 'badge' => 1]],
                ],
            ],
        ];
    }

    /**
     * Get a cached OAuth2 access token (auto-refresh).
     */
    private function getAccessToken(): ?string
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(50), function () {
            $sa = $this->loadServiceAccount();
            if (!$sa) {
                return null;
            }

            $now      = time();
            $jwtHead  = $this->b64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $jwtClaim = $this->b64url(json_encode([
                'iss'   => $sa['client_email'],
                'scope' => self::OAUTH_SCOPE,
                'aud'   => self::TOKEN_URL,
                'iat'   => $now,
                'exp'   => $now + 3600,
            ]));

            $signingInput = "$jwtHead.$jwtClaim";
            $signature    = '';

            $ok = openssl_sign($signingInput, $signature, $sa['private_key'], OPENSSL_ALGO_SHA256);
            if (!$ok) {
                Log::error('FCM: failed to sign JWT');
                return null;
            }

            $jwt = $signingInput . '.' . $this->b64url($signature);

            $res = Http::asForm()->timeout(8)->post(self::TOKEN_URL, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if (!$res->successful()) {
                Log::error('FCM token exchange failed', ['body' => $res->body()]);
                return null;
            }

            return $res->json('access_token');
        });
    }

    private function loadServiceAccount(): ?array
    {
        $path = config('services.fcm.credentials');
        if (!$path || !is_readable($path)) {
            Log::warning('FCM service-account file not readable', ['path' => $path]);
            return null;
        }

        $raw = json_decode(file_get_contents($path), true);
        if (!is_array($raw) || empty($raw['client_email']) || empty($raw['private_key'])) {
            Log::warning('FCM service-account JSON malformed', ['path' => $path]);
            return null;
        }

        return $raw;
    }

    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
