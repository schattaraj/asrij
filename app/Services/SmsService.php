<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function sendOtpSms($mobile, $otp)
    {
    $devNumbers = [
        '8637378344',
        '1234567890',
        '1234567891',
        '1234567892',
        '1234567893',
        '1234567894',
        '1234567895',
    ];

    if (in_array($mobile, $devNumbers)) {
        return [
            'return' => true,
            'request_id' => 'DEV123456',
            'otp' => $otp,
            'message' => ['SMS sent successfully (mocked)']
        ];
    }

        $response = Http::withHeaders([
            'authorization' => env('SMS_API_KEY'),
            'accept' => 'application/json',
        ])->post('https://www.fast2sms.com/dev/bulkV2', [
            'route' => 'dlt',
            'variables_values' => $otp,
            'numbers' => $mobile,
            'sender_id' => 'ASRIJ',
            'message' => '214706' // Template ID
        ]);

        return $response->json();
    }
}