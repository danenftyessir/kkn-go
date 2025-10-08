<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Problem;
use App\Models\ProblemImage;
use Illuminate\Support\Facades\Storage;

/**
 * seeder untuk problem images
 * menggunakan gambar yang sudah ada di Supabase storage
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
        $this->command->info('📸 Seeding problem images dari Supabase...');

        // cek apakah ada problems
        $problems = Problem::all();
        
        if ($problems->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada problems. Jalankan ProblemsSeeder terlebih dahulu!');
            return;
        }

        // ambil semua gambar dari supabase storage
        $imageFiles = Storage::disk('supabase')->files('problems');
        
        if (empty($imageFiles)) {
            $this->command->error('❌ Tidak ada file gambar di Supabase storage folder "problems"!');
            $this->command->info('📝 Pastikan gambar sudah diupload ke Supabase dengan path: problems/masalah-desa-X.jpg');
            $this->command->info('💡 Atau jalankan: php artisan upload:supabase --type=problems');
            return;
        }

        // filter hanya file gambar
        $validImages = array_filter($imageFiles, function($file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
        });

        if (empty($validImages)) {
            $this->command->error('❌ Tidak ada file gambar valid (JPG/PNG) di folder problems!');
            return;
        }

        $validImages = array_values($validImages);
        $totalImages = count($validImages);
        
        $this->command->info("🖼️  Ditemukan {$totalImages} gambar di Supabase");

        // hapus problem images lama jika ada
        ProblemImage::truncate();

        $imageIndex = 0;
        $totalSeeded = 0;

        // distribusikan gambar ke problems
        foreach ($problems as $problem) {
            // setiap problem dapat 2-4 gambar random
            $imagesPerProblem = rand(2, 4);
            
            for ($i = 0; $i < $imagesPerProblem; $i++) {
                // jika sudah habis, mulai dari awal lagi (cycle)
                if ($imageIndex >= $totalImages) {
                    $imageIndex = 0;
                }

                $imagePath = $validImages[$imageIndex];
                $imageIndex++;
                
                // tentukan apakah gambar ini jadi cover (gambar pertama = cover)
                $isCover = ($i === 0);
                
                ProblemImage::create([
                    'problem_id' => $problem->id,
                    'image_path' => $imagePath, // path di supabase: problems/masalah-desa-1.jpg
                    'caption' => $this->generateCaption($problem, $i),
                    'is_cover' => $isCover,
                    'order' => $i,
                ]);
                
                $totalSeeded++;
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Berhasil seed {$totalSeeded} problem images");
        $this->command->info("📊 Total problems: " . $problems->count());
        $this->command->info("🖼️  Total gambar tersedia: {$totalImages}");
    }

    /**
     * generate caption untuk gambar
     */
    protected function generateCaption(Problem $problem, int $index): string
    {
        $captions = [
            "Kondisi lapangan di {$problem->location}",
            "Dokumentasi situasi terkini",
            "Area yang memerlukan perhatian",
            "Kondisi eksisting lokasi",
            "Dokumentasi survei awal",
            "Gambaran umum masalah",
        ];

        return $captions[$index] ?? $captions[0];
    }
}