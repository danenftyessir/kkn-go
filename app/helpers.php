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

if (!function_exists('sdg_icon')) {
    /**
     * dapatkan icon atau badge untuk kategori SDG
     * 
     * @param string $category kode kategori SDG
     * @return string class CSS atau path icon
     */
    function sdg_icon(string $category): string
    {
        $icons = [
            'no_poverty' => 'icon-poverty',
            'zero_hunger' => 'icon-hunger',
            'good_health' => 'icon-health',
            'quality_education' => 'icon-education',
            'gender_equality' => 'icon-gender',
            'clean_water' => 'icon-water',
            'affordable_energy' => 'icon-energy',
            'decent_work' => 'icon-work',
            'industry_innovation' => 'icon-innovation',
            'reduced_inequalities' => 'icon-inequalities',
            'sustainable_cities' => 'icon-cities',
            'responsible_consumption' => 'icon-consumption',
            'climate_action' => 'icon-climate',
            'life_below_water' => 'icon-water-life',
            'life_on_land' => 'icon-land-life',
            'peace_justice' => 'icon-peace',
            'partnerships' => 'icon-partnerships',
        ];
        
        return $icons[$category] ?? 'icon-default';
    }
}

if (!function_exists('sdg_label')) {
    /**
     * dapatkan label dalam bahasa indonesia untuk kategori SDG
     * 
     * @param string $category kode kategori SDG
     * @return string label kategori
     */
    function sdg_label(string $category): string
    {
        $labels = [
            'no_poverty' => 'Tanpa Kemiskinan',
            'zero_hunger' => 'Tanpa Kelaparan',
            'good_health' => 'Kehidupan Sehat dan Sejahtera',
            'quality_education' => 'Pendidikan Berkualitas',
            'gender_equality' => 'Kesetaraan Gender',
            'clean_water' => 'Air Bersih dan Sanitasi',
            'affordable_energy' => 'Energi Bersih dan Terjangkau',
            'decent_work' => 'Pekerjaan Layak dan Pertumbuhan Ekonomi',
            'industry_innovation' => 'Industri, Inovasi dan Infrastruktur',
            'reduced_inequalities' => 'Berkurangnya Kesenjangan',
            'sustainable_cities' => 'Kota dan Komunitas Berkelanjutan',
            'responsible_consumption' => 'Konsumsi dan Produksi Bertanggung Jawab',
            'climate_action' => 'Penanganan Perubahan Iklim',
            'life_below_water' => 'Ekosistem Laut',
            'life_on_land' => 'Ekosistem Daratan',
            'peace_justice' => 'Perdamaian, Keadilan dan Kelembagaan yang Kuat',
            'partnerships' => 'Kemitraan untuk Mencapai Tujuan',
        ];
        
        return $labels[$category] ?? ucfirst(str_replace('_', ' ', $category));
    }
}

if (!function_exists('status_badge')) {
    /**
     * generate HTML badge untuk status
     * 
     * @param string $status status value
     * @param string $type tipe status (application, project, problem, dll)
     * @return string HTML badge
     */
    function status_badge(string $status, string $type = 'default'): string
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-green-100 text-green-800',
            'closed' => 'bg-red-100 text-red-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-purple-100 text-purple-800',
            'approved' => 'bg-green-100 text-green-800',
        ];
        
        $labels = [
            'pending' => 'Menunggu',
            'reviewed' => 'Ditinjau',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'draft' => 'Draft',
            'published' => 'Dipublikasikan',
            'closed' => 'Ditutup',
            'in_progress' => 'Berjalan',
            'completed' => 'Selesai',
            'approved' => 'Disetujui',
        ];
        
        $color = $colors[$status] ?? 'bg-gray-100 text-gray-800';
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