<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'from' => env('TWILIO_PHONE_NUMBER'),
    ],
    'whatsapp' => [
        'token' => env('WHATSAPP_TOKEN'),
        'phone_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v19.0'),
    ],

    'fcm' => [
        // Firebase Cloud Messaging HTTP v1
        // 1. Put your service-account JSON in storage/app/firebase/credentials.json
        //    (downloaded from Firebase Console → Project Settings → Service Accounts → Generate new private key)
        // 2. Set FIREBASE_PROJECT_ID and FIREBASE_CREDENTIALS in .env
        'project_id'  => env('FIREBASE_PROJECT_ID'),
        'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/credentials.json')),
        // Donor notification radius in KM (used by RequestController::store)
        'donor_radius_km' => env('FCM_DONOR_RADIUS_KM', 20),
    ],

];
