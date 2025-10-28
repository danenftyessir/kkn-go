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
 * versi extended - menambah lebih banyak variasi problems tanpa duplikasi judul
 * 
 * SPESIFIKASI KKN:
 * - durasi proyek: minimal 3 minggu, maksimal 2 bulan
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

        // seed problems untuk setiap institution - tambah lebih banyak
        foreach ($institutions as $institution) {
            // setiap institution punya 2-4 problems (lebih banyak dari sebelumnya)
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
        
        // template problems berdasarkan SDG - diperluas dengan lebih banyak variasi
        $problemTemplates = [
            // ===== SDG 1: NO POVERTY =====
            [
                'title' => 'Pemberdayaan Ekonomi Masyarakat Desa',
                'description' => 'Program pemberdayaan ekonomi masyarakat melalui pelatihan keterampilan dan pengembangan UMKM lokal untuk meningkatkan kesejahteraan warga.',
                'background' => 'Tingkat kemiskinan di wilayah ini masih cukup tinggi. Masyarakat memerlukan pendampingan dalam mengembangkan usaha mikro dan kecil.',
                'objectives' => 'Meningkatkan pendapatan masyarakat melalui pengembangan keterampilan dan akses pasar yang lebih luas.',
                'scope' => 'Pelatihan keterampilan, pendampingan UMKM, dan fasilitasi akses permodalan.',
                'sdg_categories' => json_encode(['no_poverty', 'decent_work']),
                'required_skills' => json_encode(['Manajemen UMKM', 'Kewirausahaan', 'Marketing']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pengentasan Kemiskinan Melalui Koperasi',
                'description' => 'Program pembentukan dan penguatan koperasi simpan pinjam untuk membantu masyarakat mengakses modal usaha dengan bunga rendah.',
                'background' => 'Akses terhadap permodalan masih terbatas, banyak warga terlilit rentenir dengan bunga tinggi.',
                'objectives' => 'Menyediakan akses permodalan yang terjangkau dan meningkatkan literasi keuangan masyarakat.',
                'scope' => 'Pembentukan koperasi, pelatihan manajemen keuangan, dan pendampingan anggota.',
                'sdg_categories' => json_encode(['no_poverty', 'decent_work']),
                'required_skills' => json_encode(['Manajemen Keuangan', 'Koperasi', 'Pemberdayaan Masyarakat']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Program Bantuan Sosial Terpadu',
                'description' => 'Sistem pendataan dan distribusi bantuan sosial yang terintegrasi untuk memastikan bantuan tepat sasaran.',
                'background' => 'Masih terdapat ketidakmerataan distribusi bantuan sosial dan pendataan penerima yang belum optimal.',
                'objectives' => 'Meningkatkan efektivitas penyaluran bantuan sosial kepada masyarakat miskin.',
                'scope' => 'Pendataan penerima bantuan, sistem monitoring, dan evaluasi program bantuan.',
                'sdg_categories' => json_encode(['no_poverty', 'reduced_inequalities']),
                'required_skills' => json_encode(['Sistem Informasi', 'Sosial', 'Manajemen Data']),
                'difficulty_level' => 'beginner',
            ],

            // ===== SDG 2: ZERO HUNGER =====
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
            [
                'title' => 'Pengembangan Urban Farming',
                'description' => 'Program pemanfaatan lahan sempit di perkotaan untuk budidaya sayuran organik dan tanaman pangan.',
                'background' => 'Lahan pertanian di perkotaan terbatas, namun permintaan pangan organik terus meningkat.',
                'objectives' => 'Meningkatkan ketahanan pangan keluarga dan mengurangi biaya belanja pangan.',
                'scope' => 'Pelatihan urban farming, pemberian bibit, dan pendampingan budidaya.',
                'sdg_categories' => json_encode(['zero_hunger', 'sustainable_cities']),
                'required_skills' => json_encode(['Pertanian Urban', 'Hortikultura', 'Agribisnis']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Diversifikasi Pangan Lokal',
                'description' => 'Program pengembangan dan promosi pangan lokal non-beras untuk mengurangi ketergantungan pada beras.',
                'background' => 'Ketergantungan masyarakat pada beras sangat tinggi, perlu diversifikasi sumber karbohidrat.',
                'objectives' => 'Meningkatkan konsumsi pangan lokal alternatif seperti singkong, jagung, dan sagu.',
                'scope' => 'Sosialisasi pangan lokal, pelatihan pengolahan, dan pengembangan produk.',
                'sdg_categories' => json_encode(['zero_hunger', 'responsible_consumption']),
                'required_skills' => json_encode(['Teknologi Pangan', 'Gizi', 'Pengolahan Makanan']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pencegahan Stunting Melalui Edukasi Gizi',
                'description' => 'Program edukasi gizi untuk ibu hamil dan balita dalam mencegah stunting dan malnutrisi.',
                'background' => 'Angka stunting di wilayah ini masih tinggi, perlu intervensi edukasi gizi sejak dini.',
                'objectives' => 'Menurunkan angka stunting melalui peningkatan pengetahuan gizi ibu dan anak.',
                'scope' => 'Penyuluhan gizi, pemantauan pertumbuhan balita, dan pemberian makanan tambahan.',
                'sdg_categories' => json_encode(['zero_hunger', 'good_health']),
                'required_skills' => json_encode(['Gizi', 'Kesehatan Masyarakat', 'Pendidikan']),
                'difficulty_level' => 'beginner',
            ],

            // ===== SDG 3: GOOD HEALTH =====
            [
                'title' => 'Edukasi Kesehatan Dan Sanitasi',
                'description' => 'Program sosialisasi pentingnya sanitasi, air bersih, dan pola hidup sehat untuk meningkatkan derajat kesehatan masyarakat.',
                'background' => 'Masih banyak masyarakat yang belum memahami pentingnya sanitasi dan pola hidup sehat.',
                'objectives' => 'Meningkatkan kesadaran masyarakat tentang kesehatan dan sanitasi.',
                'scope' => 'Sosialisasi kesehatan, pembangunan MCK, dan edukasi gizi.',
                'sdg_categories' => json_encode(['good_health', 'clean_water']),
                'required_skills' => json_encode(['Kesehatan Masyarakat', 'Pendidikan', 'Komunikasi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Posyandu Digital Desa',
                'description' => 'Digitalisasi sistem posyandu untuk monitoring kesehatan ibu dan anak secara real-time.',
                'background' => 'Pencatatan posyandu masih manual dan rawan kehilangan data, perlu sistem digital terintegrasi.',
                'objectives' => 'Meningkatkan efektivitas monitoring kesehatan ibu dan anak melalui digitalisasi.',
                'scope' => 'Pembuatan aplikasi posyandu, pelatihan kader, dan implementasi sistem.',
                'sdg_categories' => json_encode(['good_health', 'industry_innovation']),
                'required_skills' => json_encode(['Sistem Informasi', 'Kesehatan Masyarakat', 'Teknologi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Gerakan Hidup Sehat',
                'description' => 'Kampanye pola hidup sehat dengan fokus pada olahraga teratur dan konsumsi makanan bergizi.',
                'background' => 'Tingginya angka penyakit tidak menular akibat pola hidup tidak sehat.',
                'objectives' => 'Meningkatkan kesadaran masyarakat tentang pentingnya pola hidup sehat.',
                'scope' => 'Senam bersama, pemeriksaan kesehatan gratis, dan penyuluhan gizi.',
                'sdg_categories' => json_encode(['good_health', 'quality_education']),
                'required_skills' => json_encode(['Kesehatan', 'Olahraga', 'Komunikasi']),
                'difficulty_level' => 'beginner',
            ],

            // ===== SDG 4: QUALITY EDUCATION =====
            [
                'title' => 'Literasi Digital Untuk Anak Desa',
                'description' => 'Program pelatihan penggunaan komputer dan internet untuk anak-anak usia sekolah di desa.',
                'background' => 'Kesenjangan digital antara kota dan desa masih tinggi, anak-anak desa perlu akses teknologi.',
                'objectives' => 'Meningkatkan literasi digital anak-anak desa.',
                'scope' => 'Pelatihan komputer, internet safety, dan pembelajaran online.',
                'sdg_categories' => json_encode(['quality_education', 'reduced_inequalities']),
                'required_skills' => json_encode(['Teknologi Informasi', 'Pendidikan', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Taman Baca Masyarakat',
                'description' => 'Pembangunan dan pengelolaan taman baca untuk meningkatkan minat baca masyarakat, terutama anak-anak.',
                'background' => 'Minat baca masyarakat masih rendah dan akses terhadap buku terbatas.',
                'objectives' => 'Meningkatkan literasi dan minat baca masyarakat.',
                'scope' => 'Pengadaan buku, penataan ruang baca, dan program literasi.',
                'sdg_categories' => json_encode(['quality_education', 'reduced_inequalities']),
                'required_skills' => json_encode(['Pendidikan', 'Manajemen Perpustakaan', 'Komunikasi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Bimbingan Belajar Gratis',
                'description' => 'Program bimbingan belajar gratis untuk anak kurang mampu di desa.',
                'background' => 'Banyak anak kurang mampu tidak bisa mengakses bimbingan belajar berbayar.',
                'objectives' => 'Meningkatkan prestasi akademik anak kurang mampu.',
                'scope' => 'Bimbel mata pelajaran, motivasi belajar, dan konseling pendidikan.',
                'sdg_categories' => json_encode(['quality_education', 'no_poverty']),
                'required_skills' => json_encode(['Pendidikan', 'Tutoring', 'Konseling']),
                'difficulty_level' => 'beginner',
            ],

            // ===== SDG 5: GENDER EQUALITY =====
            [
                'title' => 'Pemberdayaan Perempuan Desa',
                'description' => 'Program pelatihan keterampilan dan kewirausahaan untuk meningkatkan kemandirian ekonomi perempuan desa.',
                'background' => 'Partisipasi ekonomi perempuan masih rendah, perlu pemberdayaan melalui keterampilan.',
                'objectives' => 'Meningkatkan kemandirian ekonomi perempuan desa.',
                'scope' => 'Pelatihan keterampilan, pengembangan usaha, dan akses permodalan.',
                'sdg_categories' => json_encode(['gender_equality', 'decent_work']),
                'required_skills' => json_encode(['Kewirausahaan', 'Keterampilan', 'Pemberdayaan']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Kepemimpinan Perempuan Dalam Desa',
                'description' => 'Program pelatihan kepemimpinan untuk meningkatkan partisipasi perempuan dalam pengambilan keputusan desa.',
                'background' => 'Partisipasi perempuan dalam kepemimpinan desa masih minim, perlu penguatan kapasitas.',
                'objectives' => 'Meningkatkan keterlibatan dan kapasitas perempuan dalam pengambilan keputusan desa.',
                'scope' => 'Pelatihan kepemimpinan, public speaking, dan manajemen organisasi.',
                'sdg_categories' => json_encode(['gender_equality', 'peace_justice']),
                'required_skills' => json_encode(['Kepemimpinan', 'Pemberdayaan', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 6: CLEAN WATER =====
            [
                'title' => 'Akses Air Bersih Dan Sanitasi',
                'description' => 'Pembangunan infrastruktur air bersih dan fasilitas sanitasi untuk meningkatkan kualitas hidup masyarakat.',
                'background' => 'Akses air bersih masih terbatas, banyak warga yang menggunakan sumber air tidak layak.',
                'objectives' => 'Menyediakan akses air bersih yang layak untuk seluruh warga.',
                'scope' => 'Pembangunan sumur bor, instalasi pipa, dan fasilitas MCK.',
                'sdg_categories' => json_encode(['clean_water', 'good_health']),
                'required_skills' => json_encode(['Teknik Sipil', 'Kesehatan Lingkungan', 'Manajemen Proyek']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Sistem Filtrasi Air Sederhana',
                'description' => 'Program pembuatan dan distribusi alat filtrasi air sederhana untuk rumah tangga.',
                'background' => 'Kualitas air sumur masih kurang baik, perlu teknologi filtrasi yang terjangkau.',
                'objectives' => 'Meningkatkan akses terhadap air bersih melalui teknologi filtrasi sederhana.',
                'scope' => 'Pelatihan pembuatan filter, distribusi alat, dan monitoring kualitas air.',
                'sdg_categories' => json_encode(['clean_water', 'good_health']),
                'required_skills' => json_encode(['Teknik Lingkungan', 'Kesehatan', 'Teknologi Tepat Guna']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Konservasi Mata Air Desa',
                'description' => 'Program pelestarian dan rehabilitasi mata air sebagai sumber air bersih komunitas.',
                'background' => 'Debit mata air semakin menurun akibat kerusakan lingkungan sekitar.',
                'objectives' => 'Menjaga kelestarian mata air dan meningkatkan debit air.',
                'scope' => 'Reboisasi area mata air, pembuatan sumur resapan, dan edukasi konservasi.',
                'sdg_categories' => json_encode(['clean_water', 'life_on_land']),
                'required_skills' => json_encode(['Lingkungan', 'Kehutanan', 'Konservasi']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 7: AFFORDABLE ENERGY =====
            [
                'title' => 'Energi Terbarukan Desa',
                'description' => 'Program pemanfaatan energi terbarukan seperti biogas dan solar panel untuk kebutuhan rumah tangga.',
                'background' => 'Biaya energi tinggi dan ketergantungan pada energi fosil masih besar.',
                'objectives' => 'Mengurangi biaya energi dan ketergantungan pada energi fosil.',
                'scope' => 'Instalasi biogas, solar panel, dan edukasi energi terbarukan.',
                'sdg_categories' => json_encode(['affordable_energy', 'climate_action']),
                'required_skills' => json_encode(['Teknik', 'Energi Terbarukan', 'Lingkungan']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Lampu Tenaga Surya',
                'description' => 'Instalasi lampu jalan tenaga surya untuk meningkatkan penerangan di desa.',
                'background' => 'Penerangan jalan di desa masih minim, menghambat aktivitas malam hari.',
                'objectives' => 'Meningkatkan penerangan jalan dengan teknologi ramah lingkungan.',
                'scope' => 'Instalasi lampu surya, perawatan berkala, dan edukasi teknologi.',
                'sdg_categories' => json_encode(['affordable_energy', 'sustainable_cities']),
                'required_skills' => json_encode(['Teknik Elektro', 'Energi Surya', 'Infrastruktur']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 8: DECENT WORK =====
            [
                'title' => 'Job Fair Dan Pelatihan Kerja',
                'description' => 'Program bursa kerja dan pelatihan untuk menghubungkan pencari kerja dengan lowongan.',
                'background' => 'Tingkat pengangguran masih tinggi, perlu fasilitasi akses pekerjaan.',
                'objectives' => 'Mengurangi pengangguran melalui job matching dan peningkatan skill.',
                'scope' => 'Job fair, pelatihan interview, dan workshop soft skills.',
                'sdg_categories' => json_encode(['decent_work', 'economic_growth']),
                'required_skills' => json_encode(['HR', 'Pelatihan', 'Karir']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Kewirausahaan Digital',
                'description' => 'Pelatihan memulai bisnis online untuk generasi muda menggunakan platform digital.',
                'background' => 'Peluang ekonomi digital belum dimanfaatkan optimal oleh masyarakat.',
                'objectives' => 'Meningkatkan jumlah wirausaha digital dan akses pasar online.',
                'scope' => 'Pelatihan e-commerce, digital marketing, dan mentoring bisnis.',
                'sdg_categories' => json_encode(['decent_work', 'economic_growth']),
                'required_skills' => json_encode(['Digital Marketing', 'E-commerce', 'Kewirausahaan']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 11: SUSTAINABLE CITIES =====
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
            [
                'title' => 'Taman Hijau Dan Ruang Terbuka Publik',
                'description' => 'Pembangunan dan revitalisasi taman sebagai ruang publik yang ramah keluarga.',
                'background' => 'Kurangnya ruang terbuka hijau mengurangi kualitas hidup masyarakat perkotaan.',
                'objectives' => 'Menyediakan ruang publik yang nyaman dan hijau untuk warga.',
                'scope' => 'Penataan taman, penanaman pohon, dan fasilitas publik.',
                'sdg_categories' => json_encode(['sustainable_cities', 'climate_action']),
                'required_skills' => json_encode(['Arsitektur Lanskap', 'Perencanaan Kota', 'Lingkungan']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Sistem Transportasi Ramah Lingkungan',
                'description' => 'Pengembangan jalur sepeda dan promosi transportasi publik untuk mengurangi polusi.',
                'background' => 'Ketergantungan pada kendaraan pribadi tinggi, menyebabkan kemacetan dan polusi.',
                'objectives' => 'Meningkatkan penggunaan transportasi ramah lingkungan.',
                'scope' => 'Pembuatan jalur sepeda, promosi bike sharing, dan edukasi transportasi publik.',
                'sdg_categories' => json_encode(['sustainable_cities', 'climate_action']),
                'required_skills' => json_encode(['Transportasi', 'Perencanaan Kota', 'Lingkungan']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 13: CLIMATE ACTION =====
            [
                'title' => 'Penghijauan Dan Konservasi Lingkungan',
                'description' => 'Program penanaman pohon dan konservasi lahan untuk mencegah erosi dan meningkatkan kualitas lingkungan.',
                'background' => 'Lahan kritis semakin luas, diperlukan upaya penghijauan dan konservasi.',
                'objectives' => 'Meningkatkan tutupan hijau dan mencegah kerusakan lingkungan.',
                'scope' => 'Penanaman pohon, pembuatan terasering, dan edukasi lingkungan.',
                'sdg_categories' => json_encode(['climate_action', 'life_on_land']),
                'required_skills' => json_encode(['Kehutanan', 'Lingkungan', 'Pertanian']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Mitigasi Bencana Berbasis Masyarakat',
                'description' => 'Program peningkatan kapasitas masyarakat dalam menghadapi bencana alam.',
                'background' => 'Wilayah ini rawan bencana, perlu peningkatan kesiapsiagaan masyarakat.',
                'objectives' => 'Meningkatkan ketahanan masyarakat terhadap bencana alam.',
                'scope' => 'Pelatihan tanggap bencana, pembentukan tim relawan, dan early warning system.',
                'sdg_categories' => json_encode(['climate_action', 'sustainable_cities']),
                'required_skills' => json_encode(['Kebencanaan', 'Sosial', 'Manajemen Risiko']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Adaptasi Perubahan Iklim Pertanian',
                'description' => 'Program adaptasi teknologi pertanian terhadap perubahan iklim dan cuaca ekstrem.',
                'background' => 'Perubahan iklim berdampak pada produktivitas pertanian, perlu adaptasi teknologi.',
                'objectives' => 'Meningkatkan ketahanan pertanian terhadap perubahan iklim.',
                'scope' => 'Pelatihan teknologi adaptasi, pemilihan varietas tahan, dan sistem irigasi efisien.',
                'sdg_categories' => json_encode(['climate_action', 'zero_hunger']),
                'required_skills' => json_encode(['Pertanian', 'Klimatologi', 'Teknologi']),
                'difficulty_level' => 'advanced',
            ],

            // ===== SDG 12: RESPONSIBLE CONSUMPTION =====
            [
                'title' => 'Kampanye Zero Waste',
                'description' => 'Program edukasi dan implementasi gaya hidup zero waste di komunitas.',
                'background' => 'Produksi sampah terus meningkat, perlu perubahan pola konsumsi masyarakat.',
                'objectives' => 'Mengurangi produksi sampah melalui perubahan pola konsumsi.',
                'scope' => 'Workshop zero waste, kampanye mengurangi plastik, dan promosi produk ramah lingkungan.',
                'sdg_categories' => json_encode(['responsible_consumption', 'sustainable_cities']),
                'required_skills' => json_encode(['Lingkungan', 'Komunikasi', 'Pendidikan']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Produk Lokal Berkelanjutan',
                'description' => 'Promosi dan pengembangan produk lokal yang ramah lingkungan.',
                'background' => 'Produk lokal kurang dikenal, padahal memiliki potensi ekonomi dan lingkungan.',
                'objectives' => 'Meningkatkan konsumsi produk lokal berkelanjutan.',
                'scope' => 'Branding produk, pelatihan packaging, dan akses pasar.',
                'sdg_categories' => json_encode(['responsible_consumption', 'decent_work']),
                'required_skills' => json_encode(['Marketing', 'Branding', 'Kewirausahaan']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 16: PEACE & JUSTICE =====
            [
                'title' => 'Transparansi Dana Desa',
                'description' => 'Sistem informasi transparansi pengelolaan dana desa untuk meningkatkan akuntabilitas.',
                'background' => 'Informasi penggunaan dana desa belum transparan, perlu sistem yang aksesibel.',
                'objectives' => 'Meningkatkan transparansi dan akuntabilitas pengelolaan dana desa.',
                'scope' => 'Pembuatan portal informasi, sosialisasi, dan monitoring partisipatif.',
                'sdg_categories' => json_encode(['peace_justice', 'partnerships']),
                'required_skills' => json_encode(['Sistem Informasi', 'Pemerintahan', 'Komunikasi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Layanan Hukum Gratis Masyarakat',
                'description' => 'Program konsultasi dan pendampingan hukum gratis untuk masyarakat kurang mampu.',
                'background' => 'Akses terhadap layanan hukum masih mahal dan sulit dijangkau masyarakat miskin.',
                'objectives' => 'Meningkatkan akses keadilan bagi masyarakat kurang mampu.',
                'scope' => 'Konsultasi hukum, pendampingan kasus, dan edukasi hukum.',
                'sdg_categories' => json_encode(['peace_justice', 'reduced_inequalities']),
                'required_skills' => json_encode(['Hukum', 'Konseling', 'Sosial']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== SDG 17: PARTNERSHIPS =====
            [
                'title' => 'Kemitraan Universitas-Desa',
                'description' => 'Program kemitraan strategis antara universitas dan desa untuk pembangunan berkelanjutan.',
                'background' => 'Potensi kerjasama akademisi dan masyarakat belum optimal untuk pembangunan desa.',
                'objectives' => 'Membangun kemitraan berkelanjutan untuk pengembangan desa.',
                'scope' => 'Riset bersama, transfer teknologi, dan program pemberdayaan.',
                'sdg_categories' => json_encode(['partnerships', 'quality_education']),
                'required_skills' => json_encode(['Manajemen Proyek', 'Penelitian', 'Pemberdayaan']),
                'difficulty_level' => 'intermediate',
            ],

            // ===== ADDITIONAL UNIQUE PROBLEMS =====
            [
                'title' => 'Digitalisasi Administrasi Desa',
                'description' => 'Transformasi digital pelayanan administrasi desa untuk efisiensi dan transparansi.',
                'background' => 'Pelayanan administrasi masih manual dan memakan waktu lama.',
                'objectives' => 'Mempercepat pelayanan administrasi melalui sistem digital.',
                'scope' => 'Pembuatan sistem informasi desa, pelatihan perangkat, dan sosialisasi.',
                'sdg_categories' => json_encode(['peace_justice', 'partnerships']),
                'required_skills' => json_encode(['Sistem Informasi', 'Pemerintahan', 'Teknologi']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Wisata Desa Berkelanjutan',
                'description' => 'Pengembangan desa wisata dengan konsep eco-tourism dan community based.',
                'background' => 'Potensi wisata alam dan budaya belum dikembangkan optimal.',
                'objectives' => 'Meningkatkan pendapatan masyarakat melalui wisata berkelanjutan.',
                'scope' => 'Pelatihan pemandu, pengembangan paket wisata, dan promosi digital.',
                'sdg_categories' => json_encode(['decent_work', 'sustainable_cities']),
                'required_skills' => json_encode(['Pariwisata', 'Marketing', 'Manajemen']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Revitalisasi Seni Dan Budaya Lokal',
                'description' => 'Program pelestarian dan pengembangan seni budaya tradisional.',
                'background' => 'Seni budaya lokal mulai terlupakan, perlu revitalisasi untuk generasi muda.',
                'objectives' => 'Melestarikan dan mengembangkan seni budaya lokal.',
                'scope' => 'Pelatihan seni tradisional, dokumentasi budaya, dan festival seni.',
                'sdg_categories' => json_encode(['quality_education', 'sustainable_cities']),
                'required_skills' => json_encode(['Seni', 'Budaya', 'Dokumentasi']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Aplikasi Kesehatan Telemedicine',
                'description' => 'Pengembangan layanan konsultasi kesehatan jarak jauh untuk daerah terpencil.',
                'background' => 'Akses ke fasilitas kesehatan terbatas, perlu telemedicine.',
                'objectives' => 'Meningkatkan akses layanan kesehatan melalui teknologi.',
                'scope' => 'Pengembangan aplikasi, pelatihan tenaga kesehatan, dan sosialisasi.',
                'sdg_categories' => json_encode(['good_health', 'reduced_inequalities']),
                'required_skills' => json_encode(['Sistem Informasi', 'Kesehatan', 'Teknologi']),
                'difficulty_level' => 'advanced',
            ],
            [
                'title' => 'Peternakan Terpadu Ramah Lingkungan',
                'description' => 'Sistem peternakan terintegrasi dengan pertanian untuk efisiensi dan kelestarian.',
                'background' => 'Peternakan konvensional kurang efisien dan mencemari lingkungan.',
                'objectives' => 'Mengembangkan sistem peternakan berkelanjutan dan ramah lingkungan.',
                'scope' => 'Pelatihan integrated farming, pembuatan kandang modern, dan manajemen limbah.',
                'sdg_categories' => json_encode(['zero_hunger', 'climate_action']),
                'required_skills' => json_encode(['Peternakan', 'Pertanian', 'Lingkungan']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Pusat Daur Ulang Komunitas',
                'description' => 'Pembangunan pusat daur ulang sampah untuk menghasilkan produk bernilai ekonomi.',
                'background' => 'Sampah menumpuk dan belum dimanfaatkan sebagai bahan daur ulang.',
                'objectives' => 'Mengolah sampah menjadi produk bernilai ekonomi.',
                'scope' => 'Pembangunan fasilitas, pelatihan daur ulang, dan pemasaran produk.',
                'sdg_categories' => json_encode(['sustainable_cities', 'responsible_consumption']),
                'required_skills' => json_encode(['Lingkungan', 'Kewirausahaan', 'Desain']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Hidroponik Dan Pertanian Modern',
                'description' => 'Pengenalan sistem hidroponik dan pertanian presisi untuk meningkatkan produktivitas.',
                'background' => 'Lahan pertanian terbatas, perlu teknologi pertanian modern.',
                'objectives' => 'Meningkatkan produktivitas pertanian dengan lahan terbatas.',
                'scope' => 'Pelatihan hidroponik, greenhouse, dan teknologi pertanian modern.',
                'sdg_categories' => json_encode(['zero_hunger', 'sustainable_cities']),
                'required_skills' => json_encode(['Pertanian', 'Teknologi', 'Agribisnis']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Program Literasi Keuangan Keluarga',
                'description' => 'Edukasi pengelolaan keuangan keluarga untuk meningkatkan kesejahteraan.',
                'background' => 'Banyak keluarga kesulitan mengelola keuangan, sering terjerat hutang.',
                'objectives' => 'Meningkatkan kemampuan mengelola keuangan keluarga.',
                'scope' => 'Workshop keuangan, konseling keluarga, dan pendampingan usaha.',
                'sdg_categories' => json_encode(['no_poverty', 'decent_work']),
                'required_skills' => json_encode(['Keuangan', 'Konseling', 'Pendidikan']),
                'difficulty_level' => 'beginner',
            ],
            [
                'title' => 'Sekolah Lapangan Petani',
                'description' => 'Program pelatihan praktis pertanian dengan metode learning by doing.',
                'background' => 'Pengetahuan petani tentang teknologi pertanian modern masih terbatas.',
                'objectives' => 'Meningkatkan pengetahuan dan keterampilan petani.',
                'scope' => 'Demonstrasi plot, pelatihan teknik budidaya, dan pengendalian hama.',
                'sdg_categories' => json_encode(['zero_hunger', 'quality_education']),
                'required_skills' => json_encode(['Pertanian', 'Penyuluhan', 'Pendidikan']),
                'difficulty_level' => 'intermediate',
            ],
            [
                'title' => 'Kampung Iklim Pintar',
                'description' => 'Program adaptasi dan mitigasi perubahan iklim tingkat desa.',
                'background' => 'Dampak perubahan iklim semakin terasa, perlu aksi konkrit di level desa.',
                'objectives' => 'Membangun desa yang tangguh terhadap perubahan iklim.',
                'scope' => 'Penanaman pohon, rainwater harvesting, dan energi terbarukan.',
                'sdg_categories' => json_encode(['climate_action', 'sustainable_cities']),
                'required_skills' => json_encode(['Lingkungan', 'Teknik', 'Pemberdayaan']),
                'difficulty_level' => 'advanced',
            ],
        ];

        // pilih template random - pastikan judul unik
        $maxAttempts = 10;
        $attempt = 0;
        $template = null;

        while ($attempt < $maxAttempts) {
            $candidateTemplate = $problemTemplates[array_rand($problemTemplates)];
            
            // cek apakah judul sudah ada untuk institution ini
            $exists = Problem::where('institution_id', $institution->id)
                ->where('title', $candidateTemplate['title'])
                ->exists();
            
            if (!$exists) {
                $template = $candidateTemplate;
                break;
            }
            
            $attempt++;
        }

        // jika semua template sudah digunakan, skip
        if (!$template) {
            return;
        }

        // ====== FIX BUG: TANGGAL BERBEDA UNTUK SETIAP PROBLEM ======
        // distribusi: 30% masa lalu, 40% sedang berjalan, 30% masa depan
        $distributionRoll = rand(1, 100);
        
        if ($distributionRoll <= 30) {
            // masa lalu (sudah selesai) - start 2-6 bulan yang lalu
            $monthsAgo = rand(2, 6);
            $startDate = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                rand(1, 28)  // tanggal acak 1-28
            )->subMonths($monthsAgo);
        } elseif ($distributionRoll <= 70) {
            // sedang berjalan - start 0-2 bulan yang lalu
            $monthsAgo = rand(0, 2);
            $startDate = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                rand(1, 28)  // tanggal acak 1-28
            )->subMonths($monthsAgo);
        } else {
            // masa depan - start 1-4 bulan kedepan
            $monthsAhead = rand(1, 4);
            $startDate = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->month,
                rand(1, 28)  // tanggal acak 1-28
            )->addMonths($monthsAhead);
        }
        
        // durasi dalam minggu: 3-8 minggu (3 minggu = ~1 bulan, 8 minggu = 2 bulan)
        $durationWeeks = rand(3, 8);
        
        // konversi ke bulan dalam integer: 3-4 minggu = 1 bulan, 5-8 minggu = 2 bulan
        $durationMonths = $durationWeeks <= 4 ? 1 : 2;
        
        // gunakan copy() untuk membuat instance baru agar tidak mengubah $startDate
        $endDate = $startDate->copy()->addWeeks($durationWeeks);
        
        // deadline aplikasi 2 minggu sebelum mulai
        $applicationDeadline = $startDate->copy()->subWeeks(2);

        // tentukan status berdasarkan tanggal
        $now = Carbon::now();
        if ($applicationDeadline->isFuture()) {
            $status = 'open';
        } elseif ($startDate->isFuture()) {
            $status = 'open'; // masih bisa apply
        } elseif ($endDate->isPast()) {
            $status = 'completed';
        } else {
            $status = 'closed'; // sedang berjalan, tidak bisa apply lagi
        }
        // ====== END FIX BUG ======

        // buat problem dengan lokasi yang sudah ditentukan di atas
        Problem::create([
            'institution_id' => $institution->id,
            'title' => $template['title'],
            'description' => $template['description'],
            'background' => $template['background'],
            'objectives' => $template['objectives'],
            'scope' => $template['scope'],
            'province_id' => $provinceId,
            'regency_id' => $regencyId,
            'village' => 'Desa ' . $this->generateVillageName(),
            'detailed_location' => 'RT ' . rand(1, 5) . '/RW ' . rand(1, 3) . ', Desa ' . $this->generateVillageName(),
            'sdg_categories' => $template['sdg_categories'],
            'required_students' => rand(8, 20), // minimal 8, maksimal 20 mahasiswa
            'required_skills' => $template['required_skills'],
            'required_majors' => json_encode($this->getRandomMajors()),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'application_deadline' => $applicationDeadline,
            'duration_months' => $durationMonths, // 1 atau 2 bulan (integer)
            'difficulty_level' => $template['difficulty_level'],
            'expected_outcomes' => 'Peningkatan Kualitas Hidup Masyarakat Dan Pemberdayaan Komunitas Lokal.',
            'deliverables' => json_encode([
                'Laporan Survei Awal',
                'Dokumentasi Kegiatan',
                'Laporan Akhir Program',
                'Evaluasi Dampak'
            ]),
            'facilities_provided' => json_encode([
                'Akomodasi',
                'Konsumsi',
                'Transportasi Lokal',
                'Sertifikat'
            ]),
            'status' => $status,
        ]);
    }

    /**
     * generate nama desa random
     */
    private function generateVillageName(): string
    {
        $prefixes = ['Suka', 'Mekar', 'Jaya', 'Maju', 'Sentosa', 'Bahagia', 'Makmur', 'Sejahtera'];
        $suffixes = ['Maju', 'Jaya', 'Raya', 'Asri', 'Indah', 'Mulya', 'Makmur', 'Santosa'];

        return $prefixes[array_rand($prefixes)] . $suffixes[array_rand($suffixes)];
    }

    /**
     * get random majors - kombinasi lengkap dengan fokus jurusan lapangan
     * minimal 5 jurusan, maksimal 10 jurusan
     */
    private function getRandomMajors(): array
    {
        // daftar jurusan lengkap - fokus pada jurusan yang aplikatif dan lapangan
        $allMajors = [
            // dari DummyDataSeeder (base)
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknik Sipil',
            'Arsitektur',
            'Manajemen',
            'Akuntansi',
            'Ilmu Komunikasi',
            'Psikologi',
            'Hukum',
            'Kedokteran',
            'Farmasi',
            'Agroteknologi',
            'Ekonomi Pembangunan',
            'Teknik Elektro',
            'Desain Grafis',
            
            // rumpun teknik (diperbanyak)
            'Teknik Mesin',
            'Teknik Industri',
            'Teknik Kimia',
            'Teknik Lingkungan',
            'Teknik Pertambangan',
            'Teknik Geologi',
            'Teknik Geodesi',
            'Teknik Arsitektur Lanskap',
            'Teknik Pengairan',
            'Teknik Perencanaan Wilayah Dan Kota',
            'Teknologi Informasi',
            'Teknik Komputer',
            
            // pertanian & peternakan (diperbanyak)
            'Agribisnis',
            'Peternakan',
            'Ilmu Tanah',
            'Proteksi Tanaman',
            'Budidaya Perairan',
            'Teknologi Hasil Pertanian',
            'Teknologi Pangan',
            'Kehutanan',
            'Agroekoteknologi',
            'Agronomi',
            'Hortikultura',
            'Perikanan',
            'Ilmu Kelautan',
            
            // kesehatan (lapangan)
            'Kesehatan Masyarakat',
            'Gizi',
            'Keperawatan',
            'Kebidanan',
            'Kesehatan Lingkungan',
            'Promosi Kesehatan',
            
            // sosial & pemberdayaan (lapangan)
            'Sosiologi',
            'Ilmu Pemerintahan',
            'Administrasi Publik',
            'Administrasi Negara',
            'Ilmu Kesejahteraan Sosial',
            'Pembangunan Sosial',
            'Penyuluhan Dan Komunikasi Pertanian',
            
            // pendidikan (lapangan)
            'Pendidikan',
            'Pendidikan Luar Sekolah',
            'Pendidikan Masyarakat',
            'Teknologi Pendidikan',
            
            // ekonomi & bisnis (lapangan)
            'Ekonomi Syariah',
            'Manajemen Bisnis',
            'Kewirausahaan',
            
            // desain & kreatif
            'Desain Komunikasi Visual',
            'Desain Interior',
            'Desain Produk',
            
            // lainnya yang aplikatif
            'Pariwisata',
            'Perhotelan',
            'Tata Boga',
            'Statistika',
            'Geografi',
            'Planologi',
        ];

        // ambil 5-10 jurusan random
        shuffle($allMajors);
        return array_slice($allMajors, 0, rand(5, 10));
    }
}