<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use App\Models\Problem;
use App\Models\Application;
use App\Models\University;
use App\Models\Province;
use App\Models\Regency;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
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
        $firstStudent = Student::with('user')->first();
        if ($firstStudent) {
            echo "  email    : " . $firstStudent->user->email . "\n";
            echo "  username : " . $firstStudent->user->username . "\n";
        }
        echo "\n";
        echo "instansi pertama:\n";
        $firstInst = Institution::with('user')->first();
        if ($firstInst) {
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
        
        // untuk PostgreSQL gunakan TRUNCATE CASCADE atau disable triggers
        DB::statement('SET session_replication_role = replica');
        
        // truncate tables dalam urutan yang benar
        DB::table('applications')->truncate();
        DB::table('wishlists')->truncate();
        DB::table('problem_images')->truncate();
        DB::table('problems')->truncate();
        DB::table('students')->truncate();
        DB::table('institutions')->truncate();
        DB::table('users')->where('user_type', '!=', 'admin')->delete();
        DB::table('universities')->truncate();
        
        // enable kembali foreign key checks
        DB::statement('SET session_replication_role = DEFAULT');
    }

    /**
     * seeding universities (20 universities termasuk UIN Walisongo)
     * menggunakan HANYA regency_id yang sudah di-seed
     */
    private function seedUniversities(): void
    {
        echo "seeding universities...\n";
        
        $universities = [
            // universitas di jakarta (province 31)
            ['name' => 'universitas indonesia', 'code' => 'UI', 'province_id' => 31, 'regency_id' => 3174, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Syarif Hidayatullah Jakarta', 'code' => 'UIN JKT', 'province_id' => 31, 'regency_id' => 3175, 'type' => 'negeri', 'accreditation' => 'A'],
            // universitas di jawa barat (province 32)
            ['name' => 'institut teknologi bandung', 'code' => 'ITB', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas padjadjaran', 'code' => 'UNPAD', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'institut pertanian bogor', 'code' => 'IPB', 'province_id' => 32, 'regency_id' => 3201, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas pendidikan indonesia', 'code' => 'UPI', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas islam bandung', 'code' => 'UNISBA', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'swasta', 'accreditation' => 'B'],
            // universitas di jawa tengah (province 33)
            ['name' => 'universitas diponegoro', 'code' => 'UNDIP', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas negeri semarang', 'code' => 'UNNES', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'UIN Walisongo Semarang', 'code' => 'WALISONGO', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas sebelas maret', 'code' => 'UNS', 'province_id' => 33, 'regency_id' => 3372, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas jenderal soedirman', 'code' => 'UNSOED', 'province_id' => 33, 'regency_id' => 3302, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'universitas islam sultan agung', 'code' => 'UNISSULA', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'universitas muhammadiyah semarang', 'code' => 'UNIMUS', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'swasta', 'accreditation' => 'B'],
            // universitas di yogyakarta (province 34)
            ['name' => 'universitas gadjah mada', 'code' => 'UGM', 'province_id' => 34, 'regency_id' => 3471, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Sunan Kalijaga Yogyakarta', 'code' => 'UIN SUKA', 'province_id' => 34, 'regency_id' => 3471, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas negeri yogyakarta', 'code' => 'UNY', 'province_id' => 34, 'regency_id' => 3471, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas islam indonesia', 'code' => 'UII', 'province_id' => 34, 'regency_id' => 3471, 'type' => 'swasta', 'accreditation' => 'A'],
            // universitas di banten (province 36)
            ['name' => 'universitas sultan ageng tirtayasa', 'code' => 'UNTIRTA', 'province_id' => 36, 'regency_id' => 3674, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'universitas muhammadiyah tangerang', 'code' => 'UMT', 'province_id' => 36, 'regency_id' => 3671, 'type' => 'swasta', 'accreditation' => 'B'],
        ];

        foreach ($universities as $univ) {
            University::create($univ);
        }
        
        echo "  -> " . University::count() . " universities berhasil dibuat\n";
    }

    /**
     * seeding students dummy (30 students)
     */
    private function seedStudents(): void
    {
        echo "seeding students...\n";
        
        $firstNames = ['andi', 'budi', 'citra', 'devi', 'eko', 'farah', 'gilang', 'hana', 'indra', 'joko', 'kiki', 'lisa', 'maya', 'nanda', 'olivia', 'putra', 'qori', 'rina', 'sari', 'tika', 'umar', 'vera', 'wawan', 'xena', 'yudi', 'zara'];
        $lastNames = ['pratama', 'santoso', 'putri', 'wijaya', 'kusuma', 'permata', 'saputra', 'lestari', 'sari', 'nugroho'];
        $majors = ['teknik informatika', 'sistem informasi', 'teknik sipil', 'arsitektur', 'manajemen', 'akuntansi', 'ilmu komunikasi', 'psikologi', 'hukum', 'kedokteran', 'farmasi', 'agroteknologi'];
        
        $universities = University::all();
        
        for ($i = 0; $i < 30; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $university = $universities->random();
            $major = $majors[array_rand($majors)];
            $username = strtolower($firstName . $lastName . rand(1, 99));
            $nim = '210' . str_pad($i, 7, '0', STR_PAD_LEFT);
            
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $username . '@student.ac.id',
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'email_verified_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'university_id' => $university->id,
                'major' => $major,
                'nim' => $nim,
                'semester' => rand(4, 8),
                'phone' => '+628' . rand(1000000000, 9999999999),
            ]);
        }
        
        echo "  -> " . Student::count() . " students berhasil dibuat\n";
    }

    /**
     * seeding institutions dummy (15 institutions)
     */
    private function seedInstitutions(): void
    {
        echo "seeding institutions...\n";
        
        $institutionNames = [
            'dinas kesehatan', 'dinas pendidikan', 'dinas sosial', 'dinas pertanian',
            'pemerintah desa sukamaju', 'pemerintah desa makmur', 'pemerintah desa sejahtera',
            'puskesmas harapan', 'puskesmas sehat', 'LSM peduli lingkungan',
            'yayasan pendidikan nusantara', 'rumah sakit umum', 'dinas pariwisata',
            'perpustakaan daerah', 'balai penelitian'
        ];
        
        $types = ['dinas', 'pemerintah_desa', 'puskesmas', 'ngo', 'perguruan_tinggi'];
        $picNames = ['dr. ahmad', 'ibu siti', 'bapak joko', 'dr. maya', 'pak hendro', 'bu ani'];
        $positions = ['kepala dinas', 'kepala desa', 'direktur', 'sekretaris', 'koordinator'];
        
        $provinces = Province::all();
        
        foreach ($institutionNames as $index => $instName) {
            $province = $provinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            if (!$regency) continue;
            
            $type = $types[array_rand($types)];
            $username = strtolower(str_replace(' ', '', $instName)) . rand(1, 99);
            
            $user = User::create([
                'name' => $instName,
                'email' => $username . '@instansi.go.id',
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'email_verified_at' => now(),
            ]);

            Institution::create([
                'user_id' => $user->id,
                'name' => $instName,
                'type' => $type,
                'address' => 'jl. ' . $instName . ' no. ' . rand(1, 100),
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'email' => $username . '@instansi.go.id',
                'phone' => '+6221' . rand(1000000, 9999999),
                'pic_name' => $picNames[array_rand($picNames)],
                'pic_position' => $positions[array_rand($positions)],
                'is_verified' => rand(0, 10) > 2, // 80% verified
                'verified_at' => rand(0, 10) > 2 ? now() : null,
            ]);
        }
        
        echo "  -> " . Institution::count() . " institutions berhasil dibuat\n";
    }


}