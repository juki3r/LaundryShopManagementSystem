<?php

namespace App\Services;

use Log;
use GuzzleHttp\Client;

class ExpoNotificationService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://exp.host/--/api/v2/push/send',
            'timeout'  => 10,
        ]);
    }

    /**
     * Send push notification to Expo token
     */
    public function send(string $expoToken, string $title, string $body, array $data = [])
    {
        if (!$expoToken) {
            return false;
        }

        $payload = [
            'to' => $expoToken,
            'sound' => 'default',
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ];

        try {
            $response = $this->client->post('', [
                'json' => $payload,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Expo notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
