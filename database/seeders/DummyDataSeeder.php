<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use App\Models\University;
use App\Models\Province;
use App\Models\Regency;

/**
 * seeder untuk data dummy users, students, institutions, universities
 * FULLY OPTIMIZED: menggunakan bulk operations dan menghindari N+1 queries
 * UPDATED: menggunakan data provinces & regencies dari database (dinamis dari BPS)
 * 
 * path: database/seeders/DummyDataSeeder.php
 * jalankan: php artisan db:seed --class=DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    private $batchSize = 50; // insert per 50 records
    
    public function run(): void
    {
        // disable query log untuk performa
        DB::connection()->disableQueryLog();
        
        echo "memulai seeding data dummy...\n\n";
        
        $this->cleanOldData();
        $this->seedUniversities();
        $this->seedStudents();
        $this->seedInstitutions();
        
        echo "\n";
        echo "==========================================\n";
        echo "seeding data dummy selesai!\n";
        echo "==========================================\n";
        echo "\n";
        echo "statistik data:\n";
        echo "  - universities  : " . University::count() . "\n";
        echo "  - students      : " . Student::count() . "\n";
        echo "  - institutions  : " . Institution::count() . "\n";
        echo "\n";
        echo "akun testing (semua password: password123):\n";
        echo "\n";
        echo "mahasiswa pertama:\n";
        $firstStudent = Student::with('user', 'university')->first();
        if ($firstStudent) {
            echo "  nama     : " . $firstStudent->first_name . " " . $firstStudent->last_name . "\n";
            echo "  email    : " . $firstStudent->user->email . "\n";
            echo "  username : " . $firstStudent->user->username . "\n";
            echo "  kampus   : " . $firstStudent->university->name . "\n";
        }
        echo "\n";
        echo "instansi pertama:\n";
        $firstInst = Institution::with('user')->first();
        if ($firstInst) {
            echo "  nama     : " . $firstInst->name . "\n";
            echo "  email    : " . $firstInst->user->email . "\n";
            echo "  username : " . $firstInst->user->username . "\n";
        }
        echo "\n";
        echo "catatan: problems, applications, projects akan di-seed oleh seeder terpisah\n";
        echo "==========================================\n";
    }

    /**
     * bersihkan data lama - kompatibel untuk PostgreSQL
     */
    private function cleanOldData(): void
    {
        echo "membersihkan data lama...\n";
        
        try {
            // untuk PostgreSQL gunakan TRUNCATE CASCADE
            DB::statement('TRUNCATE TABLE applications, wishlists, problem_images, problems, students, institutions, universities RESTART IDENTITY CASCADE');
            
            // hapus users non-admin
            DB::table('users')
                ->where('user_type', '!=', 'admin')
                ->delete();
            
            echo "  -> data lama berhasil dibersihkan\n";
        } catch (\Exception $e) {
            echo "  -> warning: " . $e->getMessage() . "\n";
            echo "  -> mencoba alternatif pembersihan...\n";
            
            try {
                // alternatif: disable foreign key checks, truncate, enable lagi
                DB::statement('SET session_replication_role = replica');
                
                DB::table('applications')->truncate();
                DB::table('wishlists')->truncate();
                DB::table('problem_images')->truncate();
                DB::table('problems')->truncate();
                DB::table('students')->truncate();
                DB::table('institutions')->truncate();
                DB::table('universities')->truncate();
                
                // hapus users non-admin
                DB::table('users')
                    ->where('user_type', '!=', 'admin')
                    ->delete();
                
                DB::statement('SET session_replication_role = DEFAULT');
                
                echo "  -> alternatif pembersihan berhasil\n";
            } catch (\Exception $e2) {
                echo "  -> error: " . $e2->getMessage() . "\n";
                echo "  -> mencoba delete manual...\n";
                
                // last resort: delete satu per satu
                DB::table('applications')->delete();
                DB::table('wishlists')->delete();
                DB::table('problem_images')->delete();
                DB::table('problems')->delete();
                DB::table('students')->delete();
                DB::table('institutions')->delete();
                DB::table('users')
                    ->where('user_type', '!=', 'admin')
                    ->delete();
                DB::table('universities')->delete();
                
                echo "  -> delete manual selesai\n";
            }
        }
    }

    /**
     * seeding universities - 120+ universitas
     * FIXED: menggunakan batch insert
     * DISTRIBUSI OTOMATIS ke semua provinsi dari database BPS
     */
    private function seedUniversities(): void
    {
        echo "seeding universities...\n";
        
        // ambil semua provinces dari database BPS
        $allProvinces = Province::all();
        
        if ($allProvinces->isEmpty()) {
            echo "  ERROR: Tidak ada data provinces. Jalankan ProvincesRegenciesSeeder terlebih dahulu!\n";
            return;
        }
        
        echo "  -> tersedia " . $allProvinces->count() . " provinsi dari BPS\n";
        
        // daftar universitas Indonesia (120+ universitas)
        $universities = $this->getUniversitiesData();
        
        $universitiesToInsert = [];
        
        foreach ($universities as $univData) {
            $location = $this->findUniversityLocation($univData['province_hint'], $allProvinces);
            
            // skip jika tidak dapat location
            if (!$location || !$location['province_id']) {
                continue;
            }
            
            $universitiesToInsert[] = [
                'name' => $univData['name'],
                'code' => $univData['code'],
                'province_id' => $location['province_id'],
                'regency_id' => $location['regency_id'],
                'type' => $univData['type'],
                'accreditation' => $univData['accreditation'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // batch insert jika sudah mencapai batch size
            if (count($universitiesToInsert) >= $this->batchSize) {
                $this->batchInsert('universities', $universitiesToInsert);
                echo "  -> " . count($universitiesToInsert) . " universities di-batch insert\n";
                $universitiesToInsert = [];
                
                // cleanup connection setelah batch
                $this->cleanupConnection();
            }
        }
        
        // insert sisa data
        if (!empty($universitiesToInsert)) {
            $this->batchInsert('universities', $universitiesToInsert);
            echo "  -> " . count($universitiesToInsert) . " universities di-batch insert\n";
        }
        
        $totalCreated = University::count();
        echo "  -> {$totalCreated} universities berhasil dibuat\n";
    }

    /**
     * seeding students dummy (400 students untuk 120+ universitas)
     * FULLY OPTIMIZED: bulk operations tanpa N+1 queries
     */
    private function seedStudents(): void
    {
        echo "seeding students...\n";
        
        $universities = University::all();
        
        if ($universities->isEmpty()) {
            echo "  ERROR: Tidak ada universities! Seed universities dulu.\n";
            return;
        }
        
        $firstNames = [
            'Andi', 'Budi', 'Citra', 'Devi', 'Eko', 'Farah', 'Gilang', 'Hana', 'Indra', 'Joko',
            'Kiki', 'Lisa', 'Maya', 'Nanda', 'Olivia', 'Putra', 'Qori', 'Rina', 'Sari', 'Tika',
            'Umar', 'Vera', 'Wawan', 'Xena', 'Yudi', 'Zara', 'Adam', 'Bella', 'Candra', 'Diana',
            'Erlangga', 'Fitri', 'Galih', 'Hesti', 'Ilham', 'Julia', 'Kevin', 'Laila', 'Mukti', 'Nisa',
            'Oscar', 'Pandu', 'Qina', 'Rafi', 'Sinta', 'Teguh', 'Ulfa', 'Vino', 'Wulan', 'Yusuf',
            'Agus', 'Bayu', 'Clara', 'Dimas', 'Elsa', 'Faisal', 'Gita', 'Hadi', 'Intan', 'Jihan',
            'Kurnia', 'Lina', 'Malik', 'Nadia', 'Omar', 'Putri', 'Qomar', 'Reza', 'Silvi', 'Toni',
            'Umi', 'Vina', 'Wahyu', 'Xaviera', 'Yoga', 'Zahra', 'Arif', 'Bunga', 'Cahya', 'Desi',
            'Eka', 'Fadli', 'Gandi', 'Hilda', 'Irfan', 'Jasmine', 'Krisna', 'Luki', 'Mira', 'Novi',
            'Oki', 'Prima', 'Qori', 'Rizki', 'Sinta', 'Tito', 'Uli', 'Vira', 'Widi', 'Yanto'
        ];
        
        $lastNames = [
            'Pratama', 'Santoso', 'Putri', 'Wijaya', 'Kusuma', 'Permata', 'Saputra', 'Lestari',
            'Sari', 'Nugroho', 'Wibowo', 'Rahayu', 'Susanto', 'Hartono', 'Setiawan', 'Anggraini',
            'Firmansyah', 'Hidayat', 'Ramadhan', 'Suryanto', 'Maharani', 'Nurfadilah', 'Maulana',
            'Syahputra', 'Syahputri', 'Rachman', 'Hakim', 'Adiputra', 'Salsabila', 'Rizqullah',
            'Azhari', 'Azzahra', 'Firdaus', 'Aisyah', 'Hasanah', 'Kamila', 'Nabila', 'Zahira'
        ];
        
        $majors = [
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Mesin',
            'Teknik Sipil', 'Arsitektur', 'Manajemen', 'Akuntansi', 'Ekonomi Pembangunan',
            'Ilmu Komunikasi', 'Hukum', 'Psikologi', 'Kedokteran', 'Keperawatan',
            'Farmasi', 'Kesehatan Masyarakat', 'Pendidikan', 'Sastra Indonesia',
            'Sastra Inggris', 'Desain Komunikasi Visual', 'Desain Produk', 'Seni Rupa',
            'Pertanian', 'Peternakan', 'Kehutanan', 'Perikanan', 'Biologi',
            'Kimia', 'Fisika', 'Matematika', 'Statistika', 'Geografi',
            'Sosiologi', 'Ilmu Politik', 'Hubungan Internasional', 'Administrasi Publik',
            'Teknik Industri', 'Teknik Kimia', 'Teknik Lingkungan', 'Agroteknologi'
        ];
        
        // password hash dibuat sekali saja untuk efisiensi
        $hashedPassword = Hash::make('password123');
        $now = now();
        
        $usersToInsert = [];
        $studentsData = []; // simpan data student untuk insert nanti
        $usedUsernames = []; // track username yang sudah digunakan
        $allUsernames = []; // simpan semua username untuk bulk lookup
        
        // buat 400 mahasiswa
        for ($i = 0; $i < 400; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            // generate username yang unique
            $baseUsername = strtolower($firstName . $lastName);
            $username = $baseUsername . rand(1000, 9999);
            
            // pastikan username benar-benar unique
            $counter = 1;
            while (isset($usedUsernames[$username])) {
                $username = $baseUsername . rand(1000, 9999) . $counter;
                $counter++;
            }
            $usedUsernames[$username] = true;
            $allUsernames[] = $username;
            
            $university = $universities->random();
            
            // generate email sesuai domain universitas
            $email = $username . '@' . $this->getUniversityEmailDomain($university->code);
            
            $usersToInsert[] = [
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'username' => $username,
                'password' => $hashedPassword,
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            
            // simpan data student untuk nanti (setelah user di-insert)
            $studentsData[] = [
                'username' => $username, // untuk lookup user_id nanti
                'university_id' => $university->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'nim' => rand(1000000000, 9999999999),
                'major' => $majors[array_rand($majors)],
                'semester' => rand(1, 8),
                'phone' => '+628' . rand(1000000000, 9999999999),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            
            // batch insert users jika sudah mencapai batch size
            if (count($usersToInsert) >= $this->batchSize) {
                $this->batchInsert('users', $usersToInsert);
                echo "  -> " . count($usersToInsert) . " users di-batch insert\n";
                $usersToInsert = [];
                
                // cleanup connection setelah batch
                $this->cleanupConnection();
            }
        }
        
        // insert sisa users
        if (!empty($usersToInsert)) {
            $this->batchInsert('users', $usersToInsert);
            echo "  -> " . count($usersToInsert) . " users di-batch insert\n";
        }
        
        // OPTIMASI CRITICAL: bulk lookup semua users sekaligus dengan whereIn
        echo "  -> mapping students ke users (bulk lookup)...\n";
        
        // ambil semua users yang baru dibuat dalam SATU query
        $usersMap = DB::table('users')
            ->whereIn('username', $allUsernames)
            ->where('user_type', 'student')
            ->pluck('id', 'username')
            ->toArray();
        
        echo "  -> " . count($usersMap) . " users berhasil di-lookup\n";
        
        // sekarang insert students dengan mapping dari usersMap
        $studentsToInsert = [];
        
        foreach ($studentsData as $studentData) {
            $username = $studentData['username'];
            
            // lookup user_id dari map (O(1) complexity)
            if (!isset($usersMap[$username])) {
                continue; // skip jika user tidak ditemukan
            }
            
            $studentsToInsert[] = [
                'user_id' => $usersMap[$username],
                'university_id' => $studentData['university_id'],
                'first_name' => $studentData['first_name'],
                'last_name' => $studentData['last_name'],
                'nim' => $studentData['nim'],
                'major' => $studentData['major'],
                'semester' => $studentData['semester'],
                'phone' => $studentData['phone'],
                'created_at' => $studentData['created_at'],
                'updated_at' => $studentData['updated_at'],
            ];
            
            // batch insert students
            if (count($studentsToInsert) >= $this->batchSize) {
                $this->batchInsert('students', $studentsToInsert);
                echo "  -> " . count($studentsToInsert) . " students di-batch insert\n";
                $studentsToInsert = [];
                
                // cleanup connection setelah batch
                $this->cleanupConnection();
            }
        }
        
        // insert sisa students
        if (!empty($studentsToInsert)) {
            $this->batchInsert('students', $studentsToInsert);
            echo "  -> " . count($studentsToInsert) . " students di-batch insert\n";
        }
        
        $totalStudents = Student::count();
        echo "  -> {$totalStudents} students berhasil dibuat\n";
    }

    /**
     * seeding institutions dummy (80 institutions untuk lebih realistis)
     * FULLY OPTIMIZED: bulk operations tanpa N+1 queries
     * DISTRIBUSI OTOMATIS ke semua provinsi dari database
     */
    private function seedInstitutions(): void
    {
        echo "seeding institutions...\n";
        
        $provinces = Province::all();
        
        if ($provinces->isEmpty()) {
            echo "  ERROR: Tidak ada data provinces!\n";
            return;
        }
        
        // template nama institusi berdasarkan tipe
        $institutionTemplates = $this->getInstitutionTemplates();
        
        // password hash dibuat sekali saja
        $hashedPassword = Hash::make('password123');
        $now = now();
        
        $usersToInsert = [];
        $institutionsData = [];
        $usedUsernames = [];
        $usedEmails = []; // track email yang sudah digunakan
        $allUsernames = [];
        
        // buat 80 institutions dengan distribusi merata ke semua provinsi
        $numInstitutions = 80;
        $provinceIndex = 0;
        
        for ($i = 0; $i < $numInstitutions; $i++) {
            // ambil province secara round-robin untuk distribusi merata
            $province = $provinces[$provinceIndex % $provinces->count()];
            $provinceIndex++;
            
            // ambil regency dari province
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            // pilih template random
            $template = $institutionTemplates[array_rand($institutionTemplates)];
            $baseInstitutionName = $template['names'][array_rand($template['names'])];
            $picData = $template['pic'][array_rand($template['pic'])];
            
            // tambahkan identifier unik ke nama institution
            // gunakan nama province yang lebih singkat untuk uniqueness
            $provinceName = str_replace(['Kab. ', 'Kota ', 'Provinsi '], '', $province->name);
            $institutionName = $baseInstitutionName . ' ' . $provinceName;
            
            // generate username unique
            $baseUsername = strtolower(str_replace(' ', '', $institutionName));
            $username = substr($baseUsername, 0, 15) . rand(100, 999);
            
            $counter = 1;
            while (isset($usedUsernames[$username])) {
                $username = substr($baseUsername, 0, 15) . rand(100, 999) . $counter;
                $counter++;
            }
            $usedUsernames[$username] = true;
            $allUsernames[] = $username;
            
            // generate email yang UNIQUE dengan menambahkan counter
            $emailDomain = str_replace(' ', '', strtolower($institutionName)) . '.id';
            $email = 'admin@' . $emailDomain;
            
            // pastikan email unique
            $emailCounter = 1;
            while (isset($usedEmails[$email])) {
                $email = 'admin' . $emailCounter . '@' . $emailDomain;
                $emailCounter++;
            }
            $usedEmails[$email] = true;
            
            $usersToInsert[] = [
                'name' => $institutionName,
                'email' => $email,
                'username' => $username,
                'password' => $hashedPassword,
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            
            $institutionsData[] = [
                'username' => $username,
                'name' => $institutionName,
                'type' => $template['type'],
                'email' => $email, // tambahkan email untuk institutions table
                'address' => 'Jl. ' . $institutionName . ' No. ' . rand(1, 999),
                'province_id' => $province->id,
                'regency_id' => $regency ? $regency->id : null,
                'phone' => '+62' . rand(21, 274) . rand(1000000, 9999999),
                'pic_name' => $picData['name'],
                'pic_position' => $picData['position'],
                'description' => 'Lembaga ' . $template['type'] . ' yang berkomitmen untuk pembangunan daerah.',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            
            // batch insert users
            if (count($usersToInsert) >= $this->batchSize) {
                $this->batchInsert('users', $usersToInsert);
                echo "  -> " . count($usersToInsert) . " users di-batch insert\n";
                $usersToInsert = [];
                
                // cleanup connection setelah batch
                $this->cleanupConnection();
            }
        }
        
        // insert sisa users
        if (!empty($usersToInsert)) {
            $this->batchInsert('users', $usersToInsert);
            echo "  -> " . count($usersToInsert) . " users di-batch insert\n";
        }
        
        // OPTIMASI CRITICAL: bulk lookup semua users sekaligus
        echo "  -> mapping institutions ke users (bulk lookup)...\n";
        
        $usersMap = DB::table('users')
            ->whereIn('username', $allUsernames)
            ->where('user_type', 'institution')
            ->pluck('id', 'username')
            ->toArray();
        
        echo "  -> " . count($usersMap) . " users berhasil di-lookup\n";
        
        // insert institutions dengan mapping
        $institutionsToInsert = [];
        
        foreach ($institutionsData as $instData) {
            $username = $instData['username'];
            
            if (!isset($usersMap[$username])) {
                continue;
            }
            
            $institutionsToInsert[] = [
                'user_id' => $usersMap[$username],
                'name' => $instData['name'],
                'type' => $instData['type'],
                'email' => $instData['email'], // tambahkan email
                'address' => $instData['address'],
                'province_id' => $instData['province_id'],
                'regency_id' => $instData['regency_id'],
                'phone' => $instData['phone'],
                'pic_name' => $instData['pic_name'],
                'pic_position' => $instData['pic_position'],
                'description' => $instData['description'],
                'created_at' => $instData['created_at'],
                'updated_at' => $instData['updated_at'],
            ];
            
            // batch insert institutions
            if (count($institutionsToInsert) >= $this->batchSize) {
                $this->batchInsert('institutions', $institutionsToInsert);
                echo "  -> " . count($institutionsToInsert) . " institutions di-batch insert\n";
                $institutionsToInsert = [];
                
                // cleanup connection setelah batch
                $this->cleanupConnection();
            }
        }
        
        // insert sisa institutions
        if (!empty($institutionsToInsert)) {
            $this->batchInsert('institutions', $institutionsToInsert);
            echo "  -> " . count($institutionsToInsert) . " institutions di-batch insert\n";
        }
        
        $totalInstitutions = Institution::count();
        echo "  -> {$totalInstitutions} institutions berhasil dibuat\n";
    }

    /**
     * batch insert dengan transaction
     */
    private function batchInsert(string $table, array $data): void
    {
        DB::transaction(function () use ($table, $data) {
            DB::table($table)->insert($data);
        });
    }

    /**
     * cleanup connection untuk clear prepared statements
     */
    private function cleanupConnection(): void
    {
        try {
            // commit pending transactions
            while (DB::transactionLevel() > 0) {
                DB::commit();
            }
            
            // untuk PostgreSQL: deallocate prepared statements
            if (DB::connection()->getDriverName() === 'pgsql') {
                try {
                    DB::statement("DEALLOCATE ALL");
                } catch (\Exception $e) {
                    // silent fail
                }
            }
            
        } catch (\Exception $e) {
            // silent fail, tidak perlu error untuk cleanup
        }
    }

    /**
     * get template institutions
     */
    private function getInstitutionTemplates(): array
    {
        return [
            [
                'type' => 'Pemerintah Desa',
                'names' => [
                    'Desa Sukamaju', 'Desa Mekar Sari', 'Desa Sukamakmur', 'Desa Cimanggis', 
                    'Desa Tanjung Raya', 'Desa Sidorejo', 'Desa Karanganyar', 'Desa Purworejo',
                    'Desa Banjarwangi', 'Desa Margahayu', 'Desa Cibitung', 'Desa Bojong Gede'
                ],
                'pic' => [
                    ['name' => 'Drs. Ahmad Hidayat', 'position' => 'Kepala Desa'],
                    ['name' => 'H. Budi Santoso, S.Sos', 'position' => 'Kepala Desa'],
                    ['name' => 'Drs. Cahyo Utomo', 'position' => 'Sekretaris Desa'],
                    ['name' => 'Ir. Dedi Mulyadi', 'position' => 'Kepala Desa'],
                ],
            ],
            [
                'type' => 'Dinas Kesehatan',
                'names' => [
                    'Dinas Kesehatan Kabupaten', 'Puskesmas Kecamatan', 
                    'Dinas Kesehatan Kota', 'Puskesmas Desa'
                ],
                'pic' => [
                    ['name' => 'dr. Endah Kusuma, M.Kes', 'position' => 'Kepala Dinas'],
                    ['name' => 'dr. Fitri Handayani, Sp.PK', 'position' => 'Kepala Puskesmas'],
                    ['name' => 'dr. Gunawan Wijaya, M.PH', 'position' => 'Kepala Dinas'],
                ],
            ],
            [
                'type' => 'Dinas Pendidikan',
                'names' => [
                    'Dinas Pendidikan Kabupaten', 'Dinas Pendidikan Kota',
                    'Dinas Pendidikan Provinsi'
                ],
                'pic' => [
                    ['name' => 'Prof. Dr. Hendra Pratama, M.Pd', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dr. Indah Permata Sari, M.Ed', 'position' => 'Sekretaris Dinas'],
                    ['name' => 'Drs. Joko Widodo, M.M', 'position' => 'Kepala Dinas'],
                ],
            ],
            [
                'type' => 'NGO',
                'names' => [
                    'Yayasan Cinta Alam', 'Lembaga Swadaya Masyarakat Peduli',
                    'Yayasan Pendidikan Bangsa', 'Forum Masyarakat Peduli Lingkungan'
                ],
                'pic' => [
                    ['name' => 'Ir. Kartika Dewi, M.Si', 'position' => 'Direktur Eksekutif'],
                    ['name' => 'Drs. Lukman Hakim, M.A', 'position' => 'Ketua Yayasan'],
                    ['name' => 'Dr. Maya Susanti, S.Sos', 'position' => 'Koordinator Program'],
                ],
            ],
            [
                'type' => 'Dinas Pertanian',
                'names' => [
                    'Dinas Pertanian Kabupaten', 'Dinas Ketahanan Pangan',
                    'Balai Penyuluhan Pertanian'
                ],
                'pic' => [
                    ['name' => 'Ir. Nanda Pratama, M.P', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dr. Oki Firmansyah, S.P., M.Si', 'position' => 'Kepala Balai'],
                ],
            ],
        ];
    }

    /**
     * get daftar universitas Indonesia
     */
    private function getUniversitiesData(): array
    {
        return [
            // PTN tier 1
            ['name' => 'Universitas Indonesia', 'code' => 'UI', 'province_hint' => 'DKI Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Bandung', 'code' => 'ITB', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Gadjah Mada', 'code' => 'UGM', 'province_hint' => 'DI Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'code' => 'ITS', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Airlangga', 'code' => 'UNAIR', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Padjadjaran', 'code' => 'UNPAD', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Diponegoro', 'code' => 'UNDIP', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Brawijaya', 'code' => 'UB', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Pertanian Bogor', 'code' => 'IPB', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Hasanuddin', 'code' => 'UNHAS', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            
            // PTN tier 2
            ['name' => 'Universitas Sebelas Maret', 'code' => 'UNS', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Sumatera Utara', 'code' => 'USU', 'province_hint' => 'Sumatera Utara', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Andalas', 'code' => 'UNAND', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Riau', 'code' => 'UNRI', 'province_hint' => 'Riau', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sriwijaya', 'code' => 'UNSRI', 'province_hint' => 'Sumatera Selatan', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Lampung', 'code' => 'UNILA', 'province_hint' => 'Lampung', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jenderal Soedirman', 'code' => 'UNSOED', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jember', 'code' => 'UNEJ', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Udayana', 'code' => 'UNUD', 'province_hint' => 'Bali', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Mataram', 'code' => 'UNRAM', 'province_hint' => 'Nusa Tenggara Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Nusa Cendana', 'code' => 'UNDANA', 'province_hint' => 'Nusa Tenggara Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Mulawarman', 'code' => 'UNMUL', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Lambung Mangkurat', 'code' => 'ULM', 'province_hint' => 'Kalimantan Selatan', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Tanjungpura', 'code' => 'UNTAN', 'province_hint' => 'Kalimantan Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Palangka Raya', 'code' => 'UPR', 'province_hint' => 'Kalimantan Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Tadulako', 'code' => 'UNTAD', 'province_hint' => 'Sulawesi Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Halu Oleo', 'code' => 'UHO', 'province_hint' => 'Sulawesi Tenggara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sam Ratulangi', 'code' => 'UNSRAT', 'province_hint' => 'Sulawesi Utara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Khairun', 'code' => 'UNKHAIR', 'province_hint' => 'Maluku Utara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Pattimura', 'code' => 'UNPATTI', 'province_hint' => 'Maluku', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Cenderawasih', 'code' => 'UNCEN', 'province_hint' => 'Papua', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Syiah Kuala', 'code' => 'UNSYIAH', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Bengkulu', 'code' => 'UNIB', 'province_hint' => 'Bengkulu', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jambi', 'code' => 'UNJA', 'province_hint' => 'Jambi', 'type' => 'negeri', 'accreditation' => 'B'],
            
            // UIN/IAIN
            ['name' => 'UIN Syarif Hidayatullah Jakarta', 'code' => 'UIN JKT', 'province_hint' => 'DKI Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Sunan Gunung Djati Bandung', 'code' => 'UIN SGD', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Walisongo Semarang', 'code' => 'UIN WALISONGO', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Sunan Kalijaga Yogyakarta', 'code' => 'UIN SUKA', 'province_hint' => 'DI Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Sunan Ampel Surabaya', 'code' => 'UIN SUNAN AMPEL', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Maulana Malik Ibrahim Malang', 'code' => 'UIN MALANG', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            
            // Politeknik
            ['name' => 'Politeknik Negeri Bandung', 'code' => 'POLBAN', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Elektronika Negeri Surabaya', 'code' => 'PENS', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Negeri Jakarta', 'code' => 'PNJ', 'province_hint' => 'DKI Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Negeri Semarang', 'code' => 'POLINES', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            
            // PTS Top
            ['name' => 'Universitas Bina Nusantara', 'code' => 'BINUS', 'province_hint' => 'DKI Jakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Telkom', 'code' => 'TEL-U', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Muhammadiyah Malang', 'code' => 'UMM', 'province_hint' => 'Jawa Timur', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Indonesia', 'code' => 'UII', 'province_hint' => 'DI Yogyakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Atma Jaya Yogyakarta', 'code' => 'UAJY', 'province_hint' => 'DI Yogyakarta', 'type' => 'swasta', 'accreditation' => 'A'],
        ];
    }

    /**
     * cari lokasi universitas berdasarkan province_hint
     */
    private function findUniversityLocation(?string $provinceHint, $allProvinces): ?array
    {
        if (!$provinceHint) {
            // random jika tidak ada hint
            $province = $allProvinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            return [
                'province_id' => $province->id,
                'regency_id' => $regency ? $regency->id : null,
            ];
        }
        
        // cari province berdasarkan hint (case insensitive, partial match)
        $province = $allProvinces->first(function($prov) use ($provinceHint) {
            return stripos($prov->name, $provinceHint) !== false;
        });
        
        if (!$province) {
            // fallback ke random
            $province = $allProvinces->random();
        }
        
        // ambil regency dari province
        $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
        
        return [
            'province_id' => $province->id,
            'regency_id' => $regency ? $regency->id : null,
        ];
    }

    /**
     * get email domain dari university code
     */
    private function getUniversityEmailDomain(string $code): string
    {
        $domains = [
            'UI' => 'ui.ac.id',
            'ITB' => 'itb.ac.id',
            'UGM' => 'ugm.ac.id',
            'ITS' => 'its.ac.id',
            'UNAIR' => 'unair.ac.id',
            'UNPAD' => 'unpad.ac.id',
            'UNDIP' => 'undip.ac.id',
            'UB' => 'ub.ac.id',
            'IPB' => 'ipb.ac.id',
            'UNHAS' => 'unhas.ac.id',
            'UNS' => 'uns.ac.id',
            'USU' => 'usu.ac.id',
            'UNAND' => 'unand.ac.id',
            'UNRI' => 'unri.ac.id',
            'UNSRI' => 'unsri.ac.id',
            'UNILA' => 'unila.ac.id',
            'UNSOED' => 'unsoed.ac.id',
            'UNEJ' => 'unej.ac.id',
            'UNUD' => 'unud.ac.id',
            'UNRAM' => 'unram.ac.id',
            'UNDANA' => 'undana.ac.id',
            'UNMUL' => 'unmul.ac.id',
            'ULM' => 'ulm.ac.id',
            'UNTAN' => 'untan.ac.id',
            'UPR' => 'upr.ac.id',
            'UNTAD' => 'untad.ac.id',
            'UHO' => 'uho.ac.id',
            'UNSRAT' => 'unsrat.ac.id',
            'UNKHAIR' => 'unkhair.ac.id',
            'UNPATTI' => 'unpatti.ac.id',
            'UNCEN' => 'uncen.ac.id',
            'UNSYIAH' => 'unsyiah.ac.id',
            'UNIB' => 'unib.ac.id',
            'UNJA' => 'unja.ac.id',
            'UIN JKT' => 'uinjkt.ac.id',
            'UIN SGD' => 'uinsgd.ac.id',
            'UIN WALISONGO' => 'walisongo.ac.id',
            'UIN SUKA' => 'uin-suka.ac.id',
            'UIN SUNAN AMPEL' => 'uinsby.ac.id',
            'UIN MALANG' => 'uin-malang.ac.id',
            'POLBAN' => 'polban.ac.id',
            'PENS' => 'pens.ac.id',
            'PNJ' => 'pnj.ac.id',
            'POLINES' => 'polines.ac.id',
            'BINUS' => 'binus.ac.id',
            'TEL-U' => 'telkomuniversity.ac.id',
            'UMM' => 'umm.ac.id',
            'UII' => 'uii.ac.id',
            'UAJY' => 'uajy.ac.id',
        ];
        
        return $domains[$code] ?? 'student.ac.id';
    }
}