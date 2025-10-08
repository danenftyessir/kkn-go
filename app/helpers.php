<?php

/**
 * helper functions untuk aplikasi KKN-GO
 * 
 * file ini otomatis di-load oleh composer (lihat composer.json)
 */

if (!function_exists('supabase_url')) {
    /**
     * generate public URL untuk file di supabase storage
     * 
     * @param string|null $path path file di bucket (contoh: 'problems/image.jpg')
     * @param string|null $bucket nama bucket (default: 'kkn-go storage')
     * @return string URL publik file atau placeholder jika path kosong
     */
    function supabase_url(?string $path, ?string $bucket = null): string
    {
        // jika path kosong, return placeholder
        if (empty($path)) {
            return asset('images/placeholder.jpg');
        }
        
        // gunakan bucket dari config atau default
        $bucket = $bucket ?? config('filesystems.disks.supabase.bucket', 'kkn-go storage');
        
        // base URL dari supabase
        $baseUrl = config('filesystems.disks.supabase.url');
        
        // encode bucket name untuk URL (ganti spasi dengan %20)
        $encodedBucket = str_replace(' ', '%20', $bucket);
        
        // encode path jika perlu
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));
        
        // format: https://PROJECT_ID.supabase.co/storage/v1/object/public/BUCKET_NAME/PATH
        return "{$baseUrl}/storage/v1/object/public/{$encodedBucket}/{$encodedPath}";
    }
}

if (!function_exists('document_url')) {
    /**
     * generate URL untuk mengakses dokumen
     * alias untuk supabase_url() khusus dokumen
     * 
     * @param string|null $path path file dokumen di bucket
     * @return string URL publik dokumen
     */
    function document_url(?string $path): string
    {
        return supabase_url($path);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * format file size dari bytes ke human readable format
     * 
     * @param int $bytes ukuran file dalam bytes
     * @return string ukuran file yang mudah dibaca (contoh: "2.5 MB")
     */
    function format_file_size(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

if (!function_exists('time_ago')) {
    /**
     * konversi timestamp ke format "time ago" dalam bahasa indonesia
     * 
     * @param \Carbon\Carbon|string $time waktu yang akan dikonversi
     * @return string format waktu relatif (contoh: "2 jam yang lalu")
     */
    function time_ago($time): string
    {
        $time = \Carbon\Carbon::parse($time);
        $now = \Carbon\Carbon::now();
        
        $diff = $time->diff($now);
        
        if ($diff->y > 0) {
            return $diff->y . ' tahun yang lalu';
        } elseif ($diff->m > 0) {
            return $diff->m . ' bulan yang lalu';
        } elseif ($diff->d > 0) {
            return $diff->d . ' hari yang lalu';
        } elseif ($diff->h > 0) {
            return $diff->h . ' jam yang lalu';
        } elseif ($diff->i > 0) {
            return $diff->i . ' menit yang lalu';
        } else {
            return 'baru saja';
        }
    }
}

if (!function_exists('status_badge')) {
    /**
     * generate HTML badge untuk status dengan warna yang sesuai
     * 
     * @param string $status nama status
     * @param string|null $type tipe badge (application, project, problem, document)
     * @return string HTML badge
     */
    function status_badge(string $status, ?string $type = null): string
    {
        // definisi warna untuk setiap status berdasarkan tipe
        $colors = [
            'application' => [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'reviewed' => 'bg-blue-100 text-blue-800',
                'accepted' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
            ],
            'project' => [
                'active' => 'bg-green-100 text-green-800',
                'on_hold' => 'bg-yellow-100 text-yellow-800',
                'completed' => 'bg-blue-100 text-blue-800',
                'cancelled' => 'bg-gray-100 text-gray-800',
            ],
            'problem' => [
                'draft' => 'bg-gray-100 text-gray-800',
                'open' => 'bg-green-100 text-green-800',
                'in_progress' => 'bg-blue-100 text-blue-800',
                'completed' => 'bg-purple-100 text-purple-800',
                'closed' => 'bg-red-100 text-red-800',
            ],
            'document' => [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
            ],
            'milestone' => [
                'pending' => 'bg-gray-100 text-gray-800',
                'in_progress' => 'bg-blue-100 text-blue-800',
                'completed' => 'bg-green-100 text-green-800',
                'delayed' => 'bg-red-100 text-red-800',
            ],
        ];
        
        // label indonesia untuk status
        $labels = [
            'pending' => 'Menunggu',
            'reviewed' => 'Ditinjau',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'active' => 'Aktif',
            'on_hold' => 'Ditahan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'draft' => 'Draft',
            'open' => 'Terbuka',
            'in_progress' => 'Sedang Berjalan',
            'closed' => 'Ditutup',
            'approved' => 'Disetujui',
            'delayed' => 'Terlambat',
        ];
        
        // ambil warna berdasarkan tipe dan status
        $color = 'bg-gray-100 text-gray-800'; // default
        if ($type && isset($colors[$type][$status])) {
            $color = $colors[$type][$status];
        } else {
            // coba cari di semua tipe
            foreach ($colors as $typeColors) {
                if (isset($typeColors[$status])) {
                    $color = $typeColors[$status];
                    break;
                }
            }
        }
        
        $label = $labels[$status] ?? ucfirst($status);
        
        return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ' . $color . '">' 
               . $label . 
               '</span>';
    }
}

if (!function_exists('user_avatar')) {
    /**
     * generate avatar URL atau inisial untuk user
     * 
     * @param object|null $user user object
     * @return string URL avatar atau data URI untuk inisial
     */
    function user_avatar($user): string
    {
        if (!$user) {
            return asset('images/default-avatar.png');
        }
        
        // jika user punya photo_path, gunakan supabase URL
        if (isset($user->photo_path) && !empty($user->photo_path)) {
            return supabase_url($user->photo_path);
        }
        
        // jika tidak ada photo, return default
        return asset('images/default-avatar.png');
    }
}