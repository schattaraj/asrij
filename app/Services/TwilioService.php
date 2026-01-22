<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendOtp(string $phone, string $otp)
    {
        try {
            $message = $this->client->messages->create($phone, [
                'from' => config('services.twilio.from'),
                'body' => "Your OTP is: {$otp}"
            ]);

            // return true;
            Log::info('Twilio SMS sent', [
                'sid' => $message->sid,
                'status' => $message->status,
            ]);

        } catch (\Exception $e) {
            // logger()->error($e->getMessage());
            // return false;
            Log::error('Twilio SMS failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
