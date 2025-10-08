<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Institution;
use App\Models\Problem;
use Carbon\Carbon;

/**
 * seeder untuk problems (proyek/masalah) dari berbagai instansi
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

        // ambil data yang sudah ada dengan eager loading
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
                'sdg_categories' => json_encode(['no_poverty', 'decent_work']),
                'required_skills' => json_encode(['Manajemen UMKM', 'Kewirausahaan', 'Marketing']),
                'difficulty_level' => 'intermediate',
            ],
            // sdg 2: zero hunger
            [
                'title' => 'Program Ketahanan Pangan Desa',
                'description' => 'Inisiatif peningkatan ketahanan pangan melalui optimalisasi lahan pertanian dan pengembangan sistem irigasi berkelanjutan.',
                'background' => 'Produksi pangan lokal masih rendah dan bergantung pada pasokan dari luar daerah.',
                'objectives' => 'Meningkatkan produksi pangan lokal dan kemandirian pangan masyarakat.',
                'scope' => 'Pelatihan pertanian modern, pengembangan irigasi, dan diversifikasi tanaman.',
                'sdg_categories' => json_encode(['zero_hunger', 'responsible_consumption']),
                'required_skills' => json_encode(['Pertanian', 'Agribisnis', 'Teknologi Pertanian']),
                'difficulty_level' => 'intermediate',
            ],
            // sdg 3: good health
            [
                'title' => 'Edukasi Kesehatan dan Sanitasi',
                'description' => 'Program sosialisasi pentingnya sanitasi, air bersih, dan pola hidup sehat untuk meningkatkan derajat kesehatan masyarakat.',
                'background' => 'Masih banyak masyarakat yang belum memahami pentingnya sanitasi dan pola hidup sehat.',
                'objectives' => 'Meningkatkan kesadaran masyarakat tentang kesehatan dan sanitasi.',
                'scope' => 'Sosialisasi kesehatan, pembangunan MCK, dan edukasi gizi.',
                'sdg_categories' => json_encode(['good_health', 'clean_water']),
                'required_skills' => json_encode(['Kesehatan Masyarakat', 'Pendidikan', 'Komunikasi']),
                'difficulty_level' => 'beginner',
            ],
            // sdg 4: quality education
            [
                'title' => 'Literasi Digital untuk Generasi Muda',
                'description' => 'Program pengenalan teknologi informasi dan literasi digital untuk anak-anak usia sekolah di daerah pedesaan.',
                'background' => 'Gap literasi digital antara desa dan kota masih tinggi, perlu peningkatan akses dan pengetahuan teknologi.',
                'objectives' => 'Meningkatkan kemampuan literasi digital generasi muda.',
                'scope' => 'Pelatihan komputer dasar, internet sehat, dan penggunaan aplikasi edukatif.',
                'sdg_categories' => json_encode(['quality_education', 'reduced_inequalities']),
                'required_skills' => json_encode(['Teknologi Informasi', 'Pendidikan', 'Komunikasi']),
                'difficulty_level' => 'beginner',
            ],
            // sdg 5: gender equality
            [
                'title' => 'Pemberdayaan Perempuan Melalui Keterampilan',
                'description' => 'Program pelatihan keterampilan untuk perempuan dalam bidang kerajinan tangan dan kewirausahaan.',
                'background' => 'Partisipasi ekonomi perempuan masih rendah, perlu peningkatan keterampilan dan akses modal.',
                'objectives' => 'Meningkatkan kemandirian ekonomi perempuan melalui pengembangan keterampilan.',
                'scope' => 'Pelatihan menjahit, kerajinan, dan manajemen usaha kecil.',
                'sdg_categories' => json_encode(['gender_equality', 'decent_work']),
                'required_skills' => json_encode(['Kewirausahaan', 'Kerajinan', 'Manajemen Usaha']),
                'difficulty_level' => 'intermediate',
            ],
            // sdg 6: clean water
            [
                'title' => 'Akses Air Bersih dan Sanitasi',
                'description' => 'Pembangunan infrastruktur air bersih dan fasilitas sanitasi untuk meningkatkan kualitas hidup masyarakat.',
                'background' => 'Akses air bersih masih terbatas, banyak warga yang menggunakan sumber air tidak layak.',
                'objectives' => 'Menyediakan akses air bersih yang layak untuk seluruh warga.',
                'scope' => 'Pembangunan sumur bor, instalasi pipa, dan fasilitas MCK.',
                'sdg_categories' => json_encode(['clean_water', 'good_health']),
                'required_skills' => json_encode(['Teknik Sipil', 'Kesehatan Lingkungan', 'Manajemen Proyek']),
                'difficulty_level' => 'advanced',
            ],
            // sdg 7: affordable energy
            [
                'title' => 'Implementasi Energi Terbarukan',
                'description' => 'Penerapan teknologi energi terbarukan seperti panel surya dan biogas untuk mengurangi ketergantungan pada energi fosil.',
                'background' => 'Biaya energi tinggi dan akses listrik masih terbatas di beberapa area.',
                'objectives' => 'Menyediakan akses energi bersih dan terjangkau untuk masyarakat.',
                'scope' => 'Instalasi panel surya, pembuatan biogas, dan edukasi hemat energi.',
                'sdg_categories' => json_encode(['affordable_energy', 'climate_action']),
                'required_skills' => json_encode(['Teknik Elektro', 'Energi Terbarukan', 'Lingkungan']),
                'difficulty_level' => 'advanced',
            ],
            // sdg 11: sustainable cities
            [
                'title' => 'Pengelolaan Sampah Ramah Lingkungan',
                'description' => 'Program pengelolaan sampah terpadu dengan sistem 3R (Reduce, Reuse, Recycle) dan pembuatan kompos.',
                'background' => 'Masalah sampah semakin menumpuk, perlu sistem pengelolaan yang berkelanjutan.',
                'objectives' => 'Mengurangi volume sampah dan menciptakan lingkungan yang bersih.',
                'scope' => 'Bank sampah, pengomposan, dan edukasi pengelolaan sampah.',
                'sdg_categories' => json_encode(['sustainable_cities', 'responsible_consumption']),
                'required_skills' => json_encode(['Lingkungan', 'Manajemen', 'Pendidikan Masyarakat']),
                'difficulty_level' => 'intermediate',
            ],
            // sdg 13: climate action
            [
                'title' => 'Penghijauan dan Konservasi Lingkungan',
                'description' => 'Program penanaman pohon dan konservasi lahan untuk mencegah erosi dan meningkatkan kualitas lingkungan.',
                'background' => 'Lahan kritis semakin luas, diperlukan upaya penghijauan dan konservasi.',
                'objectives' => 'Meningkatkan tutupan hijau dan mencegah kerusakan lingkungan.',
                'scope' => 'Penanaman pohon, pembuatan terasering, dan edukasi lingkungan.',
                'sdg_categories' => json_encode(['climate_action', 'life_on_land']),
                'required_skills' => json_encode(['Kehutanan', 'Lingkungan', 'Pertanian']),
                'difficulty_level' => 'beginner',
            ],
        ];

        // pilih template random
        $template = $problemTemplates[array_rand($problemTemplates)];

        // tentukan tanggal
        $startDate = Carbon::now()->addMonths(rand(1, 3));
        $durationMonths = rand(2, 4);
        $endDate = $startDate->copy()->addMonths($durationMonths);
        $applicationDeadline = $startDate->copy()->subWeeks(2);

        // buat problem
        Problem::create([
            'institution_id' => $institution->id,
            'title' => $template['title'],
            'description' => $template['description'],
            'background' => $template['background'],
            'objectives' => $template['objectives'],
            'scope' => $template['scope'],
            'province_id' => $institution->province_id,
            'regency_id' => $institution->regency_id,
            'village' => 'Desa ' . $this->generateVillageName(),
            'detailed_location' => 'RT ' . rand(1, 5) . '/RW ' . rand(1, 3) . ', Desa ' . $this->generateVillageName(),
            'sdg_categories' => $template['sdg_categories'],
            'required_students' => rand(2, 5),
            'required_skills' => $template['required_skills'],
            'required_majors' => json_encode($this->getRandomMajors()),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'application_deadline' => $applicationDeadline,
            'duration_months' => $durationMonths,
            'difficulty_level' => $template['difficulty_level'],
            'expected_outcomes' => 'Peningkatan kualitas hidup masyarakat dan pemberdayaan komunitas lokal.',
            'deliverables' => json_encode([
                'Laporan survei awal',
                'Dokumentasi kegiatan',
                'Laporan akhir program',
                'Evaluasi dampak'
            ]),
            'facilities_provided' => json_encode([
                'Akomodasi',
                'Konsumsi',
                'Transportasi lokal',
                'Sertifikat'
            ]),
            'status' => 'open',
        ]);
    }

    /**
     * generate nama desa random
     */
    private function generateVillageName(): string
    {
        $prefixes = ['Suka', 'Mekar', 'Jaya', 'Maju', 'Sentosa', 'Bahagia', 'Makmur', 'Sejahtera'];
        $suffixes = ['maju', 'jaya', 'raya', 'asri', 'indah', 'mulya', 'makmur', 'santosa'];

        return $prefixes[array_rand($prefixes)] . $suffixes[array_rand($suffixes)];
    }

    /**
     * dapatkan random majors
     */
    private function getRandomMajors(): array
    {
        $allMajors = [
            'Teknik Sipil',
            'Teknik Elektro',
            'Teknik Industri',
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen',
            'Ekonomi Pembangunan',
            'Akuntansi',
            'Ilmu Komunikasi',
            'Sosiologi',
            'Kesehatan Masyarakat',
            'Gizi',
            'Pertanian',
            'Kehutanan',
            'Peternakan',
            'Pendidikan',
        ];

        // ambil 2-4 jurusan random
        $count = rand(2, 4);
        $selectedMajors = [];

        for ($i = 0; $i < $count; $i++) {
            $major = $allMajors[array_rand($allMajors)];
            if (!in_array($major, $selectedMajors)) {
                $selectedMajors[] = $major;
            }
        }

        return $selectedMajors;
    }
}