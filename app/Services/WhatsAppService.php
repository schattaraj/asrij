<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendTemplateMessage(string $phone, string $template, array $parameters = [])
    {
        $response = Http::withToken(config('services.whatsapp.token'))
            ->post(config('services.whatsapp.url') . '/' . config('services.whatsapp.phone_id') . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'template',
                'template' => [
                    'name' => $template,
                    'language' => [
                        'code' => 'en_US'
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => array_map(fn ($text) => [
                                'type' => 'text',
                                'text' => $text
                            ], $parameters)
                        ]
                    ]
                ]
            ]);

        return $response->json();
    }
}
