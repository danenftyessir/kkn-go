<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'supabase'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        // disk supabase storage (S3 compatible)
        'supabase' => [
            'driver' => 's3',
            'key' => env('SUPABASE_ACCESS_KEY_ID'),
            'secret' => env('SUPABASE_SECRET_ACCESS_KEY'),
            'region' => env('SUPABASE_REGION', 'ap-southeast-1'),
            'bucket' => env('SUPABASE_BUCKET'),
            'url' => env('SUPABASE_URL'),
            'endpoint' => env('SUPABASE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'throw' => false,
            'report' => false,
            // perbaikan untuk SSL certificate error di local development
            'http' => [
                'verify' => env('SUPABASE_SSL_VERIFY', !app()->environment('local')),
                'connect_timeout' => 30,
                'timeout' => 60,
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];