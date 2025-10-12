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

<?php

/**
 * DEBUGGING HELPER SCRIPT
 * 
 * Taruh file ini di: routes/web.php (temporary route untuk debugging)
 * Akses via: /institution/problems/{id}/debug-form
 * 
 * Script ini akan:
 * 1. Cek data problem dari database
 * 2. Cek validasi rules
 * 3. Cek fillable di model
 * 4. Simulate form submission
 */

// tambahkan route ini SEMENTARA di routes/web.php untuk debugging
Route::get('/institution/problems/{id}/debug-form', function($id) {
    $problem = \App\Models\Problem::with('images')->findOrFail($id);
    
    // 1. CEK DATA PROBLEM
    echo "<h2>1. DATA PROBLEM DARI DATABASE</h2>";
    echo "<pre>";
    echo "ID: " . $problem->id . "\n";
    echo "Title: " . $problem->title . "\n";
    echo "Status: " . $problem->status . "\n";
    echo "Province ID: " . $problem->province_id . "\n";
    echo "Regency ID: " . $problem->regency_id . "\n";
    echo "Village: " . ($problem->village ?? 'NULL') . "\n";
    echo "Detailed Location: " . ($problem->detailed_location ?? 'NULL') . "\n";
    echo "Background: " . ($problem->background ?? 'NULL') . "\n";
    echo "Objectives: " . ($problem->objectives ?? 'NULL') . "\n";
    echo "Scope: " . ($problem->scope ?? 'NULL') . "\n";
    echo "Images Count: " . $problem->images->count() . "\n";
    echo "</pre>";
    
    // 2. CEK FILLABLE MODEL
    echo "<h2>2. FILLABLE FIELDS DI MODEL</h2>";
    echo "<pre>";
    print_r($problem->getFillable());
    echo "</pre>";
    
    // 3. CEK SDG CATEGORIES
    echo "<h2>3. SDG CATEGORIES</h2>";
    echo "<pre>";
    $sdg = is_array($problem->sdg_categories) ? $problem->sdg_categories : json_decode($problem->sdg_categories, true);
    print_r($sdg);
    echo "</pre>";
    
    // 4. CEK REQUIRED SKILLS
    echo "<h2>4. REQUIRED SKILLS</h2>";
    echo "<pre>";
    $skills = is_array($problem->required_skills) ? $problem->required_skills : json_decode($problem->required_skills, true);
    print_r($skills);
    echo "</pre>";
    
    // 5. SIMULATE FORM DATA
    echo "<h2>5. SIMULATE FORM DATA (Copy ini untuk test Postman)</h2>";
    $formData = [
        'title' => $problem->title . ' (EDITED)',
        'description' => $problem->description,
        'background' => $problem->background,
        'objectives' => $problem->objectives,
        'scope' => $problem->scope,
        'province_id' => $problem->province_id,
        'regency_id' => $problem->regency_id,
        'village' => $problem->village,
        'detailed_location' => $problem->detailed_location,
        'latitude' => $problem->latitude,
        'longitude' => $problem->longitude,
        'sdg_categories' => is_array($problem->sdg_categories) ? $problem->sdg_categories : json_decode($problem->sdg_categories, true),
        'required_students' => $problem->required_students,
        'required_skills' => is_array($problem->required_skills) ? $problem->required_skills : json_decode($problem->required_skills, true),
        'required_majors' => is_array($problem->required_majors) ? $problem->required_majors : json_decode($problem->required_majors, true),
        'start_date' => $problem->start_date->format('Y-m-d'),
        'end_date' => $problem->end_date->format('Y-m-d'),
        'application_deadline' => $problem->application_deadline->format('Y-m-d'),
        'duration_months' => $problem->duration_months,
        'difficulty_level' => $problem->difficulty_level,
        'status' => $problem->status,
        'expected_outcomes' => $problem->expected_outcomes,
        'deliverables' => is_array($problem->deliverables) ? $problem->deliverables : json_decode($problem->deliverables, true),
        'facilities_provided' => is_array($problem->facilities_provided) ? $problem->facilities_provided : json_decode($problem->facilities_provided, true),
    ];
    echo "<textarea style='width:100%; height:300px;'>";
    echo json_encode($formData, JSON_PRETTY_PRINT);
    echo "</textarea>";
    
    // 6. TEST VALIDATION
    echo "<h2>6. TEST VALIDATION</h2>";
    $validator = \Illuminate\Support\Facades\Validator::make($formData, [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'background' => 'nullable|string',
        'objectives' => 'nullable|string',
        'scope' => 'nullable|string',
        'province_id' => 'required|integer|exists:provinces,id',
        'regency_id' => 'required|integer|exists:regencies,id',
        'village' => 'nullable|string|max:255',
        'detailed_location' => 'nullable|string',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'sdg_categories' => 'required|array|min:1',
        'sdg_categories.*' => 'integer|between:1,17',
        'required_students' => 'required|integer|min:1',
        'required_skills' => 'required|array|min:1',
        'required_majors' => 'nullable|array',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'application_deadline' => 'required|date|before:start_date',
        'duration_months' => 'required|integer|min:1',
        'difficulty_level' => 'required|in:beginner,intermediate,advanced',
        'status' => 'required|in:draft,open,in_progress,completed,closed',
        'expected_outcomes' => 'nullable|string',
        'deliverables' => 'nullable|array',
        'facilities_provided' => 'nullable|array',
    ]);
    
    if ($validator->fails()) {
        echo "<pre style='color:red;'>";
        echo "❌ VALIDATION FAILED:\n";
        print_r($validator->errors()->toArray());
        echo "</pre>";
    } else {
        echo "<pre style='color:green;'>";
        echo "✅ VALIDATION PASSED!";
        echo "</pre>";
    }
    
    // 7. CHECK MISSING FIELDS
    echo "<h2>7. CHECK MISSING FIELDS IN FORM</h2>";
    $modelFillable = $problem->getFillable();
    $formFields = array_keys($formData);
    $missingInForm = array_diff($modelFillable, $formFields);
    
    if (count($missingInForm) > 0) {
        echo "<pre style='color:orange;'>";
        echo "⚠️ Fields in Model but NOT in Form:\n";
        print_r($missingInForm);
        echo "\nNote: Ini mungkin OK jika field auto-generated (created_at, institution_id, dll)";
        echo "</pre>";
    } else {
        echo "<pre style='color:green;'>";
        echo "✅ All necessary fields present!";
        echo "</pre>";
    }
    
    // 8. LOG TEST
    echo "<h2>8. RECENT LOGS (Last 50 lines from laravel.log)</h2>";
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        $lines = file($logPath);
        $lastLines = array_slice($lines, -50);
        echo "<textarea style='width:100%; height:300px;'>";
        echo implode('', $lastLines);
        echo "</textarea>";
    } else {
        echo "<p style='color:red;'>Log file not found</p>";
    }
    
})->middleware(['auth', 'check.user.type:institution']);