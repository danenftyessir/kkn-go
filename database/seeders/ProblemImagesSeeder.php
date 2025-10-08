<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Problem;
use App\Models\ProblemImage;

/**
 * seeder untuk problem images
 * simple version - langsung hardcode list file yang ada di supabase
 * tidak perlu upload, cukup simpan path saja
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

        // hardcode list file yang ada di supabase bucket "kkn-go storage"
        // file-file ini harus benar-benar sudah ada di supabase storage
        $availableImages = [
            'problems/masalah-desa-1.jpg',
            'problems/masalah-desa-2.jpg',
            'problems/masalah-desa-3.jpeg',
            'problems/masalah-desa-4.jpg',
            'problems/masalah-desa-5.jpeg',
            'problems/masalah-desa-6.jpg',
            'problems/masalah-desa-7.jpeg',
            'problems/masalah-desa-8.jpeg',
            'problems/masalah-desa-9.jpg',
            'problems/masalah-desa-10.jpg',
            'problems/masalah-desa-11.jpg',
            'problems/masalah-desa-12.jpg',
            'problems/masalah-desa-13.jpg',
            'problems/masalah-desa-14.jpg',
            'problems/masalah-desa-15.jpg',
            'problems/masalah-desa-15.jpeg',
            'problems/masalah-desa-16.jpeg',
            'problems/masalah-desa-17.jpeg',
            'problems/masalah-desa-18.jpeg',
            'problems/masalah-desa-19.jpeg',
            'problems/masalah-desa-20.jpeg',
            'problems/masalah-desa-21.jpeg',
            'problems/masalah-desa-22.jpeg',
            'problems/masalah-desa-23.jpeg',
            'problems/masalah-desa-24.jpg',
            'problems/masalah-desa-25.jpeg',
            'problems/masalah-desa-26.jpeg',
            'problems/masalah-desa-27.jpeg',
            'problems/masalah-desa-28.jpg',
            'problems/masalah-desa-29.jpg',
            'problems/masalah-desa-30.jpeg',
        ];

        $this->command->info("🖼️  Ditemukan " . count($availableImages) . " gambar");

        // hapus problem images lama untuk clean seeding
        ProblemImage::truncate();

        $imageIndex = 0;
        $totalSeeded = 0;

        // distribusikan gambar ke setiap problem
        foreach ($problems as $problem) {
            // setiap problem dapat 2-4 gambar secara acak
            $imagesPerProblem = min(rand(2, 4), count($availableImages));
            
            for ($i = 0; $i < $imagesPerProblem; $i++) {
                // cycle through images untuk distribusi merata
                if ($imageIndex >= count($availableImages)) {
                    $imageIndex = 0;
                }

                $imagePath = $availableImages[$imageIndex];
                $imageIndex++;
                
                // gambar pertama (order 0) menjadi cover
                $isCover = ($i === 0);
                
                // buat record problem image
                ProblemImage::create([
                    'problem_id' => $problem->id,
                    'image_path' => $imagePath,
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
     * 
     * @param Problem $problem instance problem
     * @param int $index index gambar (0, 1, 2, dst)
     * @return string caption untuk gambar
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

        // pilih caption berdasarkan index (cyclic)
        $captionIndex = $index % count($captions);
        return $captions[$captionIndex];
    }
}