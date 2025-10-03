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
        // CATATAN: provinces dan regencies TIDAK dihapus karena sudah di-seed oleh ProvincesRegenciesSeeder
        $this->clearOldData();

        // PERBAIKAN: hapus seeding provinces dan regencies karena sudah di-handle oleh ProvincesRegenciesSeeder
        // jika provinces/regencies belum ada, beri warning
        if (Province::count() === 0 || Regency::count() === 0) {
            $this->command->error('⚠️ Provinces atau Regencies belum di-seed!');
            $this->command->error('Jalankan ProvincesRegenciesSeeder terlebih dahulu.');
            return;
        }

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
        $this->command->info('🏛️ Instansi:');
        $this->command->info('   Username : desamakmur');
        $this->command->info('   Email    : desa.sukamaju@example.com');
        $this->command->info('   Password : password123');
        $this->command->info('');
        $this->command->info('==========================================');
    }

    /**
     * clear data lama untuk menghindari duplikasi
     * PERBAIKAN: TIDAK menghapus provinces dan regencies karena sudah di-seed oleh ProvincesRegenciesSeeder
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
        // PERBAIKAN: hapus DB::table('regencies')->delete() dan DB::table('provinces')->delete()
        DB::table('students')->delete();
        DB::table('institutions')->delete();
        DB::table('problems')->delete();
        DB::table('users')->delete();
        DB::table('universities')->delete();

        // enable foreign key checks kembali
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * seeding universities
     * PERBAIKAN: hanya gunakan province_id dan regency_id yang ADA di ProvincesRegenciesSeeder
     */
    private function seedUniversities(): void
    {
        $universities = [
            [
                'name' => 'Universitas Indonesia',
                'code' => 'UI',
                'province_id' => 31, // DKI Jakarta
                'regency_id' => 3174, // Kota Jakarta Barat
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'code' => 'ITB',
                'province_id' => 32, // Jawa Barat
                'regency_id' => 3273, // Kota Bandung
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'code' => 'UGM',
                'province_id' => 34, // DI Yogyakarta
                'regency_id' => 3471, // Kota Yogyakarta
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Padjadjaran',
                'code' => 'UNPAD',
                'province_id' => 32, // Jawa Barat
                'regency_id' => 3204, // Kab. Bandung
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Institut Pertanian Bogor',
                'code' => 'IPB',
                'province_id' => 32, // Jawa Barat
                'regency_id' => 3201, // Kab. Bogor
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Diponegoro',
                'code' => 'UNDIP',
                'province_id' => 33, // Jawa Tengah
                'regency_id' => 3374, // Kota Semarang
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Sebelas Maret',
                'code' => 'UNS',
                'province_id' => 33, // Jawa Tengah
                'regency_id' => 3372, // Kota Surakarta
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Pendidikan Indonesia',
                'code' => 'UPI',
                'province_id' => 32, // Jawa Barat
                'regency_id' => 3273, // Kota Bandung
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Islam Negeri Jakarta',
                'code' => 'UIN Jakarta',
                'province_id' => 31, // DKI Jakarta
                'regency_id' => 3173, // Jakarta Pusat
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Sultan Ageng Tirtayasa',
                'code' => 'UNTIRTA',
                'province_id' => 36, // Banten
                'regency_id' => 3673, // Kota Serang
                'type' => 'negeri',
                'accreditation' => 'B'
            ],
            [
                'name' => 'Politeknik Negeri Bandung',
                'code' => 'POLBAN',
                'province_id' => 32, // Jawa Barat
                'regency_id' => 3273, // Kota Bandung
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
            [
                'name' => 'Universitas Negeri Semarang',
                'code' => 'UNNES',
                'province_id' => 33, // Jawa Tengah
                'regency_id' => 3374, // Kota Semarang
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }
        
        $this->command->info('✓ ' . University::count() . ' universities berhasil dibuat');
    }

    /**
     * seeding students dengan data dummy yang bervariasi
     */
    private function seedStudents(): void
    {
        // data nama indonesia yang realistis
        $namaDepan = [
            'Budi', 'Andi', 'Siti', 'Ahmad', 'Dewi', 'Rizki', 'Fitri', 'Dimas', 
            'Ayu', 'Raka', 'Nia', 'Fajar', 'Maya', 'Eko', 'Putri', 'Arief',
            'Linda', 'Hendra', 'Ratna', 'Bambang', 'Sri', 'Wahyu', 'Indah', 'Joko',
            'Sari', 'Yudi', 'Tina', 'Galuh', 'Wawan', 'Rina'
        ];

        $namaBelakang = [
            'Santoso', 'Pratama', 'Wijaya', 'Permana', 'Kusuma', 'Saputra', 'Lestari',
            'Setiawan', 'Wibowo', 'Nugroho', 'Rahmawati', 'Hidayat', 'Kurniawan', 
            'Susanto', 'Purnomo', 'Handayani', 'Firmansyah', 'Safitri', 'Ramadhan',
            'Maharani', 'Prasetyo', 'Utami', 'Anwar', 'Yulianto'
        ];

        $jurusan = [
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Sipil', 'Teknik Elektro',
            'Manajemen', 'Akuntansi', 'Ilmu Komunikasi', 'Psikologi', 'Kedokteran',
            'Hukum', 'Ekonomi Pembangunan', 'Kesehatan Masyarakat', 'Farmasi',
            'Teknik Industri', 'Arsitektur', 'Sastra Inggris', 'Pendidikan',
            'Pertanian', 'Peternakan', 'Sosiologi', 'Teknik Mesin', 'Desain Grafis'
        ];

        $universities = University::all();

        if ($universities->isEmpty()) {
            $this->command->error('Tidak ada universities yang di-seed!');
            return;
        }

        // buat 30 mahasiswa dummy
        for ($i = 0; $i < 30; $i++) {
            $firstName = $namaDepan[array_rand($namaDepan)];
            $lastName = $namaBelakang[array_rand($namaBelakang)];
            $fullName = $firstName . ' ' . $lastName;
            
            // generate username dari nama
            $username = strtolower($firstName . $lastName);
            
            // generate email dengan domain universitas
            $university = $universities->random();
            
            // bersihkan spasi di code untuk email
            $cleanCode = str_replace(' ', '', strtolower($university->code));
            $emailDomain = $cleanCode . '.ac.id';
            
            // tambahkan suffix untuk uniqueness
            $usernameSuffix = $i > 0 ? $i : '';
            $emailSuffix = $i > 0 ? $i : '';
            
            $email = strtolower($firstName . '.' . $lastName . $emailSuffix) . '@' . $emailDomain;

            // buat user
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'username' => $username . $usernameSuffix,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // buat student profile
            Student::create([
                'user_id' => $user->id,
                'university_id' => $university->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'nim' => '2024' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'major' => $jurusan[array_rand($jurusan)],
                'semester' => rand(3, 8),
                'phone' => '08' . rand(1000000000, 9999999999),
                'bio' => 'Mahasiswa aktif yang tertarik dengan program KKN dan pengabdian masyarakat.',
                'skills' => json_encode(['Komunikasi', 'Teamwork', 'Problem Solving', 'Leadership']),
            ]);
        }

        $this->command->info('✓ ' . Student::count() . ' students berhasil dibuat');
    }

    /**
     * seeding institutions dengan data dummy yang realistis
     * PERBAIKAN: tambahkan field email yang required
     */
    private function seedInstitutions(): void
    {
        $namaInstansi = [
            'Desa Sukamaju', 'Desa Makmur Sejahtera', 'Desa Harapan', 'Desa Maju Bersama',
            'Kelurahan Cendana', 'Kelurahan Melati Indah', 'Kecamatan Raya Mandiri',
            'Dinas Kesehatan Kabupaten', 'Dinas Pendidikan Kota', 'Dinas Sosial',
            'Puskesmas Sehat Sentosa', 'Puskesmas Sejahtera', 'Puskesmas Makmur',
            'UPTD Lingkungan Hidup', 'UPTD Pertanian dan Peternakan',
            'Desa Sumber Rejeki', 'Kelurahan Harmoni', 'Puskesmas Citra Medika'
        ];

        $jenisInstansi = [
            'Pemerintah Desa', 'Kelurahan', 'Kecamatan', 'Dinas', 'Puskesmas', 'UPTD'
        ];

        // ambil provinces dengan regencies
        $provinces = Province::with('regencies')->whereIn('id', [31, 32, 33, 34, 36])->get();

        if ($provinces->isEmpty()) {
            $this->command->error('Tidak ada provinces yang di-seed!');
            return;
        }

        // buat 18 instansi dummy (sesuai jumlah namaInstansi)
        for ($i = 0; $i < count($namaInstansi); $i++) {
            $namaInst = $namaInstansi[$i];
            $jenis = $jenisInstansi[array_rand($jenisInstansi)];
            
            // pilih province dan regency secara acak
            $province = $provinces->random();
            $regency = $province->regencies->isNotEmpty() 
                ? $province->regencies->random() 
                : null;

            if (!$regency) {
                $this->command->warn("⚠️ Skip {$namaInst} - tidak ada regency untuk province {$province->name}");
                continue;
            }

            // generate username dan email
            $username = strtolower(str_replace(' ', '', $namaInst));
            $email = strtolower(str_replace(' ', '.', $namaInst)) . '@example.com';

            // tambahkan suffix untuk uniqueness
            $suffix = $i > 0 ? $i : '';

            // buat user
            $user = User::create([
                'name' => $namaInst,
                'email' => strtolower(str_replace(' ', '.', $namaInst)) . $suffix . '@example.com',
                'username' => $username . $suffix,
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // nama PIC acak
            $picNames = ['Budi Santoso', 'Andi Wijaya', 'Joko Susanto', 'Hendra Pratama', 'Siti Rahmawati', 'Dewi Lestari'];
            $picPositions = ['Kepala Desa', 'Lurah', 'Camat', 'Kepala Dinas', 'Kepala Puskesmas', 'Koordinator'];

            // PERBAIKAN: tambahkan field email yang required
            Institution::create([
                'user_id' => $user->id,
                'name' => $namaInst,
                'type' => $jenis,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'email' => $user->email, // PERBAIKAN: tambahkan email dari user
                'address' => 'Jl. Raya No. ' . rand(1, 100) . ', ' . $regency->name . ', ' . $province->name,
                'phone' => '0' . rand(21, 29) . rand(10000000, 99999999),
                'pic_name' => $picNames[array_rand($picNames)],
                'pic_position' => $picPositions[array_rand($picPositions)],
                'description' => 'Instansi yang berkomitmen untuk pengembangan masyarakat dan wilayah melalui program-program pemberdayaan.',
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        }

        $this->command->info('✓ ' . Institution::count() . ' institutions berhasil dibuat');
    }

    /**
     * seeding problems (proyek) dari instansi
     */
    private function seedProblems(): void
    {
        $judulProyek = [
            'Pemberdayaan Ekonomi Masyarakat',
            'Pengolahan Sampah Organik',
            'Literasi Digital untuk Anak',
            'Posyandu Balita Sehat',
            'Pertanian Organik Berkelanjutan',
            'Kampung Bersih dan Sehat',
            'Pelatihan UMKM Mandiri',
            'Sistem Irigasi Desa',
            'Pendidikan Anak Usia Dini',
            'Kesehatan Ibu dan Anak',
            'Bank Sampah Komunitas',
            'Wisata Desa Edukatif',
        ];

        $kategorSDG = [
            'Tanpa Kemiskinan', 'Tanpa Kelaparan', 'Kehidupan Sehat dan Sejahtera',
            'Pendidikan Berkualitas', 'Kesetaraan Gender', 'Air Bersih dan Sanitasi',
            'Energi Bersih dan Terjangkau', 'Pekerjaan Layak dan Pertumbuhan Ekonomi',
            'Industri, Inovasi dan Infrastruktur', 'Berkurangnya Kesenjangan',
            'Kota dan Komunitas Berkelanjutan', 'Konsumsi dan Produksi Bertanggung Jawab',
            'Penanganan Perubahan Iklim', 'Ekosistem Lautan', 'Ekosistem Daratan'
        ];

        $namaDesa = [
            'Desa Sukamaju', 'Desa Mekar Sari', 'Desa Cihideung', 'Desa Pasir Jaya',
            'Kelurahan Margahayu', 'Kelurahan Buah Batu', 'Desa Cimareme', 'Desa Ranca Bali'
        ];

        $institutions = Institution::all();

        if ($institutions->isEmpty()) {
            $this->command->error('Tidak ada institutions yang di-seed!');
            return;
        }

        // setiap instansi buat 1-2 problems
        foreach ($institutions as $institution) {
            $numProblems = rand(1, 2);

            for ($j = 0; $j < $numProblems; $j++) {
                $judul = $judulProyek[array_rand($judulProyek)];
                
                Problem::create([
                    'institution_id' => $institution->id,
                    'title' => $judul . ' - ' . $institution->name,
                    'description' => 'Program untuk meningkatkan kesejahteraan masyarakat melalui ' . strtolower($judul) . '. Kegiatan ini melibatkan partisipasi aktif masyarakat lokal dan bertujuan menciptakan dampak berkelanjutan.',
                    'background' => 'Kondisi masyarakat saat ini memerlukan intervensi untuk meningkatkan kualitas hidup dan pemberdayaan ekonomi. Berbagai tantangan seperti keterbatasan akses, rendahnya keterampilan, dan minimnya infrastruktur menjadi fokus utama program ini.',
                    'objectives' => 'Meningkatkan partisipasi masyarakat, menciptakan dampak positif, dan keberlanjutan program melalui pendampingan intensif dan transfer knowledge.',
                    'scope' => 'Meliputi kegiatan pelatihan, pendampingan, monitoring, evaluasi program, serta dokumentasi best practices untuk replikasi di wilayah lain.',
                    'province_id' => $institution->province_id,
                    'regency_id' => $institution->regency_id,
                    'village' => $namaDesa[array_rand($namaDesa)],
                    'detailed_location' => 'RT 02/RW 05, ' . $namaDesa[array_rand($namaDesa)],
                    'sdg_categories' => json_encode([$kategorSDG[array_rand($kategorSDG)], $kategorSDG[array_rand($kategorSDG)]]),
                    'required_students' => rand(3, 10),
                    'required_skills' => json_encode(['Komunikasi', 'Analisis Data', 'Perencanaan Program', 'Kerja Tim']),
                    'required_majors' => json_encode(['Ilmu Komunikasi', 'Kesehatan Masyarakat', 'Ekonomi Pembangunan', 'Sosiologi']),
                    'start_date' => now()->addDays(rand(100, 120)),
                    'end_date' => now()->addDays(rand(150, 210)),
                    'application_deadline' => now()->addDays(rand(30, 90)),
                    'duration_months' => [2, 3, 4, 6][array_rand([2, 3, 4, 6])],
                    'difficulty_level' => ['beginner', 'intermediate', 'advanced'][array_rand(['beginner', 'intermediate', 'advanced'])],
                    'status' => ['open', 'open', 'in_progress'][array_rand(['open', 'open', 'in_progress'])], // lebih banyak open
                    'expected_outcomes' => 'Peningkatan keterampilan masyarakat, peningkatan pendapatan rumah tangga, dan perubahan perilaku positif yang dapat diukur secara kuantitatif dan kualitatif.',
                    'deliverables' => json_encode(['Laporan Akhir', 'Dokumentasi Kegiatan', 'Modul Pelatihan', 'Video Dokumenter']),
                    'facilities_provided' => json_encode(['Akomodasi', 'Konsumsi 3x sehari', 'Transportasi lokal', 'Sertifikat', 'Uang saku']),
                    'views_count' => rand(10, 500),
                    'applications_count' => rand(0, 15),
                    'accepted_students' => 0,
                    'is_featured' => rand(0, 10) > 7, // 30% kemungkinan featured
                    'is_urgent' => rand(0, 10) > 8, // 20% kemungkinan urgent
                ]);
            }
        }

        $this->command->info('✓ ' . Problem::count() . ' problems berhasil dibuat');
    }
}