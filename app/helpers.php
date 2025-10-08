<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storage_url')) {
    /**
     * dapatkan URL publik untuk file di supabase storage
     * LANGSUNG pakai public URL, tidak pakai S3 driver
     * 
     * @param string|null $path path file di storage
     * @return string URL publik file
     */
    function storage_url($path)
    {
        if (empty($path)) {
            return '';
        }

        // supabase project ID
        $projectId = 'zgpykwjzmiqxhweifmrn';
        
        // bucket name (pakai URL encode untuk handle space)
        $bucket = rawurlencode('kkn-go storage');
        
        // clean path (hapus leading slash)
        $cleanPath = ltrim($path, '/');
        
        // format URL supabase public:
        // https://PROJECT_ID.supabase.co/storage/v1/object/public/BUCKET/PATH
        return "https://{$projectId}.supabase.co/storage/v1/object/public/{$bucket}/{$cleanPath}";
    }
}

if (!function_exists('problem_image_url')) {
    /**
     * dapatkan URL gambar problem dari supabase
     * 
     * @param string|null $imagePath path gambar (misal: problems/image1.jpg)
     * @return string URL gambar
     */
    function problem_image_url($imagePath)
    {
        if (empty($imagePath)) {
            return asset('images/placeholder-problem.jpg');
        }
        
        return storage_url($imagePath);
    }
}

if (!function_exists('document_url')) {
    /**
     * dapatkan URL dokumen dari supabase
     * 
     * @param string|null $filePath path file (misal: documents/reports/file.pdf)
     * @return string URL file
     */
    function document_url($filePath)
    {
        if (empty($filePath)) {
            return '';
        }
        
        return storage_url($filePath);
    }
}

if (!function_exists('profile_photo_url')) {
    /**
     * dapatkan URL foto profil
     * 
     * @param string|null $photoPath path foto
     * @param string $defaultType tipe default (student/institution)
     * @return string URL foto atau default avatar
     */
    function profile_photo_url($photoPath, $defaultType = 'student')
    {
        if (!empty($photoPath)) {
            return storage_url($photoPath);
        }
        
        // return default avatar
        $defaults = [
            'student' => asset('images/default-student-avatar.png'),
            'institution' => asset('images/default-institution-logo.png'),
        ];
        
        return $defaults[$defaultType] ?? $defaults['student'];
    }
}

if (!function_exists('format_file_size')) {
    /**
     * format ukuran file ke format yang mudah dibaca
     * 
     * @param int $bytes ukuran dalam bytes
     * @param int $decimals jumlah desimal
     * @return string ukuran terformat (e.g., "2.5 MB")
     */
    function format_file_size($bytes, $decimals = 2)
    {
        if ($bytes === 0 || $bytes === null) {
            return '0 Bytes';
        }

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), $decimals) . ' ' . $sizes[$i];
    }
}

if (!function_exists('sdg_category_label')) {
    /**
     * dapatkan label untuk kategori SDG
     */
    function sdg_category_label($category)
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
            'industry_innovation' => 'Industri, Inovasi, dan Infrastruktur',
            'reduced_inequalities' => 'Berkurangnya Kesenjangan',
            'sustainable_cities' => 'Kota dan Komunitas Berkelanjutan',
            'responsible_consumption' => 'Konsumsi dan Produksi yang Bertanggung Jawab',
            'climate_action' => 'Penanganan Perubahan Iklim',
            'life_below_water' => 'Ekosistem Laut',
            'life_on_land' => 'Ekosistem Daratan',
            'peace_justice' => 'Perdamaian, Keadilan, dan Kelembagaan yang Kuat',
            'partnerships' => 'Kemitraan untuk Mencapai Tujuan',
        ];

        return $labels[$category] ?? ucfirst(str_replace('_', ' ', $category));
    }
}

if (!function_exists('status_badge_class')) {
    /**
     * dapatkan class CSS untuk status badge
     */
    function status_badge_class($status)
    {
        $classes = [
            'draft' => 'bg-gray-100 text-gray-800',
            'open' => 'bg-green-100 text-green-800',
            'closed' => 'bg-red-100 text-red-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-purple-100 text-purple-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'under_review' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800';
    }
}

if (!function_exists('status_label')) {
    /**
     * dapatkan label untuk status
     */
    function status_label($status)
    {
        $labels = [
            'draft' => 'Draft',
            'open' => 'Terbuka',
            'closed' => 'Ditutup',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'pending' => 'Menunggu',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'under_review' => 'Dalam Review',
            'approved' => 'Disetujui',
            'active' => 'Aktif',
        ];

        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}

if (!function_exists('difficulty_badge_class')) {
    /**
     * dapatkan class CSS untuk difficulty badge
     */
    function difficulty_badge_class($difficulty)
    {
        $classes = [
            'beginner' => 'bg-green-100 text-green-800',
            'intermediate' => 'bg-yellow-100 text-yellow-800',
            'advanced' => 'bg-red-100 text-red-800',
        ];

        return $classes[$difficulty] ?? 'bg-gray-100 text-gray-800';
    }
}

if (!function_exists('difficulty_label')) {
    /**
     * dapatkan label untuk difficulty
     */
    function difficulty_label($difficulty)
    {
        $labels = [
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Lanjutan',
        ];

        return $labels[$difficulty] ?? ucfirst($difficulty);
    }
}