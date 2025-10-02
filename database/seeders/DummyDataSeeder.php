<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\University;
use App\Models\User;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Problem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * jalankan database seeder
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding data dummy...');

        // clear data lama untuk menghindari duplikasi
        $this->clearOldData();

        // seeding provinces
        $this->command->info('Seeding provinces...');
        $this->seedProvinces();

        // seeding regencies
        $this->command->info('Seeding regencies...');
        $this->seedRegencies();

        // seeding universities
        $this->command->info('Seeding universities...');
        $this->seedUniversities();

        // seeding students
        $this->command->info('Seeding students...');
        $this->seedStudents();

        // seeding institutions
        $this->command->info('Seeding institutions...');
        $this->seedInstitutions();

        // seeding problems
        $this->command->info('Seeding problems...');
        $this->seedProblems();

        $this->command->info('');
        $this->command->info('==========================================');
        $this->command->info('Dummy data berhasil dibuat!');
        $this->command->info('==========================================');
        $this->command->info('');
        $this->command->info('📚 Akun Testing:');
        $this->command->info('');
        $this->command->info('🎓 Mahasiswa:');
        $this->command->info('   Username : budisantoso');
        $this->command->info('   Email    : budi.santoso@ui.ac.id');
        $this->command->info('   Password : password123');
        $this->command->info('');
        $this->command->info('🏛️  Instansi:');
        $this->command->info('   Username : desamakmur');
        $this->command->info('   Email    : desa.sukamaju@example.com');
        $this->command->info('   Password : password123');
        $this->command->info('');
        $this->command->info('==========================================');
    }

    /**
     * clear data lama untuk menghindari duplikasi
     */
    private function clearOldData(): void
    {
        $this->command->info('Membersihkan data lama...');
        
        // disable foreign key checks untuk sqlite
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        // truncate tables dalam urutan yang benar
        DB::table('students')->delete();
        DB::table('institutions')->delete();
        DB::table('problems')->delete();
        DB::table('users')->delete();
        DB::table('universities')->delete();
        DB::table('regencies')->delete();
        DB::table('provinces')->delete();

        // enable foreign key checks kembali
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * seeding provinces
     */
    private function seedProvinces(): void
    {
        $provinces = [
            ['name' => 'Jawa Barat', 'code' => '32'],
            ['name' => 'DKI Jakarta', 'code' => '31'],
            ['name' => 'Jawa Tengah', 'code' => '33'],
            ['name' => 'Jawa Timur', 'code' => '35'],
            ['name' => 'Sumatera Selatan', 'code' => '16'],
            ['name' => 'Bali', 'code' => '51'],
            ['name' => 'Sulawesi Selatan', 'code' => '73'],
            ['name' => 'Sumatera Utara', 'code' => '12'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }
    }

    /**
     * seeding regencies
     */
    private function seedRegencies(): void
    {
        $regencies = [
            // jawa barat
            ['province_id' => 1, 'name' => 'Kota Bandung', 'code' => '3273'],
            ['province_id' => 1, 'name' => 'Kabupaten Bandung', 'code' => '3204'],
            ['province_id' => 1, 'name' => 'Kota Cimahi', 'code' => '3277'],
            ['province_id' => 1, 'name' => 'Kabupaten Garut', 'code' => '3205'],
            ['province_id' => 1, 'name' => 'Kabupaten Sukabumi', 'code' => '3203'],
            
            // dki jakarta
            ['province_id' => 2, 'name' => 'Jakarta Pusat', 'code' => '3171'],
            ['province_id' => 2, 'name' => 'Jakarta Selatan', 'code' => '3174'],
            ['province_id' => 2, 'name' => 'Jakarta Timur', 'code' => '3175'],
            
            // jawa tengah
            ['province_id' => 3, 'name' => 'Kota Semarang', 'code' => '3374'],
            ['province_id' => 3, 'name' => 'Kota Surakarta', 'code' => '3372'],
        ];

        foreach ($regencies as $regency) {
            Regency::create($regency);
        }
    }

    /**
     * seeding universities
     */
    private function seedUniversities(): void
    {
        $universities = [
            [
                'name' => 'Universitas Indonesia',
                'code' => 'UI',
                'province_id' => 2,
                'regency_id' => 6,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'code' => 'ITB',
                'province_id' => 1,
                'regency_id' => 1,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'code' => 'UGM',
                'province_id' => 3,
                'regency_id' => 9,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Padjadjaran',
                'code' => 'UNPAD',
                'province_id' => 1,
                'regency_id' => 1,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Institut Pertanian Bogor',
                'code' => 'IPB',
                'province_id' => 1,
                'regency_id' => 2,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }
    }

    /**
     * seeding students dengan data dummy yang bervariasi
     */
    private function seedStudents(): void
    {
        // data nama indonesia yang umum
        $namaDepan = [
            'Ahmad', 'Budi', 'Citra', 'Dian', 'Eka', 
            'Fajar', 'Gita', 'Hendra', 'Indah', 'Joko',
            'Kartika', 'Lina', 'Made', 'Nur', 'Oki',
            'Putri', 'Rama', 'Sari', 'Toni', 'Uni',
            'Vina', 'Wawan', 'Yanti', 'Zainal', 'Ani',
            'Bayu', 'Dewi', 'Eko', 'Fitri', 'Hadi'
        ];

        $namaBelakang = [
            'Santoso', 'Wijaya', 'Pratama', 'Kusuma', 'Putra',
            'Sari', 'Wati', 'Susanto', 'Permana', 'Hidayat',
            'Nugroho', 'Lestari', 'Setiawan', 'Rahma', 'Yusuf',
            'Hakim', 'Malik', 'Purnomo', 'Andika', 'Rizki',
            'Saputra', 'Saputri', 'Ramadan', 'Firmansyah', 'Utami'
        ];

        $jurusan = [
            'Teknik Informatika', 'Teknik Sipil', 'Teknik Elektro',
            'Manajemen', 'Akuntansi', 'Ilmu Komunikasi',
            'Psikologi', 'Hukum', 'Kedokteran',
            'Kesehatan Masyarakat', 'Gizi', 'Farmasi',
            'Arsitektur', 'Desain Komunikasi Visual', 'Seni Rupa'
        ];

        // buat 1 student untuk testing (sesuai dokumentasi)
        $testUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@ui.ac.id',
            'username' => 'budisantoso',
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $testUser->id,
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'university_id' => 1, // universitas indonesia
            'major' => 'Teknik Informatika',
            'nim' => '2106123456',
            'semester' => 6,
            'phone' => '081234567890',
        ]);

        // generate 50 students dummy dengan data yang unik
        for ($i = 0; $i < 50; $i++) {
            $depan = $namaDepan[array_rand($namaDepan)];
            $belakang = $namaBelakang[array_rand($namaBelakang)];
            $fullName = $depan . ' ' . $belakang;
            
            // buat username yang unik dengan angka random
            $username = strtolower($depan . '_' . $belakang . rand(100, 999));
            
            // buat email yang unik dengan timestamp dan random
            $emailPrefix = strtolower($depan . '.' . $belakang . rand(100, 999));
            $emailDomain = ['student.ac.id', 'mail.ac.id', 'std.ac.id', 'mahasiswa.ac.id'];
            $email = $emailPrefix . '@' . $emailDomain[array_rand($emailDomain)];

            try {
                $user = User::create([
                    'name' => $fullName,
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make('password123'),
                    'user_type' => 'student',
                    'email_verified_at' => now(),
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'first_name' => $depan,
                    'last_name' => $belakang,
                    'university_id' => rand(1, 5),
                    'major' => $jurusan[array_rand($jurusan)],
                    'nim' => '2' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) . rand(10, 99),
                    'semester' => rand(3, 8),
                    'phone' => '08' . rand(10, 99) . rand(10000000, 99999999),
                ]);
            } catch (\Exception $e) {
                // skip jika terjadi duplikasi
                continue;
            }
        }
    }

    /**
     * seeding institutions
     */
    private function seedInstitutions(): void
    {
        // buat user institution untuk testing (sesuai dokumentasi)
        $testUser = User::create([
            'name' => 'Desa Sukamaju',
            'email' => 'desa.sukamaju@example.com',
            'username' => 'desamakmur',
            'password' => Hash::make('password123'),
            'user_type' => 'institution',
            'email_verified_at' => now(),
        ]);

        $testInstitution = Institution::create([
            'user_id' => $testUser->id,
            'name' => 'Desa Sukamaju',
            'type' => 'Pemerintah Desa',
            'address' => 'Jl. Raya Desa No. 123, Kecamatan Jatinangor',
            'province_id' => 1,
            'regency_id' => 2,
            'email' => 'desa.sukamaju@example.com',
            'phone' => '0226789012',
            'pic_name' => 'Pak Lurah',
            'pic_position' => 'Kepala Desa',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // institutions dummy lainnya
        $institutions = [
            [
                'name' => 'Dinas Kesehatan Kota Bandung',
                'type' => 'Dinas Pemerintah',
                'email' => 'dinkes.bandung@gov.id',
                'username' => 'dinkes_bandung',
                'pic_name' => 'Dr. Siti Nurhaliza',
                'pic_position' => 'Kepala Dinas Kesehatan',
            ],
            [
                'name' => 'Puskesmas Sukahaji',
                'type' => 'Puskesmas',
                'email' => 'puskesmas.sukahaji@health.go.id',
                'username' => 'puskesmas_sukahaji',
                'pic_name' => 'dr. Ahmad Fauzi',
                'pic_position' => 'Kepala Puskesmas',
            ],
            [
                'name' => 'Desa Ciburial',
                'type' => 'Pemerintah Desa',
                'email' => 'desa.ciburial@village.id',
                'username' => 'desa_ciburial',
                'pic_name' => 'Bapak Udin',
                'pic_position' => 'Kepala Desa',
            ],
            [
                'name' => 'Kecamatan Cimahi Tengah',
                'type' => 'Kecamatan',
                'email' => 'kec.cimahitengah@gov.id',
                'username' => 'kec_cimahitengah',
                'pic_name' => 'Drs. Bambang Sutrisno',
                'pic_position' => 'Camat',
            ],
        ];

        foreach ($institutions as $index => $inst) {
            $user = User::create([
                'name' => $inst['name'],
                'email' => $inst['email'],
                'username' => $inst['username'],
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'email_verified_at' => now(),
            ]);

            Institution::create([
                'user_id' => $user->id,
                'name' => $inst['name'],
                'type' => $inst['type'],
                'address' => 'Jl. Contoh Alamat No. ' . ($index + 1) . ', Kota Bandung',
                'province_id' => 1,
                'regency_id' => 1,
                'email' => $inst['email'],
                'phone' => '022' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'pic_name' => $inst['pic_name'],
                'pic_position' => $inst['pic_position'],
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        }
    }

    /**
     * seeding problems
     */
    private function seedProblems(): void
    {
        $institutions = Institution::all();
        
        if ($institutions->isEmpty()) {
            $this->command->warn('Tidak ada institutions untuk membuat problems!');
            return;
        }

        $problems = [
            [
                'title' => 'Masalah Air Bersih Desa Jambi',
                'description' => 'Desa Jambi mengalami kesulitan akses air bersih. Dibutuhkan mahasiswa untuk membantu analisis kebutuhan dan merancang solusi sistem penyediaan air bersih yang berkelanjutan.',
                'background' => 'Desa Jambi terletak di dataran tinggi dengan sumber air yang terbatas. Mayoritas warga masih menggunakan air sungai yang kualitasnya kurang baik untuk kebutuhan sehari-hari.',
                'objectives' => 'Mengidentifikasi sumber air potensial, merancang sistem distribusi air bersih, dan memberikan edukasi kepada warga tentang pengelolaan air.',
                'scope' => 'Survei lapangan, analisis kualitas air, perancangan sistem, dan sosialisasi kepada masyarakat.',
                'province_id' => 1,
                'regency_id' => 1,
                'village' => 'Desa Jambi',
                'sdg_categories' => json_encode([6, 11, 13]),
                'required_students' => 8,
                'required_skills' => json_encode(['Teknik Sipil', 'Analisis Data', 'Komunikasi']),
                'start_date' => now()->addDays(30)->format('Y-m-d'),
                'end_date' => now()->addDays(120)->format('Y-m-d'),
                'application_deadline' => now()->addDays(20)->format('Y-m-d'),
                'duration_months' => 3,
                'difficulty_level' => 'intermediate',
                'status' => 'open',
                'facilities_provided' => json_encode(['Akomodasi', 'Transportasi', 'Konsumsi']),
                'is_featured' => true,
                'is_urgent' => false,
            ],
            [
                'title' => 'Analisa Kebutuhan Infrastruktur Pedesaan',
                'description' => 'Penelitian mendalam tentang kebutuhan infrastruktur di wilayah pedesaan Sumatera Selatan. Butuh 10 mahasiswa yang siap turun ke lapangan.',
                'background' => 'Wilayah ini membutuhkan kajian mendalam tentang kebutuhan infrastruktur untuk meningkatkan kesejahteraan masyarakat.',
                'province_id' => 5,
                'regency_id' => 1,
                'village' => 'Desa Makmur',
                'sdg_categories' => json_encode([9, 11]),
                'required_students' => 10,
                'required_skills' => json_encode(['Riset', 'Analisis Data', 'Presentasi']),
                'start_date' => now()->addDays(45)->format('Y-m-d'),
                'end_date' => now()->addDays(225)->format('Y-m-d'),
                'application_deadline' => now()->addDays(30)->format('Y-m-d'),
                'duration_months' => 6,
                'difficulty_level' => 'advanced',
                'status' => 'open',
                'facilities_provided' => json_encode(['Akomodasi', 'Transportasi']),
                'is_featured' => false,
                'is_urgent' => false,
            ],
            [
                'title' => 'Program Edukasi Kesehatan Masyarakat',
                'description' => 'Membantu puskesmas dalam menjalankan program edukasi kesehatan untuk masyarakat pedesaan.',
                'province_id' => 1,
                'regency_id' => 2,
                'sdg_categories' => json_encode([3, 4]),
                'required_students' => 5,
                'required_skills' => json_encode(['Komunikasi', 'Public Speaking', 'Desain Grafis']),
                'start_date' => now()->addDays(20)->format('Y-m-d'),
                'end_date' => now()->addDays(80)->format('Y-m-d'),
                'application_deadline' => now()->addDays(15)->format('Y-m-d'),
                'duration_months' => 2,
                'difficulty_level' => 'beginner',
                'status' => 'open',
                'facilities_provided' => json_encode(['Konsumsi', 'Sertifikat']),
                'is_featured' => true,
                'is_urgent' => true,
            ],
            [
                'title' => 'Digitalisasi UMKM Desa',
                'description' => 'Membantu pelaku UMKM desa untuk go digital dengan membuat website dan optimasi social media.',
                'province_id' => 1,
                'regency_id' => 3,
                'sdg_categories' => json_encode([8, 9, 11]),
                'required_students' => 6,
                'required_skills' => json_encode(['Web Development', 'Digital Marketing', 'Fotografi']),
                'start_date' => now()->addDays(60)->format('Y-m-d'),
                'end_date' => now()->addDays(180)->format('Y-m-d'),
                'application_deadline' => now()->addDays(45)->format('Y-m-d'),
                'duration_months' => 4,
                'difficulty_level' => 'advanced',
                'status' => 'open',
                'facilities_provided' => json_encode(['Akomodasi', 'Konsumsi', 'Laptop']),
                'is_featured' => false,
                'is_urgent' => false,
            ],
            [
                'title' => 'Pemberdayaan Petani Melalui Teknologi',
                'description' => 'Implementasi teknologi pertanian modern untuk meningkatkan produktivitas petani lokal.',
                'province_id' => 1,
                'regency_id' => 4,
                'sdg_categories' => json_encode([2, 8, 9]),
                'required_students' => 7,
                'required_skills' => json_encode(['Pertanian', 'IoT', 'Penyuluhan']),
                'start_date' => now()->addDays(90)->format('Y-m-d'),
                'end_date' => now()->addDays(210)->format('Y-m-d'),
                'application_deadline' => now()->addDays(60)->format('Y-m-d'),
                'duration_months' => 4,
                'difficulty_level' => 'intermediate',
                'status' => 'open',
                'facilities_provided' => json_encode(['Akomodasi', 'Transportasi', 'Peralatan']),
                'is_featured' => true,
                'is_urgent' => false,
            ],
        ];

        foreach ($problems as $problemData) {
            // assign problem ke institution secara random
            $institution = $institutions->random();
            
            Problem::create(array_merge($problemData, [
                'institution_id' => $institution->id,
            ]));
        }
    }
}