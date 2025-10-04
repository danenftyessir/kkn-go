<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Facades\Storage;

class DocumentsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        echo "\nSeeding documents...\n";

        $pdfPath = 'documents/reports';
        $fullPath = storage_path('app/public/' . $pdfPath);

        // cek apakah folder ada
        if (!is_dir($fullPath)) {
            echo "⚠️  Folder {$fullPath} tidak ditemukan!\n";
            echo "📁 Silakan buat folder dan masukkan file PDF terlebih dahulu.\n";
            return;
        }

        // ambil semua file PDF dari folder
        $files = array_filter(scandir($fullPath), function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
        });

        if (empty($files)) {
            echo "⚠️  Tidak ada file PDF di folder {$fullPath}\n";
            return;
        }

        echo "📄 Ditemukan " . count($files) . " file PDF\n";

        // ambil completed projects dengan SEMUA relasi yang dibutuhkan
        $completedProjects = Project::where('status', 'completed')
            ->with([
                'problem.regency.province',
                'problem.institution.user',
                'student.user',
                'student.university'
            ])
            ->get();

        // ambil data provinsi dan kabupaten untuk dokumen umum
        $provinces = Province::with('regencies')->get();
        
        // validasi: pastikan ada province dengan regencies
        $provincesWithRegencies = $provinces->filter(function($p) {
            return $p->regencies->count() > 0;
        });
        
        if ($provincesWithRegencies->count() === 0) {
            echo "⚠️  Tidak ada data provinsi/kabupaten untuk dokumen umum!\n";
            echo "💡 Jalankan: php artisan db:seed --class=ProvincesRegenciesSeeder\n";
            return;
        }

        // kategori SDG lengkap
        $sdgCategories = [
            'No Poverty',
            'Zero Hunger', 
            'Good Health and Well-being',
            'Quality Education',
            'Gender Equality',
            'Clean Water and Sanitation',
            'Affordable and Clean Energy',
            'Decent Work and Economic Growth',
            'Industry, Innovation and Infrastructure',
            'Reduced Inequalities',
            'Sustainable Cities and Communities',
            'Responsible Consumption and Production',
            'Climate Action',
            'Life Below Water',
            'Life on Land',
            'Peace, Justice and Strong Institutions',
            'Partnerships for the Goals'
        ];

        // tags yang mungkin
        $possibleTags = [
            'KKN', 'Community Service', 'Research', 'Development',
            'Education', 'Health', 'Environment', 'Technology',
            'Agriculture', 'Infrastructure', 'Social', 'Economic',
            'Cultural', 'Innovation', 'Sustainability'
        ];

        $documentsCreated = 0;
        $withProject = 0;
        $general = 0;

        foreach ($files as $index => $file) {
            $filePath = $pdfPath . '/' . $file;
            
            // tentukan apakah dokumen ini terkait project atau umum
            $hasProject = $index < count($completedProjects);
            $project = $hasProject ? $completedProjects[$index] : null;

            // generate metadata
            if ($project) {
                // dokumen terkait project
                $locationText = $project->problem->regency->name . ', ' . $project->problem->regency->province->name;
                
                $title = "Laporan KKN: " . $project->problem->title;
                $description = "Laporan lengkap kegiatan KKN di " . $locationText . 
                             " yang dilaksanakan oleh " . $project->student->user->name . 
                             " dari " . $project->student->university->name;
                $authorName = $project->student->user->name;
                $institutionName = $project->problem->institution->name;
                $universityName = $project->student->university->name;
                $year = $project->end_date->year;
                $provinceId = $project->problem->province_id;
                $regencyId = $project->problem->regency_id;
                $projectId = $project->id;
                $uploadedBy = $project->student->user_id;
                $withProject++;
            } else {
                // dokumen umum (tidak terkait project spesifik)
                $randomProvince = $provincesWithRegencies->random();
                $randomRegency = $randomProvince->regencies->random();
                
                $title = "Laporan KKN - " . fake()->words(3, true);
                $description = "Dokumentasi kegiatan KKN yang telah dilaksanakan dengan fokus pada pemberdayaan masyarakat dan pengembangan daerah.";
                $authorName = fake()->name();
                $institutionName = fake()->randomElement([
                    'Pemerintah Desa Sukamaju',
                    'Dinas Kesehatan Kabupaten',
                    'Dinas Pendidikan',
                    'Puskesmas Kecamatan',
                    'Kelurahan Makmur'
                ]);
                $universityName = fake()->randomElement([
                    'Universitas Indonesia',
                    'Institut Teknologi Bandung',
                    'Universitas Gadjah Mada',
                    'Institut Pertanian Bogor',
                    'Universitas Airlangga'
                ]);
                $year = fake()->numberBetween(2020, 2024);
                $provinceId = $randomProvince->id;
                $regencyId = $randomRegency->id;
                $projectId = null;
                // untuk dokumen umum, ambil user pertama sebagai uploader
                $uploadedBy = 1;
                $general++;
            }

            // pilih random SDG categories (1-3 kategori)
            $numCategories = fake()->numberBetween(1, 3);
            $selectedSdgs = fake()->randomElements($sdgCategories, $numCategories);

            // pilih random tags (2-5 tags)
            $numTags = fake()->numberBetween(2, 5);
            $selectedTags = fake()->randomElements($possibleTags, $numTags);

            // buat document
            Document::create([
                'title' => $title,
                'description' => $description,
                'file_path' => $filePath,
                'file_size' => filesize($fullPath . '/' . $file),
                'file_type' => 'application/pdf',
                'author_name' => $authorName,
                'institution_name' => $institutionName,
                'university_name' => $universityName,
                'year' => $year,
                'categories' => $selectedSdgs,
                'tags' => $selectedTags,
                'province_id' => $provinceId,
                'regency_id' => $regencyId,
                'project_id' => $projectId,
                'uploaded_by' => $uploadedBy,
                'is_featured' => fake()->boolean(20), // 20% chance jadi featured
                'download_count' => fake()->numberBetween(0, 500),
                'view_count' => fake()->numberBetween(0, 1000),
            ]);

            $documentsCreated++;
        }

        echo "✅ {$documentsCreated} documents berhasil di-seed!\n";
        echo "📊 Statistik:\n";
        echo "   - Dengan project: {$withProject}\n";
        echo "   - Umum: {$general}\n";
        echo "   - Featured: " . Document::where('is_featured', true)->count() . "\n";
    }
}