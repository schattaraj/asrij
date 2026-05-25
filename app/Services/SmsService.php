<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function sendOtpSms($mobile, $otp)
    {
        // $response = Http::withHeaders([
        //     'authorization' => env('SMS_API_KEY'),
        //     'accept' => 'application/json',
        // ])->post('https://www.fast2sms.com/dev/bulkV2', [
        //     'route' => 'dlt',
        //     'variables_values' => $otp,
        //     'numbers' => $mobile,
        //     'sender_id' => 'ASRIJ',
        //     'message' => '214706' // Template ID
        // ]);

        // return $response->json();
        return [
            'return' => true,
            'request_id' => 'DEV123456',
            'message' => ['SMS sent successfully (mocked)']
        ];
    }
}