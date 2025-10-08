<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocumentsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     * membuat dokumen dari file PDF real di storage
     * 
     * jalankan: php artisan db:seed --class=DocumentsSeeder
     */
    public function run(): void
    {
        echo "📄 Membuat dokumen dari file PDF real...\n";
        
        // ambil user untuk uploader (ambil beberapa user random)
        $uploaders = User::where('user_type', 'student')->limit(10)->get();
        
        if ($uploaders->isEmpty()) {
            echo "⚠️  Warning: Tidak ada user student. Jalankan DummyDataSeeder terlebih dahulu.\n";
            return;
        }

        // ambil provinces dan regencies
        $provinces = Province::all();
        if ($provinces->isEmpty()) {
            echo "⚠️  Warning: Tidak ada data provinsi. Jalankan ProvincesRegenciesSeeder terlebih dahulu.\n";
            return;
        }

        $projects = Project::all();

        // cek apakah ada file PDF di supabase storage
        $pdfFiles = Storage::disk('supabase')->files('documents/reports');
        
        if (empty($pdfFiles)) {
            echo "⚠️  Warning: Tidak ada file PDF di Supabase storage!\n";
            echo "📝 Jalankan command: php artisan upload:supabase --type=documents\n";
            echo "💡 Atau pastikan folder storage/app/public/documents/reports berisi file PDF\n";
            return;
        }

        echo "📁 Ditemukan " . count($pdfFiles) . " file PDF di Supabase\n";

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
                'tags' => ['Sanitasi', 'Kesehatan', 'Air Bersih'],
                'author_name' => 'Mahasiswa KKN UI',
                'institution_name' => 'Pemerintah Desa Mekar',
                'university_name' => 'Universitas Indonesia',
                'year' => 2023,
            ],
            [
                'title' => 'Pemberdayaan Perempuan melalui Pelatihan Kerajinan',
                'description' => 'Kegiatan pelatihan pembuatan kerajinan tangan untuk meningkatkan ekonomi keluarga melalui pemberdayaan ibu rumah tangga.',
                'categories' => ['gender_equality', 'decent_work'],
                'tags' => ['Pemberdayaan', 'Perempuan', 'Kerajinan'],
                'author_name' => 'Tim KKN UGM',
                'institution_name' => 'PKK Desa Sumberejo',
                'university_name' => 'Universitas Gadjah Mada',
                'year' => 2023,
            ],
            [
                'title' => 'Sosialisasi Hidup Sehat dan Pencegahan Stunting',
                'description' => 'Edukasi masyarakat tentang pola hidup sehat dan pencegahan stunting pada balita melalui posyandu.',
                'categories' => ['good_health', 'zero_hunger'],
                'tags' => ['Kesehatan', 'Stunting', 'Posyandu'],
                'author_name' => 'Mahasiswa KKN UNAIR',
                'institution_name' => 'Puskesmas Kecamatan Sukodono',
                'university_name' => 'Universitas Airlangga',
                'year' => 2024,
            ],
            [
                'title' => 'Pemanfaatan Energi Terbarukan di Desa Terpencil',
                'description' => 'Implementasi panel surya dan biogas sebagai sumber energi alternatif di desa yang belum terjangkau listrik PLN.',
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
        ];

        echo "\n📝 Membuat dokumen di database...\n";
        
        $fileIndex = 0;
        $totalFiles = count($pdfFiles);
        
        // buat dokumen menggunakan file PDF yang tersedia
        foreach ($documentData as $index => $docData) {
            // pilih file PDF (cycle through available files)
            if ($fileIndex >= $totalFiles) {
                $fileIndex = 0;
            }
            
            $pdfPath = $pdfFiles[$fileIndex];
            $fileIndex++;
            
            // dapatkan file size dari supabase
            $fileSize = Storage::disk('supabase')->size($pdfPath);
            
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
                'file_path' => $pdfPath, // path di supabase
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
                'is_featured' => $index < 3, // 3 dokumen pertama jadi featured
                'status' => 'approved',
                'approved_at' => now(),
            ]);
            
            echo "  ✓ {$docData['title']}\n";
        }

        echo "\n";
        echo "✅ Berhasil membuat " . count($documentData) . " dokumen\n";
        echo "📊 File PDF dari Supabase: {$totalFiles} file\n";
        echo "\n";
        echo "🎉 Seeder selesai! Silakan test download di: http://127.0.0.1:8000/student/repository\n";
    }
}