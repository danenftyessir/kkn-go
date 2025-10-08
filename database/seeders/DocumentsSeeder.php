<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Services\SupabaseStorageService;

/**
 * seeder untuk membuat dokumen
 * menggunakan Supabase REST API untuk list files
 * 
 * jalankan: php artisan db:seed --class=DocumentsSeeder
 */
class DocumentsSeeder extends Seeder
{
    protected $supabaseStorage;

    public function __construct()
    {
        $this->supabaseStorage = new SupabaseStorageService();
    }

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

        // list PDF files dari supabase
        $this->command->info('🔍 Fetching PDF files dari Supabase...');
        
        $pdfFiles = $this->supabaseStorage->listFiles('documents/reports');
        
        // filter hanya PDF
        $validPdfs = array_filter($pdfFiles, function($file) {
            return strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf';
        });

        if (empty($validPdfs)) {
            $this->command->warn('⚠️  Tidak ada file PDF di Supabase storage!');
            $this->command->info('📝 Upload PDF ke Supabase bucket "kkn-go storage" folder "documents/reports"');
            $this->command->info('💡 Atau user nanti bisa upload via form submit final report');
            return;
        }

        $validPdfs = array_values($validPdfs);
        $this->command->info('📁 Ditemukan ' . count($validPdfs) . ' file PDF');

        // data dokumen untuk seeding
        $documentData = [
            [
                'title' => 'Laporan KKN Pengembangan UMKM di Desa Sukamaju',
                'description' => 'Dokumentasi lengkap program KKN dalam pengembangan usaha mikro kecil menengah di Desa Sukamaju, Kabupaten Bandung.',
                'categories' => ['decent_work', 'reduced_inequalities'],
                'tags' => ['UMKM', 'Ekonomi', 'Pemberdayaan'],
                'author_name' => 'Tim KKN ITB',
                'institution_name' => 'Dinas Koperasi dan UMKM',
                'university_name' => 'Institut Teknologi Bandung',
                'year' => 2024,
            ],
            [
                'title' => 'Program Edukasi Sanitasi dan Air Bersih Desa Mekar',
                'description' => 'Laporan hasil sosialisasi pentingnya sanitasi dan akses air bersih untuk kesehatan masyarakat desa.',
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
            if ($fileIndex >= count($validPdfs)) {
                $fileIndex = 0;
            }<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;

/**
 * seeder untuk membuat dokumen
 * SIMPLE VERSION - hardcode list file yang ada di supabase
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

        // HARDCODE list PDF yang ada di supabase bucket "kkn-go storage"
        // sesuaikan dengan nama file yang BENAR-BENAR ada di bucket Anda
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
            // tambahkan sesuai file yang ada
        ];

        $this->command->info('📁 Ditemukan ' . count($availablePdfs) . ' file PDF');

        // data dokumen untuk seeding
        $documentData = [
            [
                'title' => 'Laporan KKN Pengembangan UMKM di Desa Sukamaju',
                'description' => 'Dokumentasi lengkap program KKN dalam pengembangan usaha mikro kecil menengah di Desa Sukamaju, Kabupaten Bandung.',
                'categories' => ['decent_work', 'reduced_inequalities'],
                'tags' => ['UMKM', 'Ekonomi', 'Pemberdayaan'],
                'author_name' => 'Tim KKN ITB',
                'institution_name' => 'Dinas Koperasi dan UMKM',
                'university_name' => 'Institut Teknologi Bandung',
                'year' => 2024,
            ],
            [
                'title' => 'Program Edukasi Sanitasi dan Air Bersih Desa Mekar',
                'description' => 'Laporan hasil sosialisasi pentingnya sanitasi dan akses air bersih untuk kesehatan masyarakat desa.',
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
                'title' => 'Literasi Digital untuk Anak-anak Desa',
                'description' => 'Program pengenalan teknologi dan internet sehat untuk anak-anak usia sekolah di daerah pedesaan.',
                'categories' => ['quality_education', 'reduced_inequalities'],
                'tags' => ['Pendidikan', 'Digital', 'Teknologi'],
                'author_name' => 'Mahasiswa KKN UB',
                'institution_name' => 'SD Negeri Pedesaan 01',
                'university_name' => 'Universitas Brawijaya',
                'year' => 2023,
            ],
            [
                'title' => 'Pemberdayaan Perempuan Melalui Pelatihan Keterampilan',
                'description' => 'Program pelatihan menjahit dan kerajinan tangan untuk meningkatkan kemandirian ekonomi perempuan desa.',
                'categories' => ['gender_equality', 'decent_work'],
                'tags' => ['Perempuan', 'Keterampilan', 'Ekonomi'],
                'author_name' => 'Tim KKN UNPAD',
                'institution_name' => 'PKK Desa Makmur',
                'university_name' => 'Universitas Padjadjaran',
                'year' => 2023,
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
        $this->command->info("🔗 Format: https://zgpykwjzmiqxhweifmrn.supabase.co/storage/v1/object/public/kkn-go%20storage/PATH");
    }
}