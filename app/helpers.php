<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storage_url')) {
    /**
     * dapatkan URL publik untuk file di storage
     * support local dan supabase storage
     * 
     * @param string $path path file di storage
     * @param string|null $disk disk yang digunakan (null = default disk)
     * @return string URL publik file
     */
    function storage_url($path, $disk = null)
    {
        if (empty($path)) {
            return '';
        }

        $disk = $disk ?? config('filesystems.default');
        
        // jika menggunakan supabase, return URL publik
        if ($disk === 'supabase') {
            $baseUrl = rtrim(config('filesystems.disks.supabase.url'), '/');
            $bucket = config('filesystems.disks.supabase.bucket');
            
            // format URL supabase: https://project.supabase.co/storage/v1/object/public/bucket/path
            return $baseUrl . '/' . $bucket . '/' . ltrim($path, '/');
        }
        
        // untuk disk lain, gunakan Storage::url()
        return Storage::disk($disk)->url($path);
    }
}

if (!function_exists('problem_image_url')) {
    /**
     * dapatkan URL gambar problem
     * 
     * @param string $imagePath path gambar
     * @return string URL gambar
     */
    function problem_image_url($imagePath)
    {
        return storage_url($imagePath);
    }
}

if (!function_exists('document_url')) {
    /**
     * dapatkan URL dokumen
     * 
     * @param string $filePath path file
     * @return string URL file
     */
    function document_url($filePath)
    {
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
            'student' => '/images/default-student-avatar.png',
            'institution' => '/images/default-institution-logo.png',
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
        if ($bytes === 0) {
            return '0 Bytes';
        }

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), $decimals) . ' ' . $sizes[$i];
    }
}

if (!function_exists('sdg_label')) {
    /**
     * dapatkan label SDG dari kode
     * 
     * @param string $code kode SDG (e.g., 'no_poverty')
     * @return string label SDG
     */
    function sdg_label($code)
    {
        $labels = [
            'no_poverty' => 'Tanpa Kemiskinan',
            'zero_hunger' => 'Tanpa Kelaparan',
            'good_health' => 'Kesehatan yang Baik',
            'quality_education' => 'Pendidikan Berkualitas',
            'gender_equality' => 'Kesetaraan Gender',
            'clean_water' => 'Air Bersih dan Sanitasi',
            'affordable_energy' => 'Energi Bersih dan Terjangkau',
            'decent_work' => 'Pekerjaan Layak',
            'industry_innovation' => 'Industri, Inovasi, dan Infrastruktur',
            'reduced_inequalities' => 'Mengurangi Kesenjangan',
            'sustainable_cities' => 'Kota dan Komunitas Berkelanjutan',
            'responsible_consumption' => 'Konsumsi dan Produksi Bertanggung Jawab',
            'climate_action' => 'Aksi Iklim',
            'life_below_water' => 'Kehidupan di Bawah Air',
            'life_on_land' => 'Kehidupan di Darat',
            'peace_justice' => 'Perdamaian, Keadilan, dan Kelembagaan',
            'partnerships' => 'Kemitraan untuk Tujuan',
        ];

        return $labels[$code] ?? ucwords(str_replace('_', ' ', $code));
    }
}

if (!function_exists('sdg_color')) {
    /**
     * dapatkan warna SDG dari kode
     * 
     * @param string $code kode SDG
     * @return string class tailwind color
     */
    function sdg_color($code)
    {
        $colors = [
            'no_poverty' => 'bg-red-500',
            'zero_hunger' => 'bg-amber-500',
            'good_health' => 'bg-green-500',
            'quality_education' => 'bg-red-600',
            'gender_equality' => 'bg-orange-500',
            'clean_water' => 'bg-cyan-400',
            'affordable_energy' => 'bg-yellow-400',
            'decent_work' => 'bg-rose-600',
            'industry_innovation' => 'bg-orange-600',
            'reduced_inequalities' => 'bg-pink-500',
            'sustainable_cities' => 'bg-amber-600',
            'responsible_consumption' => 'bg-yellow-600',
            'climate_action' => 'bg-green-600',
            'life_below_water' => 'bg-blue-500',
            'life_on_land' => 'bg-lime-500',
            'peace_justice' => 'bg-blue-600',
            'partnerships' => 'bg-indigo-600',
        ];

        return $colors[$code] ?? 'bg-gray-500';
    }
}