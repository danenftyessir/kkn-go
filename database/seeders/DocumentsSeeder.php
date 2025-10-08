<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;

/**
 * seeder untuk membuat dokumen
 * simple version - hardcode list file PDF yang ada di supabase
 * 
 * CATATAN:
 * - file PDF harus sudah ada di supabase storage bucket "kkn-go storage"
 * - folder: documents/reports/
 * - tidak perlu upload, cukup simpan path saja
 * 
 * jalankan: php artisan db:seed --class=DocumentsSeeder
 */
class DocumentsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        $this->command->info('📄 Membuat dokumen...');
        
        // ambil user untuk uploader
        $uploaders = User::where('user_type', 'student')->limit(10)->get();
        
        if ($uploaders->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada user student. Jalankan DummyDataSeeder terlebih dahulu.');
            return;
        }

        // ambil provinces dan regencies
        $provinces = Province::all();
        if ($provinces->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data provinsi. Jalankan ProvincesRegenciesSeeder terlebih dahulu.');
            return;
        }

        $projects = Project::all();

        $availablePdfs = [
            'documents/reports/1.FORMAT-DAN-CONTOH-LAPORAN-INDIVIDU-KKN.pdf',
            'documents/reports/3341b-laporan_kkn_hasbi_mudzaki_fix-1-.pdf',
            'documents/reports/bc4f599c360deae829ef0952f9200a4f.pdf',
            'documents/reports/d5460592f2ee74a2f9f5910138d650e6.pdf',
            'documents/reports/download_252705030541_laporan-panitia-kegiatan-kknpmm-reguler-periode-i-tahun-2025.pdf',
            'documents/reports/f3f3ec539ee2d963e804d3a964b3290f.pdf',
            'documents/reports/KKN_III.D.3_REG.96_2022.pdf',
            'documents/reports/LAPORAN AKHIR KKN .pdf',
            'documents/reports/laporan akhir KKN PPM OK.pdf',
            'documents/reports/LAPORAN KKN DEMAPESA.pdf',
            'documents/reports/LAPORAN KKN KELOMPOK 2250.pdf',
            'documents/reports/LAPORAN KKN Kelompok 5 fakultas teknik.pdf',
            'documents/reports/LAPORAN KKN_1.A.2_REG.119_2024.pdf',
            'documents/reports/LAPORAN KKN.pdf',
            'documents/reports/laporan_3460160906115724.pdf',
            'documents/reports/laporan_akhir_201_35_2.pdf',
            'documents/reports/laporan_akhir_3011_45_5.pdf',
            'documents/reports/Laporan-Akademik-KKN-Persemakmuran-2022.pdf',
            'documents/reports/laporan-kelompok.pdf',
            'documents/reports/Laporan-Tugas-Akhir-KKN-156.pdf',
            'documents/reports/Partisipasi-Berbasis-Komunitas-Dalam-Rangka-Percepatan-Penurunan-Stunting.pdf',
            'documents/reports/Peraturan_Akademik_UNP.pdf',
            'documents/reports/Laporan-KKN-2019.pdf',
            'documents/reports/Stimulasi-Masyarakat-Desa-Tiyohu-berbasis-Ekonomi-dan-Pengetahuan-Hukum-di-Kabupaten-Gorontalo.pdf',
        ];

        $this->command->info('📁 Menggunakan ' . count($availablePdfs) . ' file PDF yang tersedia');
        $this->command->newLine();

        // data dokumen untuk seeding
        $documentData = [
            [
                'title' => 'Laporan KKN Pengembangan UMKM di Desa Sukamaju',
                'description' => 'Dokumentasi lengkap program KKN dalam pengembangan usaha mikro kecil menengah di Desa Sukamaju, Kabupaten Bandung.',
                'categories' => ['decent_work', 'industry_innovation'],
                'tags' => ['UMKM', 'Ekonomi', 'Kewirausahaan'],
                'author_name' => 'Tim KKN ITB',
                'institution_name' => 'Dinas Koperasi Kabupaten Bandung',
                'university_name' => 'Institut Teknologi Bandung',
                'year' => 2024,
            ],
            [
                'title' => 'Program Sanitasi dan Air Bersih di Desa Mekar',
                'description' => 'Implementasi sistem air bersih dan sanitasi berbasis masyarakat untuk meningkatkan kualitas hidup warga.',
                'categories' => ['clean_water', 'good_health'],
                'tags' => ['Kesehatan', 'Air Bersih', 'Sanitasi'],
                'author_name' => 'Mahasiswa KKN UGM',
                'institution_name' => 'Puskesmas Desa Mekar',
                'university_name' => 'Universitas Gadjah Mada',
                'year' => 2024,
            ],
            [
                'title' => 'Implementasi Energi Terbarukan di Desa Nusantara',
                'description' => 'Studi kasus penerapan panel surya dan biogas untuk mengurangi ketergantungan pada energi fosil.',
                'categories' => ['affordable_energy', 'climate_action'],
                'tags' => ['Energi', 'Terbarukan', 'Ramah Lingkungan'],
                'author_name' => 'Tim KKN ITS',
                'institution_name' => 'Pemerintah Desa Nusantara',
                'university_name' => 'Institut Teknologi Sepuluh Nopember',
                'year' => 2024,
            ],
            [
                'title' => 'Pendidikan Literasi Digital untuk Anak-Anak Desa',
                'description' => 'Program pelatihan teknologi informasi dan literasi digital untuk meningkatkan keterampilan anak-anak di pedesaan.',
                'categories' => ['quality_education', 'reduced_inequalities'],
                'tags' => ['Pendidikan', 'Digital', 'Teknologi'],
                'author_name' => 'Kelompok KKN UI',
                'institution_name' => 'SDN Harapan Bangsa',
                'university_name' => 'Universitas Indonesia',
                'year' => 2023,
            ],
            [
                'title' => 'Pengelolaan Sampah Organik Menjadi Kompos',
                'description' => 'Pelatihan dan pendampingan masyarakat dalam mengolah sampah organik menjadi pupuk kompos berkualitas.',
                'categories' => ['sustainable_cities', 'responsible_consumption'],
                'tags' => ['Sampah', 'Kompos', 'Lingkungan'],
                'author_name' => 'Tim KKN UNAIR',
                'institution_name' => 'Bank Sampah Sejahtera',
                'university_name' => 'Universitas Airlangga',
                'year' => 2023,
            ],
            [
                'title' => 'Pemberdayaan Perempuan Melalui Pelatihan Keterampilan',
                'description' => 'Program pelatihan keterampilan jahit, bordir, dan kerajinan tangan untuk meningkatkan ekonomi keluarga.',
                'categories' => ['gender_equality', 'decent_work'],
                'tags' => ['Perempuan', 'Keterampilan', 'Ekonomi'],
                'author_name' => 'Tim KKN UNPAD',
                'institution_name' => 'PKK Desa Makmur',
                'university_name' => 'Universitas Padjadjaran',
                'year' => 2023,
            ],
            [
                'title' => 'Peningkatan Hasil Pertanian Melalui Teknologi Hidroponik',
                'description' => 'Pengenalan dan implementasi sistem pertanian hidroponik sederhana untuk meningkatkan produktivitas lahan terbatas.',
                'categories' => ['zero_hunger', 'industry_innovation'],
                'tags' => ['Pertanian', 'Hidroponik', 'Teknologi'],
                'author_name' => 'Mahasiswa KKN IPB',
                'institution_name' => 'Kelompok Tani Maju Jaya',
                'university_name' => 'Institut Pertanian Bogor',
                'year' => 2024,
            ],
            [
                'title' => 'Posyandu Digital: Modernisasi Pelayanan Kesehatan Ibu dan Anak',
                'description' => 'Digitalisasi sistem pencatatan dan monitoring kesehatan ibu dan anak di posyandu desa.',
                'categories' => ['good_health', 'quality_education'],
                'tags' => ['Kesehatan', 'Digital', 'Posyandu'],
                'author_name' => 'Tim KKN UNDIP',
                'institution_name' => 'Posyandu Mawar Melati',
                'university_name' => 'Universitas Diponegoro',
                'year' => 2024,
            ],
            [
                'title' => 'Pengembangan Desa Wisata Berbasis Kearifan Lokal',
                'description' => 'Strategi pengembangan potensi wisata desa dengan mempertahankan nilai-nilai budaya dan kearifan lokal.',
                'categories' => ['decent_work', 'sustainable_cities'],
                'tags' => ['Pariwisata', 'Budaya', 'Ekonomi Kreatif'],
                'author_name' => 'Kelompok KKN UGM',
                'institution_name' => 'Dinas Pariwisata Kabupaten Bantul',
                'university_name' => 'Universitas Gadjah Mada',
                'year' => 2023,
            ],
            [
                'title' => 'Bank Sampah Digital: Inovasi Pengelolaan Sampah Berbasis Aplikasi',
                'description' => 'Pengembangan sistem bank sampah digital untuk memudahkan transaksi dan monitoring pengelolaan sampah.',
                'categories' => ['sustainable_cities', 'climate_action'],
                'tags' => ['Sampah', 'Digital', 'Aplikasi'],
                'author_name' => 'Tim KKN TELKOM',
                'institution_name' => 'Bank Sampah Digital Mandiri',
                'university_name' => 'Universitas Telkom',
                'year' => 2024,
            ],
        ];

        $this->command->newLine();
        $this->command->info('📝 Membuat dokumen di database...');
        
        // hapus dokumen lama
        Document::truncate();
        
        $fileIndex = 0;
        $totalCreated = 0;
        
        // buat dokumen menggunakan file PDF yang tersedia
        foreach ($documentData as $index => $docData) {
            // cycle through available PDFs
            if ($fileIndex >= count($availablePdfs)) {
                $fileIndex = 0;
            }
            
            $pdfPath = $availablePdfs[$fileIndex];
            $fileIndex++;
            
            // estimasi file size (dummy, karena tidak perlu akses file)
            $fileSize = rand(1000000, 5000000); // 1-5 MB
            
            // pilih uploader random
            $uploader = $uploaders->random();
            
            // pilih province dan regency random
            $province = $provinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            // pilih project random (jika ada)
            $project = $projects->isNotEmpty() ? $projects->random() : null;

            Document::create([
                'project_id' => $project?->id,
                'uploaded_by' => $uploader->id,
                'title' => $docData['title'],
                'description' => $docData['description'],
                'file_path' => $pdfPath, // simpan path saja
                'file_type' => 'pdf',
                'file_size' => $fileSize,
                'categories' => json_encode($docData['categories']),
                'tags' => json_encode($docData['tags']),
                'author_name' => $docData['author_name'],
                'institution_name' => $docData['institution_name'],
                'university_name' => $docData['university_name'],
                'year' => $docData['year'],
                'province_id' => $province->id,
                'regency_id' => $regency?->id,
                'download_count' => rand(10, 500),
                'view_count' => rand(50, 1000),
                'citation_count' => rand(0, 50),
                'is_public' => true,
                'is_featured' => $index < 3,
                'status' => 'approved',
                'approved_at' => now(),
            ]);
            
            $totalCreated++;
            $this->command->info("  ✓ {$docData['title']}");
        }

        $this->command->newLine();
        $this->command->info("✅ Berhasil membuat {$totalCreated} dokumen");
        $this->command->info("📊 File PDF tersedia: " . count($availablePdfs));
        $this->command->newLine();
        $this->command->info("💡 Dokumen akan diakses via Supabase Public URL");
        $this->command->info("🔗 Format: https://zgpykwjzmiqxhweifmrn.supabase.co/storage/v1/object/public/kkn-go%20storage/{PATH}");
        $this->command->newLine();
        $this->command->info("📋 CATATAN:");
        $this->command->info("   • File PDF sudah ada di Supabase storage");
        $this->command->info("   • Bucket: kkn-go storage");
        $this->command->info("   • Folder: documents/reports/");
        $this->command->info("   • Total: " . count($availablePdfs) . " file PDF");
        $this->command->info("   • Pastikan bucket sudah PUBLIC agar bisa diakses");
    }
}