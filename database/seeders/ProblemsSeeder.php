<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Institution;
use App\Models\Problem;
use App\Models\ProblemImage;
use Carbon\Carbon;

/**
 * seeder untuk problems (proyek/masalah) dari berbagai instansi
 * NOTE: seeder ini hanya untuk problems, tidak seed provinces/regencies/universities
 * karena sudah di-seed oleh DummyDataSeeder
 * 
 * jalankan: php artisan db:seed --class=ProblemsSeeder
 */
class ProblemsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        // cek apakah data provinces dan institutions sudah ada
        if (Province::count() === 0 || Institution::count() === 0) {
            $this->command->warn('⚠️  Tidak ada provinces atau institutions.');
            $this->command->warn('Jalankan DummyDataSeeder terlebih dahulu!');
            return;
        }

        $this->command->info('Seeding problems...');

        // ambil data yang sudah ada dengan eager loading untuk menghindari lazy loading violation
        $institutions = Institution::with(['province', 'regency'])->get();

        if ($institutions->isEmpty()) {
            $this->command->error('Tidak ada institutions. Seed DummyDataSeeder dulu!');
            return;
        }

        // seed problems untuk setiap institution
        foreach ($institutions as $institution) {
            // setiap institution punya 1-3 problems
            $numProblems = rand(1, 3);

            for ($i = 0; $i < $numProblems; $i++) {
                $this->createProblem($institution);
            }
        }

        $totalProblems = Problem::count();
        $this->command->info("✓ {$totalProblems} problems berhasil dibuat!");
    }

    /**
     * buat problem untuk institution
     */
    private function createProblem($institution)
    {
        // template problems berdasarkan SDG
        $problemTemplates = [
            // sdg 1: no poverty
            [
                'title' => 'Pemberdayaan Ekonomi Masyarakat',
                'description' => 'Program pemberdayaan ekonomi masyarakat melalui pelatihan keterampilan dan pengembangan UMKM lokal untuk meningkatkan kesejahteraan warga.',
                'background' => 'Tingkat kemiskinan di wilayah ini masih cukup tinggi. Masyarakat memerlukan pendampingan dalam mengembangkan usaha mikro dan kecil.',
                'objectives' => 'Meningkatkan pendapatan masyarakat melalui pengembangan keterampilan dan akses pasar yang lebih luas.',
                'scope' => 'Pelatihan keterampilan, pendampingan UMKM, dan fasilitasi akses permodalan.',
                'sdg' => [1, 8],
                'skills' => ['Manajemen', 'Marketing', 'Komunikasi', 'Pembukuan'],
                'difficulty' => 'intermediate',
            ],
            // sdg 2: zero hunger
            [
                'title' => 'Ketahanan Pangan Desa',
                'description' => 'Program peningkatan ketahanan pangan melalui optimalisasi lahan pertanian dan diversifikasi tanaman pangan.',
                'background' => 'Desa menghadapi tantangan ketahanan pangan akibat lahan pertanian yang terbatas dan monokultur.',
                'objectives' => 'Meningkatkan produksi pangan lokal dan diversifikasi tanaman untuk ketahanan pangan berkelanjutan.',
                'scope' => 'Survey lahan, pelatihan pertanian organik, dan pendampingan petani.',
                'sdg' => [2, 12],
                'skills' => ['Pertanian', 'Analisis Data', 'Penyuluhan'],
                'difficulty' => 'beginner',
            ],
            // sdg 3: good health
            [
                'title' => 'Edukasi Kesehatan Masyarakat',
                'description' => 'Program edukasi kesehatan untuk meningkatkan kesadaran masyarakat tentang pola hidup sehat dan pencegahan penyakit.',
                'background' => 'Tingkat kesadaran masyarakat tentang kesehatan masih rendah, diperlukan program edukasi berkelanjutan.',
                'objectives' => 'Meningkatkan pengetahuan dan kesadaran masyarakat tentang kesehatan preventif.',
                'scope' => 'Penyuluhan kesehatan, pembuatan media edukasi, dan program posyandu.',
                'sdg' => [3],
                'skills' => ['Kesehatan Masyarakat', 'Komunikasi', 'Desain Grafis'],
                'difficulty' => 'beginner',
            ],
            // sdg 4: quality education
            [
                'title' => 'Peningkatan Literasi Digital',
                'description' => 'Program peningkatan literasi digital untuk siswa dan guru di sekolah-sekolah desa.',
                'background' => 'Era digital menuntut kemampuan literasi digital yang memadai. Namun akses dan pemahaman teknologi di desa masih terbatas.',
                'objectives' => 'Meningkatkan kemampuan literasi digital masyarakat desa, khususnya siswa dan guru.',
                'scope' => 'Pelatihan digital, pembuatan konten edukatif, dan pendampingan penggunaan teknologi.',
                'sdg' => [4, 9],
                'skills' => ['Teknologi Informasi', 'Pendidikan', 'Public Speaking'],
                'difficulty' => 'intermediate',
            ],
            // sdg 6: clean water
            [
                'title' => 'Akses Air Bersih Berkelanjutan',
                'description' => 'Program penyediaan akses air bersih melalui pembangunan sistem distribusi air dan edukasi konservasi air.',
                'background' => 'Masyarakat mengalami kesulitan akses air bersih, terutama di musim kemarau.',
                'objectives' => 'Menyediakan akses air bersih yang berkelanjutan dan meningkatkan kesadaran konservasi air.',
                'scope' => 'Survey sumber air, desain sistem distribusi, dan program konservasi air.',
                'sdg' => [6, 11],
                'skills' => ['Teknik Sipil', 'Analisis Data', 'Survei Lapangan'],
                'difficulty' => 'advanced',
            ],
            // sdg 7: affordable energy
            [
                'title' => 'Energi Terbarukan untuk Desa',
                'description' => 'Implementasi energi terbarukan (solar panel) untuk penerangan dan kebutuhan energi masyarakat desa.',
                'background' => 'Akses listrik masih terbatas dan biaya energi tinggi. Energi terbarukan menjadi solusi berkelanjutan.',
                'objectives' => 'Menyediakan akses energi bersih dan terjangkau melalui pemanfaatan energi surya.',
                'scope' => 'Studi kelayakan, instalasi solar panel, dan pelatihan maintenance.',
                'sdg' => [7, 13],
                'skills' => ['Teknik Elektro', 'Manajemen Proyek', 'Analisis Teknis'],
                'difficulty' => 'advanced',
            ],
            // sdg 11: sustainable cities
            [
                'title' => 'Pengelolaan Sampah Ramah Lingkungan',
                'description' => 'Program pengelolaan sampah berbasis 3R (Reduce, Reuse, Recycle) dan bank sampah untuk desa bersih dan sehat.',
                'background' => 'Volume sampah meningkat tanpa sistem pengelolaan yang baik, menyebabkan pencemaran lingkungan.',
                'objectives' => 'Mengurangi volume sampah dan meningkatkan kesadaran masyarakat tentang pengelolaan sampah.',
                'scope' => 'Pembentukan bank sampah, pelatihan 3R, dan kampanye lingkungan.',
                'sdg' => [11, 12],
                'skills' => ['Lingkungan', 'Manajemen', 'Sosialisasi'],
                'difficulty' => 'intermediate',
            ],
            // sdg 8: decent work
            [
                'title' => 'Pengembangan Pariwisata Desa',
                'description' => 'Pengembangan potensi wisata desa untuk menciptakan lapangan kerja dan meningkatkan ekonomi lokal.',
                'background' => 'Desa memiliki potensi wisata alam dan budaya yang belum teroptimalkan.',
                'objectives' => 'Mengembangkan desa wisata yang berkelanjutan dan menciptakan lapangan kerja baru.',
                'scope' => 'Mapping potensi wisata, pelatihan pemandu wisata, dan strategi marketing.',
                'sdg' => [8, 11],
                'skills' => ['Pariwisata', 'Marketing', 'Fotografi', 'Desain'],
                'difficulty' => 'intermediate',
            ],
        ];

        // pilih random template
        $template = $problemTemplates[array_rand($problemTemplates)];

        // tentukan lokasi (gunakan lokasi institution)
        $provinceId = $institution->province_id;
        $regencyId = $institution->regency_id;

        // nama lokasi untuk title (gunakan nama regency dari relasi yang sudah di-load)
        $locationName = $institution->regency ? $institution->regency->name : 'Indonesia';

        // tentukan timeline
        $startDate = Carbon::now()->addMonths(rand(1, 3));
        $durationMonths = rand(2, 6);
        $endDate = $startDate->copy()->addMonths($durationMonths);
        $applicationDeadline = $startDate->copy()->subWeeks(2);

        // tentukan status dengan distribusi realistis
        $rand = rand(1, 100);
        if ($rand <= 60) {
            $status = 'open'; // 60% open
        } elseif ($rand <= 80) {
            $status = 'in_progress'; // 20% in_progress
        } elseif ($rand <= 95) {
            $status = 'completed'; // 15% completed
        } else {
            $status = 'draft'; // 5% draft
        }

        // buat problem
        $problem = Problem::create([
            'institution_id' => $institution->id,
            'title' => $template['title'] . ' - ' . $locationName,
            'description' => $template['description'],
            'background' => $template['background'],
            'objectives' => $template['objectives'],
            'scope' => $template['scope'],
            'province_id' => $provinceId,
            'regency_id' => $regencyId,
            'village' => 'Desa ' . ['Sukamaju', 'Mekarjaya', 'Ciherang', 'Sindanglaya', 'Cibodas'][array_rand(['Sukamaju', 'Mekarjaya', 'Ciherang', 'Sindanglaya', 'Cibodas'])],
            'detailed_location' => null,
            'sdg_categories' => json_encode($template['sdg']),
            'required_students' => rand(3, 8),
            'required_skills' => json_encode($template['skills']),
            'required_majors' => json_encode([
                'Teknik Informatika',
                'Sistem Informasi',
                'Ilmu Komunikasi',
                'Manajemen',
                'Kesehatan Masyarakat'
            ]),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'application_deadline' => $applicationDeadline,
            'duration_months' => $durationMonths,
            'difficulty_level' => $template['difficulty'],
            'status' => $status,
            'expected_outcomes' => 'Peningkatan kualitas hidup masyarakat dan tercapainya target SDGs yang dicanangkan.',
            'deliverables' => json_encode([
                'Laporan hasil survei',
                'Dokumentasi kegiatan',
                'Laporan akhir program',
                'Rekomendasi kebijakan'
            ]),
            'facilities_provided' => json_encode([
                'Akomodasi',
                'Konsumsi',
                'Transportasi lokal',
                'Sertifikat',
                'Bimbingan lapangan'
            ]),
            'views_count' => rand(10, 500),
            'applications_count' => 0, // akan di-update oleh ApplicationsSeeder
            'accepted_students' => 0,
            'is_featured' => rand(1, 100) <= 20, // 20% featured
            'is_urgent' => rand(1, 100) <= 10, // 10% urgent
        ]);

        // TODO: tambahkan problem images jika ada
        // ProblemImage::create([...]);
    }
}