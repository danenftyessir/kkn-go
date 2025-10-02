<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\University;
use App\Models\User;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

/**
 * seeder untuk data dummy lengkap
 * termasuk banyak mahasiswa dan instansi untuk testing
 */
class DummyDataSeeder extends Seeder
{
    /**
     * jalankan database seeder
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding data dummy...');

        // seed provinces
        $this->command->info('Seeding provinces...');
        $this->seedProvinces();

        // seed regencies
        $this->command->info('Seeding regencies...');
        $this->seedRegencies();

        // seed universities
        $this->command->info('Seeding universities...');
        $this->seedUniversities();

        // seed students (banyak untuk testing)
        $this->command->info('Seeding students...');
        $this->seedStudents();

        // seed institutions
        $this->command->info('Seeding institutions...');
        $this->seedInstitutions();

        $this->command->info('✓ Semua data dummy berhasil dibuat!');
        $this->command->newLine();
        $this->command->info('=== Login Credentials ===');
        $this->command->info('Student 1: budi.santoso@ui.ac.id / password123');
        $this->command->info('Student 2: siti.nurhaliza@itb.ac.id / password123');
        $this->command->info('Institution 1: desa.sukamaju@example.com / password123');
        $this->command->info('Institution 2: dinkes.bandung@example.com / password123');
    }

    /**
     * seed provinces data
     */
    private function seedProvinces(): void
    {
        $provinces = [
            ['name' => 'Jawa Barat', 'code' => '32'],
            ['name' => 'DKI Jakarta', 'code' => '31'],
            ['name' => 'Jawa Tengah', 'code' => '33'],
            ['name' => 'Jawa Timur', 'code' => '35'],
            ['name' => 'DI Yogyakarta', 'code' => '34'],
            ['name' => 'Banten', 'code' => '36'],
            ['name' => 'Sumatera Utara', 'code' => '12'],
            ['name' => 'Sumatera Barat', 'code' => '13'],
            ['name' => 'Sumatera Selatan', 'code' => '16'],
            ['name' => 'Bali', 'code' => '51'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }
    }

    /**
     * seed regencies data
     */
    private function seedRegencies(): void
    {
        // Jawa Barat
        $jabarId = Province::where('code', '32')->first()->id;
        $regenciesJabar = [
            ['province_id' => $jabarId, 'name' => 'Kota Bandung', 'code' => '3273'],
            ['province_id' => $jabarId, 'name' => 'Kabupaten Bandung', 'code' => '3204'],
            ['province_id' => $jabarId, 'name' => 'Kota Cimahi', 'code' => '3277'],
            ['province_id' => $jabarId, 'name' => 'Kabupaten Garut', 'code' => '3205'],
            ['province_id' => $jabarId, 'name' => 'Kabupaten Sukabumi', 'code' => '3203'],
            ['province_id' => $jabarId, 'name' => 'Kota Bogor', 'code' => '3271'],
            ['province_id' => $jabarId, 'name' => 'Kabupaten Bogor', 'code' => '3201'],
        ];

        // DKI Jakarta
        $jakartaId = Province::where('code', '31')->first()->id;
        $regenciesJakarta = [
            ['province_id' => $jakartaId, 'name' => 'Jakarta Pusat', 'code' => '3171'],
            ['province_id' => $jakartaId, 'name' => 'Jakarta Selatan', 'code' => '3174'],
            ['province_id' => $jakartaId, 'name' => 'Jakarta Timur', 'code' => '3175'],
            ['province_id' => $jakartaId, 'name' => 'Jakarta Barat', 'code' => '3173'],
            ['province_id' => $jakartaId, 'name' => 'Jakarta Utara', 'code' => '3172'],
        ];

        // Jawa Tengah
        $jatengId = Province::where('code', '33')->first()->id;
        $regenciesJateng = [
            ['province_id' => $jatengId, 'name' => 'Kota Semarang', 'code' => '3374'],
            ['province_id' => $jatengId, 'name' => 'Kota Surakarta', 'code' => '3372'],
            ['province_id' => $jatengId, 'name' => 'Kabupaten Semarang', 'code' => '3322'],
        ];

        $allRegencies = array_merge($regenciesJabar, $regenciesJakarta, $regenciesJateng);

        foreach ($allRegencies as $regency) {
            Regency::create($regency);
        }
    }

    /**
     * seed universities data
     */
    private function seedUniversities(): void
    {
        $universities = [
            [
                'name' => 'Universitas Indonesia',
                'code' => 'UI',
                'province_id' => Province::where('code', '31')->first()->id,
                'regency_id' => Regency::where('code', '3174')->first()->id,
                'type' => 'negeri',
                'accreditation' => 'Unggul'
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'code' => 'ITB',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3273')->first()->id,
                'type' => 'negeri',
                'accreditation' => 'Unggul'
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'code' => 'UGM',
                'province_id' => Province::where('code', '34')->first()->id,
                'regency_id' => Regency::where('code', '3273')->first()->id, // dummy regency
                'type' => 'negeri',
                'accreditation' => 'Unggul'
            ],
            [
                'name' => 'Universitas Padjadjaran',
                'code' => 'UNPAD',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3273')->first()->id,
                'type' => 'negeri',
                'accreditation' => 'Unggul'
            ],
            [
                'name' => 'Institut Teknologi Sepuluh Nopember',
                'code' => 'ITS',
                'province_id' => Province::where('code', '35')->first()->id,
                'regency_id' => Regency::where('code', '3273')->first()->id, // dummy
                'type' => 'negeri',
                'accreditation' => 'Unggul'
            ],
            [
                'name' => 'Universitas Diponegoro',
                'code' => 'UNDIP',
                'province_id' => Province::where('code', '33')->first()->id,
                'regency_id' => Regency::where('code', '3374')->first()->id,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Telkom',
                'code' => 'TELKOM',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3273')->first()->id,
                'type' => 'swasta',
                'accreditation' => 'Unggul'
            ],
            [
                'name' => 'Universitas Bina Nusantara',
                'code' => 'BINUS',
                'province_id' => Province::where('code', '31')->first()->id,
                'regency_id' => Regency::where('code', '3173')->first()->id,
                'type' => 'swasta',
                'accreditation' => 'A'
            ],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }
    }

    /**
     * seed students data (banyak untuk testing)
     */
    private function seedStudents(): void
    {
        $firstNames = ['Budi', 'Siti', 'Ahmad', 'Dewi', 'Rizki', 'Maya', 'Andi', 'Nur', 'Fajar', 'Indah', 'Rudi', 'Lestari', 'Dian', 'Yoga', 'Ratna'];
        $lastNames = ['Santoso', 'Nurhaliza', 'Pratama', 'Lestari', 'Wijaya', 'Anggraini', 'Putra', 'Hidayat', 'Kusuma', 'Permata', 'Setiawan', 'Rahayu', 'Sari', 'Pradana', 'Maharani'];
        $majors = ['Teknik Informatika', 'Sistem Informasi', 'Ilmu Komunikasi', 'Manajemen', 'Akuntansi', 'Teknik Sipil', 'Kesehatan Masyarakat', 'Psikologi', 'Desain Komunikasi Visual', 'Teknik Elektro'];
        
        $universities = University::all();

        // buat 20 mahasiswa untuk testing
        for ($i = 1; $i <= 20; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $major = $majors[array_rand($majors)];
            $university = $universities->random();

            // generate email berdasarkan universitas
            $email = strtolower($firstName . '.' . $lastName . '@' . 
                    ($university->code === 'UI' ? 'ui.ac.id' : 
                    ($university->code === 'ITB' ? 'itb.ac.id' : 
                    ($university->code === 'UGM' ? 'ugm.ac.id' : 
                    'student.ac.id'))));

            $username = strtolower($firstName . '_' . $lastName . $i);

            // buat user
            $user = User::create([
                'name' => "{$firstName} {$lastName}",
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'email_verified_at' => now(),
            ]);

            // buat student profile
            Student::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'university_id' => $university->id,
                'major' => $major,
                'nim' => '12' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'semester' => rand(4, 8),
                'phone' => '08' . rand(1000000000, 9999999999),
                'bio' => "Mahasiswa {$major} di {$university->name} yang passionate tentang social impact dan community development.",
                'skills' => json_encode(['Komunikasi', 'Teamwork', 'Problem Solving', 'Analisis Data']),
                'portfolio_visible' => true,
            ]);
        }
    }

    /**
     * seed institutions data
     */
    private function seedInstitutions(): void
    {
        $institutions = [
            [
                'name' => 'Desa Sukamaju',
                'type' => 'Pemerintah Desa',
                'address' => 'Jl. Raya Desa Sukamaju No. 123, Garut',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3205')->first()->id,
                'pic_name' => 'Pak Eko Sugianto',
                'pic_position' => 'Kepala Desa',
            ],
            [
                'name' => 'Dinas Kesehatan Kota Bandung',
                'type' => 'Dinas',
                'address' => 'Jl. Soekarno Hatta No. 590, Bandung',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3273')->first()->id,
                'pic_name' => 'Dr. Siti Rahmawati',
                'pic_position' => 'Kepala Bidang Promosi Kesehatan',
            ],
            [
                'name' => 'Yayasan Peduli Lingkungan Indonesia',
                'type' => 'NGO',
                'address' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan',
                'province_id' => Province::where('code', '31')->first()->id,
                'regency_id' => Regency::where('code', '3174')->first()->id,
                'pic_name' => 'Ahmad Fauzi',
                'pic_position' => 'Program Manager',
            ],
            [
                'name' => 'Puskesmas Cimahi Utara',
                'type' => 'Puskesmas',
                'address' => 'Jl. Transyogi No. 12, Cimahi',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3277')->first()->id,
                'pic_name' => 'dr. Maya Kusuma',
                'pic_position' => 'Kepala Puskesmas',
            ],
            [
                'name' => 'Desa Mekarsari',
                'type' => 'Pemerintah Desa',
                'address' => 'Jl. Desa Mekarsari No. 88, Sukabumi',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3203')->first()->id,
                'pic_name' => 'Bapak Dedi Mulyadi',
                'pic_position' => 'Sekretaris Desa',
            ],
            [
                'name' => 'Dinas Pendidikan Kabupaten Bogor',
                'type' => 'Dinas',
                'address' => 'Jl. Tegar Beriman No. 1, Cibinong',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3201')->first()->id,
                'pic_name' => 'Drs. Bambang Suryono',
                'pic_position' => 'Kepala Dinas',
            ],
            [
                'name' => 'Komunitas Sahabat Petani Nusantara',
                'type' => 'NGO',
                'address' => 'Jl. Raya Cianjur No. 234, Cianjur',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3204')->first()->id,
                'pic_name' => 'Ibu Fitri Handayani',
                'pic_position' => 'Koordinator Program',
            ],
            [
                'name' => 'Desa Wisata Ciwidey',
                'type' => 'Pemerintah Desa',
                'address' => 'Jl. Raya Ciwidey No. 789, Bandung',
                'province_id' => Province::where('code', '32')->first()->id,
                'regency_id' => Regency::where('code', '3204')->first()->id,
                'pic_name' => 'Pak Asep Sutisna',
                'pic_position' => 'Kepala Desa',
            ],
        ];

        foreach ($institutions as $i => $instData) {
            // buat user untuk institution
            $email = strtolower(str_replace(' ', '.', $instData['name'])) . '@example.com';
            $username = 'inst_' . ($i + 1);

            $user = User::create([
                'name' => $instData['name'],
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'email_verified_at' => now(),
            ]);

            // buat institution profile
            Institution::create([
                'user_id' => $user->id,
                'name' => $instData['name'],
                'type' => $instData['type'],
                'address' => $instData['address'],
                'province_id' => $instData['province_id'],
                'regency_id' => $instData['regency_id'],
                'email' => $email,
                'phone' => '022' . rand(1000000, 9999999),
                'pic_name' => $instData['pic_name'],
                'pic_position' => $instData['pic_position'],
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        }
    }
}