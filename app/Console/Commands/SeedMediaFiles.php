<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Problem;
use App\Models\ProblemImage;
use App\Models\Project;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

/**
 * command untuk seeding media files (foto problems dan PDF documents)
 * files harus sudah ada di storage/app/public/ sebelum command ini dijalankan
 * 
 * jalankan: php artisan seed:media
 */
class SeedMediaFiles extends Command
{
    /**
     * signature command
     */
    protected $signature = 'seed:media';

    /**
     * deskripsi command
     */
    protected $description = 'seed problem images dan document files dari storage';

    /**
     * execute command
     */
    public function handle()
    {
        $this->info('==========================================');
        $this->info('  SEEDING MEDIA FILES');
        $this->info('==========================================');
        $this->newLine();

        // seed problem images
        $this->seedProblemImages();
        
        // seed document files
        $this->seedDocuments();

        $this->newLine();
        $this->info('==========================================');
        $this->info('  SEEDING MEDIA SELESAI!');
        $this->info('==========================================');
    }

    /**
     * seed gambar untuk problems
     */
    private function seedProblemImages()
    {
        $this->info('🖼️  seeding problem images...');

        // cek apakah folder problems/ ada
        if (!Storage::disk('public')->exists('problems')) {
            $this->error('folder storage/app/public/problems/ tidak ditemukan!');
            $this->warn('buat folder dan masukkan foto-foto terlebih dahulu.');
            return;
        }

        // ambil semua file gambar dari folder problems/
        $imageFiles = collect(Storage::disk('public')->files('problems'))
            ->filter(function($file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
            })
            ->values();

        if ($imageFiles->isEmpty()) {
            $this->warn('tidak ada file gambar di folder problems/');
            return;
        }

        $this->info("ditemukan {$imageFiles->count()} file gambar");

        // ambil semua problems
        $problems = Problem::all();

        if ($problems->isEmpty()) {
            $this->error('tidak ada problems di database! jalankan seeder terlebih dahulu.');
            return;
        }

        // distribusikan gambar ke problems
        // setiap problem dapat 1-3 gambar secara acak
        $imageIndex = 0;
        $totalImagesAdded = 0;

        foreach ($problems as $problem) {
            $numImages = rand(1, min(3, $imageFiles->count())); // 1-3 gambar per problem
            
            for ($i = 0; $i < $numImages; $i++) {
                if ($imageIndex >= $imageFiles->count()) {
                    $imageIndex = 0; // reset jika sudah mentok
                }

                $imagePath = $imageFiles[$imageIndex];
                
                // cek apakah sudah ada di database
                $exists = ProblemImage::where('problem_id', $problem->id)
                    ->where('image_path', $imagePath)
                    ->exists();

                if (!$exists) {
                    ProblemImage::create([
                        'problem_id' => $problem->id,
                        'image_path' => $imagePath,
                        'caption' => 'dokumentasi kondisi lapangan untuk ' . $problem->title,
                        'order' => $i + 1,
                    ]);
                    $totalImagesAdded++;
                }

                $imageIndex++;
            }
        }

        $this->info("✓ {$totalImagesAdded} problem images berhasil ditambahkan ke database");
    }

    /**
     * seed documents (PDF laporan)
     */
    private function seedDocuments()
    {
        $this->info('📄 seeding document files...');

        // cek apakah folder documents/reports/ ada
        if (!Storage::disk('public')->exists('documents/reports')) {
            $this->error('folder storage/app/public/documents/reports/ tidak ditemukan!');
            $this->warn('buat folder dan masukkan PDF terlebih dahulu.');
            return;
        }

        // ambil semua file PDF dari folder documents/reports/
        $pdfFiles = collect(Storage::disk('public')->files('documents/reports'))
            ->filter(function($file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return $ext === 'pdf';
            })
            ->values();

        if ($pdfFiles->isEmpty()) {
            $this->warn('tidak ada file PDF di folder documents/reports/');
            return;
        }

        $this->info("ditemukan {$pdfFiles->count()} file PDF");

        // ambil completed projects untuk dikasih document
        $completedProjects = Project::where('status', 'completed')
            ->whereNotNull('final_report_path')
            ->with(['student', 'problem'])
            ->get();

        if ($completedProjects->isEmpty()) {
            $this->warn('tidak ada completed projects. jalankan ProjectsSeeder terlebih dahulu.');
            return;
        }

        $totalDocsAdded = 0;

        // assign PDF ke completed projects
        foreach ($completedProjects as $index => $project) {
            if ($index >= $pdfFiles->count()) {
                break; // tidak cukup PDF
            }

            $pdfPath = $pdfFiles[$index];
            $fileName = basename($pdfPath);

            // cek apakah document sudah ada
            $exists = Document::where('project_id', $project->id)
                ->where('file_path', $pdfPath)
                ->exists();

            if (!$exists) {
                Document::create([
                    'project_id' => $project->id,
                    'student_id' => $project->student_id,
                    'problem_id' => $project->problem_id,
                    'title' => 'laporan KKN: ' . $project->title,
                    'description' => 'laporan hasil pelaksanaan KKN untuk proyek ' . $project->problem->title,
                    'file_path' => $pdfPath,
                    'file_name' => $fileName,
                    'file_type' => 'application/pdf',
                    'file_size' => Storage::disk('public')->size($pdfPath),
                    'category' => 'final_report',
                    'is_public' => true,
                    'is_featured' => rand(1, 100) <= 30, // 30% featured
                    'status' => 'approved',
                    'approved_by' => $project->institution_id,
                    'approved_at' => now()->subDays(rand(1, 30)),
                    'downloads_count' => rand(5, 200),
                    'views_count' => rand(20, 500),
                ]);
                $totalDocsAdded++;
            }
        }

        $this->info("✓ {$totalDocsAdded} documents berhasil ditambahkan ke database");
    }
}