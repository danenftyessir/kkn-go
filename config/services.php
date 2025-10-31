<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | File ini untuk menyimpan kredensial untuk third party services seperti
    | Mailgun, Postmark, AWS, dan lainnya. File ini menyediakan lokasi default
    | untuk tipe informasi ini, memungkinkan packages untuk memiliki file
    | konvensional untuk mencari berbagai kredensial service.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk Supabase Storage
    | Digunakan untuk menyimpan file seperti gambar problems, dokumen, dll
    |
    */

    'supabase' => [
        'project_id' => env('SUPABASE_PROJECT_ID', 'zgpykwjzmiqxhweifmrn'),
        'url' => env('SUPABASE_URL', 'https://zgpykwjzmiqxhweifmrn.supabase.co'),
        'anon_key' => env('SUPABASE_ANON_KEY'),
        'service_key' => env('SUPABASE_SERVICE_KEY'),
        'bucket' => env('SUPABASE_BUCKET', 'kkngo-storage'),
    ],

];