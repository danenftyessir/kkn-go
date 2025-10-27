<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Problem;
use App\Models\ProblemImage;

/**
 * seeder untuk problem images
 * extended version - menggunakan gambar baru dan minimalisir duplikasi
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

        // daftar lengkap gambar yang tersedia di supabase storage
        // gabungan gambar lama dan gambar baru
        $availableImages = [
            // gambar lama (1-30)
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
            
            // gambar baru (31-94)
            'problems/masalah-desa-31.JPG',
            'problems/masalah-desa-32.JPG',
            'problems/masalah-desa-33.JPG',
            'problems/masalah-desa-34.JPG',
            'problems/masalah-desa-35.JPG',
            'problems/masalah-desa-36.JPG',
            'problems/masalah-desa-37.JPG',
            'problems/masalah-desa-38.JPG',
            'problems/masalah-desa-39.JPG',
            'problems/masalah-desa-40.JPG',
            'problems/masalah-desa-41.JPG',
            'problems/masalah-desa-42.JPG',
            'problems/masalah-desa-43.JPG',
            'problems/masalah-desa-44.JPG',
            'problems/masalah-desa-45.JPG',
            'problems/masalah-desa-46.JPG',
            'problems/masalah-desa-47.JPG',
            'problems/masalah-desa-48.JPG',
            'problems/masalah-desa-49.JPG',
            'problems/masalah-desa-50.JPG',
            'problems/masalah-desa-51.JPG',
            'problems/masalah-desa-52.JPG',
            'problems/masalah-desa-53.JPG',
            'problems/masalah-desa-54.JPG',
            'problems/masalah-desa-55.JPG',
            'problems/masalah-desa-56.JPG',
            'problems/masalah-desa-57.JPG',
            'problems/masalah-desa-58.JPG',
            'problems/masalah-desa-59.JPG',
            'problems/masalah-desa-60.JPG',
            'problems/masalah-desa-61.JPG',
            'problems/masalah-desa-62.JPG',
            'problems/masalah-desa-63.JPG',
            'problems/masalah-desa-64.JPG',
            'problems/masalah-desa-65.JPG',
            'problems/masalah-desa-66.JPG',
            'problems/masalah-desa-67.JPG',
            'problems/masalah-desa-68.JPG',
            'problems/masalah-desa-69.JPG',
            'problems/masalah-desa-70.JPG',
            'problems/masalah-desa-71.JPG',
            'problems/masalah-desa-72.JPG',
            'problems/masalah-desa-73.JPG',
            'problems/masalah-desa-74.JPG',
            'problems/masalah-desa-75.JPG',
            'problems/masalah-desa-76.JPG',
            'problems/masalah-desa-77.JPG',
            'problems/masalah-desa-78.JPG',
            'problems/masalah-desa-79.JPG',
            'problems/masalah-desa-80.JPG',
            'problems/masalah-desa-81.JPG',
            'problems/masalah-desa-82.JPG',
            'problems/masalah-desa-83.JPG',
            'problems/masalah-desa-84.JPG',
            'problems/masalah-desa-85.JPG',
            'problems/masalah-desa-86.JPG',
            'problems/masalah-desa-87.JPG',
            'problems/masalah-desa-88.JPG',
            'problems/masalah-desa-89.JPG',
            'problems/masalah-desa-90.JPG',
            'problems/masalah-desa-91.JPG',
            'problems/masalah-desa-92.JPG',
            'problems/masalah-desa-93.JPG',
            'problems/masalah-desa-94.JPG',
        ];

        $this->command->info("🖼️  Ditemukan " . count($availableImages) . " gambar");
        $this->command->info("📁 Total problems: " . $problems->count());

        // shuffle gambar untuk random assignment dan minimalisir duplikasi
        shuffle($availableImages);

        // track gambar yang sudah digunakan untuk minimalisir duplikasi
        $usedImages = [];
        $imageIndex = 0;

        // assign gambar ke setiap problem
        foreach ($problems as $problem) {
            // setiap problem dapat 2-4 gambar
            $numImages = rand(2, 4);
            $assignedImages = [];

            for ($i = 0; $i < $numImages; $i++) {
                // ambil gambar yang belum digunakan atau paling sedikit digunakan
                $selectedImage = null;
                $minUsageCount = PHP_INT_MAX;

                // cari gambar dengan usage paling rendah
                foreach ($availableImages as $image) {
                    $usageCount = $usedImages[$image] ?? 0;
                    
                    // skip jika gambar sudah di-assign ke problem ini
                    if (in_array($image, $assignedImages)) {
                        continue;
                    }

                    if ($usageCount < $minUsageCount) {
                        $minUsageCount = $usageCount;
                        $selectedImage = $image;
                    }
                }

                // jika semua gambar sudah digunakan untuk problem ini, ambil random
                if ($selectedImage === null) {
                    $selectedImage = $availableImages[array_rand($availableImages)];
                }

                // track image yang dipilih
                $assignedImages[] = $selectedImage;
                $usedImages[$selectedImage] = ($usedImages[$selectedImage] ?? 0) + 1;

                // caption bervariasi
                $captions = [
                    'Kondisi lapangan saat ini',
                    'Situasi yang memerlukan perhatian',
                    'Area pelaksanaan program',
                    'Dokumentasi kondisi eksisting',
                    'Lokasi kegiatan',
                    'Gambaran permasalahan',
                    'Foto survei lokasi',
                    'Kondisi infrastruktur',
                    'Aktivitas masyarakat',
                    'Potensi yang dapat dikembangkan',
                ];

                // buat problem image
                ProblemImage::create([
                    'problem_id' => $problem->id,
                    'image_path' => $selectedImage,
                    'caption' => $captions[array_rand($captions)],
                    'order' => $i + 1,
                ]);
            }

            $this->command->info("  ✓ Problem #{$problem->id}: {$numImages} gambar ditambahkan");
        }

        // tampilkan statistik penggunaan gambar
        $this->command->newLine();
        $this->command->info('📊 Statistik Penggunaan Gambar:');
        
        $totalUsage = array_sum($usedImages);
        $uniqueImages = count($usedImages);
        $avgUsage = $totalUsage / max($uniqueImages, 1);
        
        $this->command->info("  - Total penggunaan: {$totalUsage}");
        $this->command->info("  - Gambar unik digunakan: {$uniqueImages} dari " . count($availableImages));
        $this->command->info("  - Rata-rata penggunaan per gambar: " . round($avgUsage, 2));
        
        // gambar yang paling sering digunakan
        arsort($usedImages);
        $topUsed = array_slice($usedImages, 0, 5, true);
        $this->command->info("  - Top 5 gambar paling sering digunakan:");
        foreach ($topUsed as $image => $count) {
            $this->command->info("    • " . basename($image) . ": {$count}x");
        }

        $this->command->newLine();
        $totalImages = ProblemImage::count();
        $this->command->info("✅ {$totalImages} problem images berhasil dibuat!");
        $this->command->info("✨ Duplikasi gambar diminimalisir dengan algoritma distribusi merata");
    }
}