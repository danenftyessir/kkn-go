<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

/**
 * seeder untuk documents (knowledge repository)
 * menggunakan PDF yang sudah disiapkan di storage/app/public/documents/reports/
 * 
 * path: database/seeders/DocumentsSeeder.php
 * jalankan: php artisan db:seed --class=DocumentsSeeder
 */
class DocumentsSeeder extends Seeder
{
    /**
     * run seeder
     */
    public function run(): void
    {
        $this->command->info('Seeding documents...');

        // path ke folder PDF
        $documentsPath = storage_path('app/public/documents/reports');
        
        // cek apakah folder ada
        if (!File::exists($documentsPath)) {
            $this->command->error("❌ Folder {$documentsPath} tidak ditemukan!");
            $this->command->info('Buat folder dan masukkan PDF terlebih dahulu.');
            return;
        }

        // ambil semua file PDF
        $pdfFiles = File::files($documentsPath);
        $validPdfs = array_filter($pdfFiles, function($file) {
            return strtolower($file->getExtension()) === 'pdf';
        });

        if (empty($validPdfs)) {
            $this->command->error('❌ Tidak ada file PDF di folder documents/reports!');
            $this->command->info('Masukkan minimal 30 PDF ke ' . $documentsPath);
            return;
        }

        $validPdfs = array_values($validPdfs);
        $totalPdfs = count($validPdfs);
        
        $this->command->info("📄 Ditemukan {$totalPdfs} file PDF");

        // ambil completed projects untuk di-assign ke documents
        $completedProjects = Project::with(['student.user', 'student.university', 'problem.institution'])
            ->where('status', 'completed')
            ->get();

        if ($completedProjects->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada completed projects. Documents akan dibuat tanpa relasi project.');
        }

        // hapus documents lama jika ada
        Document::truncate();

        $documentIndex = 0;
        $totalSeeded = 0;

        // template judul dokumen
        $documentTitles = [
            'Laporan Akhir KKN: Pemberdayaan Masyarakat',
            'Laporan KKN: Pengembangan Ekonomi Lokal',
            'Dokumentasi KKN: Program Kesehatan Masyarakat',
            'Laporan Proyek: Pendidikan dan Literasi Digital',
            'Hasil KKN: Pengelolaan Sampah Berbasis Komunitas',
            'Laporan KKN: Pengembangan Pertanian Organik',
            'Dokumentasi: Program Penyuluhan Kesehatan',
            'Laporan Akhir: Pemberdayaan UMKM Desa',
            'Hasil KKN: Revitalisasi Potensi Wisata Lokal',
            'Laporan Proyek: Peningkatan Kualitas Air Bersih',
            'Dokumentasi KKN: Pelatihan Keterampilan Masyarakat',
            'Laporan KKN: Pengembangan Infrastruktur Desa',
            'Hasil KKN: Program Literasi Anak',
            'Laporan Akhir: Konservasi Lingkungan',
            'Dokumentasi: Pemberdayaan Perempuan',
            'Laporan KKN: Pengembangan Teknologi Tepat Guna',
            'Hasil Proyek: Peningkatan Gizi Masyarakat',
            'Laporan KKN: Pengembangan Agribisnis',
            'Dokumentasi: Program Sanitasi Lingkungan',
            'Laporan Akhir: Pembangunan Kapasitas Masyarakat',
        ];

        // kategori SDG untuk random assignment
        $sdgCategories = range(1, 17);

        // tags untuk documents
        $possibleTags = [
            'KKN', 'Community Development', 'Pemberdayaan', 'Kesehatan', 
            'Pendidikan', 'Ekonomi', 'Lingkungan', 'UMKM', 'Pertanian',
            'Sanitasi', 'Infrastruktur', 'Wisata', 'Literasi', 'Teknologi'
        ];

        // jika ada completed projects, assign ke projects
        if ($completedProjects->isNotEmpty()) {
            foreach ($completedProjects as $project) {
                if ($documentIndex >= $totalPdfs) {
                    break; // sudah habis PDF
                }

                $pdfFile = $validPdfs[$documentIndex];
                $fileName = $pdfFile->getFilename();
                $fileSize = $pdfFile->getSize();
                
                // path relatif
                $relativePath = 'documents/reports/' . $fileName;

                // random SDG categories (1-3 categories)
                $numCategories = rand(1, 3);
                $selectedSdg = array_rand(array_flip($sdgCategories), $numCategories);
                if (!is_array($selectedSdg)) {
                    $selectedSdg = [$selectedSdg];
                }

                // random tags (2-5 tags)
                $numTags = rand(2, 5);
                $selectedTags = array_rand(array_flip($possibleTags), $numTags);
                if (!is_array($selectedTags)) {
                    $selectedTags = [$selectedTags];
                }

                // random year (2020-2024)
                $year = rand(2020, 2024);

                Document::create([
                    'project_id' => $project->id,
                    'uploaded_by' => $project->student->user->id,
                    'title' => $project->title . ' - Laporan Akhir',
                    'description' => "Laporan akhir pelaksanaan proyek {$project->title} di {$project->problem->regency->name}, {$project->problem->province->name}. Proyek ini dilaksanakan oleh {$project->student->user->name} dari {$project->student->university->name}.",
                    'file_path' => $relativePath,
                    'file_type' => 'pdf',
                    'file_size' => $fileSize,
                    'categories' => json_encode($selectedSdg),
                    'tags' => json_encode($selectedTags),
                    'author_name' => $project->student->user->name,
                    'institution_name' => $project->problem->institution->name,
                    'university_name' => $project->student->university->name,
                    'year' => $year,
                    'province_id' => $project->problem->province_id,
                    'regency_id' => $project->problem->regency_id,
                    'download_count' => rand(5, 150),
                    'view_count' => rand(20, 500),
                    'is_featured' => rand(1, 100) <= 20, // 20% chance featured
                    'is_public' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 365)),
                ]);

                $documentIndex++;
                $totalSeeded++;
            }
        }

        // sisa PDF dibuat sebagai dokumen umum (tanpa project_id)
        while ($documentIndex < $totalPdfs) {
            $pdfFile = $validPdfs[$documentIndex];
            $fileName = $pdfFile->getFilename();
            $fileSize = $pdfFile->getSize();
            
            $relativePath = 'documents/reports/' . $fileName;

            // random title dari template
            $title = $documentTitles[array_rand($documentTitles)];
            
            // random SDG
            $numCategories = rand(1, 3);
            $selectedSdg = array_rand(array_flip($sdgCategories), $numCategories);
            if (!is_array($selectedSdg)) {
                $selectedSdg = [$selectedSdg];
            }

            // random tags
            $numTags = rand(2, 5);
            $selectedTags = array_rand(array_flip($possibleTags), $numTags);
            if (!is_array($selectedTags)) {
                $selectedTags = [$selectedTags];
            }

            // random user sebagai uploader
            $uploader = User::whereHas('student')->inRandomOrder()->first();
            if (!$uploader) {
                $uploader = User::first(); // fallback ke user pertama
            }

            $year = rand(2020, 2024);

            // random province dan regency
            $provinces = \App\Models\Province::pluck('id')->toArray();
            $randomProvinceId = $provinces[array_rand($provinces)];
            $regencies = \App\Models\Regency::where('province_id', $randomProvinceId)->pluck('id')->toArray();
            $randomRegencyId = !empty($regencies) ? $regencies[array_rand($regencies)] : null;

            Document::create([
                'project_id' => null, // dokumen umum
                'uploaded_by' => $uploader->id,
                'title' => $title,
                'description' => "Dokumentasi hasil kegiatan KKN tahun {$year}. Berisi laporan lengkap pelaksanaan program dan hasil yang dicapai.",
                'file_path' => $relativePath,
                'file_type' => 'pdf',
                'file_size' => $fileSize,
                'categories' => json_encode($selectedSdg),
                'tags' => json_encode($selectedTags),
                'author_name' => $uploader->name,
                'institution_name' => 'Instansi Mitra KKN',
                'university_name' => $uploader->student ? $uploader->student->university->name : 'Universitas Indonesia',
                'year' => $year,
                'province_id' => $randomProvinceId,
                'regency_id' => $randomRegencyId,
                'download_count' => rand(5, 150),
                'view_count' => rand(20, 500),
                'is_featured' => rand(1, 100) <= 15, // 15% chance featured
                'is_public' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 730)), // 2 tahun ke belakang
            ]);

            $documentIndex++;
            $totalSeeded++;
        }

        $this->command->info("✅ {$totalSeeded} documents berhasil di-seed!");
        
        // statistik
        $withProject = Document::whereNotNull('project_id')->count();
        $withoutProject = Document::whereNull('project_id')->count();
        $featured = Document::where('is_featured', true)->count();
        
        $this->command->info("📊 Statistik:");
        $this->command->info("   - Dengan project: {$withProject}");
        $this->command->info("   - Umum: {$withoutProject}");
        $this->command->info("   - Featured: {$featured}");
    }
}