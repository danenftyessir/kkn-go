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
 * 
 * path: database/seeders/DummyDataSeeder.php
 * jalankan: php artisan db:seed --class=DummyDataSeeder
 */
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
     * seeding universities (21 universities)
     */
    private function seedUniversities(): void
    {
        echo "seeding universities...\n";
        
        $universities = [
            // universitas di jakarta (province 31)
            ['name' => 'universitas indonesia', 'code' => 'UI', 'province_id' => 31, 'regency_id' => 3174, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Syarif Hidayatullah Jakarta', 'code' => 'UIN JKT', 'province_id' => 31, 'regency_id' => 3174, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas trisakti', 'code' => 'USAKTI', 'province_id' => 31, 'regency_id' => 3174, 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'universitas mercu buana', 'code' => 'UMB', 'province_id' => 31, 'regency_id' => 3174, 'type' => 'swasta', 'accreditation' => 'B'],
            
            // universitas di jawa barat (province 32)
            ['name' => 'institut teknologi bandung', 'code' => 'ITB', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas padjadjaran', 'code' => 'UNPAD', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Sunan Gunung Djati Bandung', 'code' => 'UIN SGD', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas pasundan', 'code' => 'UNPAS', 'province_id' => 32, 'regency_id' => 3273, 'type' => 'swasta', 'accreditation' => 'B'],
            
            // universitas di jawa tengah (province 33)
            ['name' => 'universitas diponegoro', 'code' => 'UNDIP', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'universitas negeri semarang', 'code' => 'UNNES', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'UIN Walisongo Semarang', 'code' => 'UIN WALISONGO', 'province_id' => 33, 'regency_id' => 3374, 'type' => 'negeri', 'accreditation' => 'A'],
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
     * seeding students dummy (60 students)
     */
    private function seedStudents(): void
    {
        echo "seeding students...\n";
        
        $firstNames = [
            'andi', 'budi', 'citra', 'devi', 'eko', 'farah', 'gilang', 'hana', 'indra', 'joko',
            'kiki', 'lisa', 'maya', 'nanda', 'olivia', 'putra', 'qori', 'rina', 'sari', 'tika',
            'umar', 'vera', 'wawan', 'xena', 'yudi', 'zara', 'adam', 'bella', 'candra', 'diana',
            'erlangga', 'fitri', 'galih', 'hesti', 'ilham', 'julia', 'kevin', 'laila', 'mukti', 'nisa',
            'oscar', 'pandu', 'qina', 'rafi', 'sinta', 'teguh', 'ulfa', 'vino', 'wulan', 'yusuf'
        ];
        
        $lastNames = [
            'pratama', 'santoso', 'putri', 'wijaya', 'kusuma', 'permata', 'saputra', 'lestari',
            'sari', 'nugroho', 'wibowo', 'rahayu', 'susanto', 'hartono', 'setiawan', 'anggraini'
        ];
        
        $majors = [
            'teknik informatika', 'sistem informasi', 'teknik sipil', 'arsitektur', 'manajemen',
            'akuntansi', 'ilmu komunikasi', 'psikologi', 'hukum', 'kedokteran', 'farmasi',
            'agroteknologi', 'ekonomi pembangunan', 'teknik elektro', 'desain grafis'
        ];
        
        $universities = University::all();
        
        // buat 60 mahasiswa
        for ($i = 0; $i < 60; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $university = $universities->random();
            $major = $majors[array_rand($majors)];
            $username = strtolower($firstName . $lastName . rand(1, 99));
            $nim = '210' . str_pad($i, 7, '0', STR_PAD_LEFT);
            
            // generate email berdasarkan kode universitas
            $emailDomain = $this->getUniversityEmailDomain($university->code);
            $email = $username . '@' . $emailDomain;
            
            // buat user - TIDAK ADA is_verified di sini
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // buat student
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
     * seeding institutions dummy (35 institutions)
     */
    private function seedInstitutions(): void
    {
        echo "seeding institutions...\n";
        
        $institutionNames = [
            // dinas pemerintah (10)
            'dinas kesehatan kabupaten semarang',
            'dinas pendidikan kota bandung',
            'dinas sosial kabupaten yogyakarta',
            'dinas pertanian kabupaten banyumas',
            'dinas pariwisata kota solo',
            'dinas lingkungan hidup kabupaten tangerang',
            'dinas pemberdayaan masyarakat kabupaten bogor',
            'dinas perikanan kabupaten pekalongan',
            'dinas perindustrian kota semarang',
            'dinas perhubungan kabupaten purwokerto',
            
            // pemerintah desa (10)
            'pemerintah desa sukamaju',
            'pemerintah desa makmur sejahtera',
            'pemerintah desa maju bersama',
            'pemerintah desa harapan jaya',
            'pemerintah desa cinta damai',
            'pemerintah desa sumber rejeki',
            'pemerintah desa karya mandiri',
            'pemerintah desa mekar sari',
            'pemerintah desa tani makmur',
            'pemerintah desa sumber waras',
            
            // puskesmas & kesehatan (5)
            'puskesmas harapan sehat',
            'puskesmas budi luhur',
            'puskesmas sejahtera',
            'rumah sakit umum daerah',
            'klinik pratama sejahtera',
            
            // LSM & yayasan (5)
            'LSM peduli lingkungan indonesia',
            'yayasan pendidikan nusantara',
            'yayasan sosial harapan bangsa',
            'LSM pemberdayaan perempuan',
            'yayasan anak bangsa',
            
            // lainnya (5)
            'perpustakaan daerah',
            'balai penelitian pertanian',
            'koperasi tani makmur',
            'sanggar seni budaya',
            'pusat kegiatan belajar masyarakat',
        ];
        
        $picNames = [
            'dr. ahmad suryanto', 'ibu siti nurhaliza', 'bapak joko widodo', 'dr. maya sari',
            'pak hendro susanto', 'bu ani lestari', 'dr. rina pratiwi', 'bapak agus santoso',
            'ibu fitri rahmawati', 'pak dedi setiawan', 'bu hana permata', 'dr. bambang wijaya'
        ];
        
        $positions = [
            'kepala dinas', 'kepala desa', 'direktur', 'sekretaris', 'koordinator program',
            'kepala puskesmas', 'manajer operasional', 'ketua yayasan', 'kepala bagian'
        ];
        
        $provinces = Province::all();
        
        foreach ($institutionNames as $index => $instName) {
            $province = $provinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            if (!$regency) continue;
            
            // tentukan tipe berdasarkan nama
            $type = 'lainnya';
            if (str_contains(strtolower($instName), 'dinas')) {
                $type = 'dinas';
            } elseif (str_contains(strtolower($instName), 'desa')) {
                $type = 'pemerintah_desa';
            } elseif (str_contains(strtolower($instName), 'puskesmas') || str_contains(strtolower($instName), 'rumah sakit')) {
                $type = 'puskesmas';
            } elseif (str_contains(strtolower($instName), 'lsm') || str_contains(strtolower($instName), 'yayasan')) {
                $type = 'ngo';
            }
            
            $username = strtolower(str_replace(' ', '', $instName)) . rand(1, 99);
            $email = $username . '@instansi.id';
            
            // buat user untuk instansi - HAPUS is_verified dari sini
            $user = User::create([
                'name' => ucwords($instName),
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // buat institution - is_verified HANYA di sini
            Institution::create([
                'user_id' => $user->id,
                'name' => ucwords($instName),
                'type' => $type,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'email' => $email, // email instansi ada di tabel institutions juga
                'address' => 'Jl. ' . ucwords($instName) . ' No. ' . rand(1, 100) . ', ' . $regency->name,
                'phone' => '+622' . rand(100000000, 999999999),
                'pic_name' => $picNames[array_rand($picNames)],
                'pic_position' => $positions[array_rand($positions)],
                'description' => 'Instansi yang bergerak di bidang ' . $type . ' dengan fokus pemberdayaan masyarakat.',
                'is_verified' => true, // is_verified ADA DI SINI (tabel institutions)
            ]);
        }
        
        echo "  -> " . Institution::count() . " institutions berhasil dibuat\n";
    }

    /**
     * dapatkan email domain berdasarkan kode universitas
     */
    private function getUniversityEmailDomain($code): string
    {
        return match(strtoupper($code)) {
            'UI' => 'ui.ac.id',
            'ITB' => 'itb.ac.id',
            'UGM' => 'ugm.ac.id',
            'UNDIP' => 'undip.ac.id',
            'UNPAD' => 'unpad.ac.id',
            'UNS' => 'uns.ac.id',
            'UNNES' => 'unnes.ac.id',
            'UNY' => 'uny.ac.id',
            'UNSOED' => 'unsoed.ac.id',
            'UNTIRTA' => 'untirta.ac.id',
            'UIN WALISONGO' => 'walisongo.ac.id',
            'UIN JKT' => 'uinjkt.ac.id',
            'UIN SGD' => 'uinsgd.ac.id',
            'UIN SUKA' => 'uin-suka.ac.id',
            'UII' => 'uii.ac.id',
            'UNISSULA' => 'unissula.ac.id',
            'UNIMUS' => 'unimus.ac.id',
            'UMT' => 'umt.ac.id',
            'USAKTI' => 'trisakti.ac.id',
            'UMB' => 'mercubuana.ac.id',
            'UNPAS' => 'unpas.ac.id',
            default => 'student.ac.id',
        };
    }
}