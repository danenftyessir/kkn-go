<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Problem;
use App\Models\Institution;
use App\Models\Province;
use App\Models\Regency;
use Carbon\Carbon;

/**
 * problems seeder dengan data dummy yang realistis
 * 
 * SPESIFIKASI KKN:
 * - durasi proyek: minimal 3 minggu (1 bulan), maksimal 2 bulan
 * - jumlah anggota: minimal 8, maksimal 20
 * - required majors: minimal 5, maksimal 10
 * - persebaran lokasi lebih merata (menggunakan data BPS)
 * 
 * BUG FIXED (28 Okt 2025):
 * - setiap problem sekarang memiliki tanggal start dan deadline yang berbeda-beda
 * - menggunakan Carbon::create() untuk membuat instance baru setiap kali
 * - distribusi waktu: 30% masa lalu, 40% sedang berjalan, 30% masa depan
 * - gunakan copy() untuk endDate dan applicationDeadline
 * - status otomatis berdasarkan tanggal
 */
class ProblemsSeeder extends Seeder
{
    /**
     * run the database seeds
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding problems...');

        // pastikan institutions sudah ada (gunakan is_verified bukan status)
        $institutions = Institution::where('is_verified', true)->get();
        
        if ($institutions->isEmpty()) {
            $this->command->warn('⚠ Tidak ada institutions yang verified. Seed DummyDataSeeder dulu!');
            return;
        }

        // seed problems untuk setiap institution
        foreach ($institutions as $institution) {
            // setiap institution punya 2-4 problems
            $numProblems = rand(2, 4);

            for ($i = 0; $i < $numProblems; $i++) {
                $this->createProblem($institution);
            }
        }

        $totalProblems = Problem::count();
        $this->command->info("✓ {$totalProblems} problems berhasil dibuat!");
    }

    /**
     * buat problem untuk institution dengan persebaran lokasi yang lebih merata
     */
    private function createProblem($institution)
    {
        // untuk variasi lokasi, ambil province dan regency random dari database
        // 50% mengikuti lokasi institution, 50% random dari BPS data
        $useInstitutionLocation = rand(0, 1) === 1;
        
        if ($useInstitutionLocation) {
            $provinceId = $institution->province_id;
            $regencyId = $institution->regency_id;
        } else {
            // ambil random province dan regency dari BPS
            $province = Province::inRandomOrder()->first();
            $provinceId = $province->id;
            
            // ambil regency dari province tersebut
            $regency = Regency::where('province_id', $provinceId)->inRandomOrder()->first();
            $regencyId = $regency ? $regency->id : $institution->regency_id;
        }
        
        // ambil template random
        $template = $this->getProblemTemplates()[array_rand($this->getProblemTemplates())];

        // tambahkan field tambahan
        $template['institution_id'] = $institution->id;
        $template['province_id'] = $provinceId;
        $template['regency_id'] = $regencyId;
        
        // tambahkan informasi lokasi detail
        $template['village'] = $this->getVillageName();
        $template['detailed_location'] = 'Dusun ' . ['Satu', 'Dua', 'Tiga', 'Empat'][array_rand(['Satu', 'Dua', 'Tiga', 'Empat'])] . ', RT/RW ' . rand(1, 10) . '/' . rand(1, 5);

        // durasi proyek: minimal 1 bulan (3-4 minggu), maksimal 2 bulan
        $template['duration_months'] = rand(1, 2);
        
        // jumlah mahasiswa: minimal 8, maksimal 20
        $template['required_students'] = rand(8, 20);

        // generate tanggal dengan distribusi yang bervariasi
        // 30% masa lalu, 40% sedang berjalan, 30% masa depan
        $timeDistribution = rand(1, 100);
        
        if ($timeDistribution <= 30) {
            // 30% masa lalu - project sudah selesai
            $startDate = Carbon::create(2024, rand(1, 12), rand(1, 28))->startOfDay();
            $template['status'] = 'completed';
        } elseif ($timeDistribution <= 70) {
            // 40% sedang berjalan - project aktif
            $startDate = Carbon::create(2025, rand(1, 10), rand(1, 28))->startOfDay();
            $template['status'] = 'open';
        } else {
            // 30% masa depan - project belum dimulai
            $startDate = Carbon::create(2025, rand(11, 12), rand(1, 28))->startOfDay();
            $template['status'] = 'open';
        }

        // hitung end date berdasarkan durasi (gunakan copy() untuk instance baru)
        $endDate = $startDate->copy()->addMonths($template['duration_months']);
        
        // application deadline: 2-4 minggu sebelum start date (gunakan copy())
        $applicationDeadline = $startDate->copy()->subWeeks(rand(2, 4));

        // set tanggal
        $template['start_date'] = $startDate->format('Y-m-d');
        $template['end_date'] = $endDate->format('Y-m-d');
        $template['application_deadline'] = $applicationDeadline->format('Y-m-d');

        // deliverables dummy
        $template['deliverables'] = json_encode([
            'Laporan Kegiatan Lengkap',
            'Dokumentasi Foto Dan Video',
            'Modul Atau Panduan Pelaksanaan',
            'Hasil Produk/Output Program'
        ]);

        // facilities provided dummy
        $template['facilities_provided'] = json_encode([
            'Konsumsi Selama Kegiatan',
            'Transportasi Lokal',
            'Akomodasi (Jika Diperlukan)',
            'Pendampingan Lapangan'
        ]);

        // required majors: minimal 5, maksimal 10
        $template['required_majors'] = json_encode($this->getRandomMajors());

        // features
        $template['is_featured'] = rand(0, 100) < 20; // 20% chance featured
        $template['is_urgent'] = rand(0, 100) < 15; // 15% chance urgent
        $template['views_count'] = rand(50, 500);

        Problem::create($template);
    }

    /**
     * ambil nama desa random
     */
    private function getVillageName(): string
    {
        $villages = [
            'Sukamaju', 'Makmur Jaya', 'Sumber Rezeki', 'Harapan Baru', 
            'Maju Bersama', 'Cinta Damai', 'Karya Mulya', 'Sejahtera',
            'Bina Sejahtera', 'Suka Maju', 'Tanjung Sari', 'Mekar Sari',
            'Puri Agung', 'Cipta Karya', 'Sumber Makmur', 'Cahaya Abadi'
        ];
        
        return $villages[array_rand($villages)];
    }

