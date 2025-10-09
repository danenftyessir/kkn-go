<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | menggunakan database sebagai default untuk session persistence yang
    | lebih reliable dibanding file storage
    |
    | Supported: "file", "cookie", "database", "memcached",
    |            "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | durasi session dalam menit sebelum expire
    | 120 menit = 2 jam
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => (bool) env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | encrypt session data sebelum disimpan
    | set false untuk performa lebih baik di development
    |
    */

    'encrypt' => (bool) env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | lokasi penyimpanan session files jika menggunakan file driver
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | koneksi database untuk menyimpan sessions
    | null = gunakan default DB connection
    |
    */

    'connection' => env('SESSION_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | nama tabel untuk menyimpan sessions
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | cache store untuk session backends yang menggunakan cache
    |
    | Affects: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | probabilitas untuk membersihkan session lama
    | [2, 100] = 2% chance per request
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | nama cookie untuk session
    | CRITICAL: jangan gunakan karakter aneh atau spasi
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | path dimana cookie tersedia
    | '/' = tersedia untuk semua path di domain
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | CRITICAL: konfigurasi ini sering menjadi penyebab masalah login
    |
    | Local development: KOSONGKAN atau set null
    | Production dengan single domain: kosongkan atau gunakan 'domain.com'
    | Production dengan subdomain: gunakan '.domain.com' (dengan titik)
    |
    | Contoh:
    | - Local: SESSION_DOMAIN= (kosong)
    | - app.kkn-go.com only: SESSION_DOMAIN=app.kkn-go.com
    | - *.kkn-go.com (all subdomains): SESSION_DOMAIN=.kkn-go.com
    |
    */

    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | CRITICAL: harus disesuaikan dengan protocol yang digunakan
    |
    | HTTP (local development): false
    | HTTPS (production): true
    |
    | Jika setting ini salah, cookie tidak akan ter-set!
    |
    */

    'secure' => (bool) env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | mencegah JavaScript mengakses session cookie
    | selalu set true untuk keamanan
    |
    */

    'http_only' => (bool) env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | mengontrol bagaimana cookie dikirim pada cross-site requests
    |
    | 'lax' = recommended, balance antara keamanan dan usability
    | 'strict' = lebih aman tapi bisa menyebabkan masalah pada redirect
    | 'none' = perlu SESSION_SECURE_COOKIE=true
    |
    | Supported: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | cookies yang di-partition untuk cross-site context
    | biasanya tidak perlu diaktifkan kecuali ada requirement khusus
    |
    */

    'partitioned' => (bool) env('SESSION_PARTITIONED_COOKIE', false),

];