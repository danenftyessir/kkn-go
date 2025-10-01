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

class DummyDataSeeder extends Seeder
{
    /**
     * jalankan database seeder
     */
    public function run(): void
    {
        // provinces
        $provinces = [
            ['name' => 'Jawa Barat', 'code' => '32'],
            ['name' => 'DKI Jakarta', 'code' => '31'],
            ['name' => 'Jawa Tengah', 'code' => '33'],
            ['name' => 'Jawa Timur', 'code' => '35'],
            ['name' => 'Sumatera Selatan', 'code' => '16'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }

        // regencies untuk Jawa Barat
        $regencies = [
            ['province_id' => 1, 'name' => 'Kota Bandung', 'code' => '3273'],
            ['province_id' => 1, 'name' => 'Kabupaten Bandung', 'code' => '3204'],
            ['province_id' => 1, 'name' => 'Kota Cimahi', 'code' => '3277'],
            ['province_id' => 1, 'name' => 'Kabupaten Garut', 'code' => '3205'],
            ['province_id' => 1, 'name' => 'Kabupaten Sukabumi', 'code' => '3203'],
        ];

        foreach ($regencies as $regency) {
            Regency::create($regency);
        }

        // universities
        $universities = [
            [
                'name' => 'Universitas Indonesia',
                'code' => 'UI',
                'province_id' => 2,
                'regency_id' => 1,
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
                'regency_id' => 1,
                'type' => 'negeri',
                'accreditation' => 'A'
            ],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }

        // user student untuk testing
        $studentUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'student@test.com',
            'username' => 'student_test',
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'university_id' => 1,
            'major' => 'Teknik Informatika',
            'nim' => '1234567890',
            'semester' => 6,
            'phone' => '081234567890',
        ]);

        // user institution untuk testing
        $institutionUser = User::create([
            'name' => 'Desa Sukamaju',
            'email' => 'institution@test.com',
            'username' => 'institution_test',
            'password' => Hash::make('password123'),
            'user_type' => 'institution',
            'email_verified_at' => now(),
        ]);

        $institution = Institution::create([
            'user_id' => $institutionUser->id,
            'name' => 'Desa Sukamaju',
            'type' => 'Pemerintah Desa',
            'address' => 'Jl. Raya Desa No. 123',
            'province_id' => 1,
            'regency_id' => 1,
            'email' => 'desasukamaju@example.com',
            'phone' => '0226789012',
            'pic_name' => 'Pak Lurah',
            'pic_position' => 'Kepala Desa',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // dummy problems
        $problems = [
            [
                'institution_id' => $institution->id,
                'title' => 'Masalah Air Bersih Desa Jambi',
                'description' => 'Desa Jambi mengalami kesulitan akses air bersih. Dibutuhkan mahasiswa untuk membantu analisis kebutuhan dan merancang solusi sistem penyediaan air bersih yang berkelanjutan.',
                'background' => 'Desa Jambi terletak di dataran tinggi dengan sumber air yang terbatas. Mayoritas warga masih menggunakan air sungai yang kualitasnya kurang baik untuk kebutuhan sehari-hari.',
                'objectives' => 'Mengidentifikasi sumber air potensial, merancang sistem distribusi air bersih, dan memberikan edukasi kepada warga tentang pengelolaan air.',
                'scope' => 'Survei lapangan, analisis kualitas air, perancangan sistem, dan sosialisasi kepada masyarakat.',
                'province_id' => 1,
                'regency_id' => 4,
                'village' => 'Desa Jambi',
                'detailed_location' => 'Kecamatan Samarang',
                'sdg_categories' => json_encode([6, 11, 13]),
                'required_students' => 10,
                'required_skills' => json_encode(['Analisis Data', 'Survey Lapangan', 'Teknik Sipil', 'Komunikasi']),
                'required_majors' => json_encode(['Teknik Sipil', 'Teknik Lingkungan', 'Kesehatan Masyarakat']),
                'start_date' => '2025-11-05',
                'end_date' => '2026-02-05',
                'application_deadline' => '2025-10-20',
                'duration_months' => 3,
                'difficulty_level' => 'intermediate',
                'status' => 'open',
                'expected_outcomes' => 'Laporan analisis kebutuhan air, desain sistem distribusi, dan dokumentasi sosialisasi.',
                'deliverables' => json_encode(['Laporan Survei', 'Desain Teknis', 'Proposal Anggaran', 'Dokumentasi Kegiatan']),
                'facilities_provided' => json_encode(['Akomodasi', 'Konsumsi', 'Transportasi Lokal', 'Sertifikat']),
                'is_featured' => true,
                'is_urgent' => true,
            ],
            [
                'institution_id' => $institution->id,
                'title' => 'Analisis Kebutuhan Sumatera Selatan',
                'description' => 'Proyek analisis kebutuhan pembangunan infrastruktur di wilayah Sumatera Selatan untuk mendukung pertumbuhan ekonomi lokal.',
                'background' => 'Wilayah ini membutuhkan kajian mendalam tentang kebutuhan infrastruktur untuk meningkatkan kesejahteraan masyarakat.',
                'province_id' => 5,
                'regency_id' => 1,
                'village' => null,
                'sdg_categories' => json_encode([9, 11]),
                'required_students' => 6,
                'required_skills' => json_encode(['Riset', 'Analisis Data', 'Presentasi']),
                'start_date' => '2025-12-01',
                'end_date' => '2026-06-01',
                'application_deadline' => '2025-11-15',
                'duration_months' => 6,
                'difficulty_level' => 'advanced',
                'status' => 'open',
                'facilities_provided' => json_encode(['Akomodasi', 'Transportasi']),
                'is_featured' => false,
                'is_urgent' => false,
            ],
            [
                'institution_id' => $institution->id,
                'title' => 'Program Edukasi Kesehatan Masyarakat',
                'description' => 'Membantu puskesmas dalam menjalankan program edukasi kesehatan untuk masyarakat pedesaan.',
                'province_id' => 1,
                'regency_id' => 2,
                'sdg_categories' => json_encode([3, 4]),
                'required_students' => 5,
                'required_skills' => json_encode(['Komunikasi', 'Public Speaking', 'Desain Grafis']),
                'start_date' => '2026-01-10',
                'end_date' => '2026-03-10',
                'application_deadline' => '2025-12-20',
                'duration_months' => 2,
                'difficulty_level' => 'beginner',
                'status' => 'open',
                'facilities_provided' => json_encode(['Konsumsi', 'Sertifikat']),
            ],
        ];

        foreach ($problems as $problemData) {
            Problem::create($problemData);
        }

        $this->command->info('Dummy data berhasil dibuat!');
        $this->command->info('Student Login: student@test.com / password123');
        $this->command->info('Institution Login: institution@test.com / password123');
    }
}