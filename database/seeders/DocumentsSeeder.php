<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use Carbon\Carbon;

/**
 * seeder untuk documents dengan format categories yang benar
 * ✅ PERBAIKAN: categories menggunakan INTEGER (1-17), bukan string slug
 * 
 * jalankan: php artisan db:seed --class=DocumentsSeeder
 * 
 * TIDAK PERLU lagi menjalankan php artisan fix:double-encoded-json setelah seeding!
 */
class DocumentsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        // ✅ FIX: ambil users yang punya relasi student ATAU institution
        // karena tabel users tidak punya kolom 'role'
        $studentUsers = User::whereHas('student')->get();
        $institutionUsers = User::whereHas('institution')->get();
        $uploaders = $studentUsers->merge($institutionUsers);

        $provinces = Province::all();

        if ($uploaders->isEmpty() || $provinces->isEmpty()) {
            $this->command->error('Harap jalankan UsersSeeder dan ProvincesSeeder terlebih dahulu!');
            return;
        }

        // ✅ PERBAIKAN: Gunakan PDF yang sudah ada di Supabase
        // File-file ini ada di folder documents/reports/ di Supabase bucket
        $existingPdfs = [
            '3341b-laporan_kkn_hasbi_mudzaki_fix-1-.pdf',
            'aaLAPORAN-PROGRAM-KERJA-KKN.pdf',
            'bc4f599c360deae829ef0952f9200a4f.pdf',
            'd5460592f2ee74a2f9f5910138d650e6.pdf',
            'f3f3ec539ee2d963e804d3a964b3290f.pdf',
            'KKN_III.D.3_REG.96_2022.pdf',
            'LAPORAN AKHIR KKN .pdf',
            'laporan akhir KKN PPM OK.pdf',
            'LAPORAN KELOMPOK KKN 1077fix.pdf',
            'LAPORAN KKN DEMAPESA.pdf',
            'LAPORAN KKN KELOMPOK 2250.pdf',
            'LAPORAN KKN_1.A.2_REG.119_2024.pdf',
            'LAPORAN KKN.pdf',
            'laporan_3460160906115724.pdf',
            'laporan_akhir_201_35_2.pdf',
            'laporan_akhir_3011_45_5.pdf',
            'laporan-kelompok.pdf',
            'Laporan-KKN-2019.pdf',
            'Laporan-Tugas-Akhir-KKN-156.pdf',
        ];

        $this->command->info('📄 Menggunakan ' . count($existingPdfs) . ' PDF yang sudah ada di Supabase...');

        // template documents dengan SDG categories yang benar (INTEGER)
        $documentsTemplates = [
            [
                'title' => 'Laporan KKN Pengembangan UMKM Di Desa Sukamaju',
                'description' => 'Dokumentasi lengkap program pengembangan UMKM yang meningkatkan pendapatan masyarakat desa hingga 40%.',
                'categories' => [8, 9], // ✅ INTEGER: Decent Work, Industry Innovation
                'tags' => ['UMKM', 'Kewirausahaan', 'Ekonomi Desa', 'Pemberdayaan'],
                'author_name' => 'Tim KKN ITB 2024',
                'institution_name' => 'Pemerintah Desa Sukamaju',
                'university_name' => 'Institut Teknologi Bandung',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Program Sanitasi Dan Air Bersih Di Desa Mekar',
                'description' => 'Hasil implementasi sistem sanitasi dan penyediaan air bersih untuk 500 keluarga.',
                'categories' => [6, 3], // ✅ INTEGER: Clean Water, Good Health
                'tags' => ['Sanitasi', 'Air Bersih', 'Kesehatan', 'Infrastruktur'],
                'author_name' => 'Tim KKN UGM 2024',
                'institution_name' => 'Dinas Kesehatan Kabupaten',
                'university_name' => 'Universitas Gadjah Mada',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Implementasi Energi Terbarukan Di Desa Nusantara',
                'description' => 'Studi kelayakan dan implementasi panel surya untuk listrik desa.',
                'categories' => [7, 13], // ✅ INTEGER: Affordable Energy, Climate Action
                'tags' => ['Energi Terbarukan', 'Panel Surya', 'Listrik Desa', 'Lingkungan'],
                'author_name' => 'Tim KKN UI 2024',
                'institution_name' => 'Pemerintah Desa Nusantara',
                'university_name' => 'Universitas Indonesia',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Pendidikan Literasi Digital Untuk Anak-Anak Desa',
                'description' => 'Program pelatihan komputer dan internet untuk siswa SD dan SMP di daerah terpencil.',
                'categories' => [4, 10], // ✅ INTEGER: Quality Education, Reduced Inequalities
                'tags' => ['Pendidikan', 'Literasi Digital', 'Teknologi', 'Anak-anak'],
                'author_name' => 'Tim KKN Unpad 2024',
                'institution_name' => 'Dinas Pendidikan Kabupaten',
                'university_name' => 'Universitas Padjadjaran',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Pengelolaan Sampah Organik Menjadi Kompos',
                'description' => 'Panduan praktis pengelolaan sampah organik menjadi kompos bernilai ekonomi.',
                'categories' => [11, 12], // ✅ INTEGER: Sustainable Cities, Responsible Consumption
                'tags' => ['Sampah', 'Kompos', 'Daur Ulang', 'Ekonomi Sirkular'],
                'author_name' => 'Tim KKN ITS 2024',
                'institution_name' => 'Dinas Lingkungan Hidup',
                'university_name' => 'Institut Teknologi Sepuluh Nopember',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Pemberdayaan Perempuan Melalui Pelatihan Keterampilan',
                'description' => 'Dokumentasi program pelatihan menjahit dan kerajinan untuk perempuan desa.',
                'categories' => [5, 8], // ✅ INTEGER: Gender Equality, Decent Work
                'tags' => ['Pemberdayaan Perempuan', 'Pelatihan', 'Keterampilan', 'UMKM'],
                'author_name' => 'Tim KKN Unair 2024',
                'institution_name' => 'Dinas Pemberdayaan Perempuan',
                'university_name' => 'Universitas Airlangga',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Peningkatan Hasil Pertanian Melalui Teknologi Hidroponik',
                'description' => 'Implementasi sistem hidroponik untuk meningkatkan produktivitas pertanian.',
                'categories' => [2, 9], // ✅ INTEGER: Zero Hunger, Industry Innovation
                'tags' => ['Pertanian', 'Hidroponik', 'Teknologi', 'Ketahanan Pangan'],
                'author_name' => 'Tim KKN IPB 2024',
                'institution_name' => 'Dinas Pertanian Kabupaten',
                'university_name' => 'Institut Pertanian Bogor',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Posyandu Digital: Modernisasi Pelayanan Kesehatan Ibu Dan Anak',
                'description' => 'Implementasi sistem digital untuk posyandu yang meningkatkan efisiensi layanan.',
                'categories' => [3, 4], // ✅ INTEGER: Good Health, Quality Education
                'tags' => ['Posyandu', 'Kesehatan', 'Digital', 'Ibu dan Anak'],
                'author_name' => 'Tim KKN Undip 2024',
                'institution_name' => 'Puskesmas Kecamatan',
                'university_name' => 'Universitas Diponegoro',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Pengembangan Desa Wisata Berbasis Kearifan Lokal',
                'description' => 'Strategi pengembangan desa wisata yang berkelanjutan dengan nilai budaya lokal.',
                'categories' => [8, 11], // ✅ INTEGER: Decent Work, Sustainable Cities
                'tags' => ['Desa Wisata', 'Pariwisata', 'Budaya Lokal', 'Ekonomi Kreatif'],
                'author_name' => 'Tim KKN UNS 2024',
                'institution_name' => 'Dinas Pariwisata Kabupaten',
                'university_name' => 'Universitas Sebelas Maret',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
            [
                'title' => 'Bank Sampah Digital: Inovasi Pengelolaan Sampah Berbasis Aplikasi',
                'description' => 'Pengembangan aplikasi mobile untuk manajemen bank sampah yang efisien.',
                'categories' => [11, 13], // ✅ INTEGER: Sustainable Cities, Climate Action
                'tags' => ['Bank Sampah', 'Aplikasi', 'Teknologi', 'Lingkungan'],
                'author_name' => 'Tim KKN Telkom University 2024',
                'institution_name' => 'Kelompok Bank Sampah',
                'university_name' => 'Telkom University',
                'file_type' => 'pdf',
                'year' => 2024,
            ],
        ];

        // create documents
        foreach ($documentsTemplates as $template) {
            $uploader = $uploaders->random();
            $province = $provinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            if (!$regency) {
                continue;
            }

            // ✅ PERBAIKAN: Ambil random PDF dari yang sudah ada di Supabase
            // Path disimpan dengan prefix documents/reports/ sesuai lokasi di bucket
            $randomPdf = $existingPdfs[array_rand($existingPdfs)];
            $filePath = 'documents/reports/' . $randomPdf;

            // Debug: Log file path yang akan disimpan
            $this->command->info("  → Assigning file: {$filePath}");
            
            // ✅ BENAR: categories langsung pass array of integers
            Document::create([
                'uploaded_by' => $uploader->id,
                'title' => $template['title'],
                'description' => $template['description'],
                'file_path' => $filePath,
                'file_type' => $template['file_type'],
                'file_size' => rand(500000, 5000000), // 500KB - 5MB
                
                // ✅ PENTING: categories adalah array of integers (1-17)
                'categories' => $template['categories'],
                'tags' => $template['tags'],
                
                'author_name' => $template['author_name'],
                'institution_name' => $template['institution_name'],
                'university_name' => $template['university_name'],
                'year' => $template['year'],
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                
                'download_count' => rand(0, 100),
                'view_count' => rand(0, 500),
                'citation_count' => rand(0, 20),
                
                'is_public' => true,
                'is_featured' => fake()->boolean(30), // 30% chance
                'status' => 'approved',
                'approved_at' => Carbon::now(),
            ]);
        }

        $this->command->info('✅ Documents seeder berhasil dijalankan!');
        $this->command->info('Total documents: ' . Document::count());
    }
}