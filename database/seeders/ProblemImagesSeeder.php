<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Problem;
use App\Models\ProblemImage;

/**
 * seeder untuk problem images
 * SIMPLE VERSION - langsung hardcode list file yang ada di supabase
 * tidak perlu S3 driver, cukup simpan path saja
 * 
 * jalankan: php artisan db:seed --class=ProblemImagesSeeder
 */
class ProblemImagesSeeder extends Seeder
{
    /**
     * run seeder
     */
    public function run(): void
    {
        $this->command->info('📸 Seeding problem images...');

        // cek apakah ada problems
        $problems = Problem::all();
        
        if ($problems->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada problems. Jalankan ProblemsSeeder terlebih dahulu!');
            return;
        }

        // HARDCODE list file yang ada di supabase bucket "kkn-go storage"
        // sesuaikan dengan nama file yang BENAR-BENAR ada di bucket Anda
        $availableImages = [
            'problems/image-1.jpg',
            'problems/image-2.jpg',
            'problems/image-3.jpg',
            'problems/image-4.jpg',
            'problems/image-5.jpg',
            'problems/image-6.jpg',
            'problems/image-7.jpg',
            'problems/image-8.jpg',
            'problems/image-9.jpg',
            'problems/image-10.jpg',
            // tambahkan sesuai file yang ada
        ];

        $this->command->info("🖼️  Ditemukan " . count($availableImages) . " gambar");

        // hapus problem images lama
        ProblemImage::truncate();

        $imageIndex = 0;
        $totalSeeded = 0;

        // distribusikan gambar ke problems
        foreach ($problems as $problem) {
            // setiap problem dapat 2-4 gambar
            $imagesPerProblem = min(rand(2, 4), count($availableImages));
            
            for ($i = 0; $i < $imagesPerProblem; $i++) {
                // cycle through images
                if ($imageIndex >= count($availableImages)) {
                    $imageIndex = 0;
                }

                $imagePath = $availableImages[$imageIndex];
                $imageIndex++;
                
                // tentukan cover (gambar pertama)
                $isCover = ($i === 0);
                
                ProblemImage::create([
                    'problem_id' => $problem->id,
                    'image_path' => $imagePath, // simpan path saja
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
        $this->command->info("🖼️  Images per problem: 2-4 gambar");
        $this->command->newLine();
        $this->command->info("💡 Gambar akan diakses via Supabase Public URL");
        $this->command->info("🔗 Format: https://zgpykwjzmiqxhweifmrn.supabase.co/storage/v1/object/public/kkn-go%20storage/PATH");
    }

    /**
     * generate caption untuk gambar
     */
    protected function generateCaption(Problem $problem, int $index): string
    {
        $captions = [
            "Kondisi lapangan",
            "Dokumentasi situasi terkini",
            "Area yang memerlukan perhatian",
            "Kondisi eksisting lokasi",
            "Dokumentasi survei awal",
            "Gambaran umum permasalahan",
        ];

        $captionIndex = $index % count($captions);
        return $captions[$captionIndex];
    }
}