    /**
     * ambil random majors (minimal 5, maksimal 10)
     */
    private function getRandomMajors(): array
    {
        $allMajors = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknik Sipil',
            'Teknik Elektro',
            'Teknik Mesin',
            'Arsitektur',
            'Ilmu Komunikasi',
            'Manajemen',
            'Akuntansi',
            'Ekonomi Pembangunan',
            'Administrasi Publik',
            'Ilmu Pemerintahan',
            'Hukum',
            'Psikologi',
            'Sosiologi',
            'Antropologi',
            'Kesehatan Masyarakat',
            'Keperawatan',
            'Farmasi',
            'Gizi',
            'Kedokteran',
            'Pendidikan',
            'PGSD',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Matematika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Pertanian',
            'Agribisnis',
            'Peternakan',
            'Kehutanan',
            'Perikanan',
            'Ilmu Lingkungan',
            'Pariwisata',
            'Perhotelan',
            'Desain Grafis',
            'Desain Interior',
            'Seni Rupa',
            'Broadcasting'
        ];

        // acak array
        shuffle($allMajors);
        
        // ambil 5-10 jurusan random
        $count = rand(5, 10);
        return array_slice($allMajors, 0, $count);
    }

    /**
     * template problems berdasarkan SDG
     * setiap SDG minimal 4-6 template problem
     */
    private function getProblemTemplates(): array
    {
        return [
            // ===== SDG 1: TANPA KEMISKINAN (6 templates) =====
            [
                'title' => 'Pemberdayaan Ekonomi Masyarakat Desa',
                'description' => 'Program pemberdayaan ekonomi masyarakat melalui pelatihan keterampilan dan pengembangan UMKM lokal untuk meningkatkan kesejahteraan warga.',
                'background' => 'Tingkat kemiskinan di wilayah ini masih cukup tinggi. Masyarakat memerlukan pendampingan dalam mengembangkan usaha mikro dan kecil.',
                'objectives' => 'Meningkatkan pendapatan masyarakat melalui pengembangan keterampilan dan akses pasar yang lebih luas.',
                'scope' => 'Pelatihan keterampilan, pendampingan UMKM, dan fasilitasi akses permodalan.',
                'sdg_categories' => json_encode([1, 8]),
                'required_skills' => json_encode(['Manajemen UMKM', 'Kewirausahaan', 'Marketing', 'Pemberdayaan Masyarakat']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pengentasan Kemiskinan Melalui Koperasi',
                'description' => 'Program pembentukan dan penguatan koperasi simpan pinjam untuk membantu masyarakat mengakses modal usaha dengan bunga rendah.',
                'background' => 'Akses terhadap permodalan masih terbatas, banyak warga terlilit rentenir dengan bunga tinggi.',
                'objectives' => 'Menyediakan akses permodalan yang terjangkau dan meningkatkan literasi keuangan masyarakat.',
                'scope' => 'Pembentukan koperasi, pelatihan manajemen keuangan, dan pendampingan anggota.',
                'sdg_categories' => json_encode([1, 8]),
                'required_skills' => json_encode(['Manajemen Keuangan', 'Koperasi', 'Pemberdayaan Masyarakat', 'Akuntansi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Program Bantuan Sosial Terpadu',
                'description' => 'Sistem pendataan dan distribusi bantuan sosial yang terintegrasi untuk memastikan bantuan tepat sasaran.',
                'background' => 'Masih terdapat ketidakmerataan distribusi bantuan sosial dan pendataan penerima yang belum optimal.',
                'objectives' => 'Meningkatkan efektivitas penyaluran bantuan sosial kepada masyarakat miskin.',
                'scope' => 'Pendataan penerima bantuan, sistem monitoring, dan evaluasi program bantuan.',
                'sdg_categories' => json_encode([1, 10]),
                'required_skills' => json_encode(['Sistem Informasi', 'Sosial', 'Manajemen Data', 'Administrasi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Literasi Keuangan Keluarga',
                'description' => 'Edukasi pengelolaan keuangan keluarga untuk meningkatkan kesejahteraan dan mengurangi kemiskinan.',
                'background' => 'Banyak keluarga kesulitan mengelola keuangan, sering terjerat hutang konsumtif.',
                'objectives' => 'Meningkatkan kemampuan keluarga dalam mengelola keuangan dengan bijak.',
                'scope' => 'Workshop keuangan keluarga, konseling, dan pendampingan usaha rumahan.',
                'sdg_categories' => json_encode([1, 8]),
                'required_skills' => json_encode(['Keuangan', 'Konseling', 'Pendidikan', 'Ekonomi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Pelatihan Keterampilan Warga Miskin',
                'description' => 'Program pelatihan keterampilan praktis untuk meningkatkan daya saing ekonomi warga miskin.',
                'background' => 'Kurangnya keterampilan menjadi hambatan utama dalam mendapatkan pekerjaan layak.',
                'objectives' => 'Memberikan keterampilan yang marketable untuk meningkatkan pendapatan.',
                'scope' => 'Pelatihan menjahit, tata boga, otomotif, dan keterampilan lainnya.',
                'sdg_categories' => json_encode([1, 4]),
                'required_skills' => json_encode(['Pelatihan Vokasi', 'Pemberdayaan', 'Manajemen', 'Komunikasi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Akses Perbankan Untuk Masyarakat Miskin',
                'description' => 'Program fasilitasi akses layanan perbankan dan keuangan digital untuk masyarakat miskin.',
                'background' => 'Masyarakat miskin kesulitan mengakses layanan perbankan formal.',
                'objectives' => 'Meningkatkan inklusi keuangan masyarakat miskin.',
                'scope' => 'Edukasi perbankan, pendampingan pembukaan rekening, dan literasi keuangan digital.',
                'sdg_categories' => json_encode([1, 9]),
                'required_skills' => json_encode(['Perbankan', 'Keuangan Digital', 'Teknologi', 'Pendidikan']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 2: TANPA KELAPARAN (6 templates) =====
            [
                'title' => 'Program Ketahanan Pangan Desa',
                'description' => 'Inisiatif peningkatan ketahanan pangan melalui optimalisasi lahan pertanian dan pengembangan sistem irigasi berkelanjutan.',
                'background' => 'Produksi pangan lokal masih rendah dan bergantung pada pasokan dari luar daerah.',
                'objectives' => 'Meningkatkan produksi pangan lokal dan kemandirian pangan masyarakat.',
                'scope' => 'Pelatihan pertanian modern, pengembangan irigasi, dan diversifikasi tanaman.',
                'sdg_categories' => json_encode([2, 12]),
                'required_skills' => json_encode(['Pertanian', 'Agribisnis', 'Teknologi Pertanian', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pengembangan Urban Farming',
                'description' => 'Program pemanfaatan lahan sempit di perkotaan untuk budidaya sayuran organik dan tanaman pangan.',
                'background' => 'Lahan pertanian di perkotaan terbatas, namun permintaan pangan organik terus meningkat.',
                'objectives' => 'Meningkatkan ketahanan pangan keluarga dan mengurangi biaya belanja pangan.',
                'scope' => 'Pelatihan urban farming, pemberian bibit, dan pendampingan budidaya.',
                'sdg_categories' => json_encode([2, 11]),
                'required_skills' => json_encode(['Pertanian Urban', 'Hortikultura', 'Agribisnis', 'Lingkungan']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Diversifikasi Pangan Lokal',
                'description' => 'Program pengembangan dan promosi pangan lokal non-beras untuk mengurangi ketergantungan pada beras.',
                'background' => 'Ketergantungan masyarakat pada beras sangat tinggi, perlu diversifikasi sumber karbohidrat.',
                'objectives' => 'Meningkatkan konsumsi pangan lokal alternatif seperti singkong, jagung, dan sagu.',
                'scope' => 'Sosialisasi pangan lokal, pelatihan pengolahan, dan pengembangan produk.',
                'sdg_categories' => json_encode([2, 12]),
                'required_skills' => json_encode(['Teknologi Pangan', 'Gizi', 'Pengolahan Makanan', 'Marketing']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pencegahan Stunting Melalui Edukasi Gizi',
                'description' => 'Program edukasi gizi untuk ibu hamil dan balita dalam mencegah stunting dan malnutrisi.',
                'background' => 'Angka stunting di wilayah ini masih tinggi, perlu intervensi edukasi gizi sejak dini.',
                'objectives' => 'Menurunkan angka stunting melalui peningkatan pengetahuan gizi ibu dan anak.',
                'scope' => 'Penyuluhan gizi, pemantauan pertumbuhan balita, dan pemberian makanan tambahan.',
                'sdg_categories' => json_encode([2, 3]),
                'required_skills' => json_encode(['Gizi', 'Kesehatan Masyarakat', 'Pendidikan', 'Keperawatan']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Hidroponik Dan Pertanian Modern',
                'description' => 'Pengenalan sistem hidroponik dan pertanian presisi untuk meningkatkan produktivitas dengan lahan terbatas.',
                'background' => 'Lahan pertanian terbatas, perlu teknologi pertanian modern yang efisien.',
                'objectives' => 'Meningkatkan produktivitas pertanian dengan teknologi modern dan lahan minimal.',
                'scope' => 'Pelatihan hidroponik, greenhouse, dan teknologi pertanian presisi.',
                'sdg_categories' => json_encode([2, 9]),
                'required_skills' => json_encode(['Pertanian', 'Teknologi', 'Agribisnis', 'Teknik']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Bank Pangan Desa',
                'description' => 'Pembentukan lumbung pangan desa untuk menjaga stabilitas ketersediaan pangan.',
                'background' => 'Fluktuasi harga dan ketersediaan pangan menjadi masalah, terutama saat paceklik.',
                'objectives' => 'Menjaga stabilitas pangan dan harga melalui sistem lumbung desa.',
                'scope' => 'Pembangunan lumbung, sistem pengelolaan stok, dan distribusi pangan.',
                'sdg_categories' => json_encode([2, 1]),
                'required_skills' => json_encode(['Manajemen', 'Pertanian', 'Logistik', 'Administrasi']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 3: KEHIDUPAN SEHAT DAN SEJAHTERA (6 templates) =====
            [
                'title' => 'Edukasi Kesehatan Dan Sanitasi',
                'description' => 'Program sosialisasi pentingnya sanitasi, air bersih, dan pola hidup sehat untuk meningkatkan derajat kesehatan masyarakat.',
                'background' => 'Masih banyak masyarakat yang belum memahami pentingnya sanitasi dan pola hidup sehat.',
                'objectives' => 'Meningkatkan kesadaran masyarakat tentang kesehatan dan sanitasi.',
                'scope' => 'Sosialisasi kesehatan, pembangunan MCK, dan edukasi gizi.',
                'sdg_categories' => json_encode([3, 6]),
                'required_skills' => json_encode(['Kesehatan Masyarakat', 'Pendidikan', 'Komunikasi', 'Keperawatan']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Posyandu Digital Desa',
                'description' => 'Digitalisasi sistem posyandu untuk monitoring kesehatan ibu dan anak secara real-time.',
                'background' => 'Pencatatan posyandu masih manual dan rawan kehilangan data, perlu sistem digital terintegrasi.',
                'objectives' => 'Meningkatkan efektivitas monitoring kesehatan ibu dan anak melalui digitalisasi.',
                'scope' => 'Pembuatan aplikasi posyandu, pelatihan kader, dan implementasi sistem.',
                'sdg_categories' => json_encode([3, 9]),
                'required_skills' => json_encode(['Sistem Informasi', 'Kesehatan Masyarakat', 'Teknologi', 'Manajemen']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Gerakan Hidup Sehat',
                'description' => 'Kampanye pola hidup sehat dengan fokus pada olahraga teratur dan konsumsi makanan bergizi.',
                'background' => 'Tingginya angka penyakit tidak menular akibat pola hidup tidak sehat.',
                'objectives' => 'Meningkatkan kesadaran masyarakat tentang pentingnya pola hidup sehat.',
                'scope' => 'Senam bersama, pemeriksaan kesehatan gratis, dan penyuluhan gizi.',
                'sdg_categories' => json_encode([3, 4]),
                'required_skills' => json_encode(['Kesehatan', 'Olahraga', 'Komunikasi', 'Gizi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Sistem Rujukan Kesehatan Terintegrasi',
                'description' => 'Pengembangan sistem rujukan kesehatan yang terintegrasi antara puskesmas dan rumah sakit.',
                'background' => 'Sistem rujukan kesehatan belum optimal, sering terjadi keterlambatan penanganan.',
                'objectives' => 'Meningkatkan efektivitas sistem rujukan kesehatan.',
                'scope' => 'Pembuatan SOP rujukan, sistem informasi, dan koordinasi antar fasilitas kesehatan.',
                'sdg_categories' => json_encode([3, 9]),
                'required_skills' => json_encode(['Kesehatan Masyarakat', 'Manajemen', 'Sistem Informasi', 'Administrasi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Program Lansia Sehat',
                'description' => 'Pemberdayaan dan peningkatan kesehatan lansia melalui posyandu lansia dan senam rutin.',
                'background' => 'Jumlah lansia meningkat namun pelayanan kesehatan khusus lansia masih terbatas.',
                'objectives' => 'Meningkatkan kualitas hidup dan kesehatan lansia.',
                'scope' => 'Posyandu lansia, senam lansia, dan pemeriksaan kesehatan berkala.',
                'sdg_categories' => json_encode([3, 10]),
                'required_skills' => json_encode(['Kesehatan', 'Keperawatan', 'Gerontologi', 'Sosial']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pengendalian Penyakit Menular',
                'description' => 'Program pencegahan dan pengendalian penyakit menular berbasis masyarakat.',
                'background' => 'Masih tingginya angka penyakit menular seperti DBD, TB, dan diare.',
                'objectives' => 'Menurunkan angka kesakitan dan kematian akibat penyakit menular.',
                'scope' => 'Surveilans penyakit, edukasi pencegahan, dan gerakan bersih lingkungan.',
                'sdg_categories' => json_encode([3, 6]),
                'required_skills' => json_encode(['Kesehatan Masyarakat', 'Epidemiologi', 'Pendidikan', 'Lingkungan']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 4: PENDIDIKAN BERKUALITAS (6 templates) =====
            [
                'title' => 'Literasi Digital Untuk Anak Desa',
                'description' => 'Program pelatihan penggunaan komputer dan internet untuk anak-anak usia sekolah di desa.',
                'background' => 'Kesenjangan digital antara kota dan desa masih tinggi, anak-anak desa perlu akses teknologi.',
                'objectives' => 'Meningkatkan literasi digital anak-anak desa.',
                'scope' => 'Pelatihan komputer, internet safety, dan pembelajaran online.',
                'sdg_categories' => json_encode([4, 10]),
                'required_skills' => json_encode(['Teknologi Informasi', 'Pendidikan', 'Komunikasi', 'Psikologi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Taman Baca Masyarakat',
                'description' => 'Pembangunan dan pengelolaan taman baca untuk meningkatkan minat baca masyarakat, terutama anak-anak.',
                'background' => 'Minat baca masyarakat masih rendah dan akses terhadap buku terbatas.',
                'objectives' => 'Meningkatkan literasi dan minat baca masyarakat.',
                'scope' => 'Pengadaan buku, penataan ruang baca, dan program literasi.',
                'sdg_categories' => json_encode([4, 10]),
                'required_skills' => json_encode(['Pendidikan', 'Manajemen Perpustakaan', 'Komunikasi', 'Sastra']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Bimbingan Belajar Gratis',
                'description' => 'Program bimbingan belajar gratis untuk anak kurang mampu di desa.',
                'background' => 'Banyak anak kurang mampu tidak bisa mengakses bimbingan belajar berbayar.',
                'objectives' => 'Meningkatkan prestasi akademik anak kurang mampu.',
                'scope' => 'Bimbel mata pelajaran, motivasi belajar, dan konseling pendidikan.',
                'sdg_categories' => json_encode([4, 1]),
                'required_skills' => json_encode(['Pendidikan', 'Tutoring', 'Konseling', 'Psikologi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Pelatihan Guru PAUD',
                'description' => 'Program peningkatan kompetensi guru PAUD melalui pelatihan metode pembelajaran modern.',
                'background' => 'Kompetensi guru PAUD masih perlu ditingkatkan untuk pendidikan anak usia dini yang optimal.',
                'objectives' => 'Meningkatkan kualitas pembelajaran PAUD di desa.',
                'scope' => 'Workshop metode pembelajaran, pembuatan APE, dan pendampingan guru.',
                'sdg_categories' => json_encode([4, 5]),
                'required_skills' => json_encode(['Pendidikan', 'PAUD', 'Psikologi Anak', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Revitalisasi Seni Dan Budaya Lokal',
                'description' => 'Program pelestarian dan pengembangan seni budaya tradisional untuk generasi muda.',
                'background' => 'Seni budaya lokal mulai terlupakan, perlu revitalisasi untuk generasi muda.',
                'objectives' => 'Melestarikan dan mengembangkan seni budaya lokal.',
                'scope' => 'Pelatihan seni tradisional, dokumentasi budaya, dan festival seni.',
                'sdg_categories' => json_encode([4, 11]),
                'required_skills' => json_encode(['Seni', 'Budaya', 'Dokumentasi', 'Pendidikan']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Sekolah Inklusi Ramah Disabilitas',
                'description' => 'Program peningkatan aksesibilitas pendidikan bagi anak berkebutuhan khusus.',
                'background' => 'Anak berkebutuhan khusus kesulitan mengakses pendidikan yang layak.',
                'objectives' => 'Meningkatkan aksesibilitas dan kualitas pendidikan bagi ABK.',
                'scope' => 'Adaptasi fasilitas, pelatihan guru, dan pendampingan siswa ABK.',
                'sdg_categories' => json_encode([4, 10]),
                'required_skills' => json_encode(['Pendidikan Khusus', 'Psikologi', 'Terapi', 'Arsitektur']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 5: KESETARAAN GENDER (4 templates) =====
            [
                'title' => 'Pemberdayaan Perempuan Desa',
                'description' => 'Program pelatihan keterampilan dan kewirausahaan untuk meningkatkan kemandirian ekonomi perempuan desa.',
                'background' => 'Partisipasi ekonomi perempuan masih rendah, perlu pemberdayaan melalui keterampilan.',
                'objectives' => 'Meningkatkan kemandirian ekonomi perempuan desa.',
                'scope' => 'Pelatihan keterampilan, pengembangan usaha, dan akses permodalan.',
                'sdg_categories' => json_encode([5, 8]),
                'required_skills' => json_encode(['Kewirausahaan', 'Keterampilan', 'Pemberdayaan', 'Gender']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Kepemimpinan Perempuan Dalam Desa',
                'description' => 'Program pelatihan kepemimpinan untuk meningkatkan partisipasi perempuan dalam pengambilan keputusan desa.',
                'background' => 'Partisipasi perempuan dalam kepemimpinan desa masih minim, perlu penguatan kapasitas.',
                'objectives' => 'Meningkatkan keterlibatan dan kapasitas perempuan dalam pengambilan keputusan desa.',
                'scope' => 'Pelatihan kepemimpinan, public speaking, dan manajemen organisasi.',
                'sdg_categories' => json_encode([5, 16]),
                'required_skills' => json_encode(['Kepemimpinan', 'Pemberdayaan', 'Manajemen', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pencegahan Kekerasan Terhadap Perempuan',
                'description' => 'Program edukasi dan pencegahan kekerasan berbasis gender di masyarakat.',
                'background' => 'Kasus kekerasan terhadap perempuan masih tinggi namun sering tidak terlaporkan.',
                'objectives' => 'Menurunkan angka kekerasan terhadap perempuan dan meningkatkan kesadaran gender.',
                'scope' => 'Sosialisasi anti-kekerasan, pendampingan korban, dan pembentukan support group.',
                'sdg_categories' => json_encode([5, 16]),
                'required_skills' => json_encode(['Hukum', 'Psikologi', 'Sosial', 'Konseling']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Perempuan Dan Teknologi',
                'description' => 'Program pelatihan teknologi informasi khusus untuk perempuan desa.',
                'background' => 'Kesenjangan gender dalam akses teknologi masih tinggi di pedesaan.',
                'objectives' => 'Meningkatkan literasi digital perempuan desa.',
                'scope' => 'Pelatihan komputer, media sosial untuk bisnis, dan aplikasi produktivitas.',
                'sdg_categories' => json_encode([5, 9]),
                'required_skills' => json_encode(['Teknologi Informasi', 'Pendidikan', 'Gender Studies', 'Marketing']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 6: AIR BERSIH DAN SANITASI (5 templates) =====
            [
                'title' => 'Akses Air Bersih Dan Sanitasi',
                'description' => 'Pembangunan infrastruktur air bersih dan fasilitas sanitasi untuk meningkatkan kualitas hidup masyarakat.',
                'background' => 'Akses air bersih masih terbatas, banyak warga yang menggunakan sumber air tidak layak.',
                'objectives' => 'Menyediakan akses air bersih yang layak untuk seluruh warga.',
                'scope' => 'Pembangunan sumur bor, instalasi pipa, dan fasilitas MCK.',
                'sdg_categories' => json_encode([6, 3]),
                'required_skills' => json_encode(['Teknik Sipil', 'Kesehatan Lingkungan', 'Manajemen Proyek', 'Geologi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Sistem Filtrasi Air Sederhana',
                'description' => 'Program pembuatan dan distribusi alat filtrasi air sederhana untuk rumah tangga.',
                'background' => 'Kualitas air sumur masih kurang baik, perlu teknologi filtrasi yang terjangkau.',
                'objectives' => 'Meningkatkan akses terhadap air bersih melalui teknologi filtrasi sederhana.',
                'scope' => 'Pelatihan pembuatan filter, distribusi alat, dan monitoring kualitas air.',
                'sdg_categories' => json_encode([6, 3]),
                'required_skills' => json_encode(['Teknik Lingkungan', 'Kesehatan', 'Teknologi Tepat Guna', 'Kimia']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Konservasi Mata Air Desa',
                'description' => 'Program pelestarian dan rehabilitasi mata air sebagai sumber air bersih komunitas.',
                'background' => 'Debit mata air semakin menurun akibat kerusakan lingkungan sekitar.',
                'objectives' => 'Menjaga kelestarian mata air dan meningkatkan debit air.',
                'scope' => 'Reboisasi area mata air, pembuatan sumur resapan, dan edukasi konservasi.',
                'sdg_categories' => json_encode([6, 15]),
                'required_skills' => json_encode(['Lingkungan', 'Kehutanan', 'Konservasi', 'Hidrologi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Sanitasi Total Berbasis Masyarakat',
                'description' => 'Program perubahan perilaku sanitasi untuk mencapai desa bebas buang air besar sembarangan.',
                'background' => 'Masih ada warga yang buang air besar sembarangan, mengancam kesehatan publik.',
                'objectives' => 'Mencapai status Open Defecation Free (ODF) di desa.',
                'scope' => 'Pemicuan STBM, pembangunan jamban, dan monitoring perilaku.',
                'sdg_categories' => json_encode([6, 3]),
                'required_skills' => json_encode(['Kesehatan Masyarakat', 'Sanitasi', 'Pemberdayaan', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pengelolaan Limbah Rumah Tangga',
                'description' => 'Sistem pengelolaan limbah rumah tangga yang ramah lingkungan.',
                'background' => 'Limbah rumah tangga belum dikelola dengan baik, mencemari lingkungan.',
                'objectives' => 'Mengurangi pencemaran dari limbah rumah tangga.',
                'scope' => 'Pembuatan septic tank komunal, biofilter, dan edukasi pengelolaan limbah.',
                'sdg_categories' => json_encode([6, 11]),
                'required_skills' => json_encode(['Teknik Lingkungan', 'Kesehatan', 'Teknologi', 'Manajemen']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 7: ENERGI BERSIH DAN TERJANGKAU (4 templates) =====
            [
                'title' => 'Energi Terbarukan Desa',
                'description' => 'Program pemanfaatan energi terbarukan seperti biogas dan solar panel untuk kebutuhan rumah tangga.',
                'background' => 'Biaya energi tinggi dan ketergantungan pada energi fosil masih besar.',
                'objectives' => 'Mengurangi biaya energi dan ketergantungan pada energi fosil.',
                'scope' => 'Instalasi biogas, solar panel, dan edukasi energi terbarukan.',
                'sdg_categories' => json_encode([7, 13]),
                'required_skills' => json_encode(['Teknik', 'Energi Terbarukan', 'Lingkungan', 'Fisika']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Lampu Tenaga Surya',
                'description' => 'Instalasi lampu jalan tenaga surya untuk meningkatkan penerangan di desa.',
                'background' => 'Penerangan jalan di desa masih minim, menghambat aktivitas malam hari.',
                'objectives' => 'Meningkatkan penerangan jalan dengan teknologi ramah lingkungan.',
                'scope' => 'Instalasi lampu surya, perawatan berkala, dan edukasi teknologi.',
                'sdg_categories' => json_encode([7, 11]),
                'required_skills' => json_encode(['Teknik Elektro', 'Energi Surya', 'Infrastruktur', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Kompor Biogas Rumah Tangga',
                'description' => 'Program pembuatan dan pemanfaatan kompor biogas dari limbah ternak.',
                'background' => 'Limbah ternak melimpah namun belum dimanfaatkan optimal untuk energi.',
                'objectives' => 'Mengurangi penggunaan LPG dan memanfaatkan limbah ternak.',
                'scope' => 'Pembuatan digester biogas, instalasi kompor, dan pelatihan perawatan.',
                'sdg_categories' => json_encode([7, 12]),
                'required_skills' => json_encode(['Teknik', 'Pertanian', 'Energi', 'Lingkungan']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Hemat Energi Rumah Tangga',
                'description' => 'Kampanye dan edukasi penggunaan energi hemat di rumah tangga.',
                'background' => 'Konsumsi energi rumah tangga tinggi akibat kurangnya kesadaran hemat energi.',
                'objectives' => 'Mengurangi konsumsi energi melalui perubahan perilaku.',
                'scope' => 'Sosialisasi hemat energi, audit energi rumah, dan tips praktis.',
                'sdg_categories' => json_encode([7, 13]),
                'required_skills' => json_encode(['Teknik', 'Komunikasi', 'Lingkungan', 'Pendidikan']),
                'difficulty_level' => 'beginner',
            ],

            // ===== SDG 8: PEKERJAAN LAYAK (5 templates) =====
            [
                'title' => 'Job Fair Dan Pelatihan Kerja',
                'description' => 'Program bursa kerja dan pelatihan untuk menghubungkan pencari kerja dengan lowongan.',
                'background' => 'Tingkat pengangguran masih tinggi, perlu fasilitasi akses pekerjaan.',
                'objectives' => 'Mengurangi pengangguran melalui job matching dan peningkatan skill.',
                'scope' => 'Job fair, pelatihan interview, dan workshop soft skills.',
                'sdg_categories' => json_encode([8]),
                'required_skills' => json_encode(['HR', 'Pelatihan', 'Karir', 'Psikologi', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Kewirausahaan Digital',
                'description' => 'Pelatihan memulai bisnis online untuk generasi muda menggunakan platform digital.',
                'background' => 'Peluang ekonomi digital belum dimanfaatkan optimal oleh masyarakat.',
                'objectives' => 'Meningkatkan jumlah wirausaha digital dan akses pasar online.',
                'scope' => 'Pelatihan e-commerce, digital marketing, dan mentoring bisnis.',
                'sdg_categories' => json_encode([8, 9]),
                'required_skills' => json_encode(['Digital Marketing', 'E-commerce', 'Kewirausahaan', 'Desain']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Wisata Desa Berkelanjutan',
                'description' => 'Pengembangan desa wisata dengan konsep eco-tourism dan community based.',
                'background' => 'Potensi wisata alam dan budaya belum dikembangkan optimal.',
                'objectives' => 'Meningkatkan pendapatan masyarakat melalui wisata berkelanjutan.',
                'scope' => 'Pelatihan pemandu, pengembangan paket wisata, dan promosi digital.',
                'sdg_categories' => json_encode([8, 11]),
                'required_skills' => json_encode(['Pariwisata', 'Marketing', 'Manajemen', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Sertifikasi Produk UMKM',
                'description' => 'Pendampingan UMKM dalam mendapatkan sertifikasi produk untuk akses pasar lebih luas.',
                'background' => 'Produk UMKM sulit masuk pasar modern karena belum bersertifikat.',
                'objectives' => 'Meningkatkan daya saing UMKM melalui sertifikasi produk.',
                'scope' => 'Pendampingan sertifikasi halal, PIRT, SNI, dan akses pasar.',
                'sdg_categories' => json_encode([8, 9]),
                'required_skills' => json_encode(['Manajemen', 'UMKM', 'Teknologi Pangan', 'Regulasi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Pekerja Migran Aman',
                'description' => 'Program edukasi dan perlindungan untuk calon pekerja migran.',
                'background' => 'Banyak pekerja migran menjadi korban penipuan dan perlakuan tidak layak.',
                'objectives' => 'Melindungi hak-hak pekerja migran dan mencegah penipuan.',
                'scope' => 'Edukasi prosedur migrasi, pendampingan legal, dan support group keluarga.',
                'sdg_categories' => json_encode([8, 16]),
                'required_skills' => json_encode(['Hukum', 'Sosial', 'Konseling', 'Administrasi']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 11: KOTA DAN KOMUNITAS BERKELANJUTAN (6 templates) =====
            [
                'title' => 'Pengelolaan Sampah Ramah Lingkungan',
                'description' => 'Program pengelolaan sampah terpadu dengan sistem 3R (Reduce, Reuse, Recycle) dan pembuatan kompos.',
                'background' => 'Masalah sampah semakin menumpuk, perlu sistem pengelolaan yang berkelanjutan.',
                'objectives' => 'Mengurangi volume sampah dan menciptakan lingkungan yang bersih.',
                'scope' => 'Bank sampah, pengomposan, dan edukasi pengelolaan sampah.',
                'sdg_categories' => json_encode([11, 12]),
                'required_skills' => json_encode(['Lingkungan', 'Manajemen', 'Pendidikan Masyarakat', 'Teknologi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Taman Hijau Dan Ruang Terbuka Publik',
                'description' => 'Pembangunan dan revitalisasi taman sebagai ruang publik yang ramah keluarga.',
                'background' => 'Kurangnya ruang terbuka hijau mengurangi kualitas hidup masyarakat perkotaan.',
                'objectives' => 'Menyediakan ruang publik yang nyaman dan hijau untuk warga.',
                'scope' => 'Penataan taman, penanaman pohon, dan fasilitas publik.',
                'sdg_categories' => json_encode([11, 13]),
                'required_skills' => json_encode(['Arsitektur Lanskap', 'Perencanaan Kota', 'Lingkungan', 'Teknik Sipil']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Sistem Transportasi Ramah Lingkungan',
                'description' => 'Pengembangan jalur sepeda dan promosi transportasi publik untuk mengurangi polusi.',
                'background' => 'Ketergantungan pada kendaraan pribadi tinggi, menyebabkan kemacetan dan polusi.',
                'objectives' => 'Meningkatkan penggunaan transportasi ramah lingkungan.',
                'scope' => 'Pembuatan jalur sepeda, promosi bike sharing, dan edukasi transportasi publik.',
                'sdg_categories' => json_encode([11, 13]),
                'required_skills' => json_encode(['Transportasi', 'Perencanaan Kota', 'Lingkungan', 'Komunikasi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Pusat Daur Ulang Komunitas',
                'description' => 'Pembangunan pusat daur ulang sampah untuk menghasilkan produk bernilai ekonomi.',
                'background' => 'Sampah menumpuk dan belum dimanfaatkan sebagai bahan daur ulang.',
                'objectives' => 'Mengolah sampah menjadi produk bernilai ekonomi.',
                'scope' => 'Pembangunan fasilitas, pelatihan daur ulang, dan pemasaran produk.',
                'sdg_categories' => json_encode([11, 12]),
                'required_skills' => json_encode(['Lingkungan', 'Kewirausahaan', 'Desain', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Smart Village Teknologi',
                'description' => 'Implementasi teknologi IoT untuk monitoring dan pengelolaan infrastruktur desa.',
                'background' => 'Pengelolaan infrastruktur desa belum optimal, perlu teknologi smart village.',
                'objectives' => 'Meningkatkan efisiensi pengelolaan infrastruktur desa dengan teknologi.',
                'scope' => 'Instalasi sensor IoT, dashboard monitoring, dan pelatihan operator.',
                'sdg_categories' => json_encode([11, 9]),
                'required_skills' => json_encode(['Teknik Informatika', 'IoT', 'Elektronika', 'Manajemen Data']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Penataan Permukiman Kumuh',
                'description' => 'Program penataan dan revitalisasi kawasan permukiman kumuh.',
                'background' => 'Permukiman kumuh mengurangi kualitas hidup dan kesehatan warga.',
                'objectives' => 'Meningkatkan kualitas permukiman dan kesejahteraan warga.',
                'scope' => 'Penataan fisik, perbaikan infrastruktur, dan pemberdayaan ekonomi.',
                'sdg_categories' => json_encode([11, 1]),
                'required_skills' => json_encode(['Arsitektur', 'Teknik Sipil', 'Sosial', 'Perencanaan']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 12: KONSUMSI BERTANGGUNG JAWAB (4 templates) =====
            [
                'title' => 'Kampanye Zero Waste',
                'description' => 'Program edukasi dan implementasi gaya hidup zero waste di komunitas.',
                'background' => 'Produksi sampah terus meningkat, perlu perubahan pola konsumsi masyarakat.',
                'objectives' => 'Mengurangi produksi sampah melalui perubahan pola konsumsi.',
                'scope' => 'Workshop zero waste, kampanye mengurangi plastik, dan promosi produk ramah lingkungan.',
                'sdg_categories' => json_encode([12, 11]),
                'required_skills' => json_encode(['Lingkungan', 'Komunikasi', 'Pendidikan', 'Marketing']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Produk Lokal Berkelanjutan',
                'description' => 'Promosi dan pengembangan produk lokal yang ramah lingkungan.',
                'background' => 'Produk lokal kurang dikenal, padahal memiliki potensi ekonomi dan lingkungan.',
                'objectives' => 'Meningkatkan konsumsi produk lokal berkelanjutan.',
                'scope' => 'Branding produk, pelatihan packaging, dan akses pasar.',
                'sdg_categories' => json_encode([12, 8]),
                'required_skills' => json_encode(['Marketing', 'Branding', 'Kewirausahaan', 'Desain']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Fashion Berkelanjutan',
                'description' => 'Program edukasi dan produksi fashion dari bahan daur ulang.',
                'background' => 'Industri fashion menghasilkan limbah besar, perlu alternatif berkelanjutan.',
                'objectives' => 'Mengurangi limbah fashion melalui upcycling dan daur ulang.',
                'scope' => 'Pelatihan upcycling pakaian, produksi tas dari sampah, dan fashion show.',
                'sdg_categories' => json_encode([12, 13]),
                'required_skills' => json_encode(['Desain Fashion', 'Kewirausahaan', 'Lingkungan', 'Marketing']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Packaging Ramah Lingkungan',
                'description' => 'Pengembangan dan promosi kemasan ramah lingkungan untuk UMKM.',
                'background' => 'UMKM masih menggunakan kemasan plastik yang tidak ramah lingkungan.',
                'objectives' => 'Mengganti packaging plastik dengan alternatif ramah lingkungan.',
                'scope' => 'Pelatihan pembuatan packaging, sourcing bahan, dan desain kemasan.',
                'sdg_categories' => json_encode([12, 8]),
                'required_skills' => json_encode(['Desain', 'Teknologi Pangan', 'Lingkungan', 'UMKM']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 13: PENANGANAN PERUBAHAN IKLIM (5 templates) =====
            [
                'title' => 'Penghijauan Dan Konservasi Lingkungan',
                'description' => 'Program penanaman pohon dan konservasi lahan untuk mencegah erosi dan meningkatkan kualitas lingkungan.',
                'background' => 'Lahan kritis semakin luas, diperlukan upaya penghijauan dan konservasi.',
                'objectives' => 'Meningkatkan tutupan hijau dan mencegah kerusakan lingkungan.',
                'scope' => 'Penanaman pohon, pembuatan terasering, dan edukasi lingkungan.',
                'sdg_categories' => json_encode([13, 15]),
                'required_skills' => json_encode(['Kehutanan', 'Lingkungan', 'Pertanian', 'Konservasi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Mitigasi Bencana Berbasis Masyarakat',
                'description' => 'Program peningkatan kapasitas masyarakat dalam menghadapi bencana alam.',
                'background' => 'Wilayah ini rawan bencana, perlu peningkatan kesiapsiagaan masyarakat.',
                'objectives' => 'Meningkatkan ketahanan masyarakat terhadap bencana alam.',
                'scope' => 'Pelatihan tanggap bencana, pembentukan tim relawan, dan early warning system.',
                'sdg_categories' => json_encode([13, 11]),
                'required_skills' => json_encode(['Kebencanaan', 'Sosial', 'Manajemen Risiko', 'Geografi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Adaptasi Perubahan Iklim Pertanian',
                'description' => 'Program adaptasi teknologi pertanian terhadap perubahan iklim dan cuaca ekstrem.',
                'background' => 'Perubahan iklim berdampak pada produktivitas pertanian, perlu adaptasi teknologi.',
                'objectives' => 'Meningkatkan ketahanan pertanian terhadap perubahan iklim.',
                'scope' => 'Pelatihan teknologi adaptasi, pemilihan varietas tahan, dan sistem irigasi efisien.',
                'sdg_categories' => json_encode([13, 2]),
                'required_skills' => json_encode(['Pertanian', 'Klimatologi', 'Teknologi', 'Agronomi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Kampung Iklim Pintar',
                'description' => 'Program adaptasi dan mitigasi perubahan iklim tingkat desa.',
                'background' => 'Dampak perubahan iklim semakin terasa, perlu aksi konkrit di level desa.',
                'objectives' => 'Membangun desa yang tangguh terhadap perubahan iklim.',
                'scope' => 'Penanaman pohon, rainwater harvesting, dan energi terbarukan.',
                'sdg_categories' => json_encode([13, 7]),
                'required_skills' => json_encode(['Lingkungan', 'Energi', 'Pemberdayaan', 'Teknologi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Monitoring Kualitas Udara',
                'description' => 'Sistem monitoring kualitas udara berbasis komunitas.',
                'background' => 'Polusi udara meningkat namun belum ada sistem monitoring yang memadai.',
                'objectives' => 'Meningkatkan kesadaran dan monitoring kualitas udara.',
                'scope' => 'Instalasi sensor udara, dashboard monitoring, dan kampanye udara bersih.',
                'sdg_categories' => json_encode([13, 3]),
                'required_skills' => json_encode(['Teknik Lingkungan', 'Teknologi', 'Data Science', 'Kesehatan']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 16: PERDAMAIAN DAN KEADILAN (4 templates) =====
            [
                'title' => 'Transparansi Dana Desa',
                'description' => 'Sistem informasi transparansi pengelolaan dana desa untuk meningkatkan akuntabilitas.',
                'background' => 'Informasi penggunaan dana desa belum transparan, perlu sistem yang aksesibel.',
                'objectives' => 'Meningkatkan transparansi dan akuntabilitas pengelolaan dana desa.',
                'scope' => 'Pembuatan portal informasi, sosialisasi, dan monitoring partisipatif.',
                'sdg_categories' => json_encode([16, 17]),
                'required_skills' => json_encode(['Sistem Informasi', 'Pemerintahan', 'Komunikasi', 'Web Development']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Layanan Hukum Gratis Masyarakat',
                'description' => 'Program konsultasi dan pendampingan hukum gratis untuk masyarakat kurang mampu.',
                'background' => 'Akses terhadap layanan hukum masih mahal dan sulit dijangkau masyarakat miskin.',
                'objectives' => 'Meningkatkan akses keadilan bagi masyarakat kurang mampu.',
                'scope' => 'Konsultasi hukum, pendampingan kasus, dan edukasi hukum.',
                'sdg_categories' => json_encode([16, 10]),
                'required_skills' => json_encode(['Hukum', 'Konseling', 'Sosial', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pencegahan Korupsi Desa',
                'description' => 'Program edukasi anti-korupsi dan sistem pengawasan partisipatif di desa.',
                'background' => 'Potensi korupsi di level desa masih tinggi, perlu pengawasan masyarakat.',
                'objectives' => 'Meningkatkan integritas dan pengawasan pengelolaan dana desa.',
                'scope' => 'Edukasi anti-korupsi, pembentukan tim pengawas, dan sistem pelaporan.',
                'sdg_categories' => json_encode([16, 17]),
                'required_skills' => json_encode(['Hukum', 'Pemerintahan', 'Komunikasi', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Mediasi Konflik Komunitas',
                'description' => 'Program pelatihan mediasi dan penyelesaian konflik berbasis kearifan lokal.',
                'background' => 'Konflik horizontal di masyarakat perlu mekanisme penyelesaian yang damai.',
                'objectives' => 'Meningkatkan kapasitas penyelesaian konflik secara damai.',
                'scope' => 'Pelatihan mediator, fasilitasi dialog, dan dokumentasi kearifan lokal.',
                'sdg_categories' => json_encode([16, 11]),
                'required_skills' => json_encode(['Hukum', 'Sosial', 'Antropologi', 'Psikologi']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 17: KEMITRAAN (4 templates) =====
            [
                'title' => 'Kemitraan Universitas-Desa',
                'description' => 'Program kemitraan strategis antara universitas dan desa untuk pembangunan berkelanjutan.',
                'background' => 'Potensi kerjasama akademisi dan masyarakat belum optimal untuk pembangunan desa.',
                'objectives' => 'Membangun kemitraan berkelanjutan untuk pengembangan desa.',
                'scope' => 'Riset bersama, transfer teknologi, dan program pemberdayaan.',
                'sdg_categories' => json_encode([17, 4]),
                'required_skills' => json_encode(['Manajemen Proyek', 'Penelitian', 'Pemberdayaan', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Digitalisasi Administrasi Desa',
                'description' => 'Transformasi digital pelayanan administrasi desa untuk efisiensi dan transparansi.',
                'background' => 'Pelayanan administrasi masih manual dan memakan waktu lama.',
                'objectives' => 'Mempercepat pelayanan administrasi melalui sistem digital.',
                'scope' => 'Pembuatan sistem informasi desa, pelatihan perangkat, dan sosialisasi.',
                'sdg_categories' => json_encode([17, 9]),
                'required_skills' => json_encode(['Sistem Informasi', 'Pemerintahan', 'Teknologi', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Forum Multi Stakeholder Desa',
                'description' => 'Pembentukan forum komunikasi antar stakeholder untuk pembangunan desa.',
                'background' => 'Koordinasi antar stakeholder belum optimal, perlu forum komunikasi.',
                'objectives' => 'Meningkatkan koordinasi dan kolaborasi pembangunan desa.',
                'scope' => 'Pembentukan forum, fasilitasi pertemuan, dan dokumentasi kesepakatan.',
                'sdg_categories' => json_encode([17, 16]),
                'required_skills' => json_encode(['Manajemen', 'Komunikasi', 'Fasilitasi', 'Administrasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Inkubasi Bisnis Desa',
                'description' => 'Program inkubasi dan akselerasi bisnis untuk wirausaha muda di desa.',
                'background' => 'Wirausaha muda di desa butuh pendampingan dan akses ke mentor bisnis.',
                'objectives' => 'Mengembangkan ekosistem kewirausahaan di desa.',
                'scope' => 'Mentoring bisnis, akses permodalan, dan networking dengan investor.',
                'sdg_categories' => json_encode([17, 8]),
                'required_skills' => json_encode(['Kewirausahaan', 'Manajemen', 'Marketing', 'Keuangan']),
                'difficulty_level' => 'advanced',
            ],
        ];
    }
}