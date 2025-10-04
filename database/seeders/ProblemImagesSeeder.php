<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Problem;
use App\Models\ProblemImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * seeder untuk problem images
 * menggunakan gambar yang sudah disiapkan di storage/app/public/problems/
 * 
 * path: database/seeders/ProblemImagesSeeder.php
 * jalankan: php artisan db:seed --class=ProblemImagesSeeder
 */
class ProblemImagesSeeder extends Seeder
{
    /**
     * run seeder
     */
    public function run(): void
    {
        $this->command->info('Seeding problem images...');

        // cek apakah ada problems
        $problems = Problem::all();
        
        if ($problems->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada problems. Jalankan ProblemsSeeder terlebih dahulu!');
            return;
        }

        // path ke folder gambar
        $imagesPath = storage_path('app/public/problems');
        
        // cek apakah folder ada
        if (!File::exists($imagesPath)) {
            $this->command->error("❌ Folder {$imagesPath} tidak ditemukan!");
            $this->command->info('Buat folder dan masukkan gambar terlebih dahulu.');
            return;
        }

        // ambil semua file gambar (jpg, jpeg, png)
        $imageFiles = File::files($imagesPath);
        $validImages = array_filter($imageFiles, function($file) {
            $extension = strtolower($file->getExtension());
            return in_array($extension, ['jpg', 'jpeg', 'png']);
        });

        if (empty($validImages)) {
            $this->command->error('❌ Tidak ada file gambar (JPG/PNG) di folder problems!');
            $this->command->info('Masukkan minimal 30 gambar ke ' . $imagesPath);
            return;
        }

        $validImages = array_values($validImages);
        $totalImages = count($validImages);
        
        $this->command->info("📸 Ditemukan {$totalImages} gambar");

        // hapus problem images lama jika ada
        ProblemImage::truncate();

        $imageIndex = 0;
        $totalSeeded = 0;

        // distribusikan gambar ke problems
        foreach ($problems as $problem) {
            // setiap problem dapat 2-4 gambar random
            $imagesPerProblem = rand(2, 4);
            
            for ($i = 0; $i < $imagesPerProblem; $i++) {
                // jika sudah habis, mulai dari awal lagi
                if ($imageIndex >= $totalImages) {
                    $imageIndex = 0;
                }

                $imageFile = $validImages[$imageIndex];
                $fileName = $imageFile->getFilename();
                
                // path relatif untuk disimpan di database
                $relativePath = 'problems/' . $fileName;

                // simpan ke database
                ProblemImage::create([
                    'problem_id' => $problem->id,
                    'image_path' => $relativePath,
                    'caption' => "Dokumentasi untuk {$problem->title}",
                    'order' => $i + 1,
                ]);

                $imageIndex++;
                $totalSeeded++;
            }
        }

        $this->command->info("✅ {$totalSeeded} problem images berhasil di-seed!");
        $this->command->info("📊 Distribusi: {$problems->count()} problems dengan rata-rata " . round($totalSeeded / $problems->count(), 1) . " gambar per problem");
    }
}