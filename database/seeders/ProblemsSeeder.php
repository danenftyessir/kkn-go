<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\University;
use App\Models\Institution;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProblemsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        // seeder untuk provinces (sample data)
        $this->seedProvinces();
        
        // seeder untuk universities (sample data)
        $this->seedUniversities();
        
        // seeder untuk institutions (sample data)
        $this->seedInstitutions();
        
        // seeder untuk problems (sample data)
        $this->seedProblems();
    }

    /**
     * seed provinces data
     */
    private function seedProvinces(): void
    {
        $provinces = [
            ['code' => '11', 'name' => 'Aceh'],
            ['code' => '12', 'name' => 'Sumatera Utara'],
            ['code' => '13', 'name' => 'Sumatera Barat'],
            ['code' => '14', 'name' => 'Riau'],
            ['code' => '15', 'name' => 'Jambi'],
            ['code' => '16', 'name' => 'Sumatera Selatan'],
            ['code' => '17', 'name' => 'Bengkulu'],
            ['code' => '18', 'name' => 'Lampung'],
            ['code' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['code' => '21', 'name' => 'Kepulauan Riau'],
            ['code' => '31', 'name' => 'DKI Jakarta'],
            ['code' => '32', 'name' => 'Jawa Barat'],
            ['code' => '33', 'name' => 'Jawa Tengah'],
            ['code' => '34', 'name' => 'DI Yogyakarta'],
            ['code' => '35', 'name' => 'Jawa Timur'],
            ['code' => '36', 'name' => 'Banten'],
            ['code' => '51', 'name' => 'Bali'],
            ['code' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['code' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['code' => '61', 'name' => 'Kalimantan Barat'],
            ['code' => '62', 'name' => 'Kalimantan Tengah'],
            ['code' => '63', 'name' => 'Kalimantan Selatan'],
            ['code' => '64', 'name' => 'Kalimantan Timur'],
            ['code' => '65', 'name' => 'Kalimantan Utara'],
            ['code' => '71', 'name' => 'Sulawesi Utara'],
            ['code' => '72', 'name' => 'Sulawesi Tengah'],
            ['code' => '73', 'name' => 'Sulawesi Selatan'],
            ['code' => '74', 'name' => 'Sulawesi Tenggara'],
            ['code' => '75', 'name' => 'Gorontalo'],
            ['code' => '76', 'name' => 'Sulawesi Barat'],
            ['code' => '81', 'name' => 'Maluku'],
            ['code' => '82', 'name' => 'Maluku Utara'],
            ['code' => '91', 'name' => 'Papua'],
            ['code' => '92', 'name' => 'Papua Barat'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }

        // seeder regencies untuk jawa barat sebagai contoh
        $jabarId = Province::where('code', '32')->first()->id;
        $regencies = [
            ['province_id' => $jabarId, 'code' => '3201', 'name' => 'Kabupaten Bogor'],
            ['province_id' => $jabarId, 'code' => '3202', 'name' => 'Kabupaten Sukabumi'],
            ['province_id' => $jabarId, 'code' => '3203', 'name' => 'Kabupaten Cianjur'],
            ['province_id' => $jabarId, 'code' => '3204', 'name' => 'Kabupaten Bandung'],
            ['province_id' => $jabarId, 'code' => '3205', 'name' => 'Kabupaten Garut'],
            ['province_id' => $jabarId, 'code' => '3206', 'name' => 'Kabupaten Tasikmalaya'],
            ['province_id' => $jabarId, 'code' => '3207', 'name' => 'Kabupaten Ciamis'],
            ['province_id' => $jabarId, 'code' => '3271', 'name' => 'Kota Bogor'],
            ['province_id' => $jabarId, 'code' => '3273', 'name' => 'Kota Bandung'],
            ['province_id' => $jabarId, 'code' => '3277', 'name' => 'Kota Cimahi'],
        ];

        foreach ($regencies as $regency) {
            Regency::create($regency);
        }
    }

    /**
     * seed universities data
     */
    private function seedUniversities(): void
    {
        $jabarId = Province::where('code', '32')->first()->id;
        $bandungId = Regency::where('code', '3273')->first()->id;
        
        $universities = [
            [
                'code' => 'ITB',
                'name' => 'Institut Teknologi Bandung',
                'province_id' => $jabarId,
                'regency_id' => $bandungId,
                'type' => 'negeri',
                'accreditation' => 'Unggul',
                'website' => 'https://www.itb.ac.id',
            ],
            [
                'code' => 'UNPAD',
                'name' => 'Universitas Padjadjaran',
                'province_id' => $jabarId,
                'regency_id' => $bandungId,
                'type' => 'negeri',
                'accreditation' => 'Unggul',
                'website' => 'https://www.unpad.ac.id',
            ],
            [
                'code' => 'UPI',
                'name' => 'Universitas Pendidikan Indonesia',
                'province_id' => $jabarId,
                'regency_id' => $bandungId,
                'type' => 'negeri',
                'accreditation' => 'A',
                'website' => 'https://www.upi.edu',
            ],
            [
                'code' => 'TELKOM',
                'name' => 'Universitas Telkom',
                'province_id' => $jabarId,
                'regency_id' => $bandungId,
                'type' => 'swasta',
                'accreditation' => 'Unggul',
                'website' => 'https://www.telkomuniversity.ac.id',
            ],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }
    }

    /**
     * seed institutions data
     */
    private function seedInstitutions(): void
    {
        $jabarId = Province::where('code', '32')->first()->id;
        $bandungId = Regency::where('code', '3273')->first()->id;

        // buat user untuk institution
        $users = [
            [
                'name' => 'Desa Sukamaju',
                'email' => 'desa.sukamaju@example.com',
                'user_type' => 'institution',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dinas Kesehatan Kota Bandung',
                'email' => 'dinkes.bandung@example.com',
                'user_type' => 'institution',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Puskesmas Sukahaji',
                'email' => 'puskesmas.sukahaji@example.com',
                'user_type' => 'institution',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $index => $userData) {
            $user = User::create($userData);
            
            // buat institution profile
            $institutionTypes = ['Pemerintah Desa', 'Dinas Pemerintah', 'Puskesmas'];
            Institution::create([
                'user_id' => $user->id,
                'name' => $userData['name'],
                'type' => $institutionTypes[$index],
                'address' => 'Jl. Contoh No. ' . ($index + 1),
                'province_id' => $jabarId,
                'regency_id' => $bandungId,
                'email' => $userData['email'],
                'phone' => '022123456' . $index,
                'pic_name' => 'Budi Santoso',
                'pic_position' => 'Kepala ' . $institutionTypes[$index],
                'is_verified' => true,
                'verified_at' => now(),
                'description' => 'Deskripsi singkat tentang ' . $userData['name'],
            ]);
        }
    }

    /**
     * seed problems data
     */
    private function seedProblems(): void
    {
        $institutions = Institution::all();
        $jabarId = Province::where('code', '32')->first()->id;
        $regencies = Regency::where('province_id', $jabarId)->get();

        $problemsData = [
            [
                'title' => 'Masalah Air Bersih Desa Jambi',
                'description' => 'Desa Jambi mengalami kesulitan akses air bersih. Warga harus berjalan jauh untuk mendapatkan air. Kami membutuhkan mahasiswa untuk membantu merancang sistem distribusi air yang efisien.',
                'background' => 'Desa Jambi terletak di daerah perbukitan dengan akses air terbatas. Sumber air terdekat berjarak 5 km dari pemukiman.',
                'objectives' => 'Merancang dan mengimplementasikan sistem distribusi air bersih yang berkelanjutan untuk 100 keluarga.',
                'scope' => 'Survei lapangan, analisis kebutuhan air, desain sistem distribusi, implementasi pilot project.',
                'sdg_categories' => [6, 11, 13],
                'required_students' => 10,
                'required_skills' => ['Teknik Sipil', 'Analisis Data', 'Komunikasi'],
                'required_majors' => ['Teknik Lingkungan', 'Teknik Sipil', 'Kesehatan Masyarakat'],
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(90),
                'application_deadline' => now()->addDays(20),
                'duration_months' => 2,
                'difficulty_level' => 'intermediate',
                'status' => 'open',
                'expected_outcomes' => 'Sistem distribusi air bersih yang berfungsi dan laporan teknis lengkap.',
                'deliverables' => ['Laporan survey', 'Desain sistem', 'Dokumentasi implementasi', 'Laporan akhir'],
                'facilities_provided' => ['Akomodasi', 'Konsumsi', 'Transportasi lokal', 'Perlengkapan survey'],
                'is_featured' => true,
                'is_urgent' => false,
            ],
            [
                'title' => 'Analisa Kebutuhan Sumatra Selatan',
                'description' => 'Penelitian tentang kebutuhan masyarakat di wilayah Sumatra Selatan untuk program kesehatan. Butuh 10 mahasiswa yang siap turun ke lapangan.',
                'background' => 'Wilayah ini memerlukan data komprehensif tentang kondisi kesehatan masyarakat.',
                'sdg_categories' => [3, 11],
                'required_students' => 10,
                'required_skills' => ['Penelitian', 'Analisis Data', 'Survei Lapangan'],
                'required_majors' => ['Kesehatan Masyarakat', 'Statistika', 'Teknik Industri'],
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(7),
                'application_deadline' => now()->addDays(15),
                'duration_months' => 6,
                'difficulty_level' => 'intermediate',
                'status' => 'open',
                'expected_outcomes' => 'Laporan analisa kebutuhan kesehatan masyarakat yang komprehensif.',
                'deliverables' => ['Data survey', 'Laporan analisis', 'Rekomendasi program'],
                'facilities_provided' => ['Akomodasi', 'Konsumsi', 'Transportasi'],
                'is_featured' => false,
                'is_urgent' => true,
            ],
            [
                'title' => 'Program Edukasi Kesehatan Ibu dan Anak',
                'description' => 'Puskesmas Sukahaji membutuhkan mahasiswa untuk membantu program edukasi kesehatan ibu dan anak di wilayah kerja puskesmas.',
                'background' => 'Angka kesehatan ibu dan anak masih perlu ditingkatkan melalui program edukasi yang terstruktur.',
                'objectives' => 'Meningkatkan pengetahuan ibu-ibu tentang kesehatan anak dan gizi balita.',
                'scope' => 'Penyuluhan di posyandu, kunjungan rumah, pembuatan media edukasi.',
                'sdg_categories' => [3, 4, 5],
                'required_students' => 5,
                'required_skills' => ['Komunikasi', 'Public Speaking', 'Desain Grafis'],
                'required_majors' => ['Kesehatan Masyarakat', 'Gizi', 'Keperawatan', 'Desain Komunikasi Visual'],
                'start_date' => now()->addDays(45),
                'end_date' => now()->addDays(105),
                'application_deadline' => now()->addDays(30),
                'duration_months' => 2,
                'difficulty_level' => 'beginner',
                'status' => 'open',
                'expected_outcomes' => 'Peningkatan pengetahuan ibu-ibu minimal 50% berdasarkan pre-post test.',
                'deliverables' => ['Modul edukasi', 'Media penyuluhan', 'Laporan kegiatan', 'Dokumentasi'],
                'facilities_provided' => ['Transport', 'Konsumsi', 'Pelatihan awal', 'Sertifikat'],
                'is_featured' => true,
                'is_urgent' => false,
            ],
            [
                'title' => 'Digitalisasi UMKM Desa',
                'description' => 'Membantu UMKM di desa untuk go digital dengan membuat website dan social media marketing.',
                'sdg_categories' => [8, 9, 11],
                'required_students' => 8,
                'required_skills' => ['Web Development', 'Digital Marketing', 'Fotografi', 'Videografi'],
                'required_majors' => ['Informatika', 'Sistem Informasi', 'Manajemen', 'DKV'],
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(6),
                'application_deadline' => now()->addMonth(),
                'duration_months' => 4,
                'difficulty_level' => 'advanced',
                'status' => 'open',
                'expected_outcomes' => 'Minimal 20 UMKM memiliki platform digital dan meningkat omzetnya.',
                'deliverables' => ['Website untuk UMKM', 'Konten digital', 'Laporan implementasi'],
                'facilities_provided' => ['Akomodasi', 'Konsumsi', 'Peralatan dokumentasi'],
                'is_featured' => false,
                'is_urgent' => false,
            ],
            [
                'title' => 'Bank Sampah dan Pengelolaan Limbah',
                'description' => 'Membangun sistem bank sampah dan edukasi pengelolaan limbah untuk masyarakat desa.',
                'sdg_categories' => [11, 12, 13],
                'required_students' => 6,
                'required_skills' => ['Manajemen', 'Komunikasi', 'Lingkungan'],
                'required_majors' => ['Teknik Lingkungan', 'Manajemen', 'Kesehatan Masyarakat'],
                'start_date' => now()->addDays(60),
                'end_date' => now()->addDays(150),
                'application_deadline' => now()->addDays(40),
                'duration_months' => 3,
                'difficulty_level' => 'intermediate',
                'status' => 'open',
                'expected_outcomes' => 'Bank sampah yang operasional dan masyarakat yang sadar lingkungan.',
                'deliverables' => ['SOP bank sampah', 'Material edukasi', 'Laporan implementasi'],
                'facilities_provided' => ['Akomodasi', 'Konsumsi', 'Pelatihan'],
                'is_featured' => false,
                'is_urgent' => true,
            ],
        ];

        foreach ($problemsData as $index => $data) {
            // assign ke institution secara random
            $institution = $institutions->random();
            $regency = $regencies->random();

            Problem::create(array_merge($data, [
                'institution_id' => $institution->id,
                'province_id' => $jabarId,
                'regency_id' => $regency->id,
                'village' => 'Desa Contoh ' . ($index + 1),
                'detailed_location' => 'Detail lokasi akan diinformasikan setelah diterima',
                'views_count' => rand(50, 500),
                'applications_count' => rand(5, 30),
                'accepted_students' => 0,
            ]));
        }
    }
}