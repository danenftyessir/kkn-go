<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use App\Models\University;
use App\Models\Province;
use App\Models\Regency;

/**
 * seeder untuk data dummy users, students, institutions, universities
 * UPDATED: menggunakan data provinces & regencies dari database (dinamis dari BPS)
 * 
 * path: database/seeders/DummyDataSeeder.php
 * jalankan: php artisan db:seed --class=DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "memulai seeding data dummy...\n\n";
        
        $this->cleanOldData();
        $this->seedUniversities();
        $this->seedStudents();
        $this->seedInstitutions();
        
        echo "\n";
        echo "==========================================\n";
        echo "seeding data dummy selesai!\n";
        echo "==========================================\n";
        echo "\n";
        echo "statistik data:\n";
        echo "  - universities  : " . University::count() . "\n";
        echo "  - students      : " . Student::count() . "\n";
        echo "  - institutions  : " . Institution::count() . "\n";
        echo "\n";
        echo "akun testing (semua password: password123):\n";
        echo "\n";
        echo "mahasiswa pertama:\n";
        $firstStudent = Student::with('user', 'university')->first();
        if ($firstStudent) {
            echo "  nama     : " . $firstStudent->first_name . " " . $firstStudent->last_name . "\n";
            echo "  email    : " . $firstStudent->user->email . "\n";
            echo "  username : " . $firstStudent->user->username . "\n";
            echo "  kampus   : " . $firstStudent->university->name . "\n";
        }
        echo "\n";
        echo "instansi pertama:\n";
        $firstInst = Institution::with('user')->first();
        if ($firstInst) {
            echo "  nama     : " . $firstInst->name . "\n";
            echo "  email    : " . $firstInst->user->email . "\n";
            echo "  username : " . $firstInst->user->username . "\n";
        }
        echo "\n";
        echo "catatan: problems, applications, projects akan di-seed oleh seeder terpisah\n";
        echo "==========================================\n";
    }

    /**
     * bersihkan data lama - kompatibel untuk PostgreSQL
     */
    private function cleanOldData(): void
    {
        echo "membersihkan data lama...\n";
        
        // untuk PostgreSQL gunakan TRUNCATE CASCADE atau disable triggers
        DB::statement('SET session_replication_role = replica');
        
        // truncate tables dalam urutan yang benar
        DB::table('applications')->truncate();
        DB::table('wishlists')->truncate();
        DB::table('problem_images')->truncate();
        DB::table('problems')->truncate();
        DB::table('students')->truncate();
        DB::table('institutions')->truncate();
        DB::table('users')->where('user_type', '!=', 'admin')->delete();
        DB::table('universities')->truncate();
        
        // enable kembali foreign key checks
        DB::statement('SET session_replication_role = DEFAULT');
    }

    /**
     * seeding universities - 120+ universitas
     * DISTRIBUSI OTOMATIS ke semua provinsi dari database BPS
     */
    private function seedUniversities(): void
    {
        echo "seeding universities...\n";
        
        // ambil semua provinces dari database BPS
        $allProvinces = Province::all();
        
        if ($allProvinces->isEmpty()) {
            echo "  ERROR: Tidak ada data provinces. Jalankan ProvincesRegenciesSeeder terlebih dahulu!\n";
            return;
        }
        
        echo "  -> tersedia " . $allProvinces->count() . " provinsi dari BPS\n";
        
        // helper function untuk mendapatkan location
        $getLocation = function($provinceName = null) use ($allProvinces) {
            if ($provinceName) {
                // cari province berdasarkan nama
                $province = $allProvinces->first(function($p) use ($provinceName) {
                    return stripos($p->name, $provinceName) !== false;
                });
                
                if (!$province) {
                    $province = $allProvinces->random();
                }
            } else {
                // random province
                $province = $allProvinces->random();
            }
            
            // ambil regency dari province tersebut
            $regencies = Regency::where('province_id', $province->id)->get();
            
            if ($regencies->isEmpty()) {
                // fallback jika tidak ada regency
                return [
                    'province_id' => $province->id,
                    'regency_id' => null // akan dihandle oleh database
                ];
            }
            
            $regency = $regencies->random();
            
            return [
                'province_id' => $province->id,
                'regency_id' => $regency->id
            ];
        };
        
        // daftar universitas dengan hint lokasi (opsional)
        $universitiesData = [
            ['name' => 'Universitas Indonesia', 'code' => 'UI', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Bandung', 'code' => 'ITB', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Pertanian Bogor', 'code' => 'IPB', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Padjadjaran', 'code' => 'UNPAD', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Diponegoro', 'code' => 'UNDIP', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Sebelas Maret', 'code' => 'UNS', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Gadjah Mada', 'code' => 'UGM', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Airlangga', 'code' => 'UNAIR', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Brawijaya', 'code' => 'UB', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'code' => 'ITS', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Udayana', 'code' => 'UNUD', 'province_hint' => 'Bali', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Trunojoyo Madura', 'code' => 'UTM', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Teuku Umar', 'code' => 'UTU', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Terbuka', 'code' => 'UT', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Tanjungpura', 'code' => 'UNTAN', 'province_hint' => 'Kalimantan Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Syiah Kuala', 'code' => 'UNSYIAH', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Sumatera Utara', 'code' => 'USU', 'province_hint' => 'Sumatera Utara', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Sultan Ageng Tirtayasa', 'code' => 'UNTIRTA', 'province_hint' => 'Banten', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sulawesi Tenggara', 'code' => 'USULTRA', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sulawesi Barat', 'code' => 'UNSULBAR', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sembilanbelas November', 'code' => 'UNS19', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Samudra', 'code' => 'UNSAM', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sains Al Quran', 'code' => 'UNSIQ', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Pendidikan Muhammadiyah Sorong', 'code' => 'UNIMUDA', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Pendidikan Indonesia', 'code' => 'UPI', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Pendidikan Ganesha', 'code' => 'UNDIKSHA', 'province_hint' => 'Bali', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Pembangunan Nasional Veteran Jawa Timur', 'code' => 'UPNVJT', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Pattimura', 'code' => 'UNPATTI', 'province_hint' => 'Maluku', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Pasifik Morotai', 'code' => 'UNIPAS', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Pancasakti Tegal', 'code' => 'UPS', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Palangka Raya', 'code' => 'UPR', 'province_hint' => 'Kalimantan Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Nurul Huda', 'code' => 'UNUHA', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Noor Huda Mustofa', 'code' => 'UNHM', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Negeri Yogyakarta', 'code' => 'UNY', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Surabaya', 'code' => 'UNESA', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Semarang', 'code' => 'UNNES', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Padang', 'code' => 'UNP', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Medan', 'code' => 'UNIMED', 'province_hint' => 'Sumatera Utara', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Manado', 'code' => 'UNIMA', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Negeri Malang', 'code' => 'UM', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Makassar', 'code' => 'UNM', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Jakarta', 'code' => 'UNJ', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Gorontalo', 'code' => 'UNG', 'province_hint' => 'Gorontalo', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Muslim Nusantara Al Washliyah', 'code' => 'UMNAW', 'province_hint' => 'Sumatera Utara', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muslim Indonesia', 'code' => 'UMI', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Musamus Merauke', 'code' => 'UNMUS', 'province_hint' => 'Papua', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Muria Kudus', 'code' => 'UMK', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Mulawarman', 'code' => 'UNMUL', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Muhammadiyah Sinjai', 'code' => 'UMSINJAI', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Muhammadiyah Purworejo', 'code' => 'UMPWR', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Ponorogo', 'code' => 'UMPO', 'province_hint' => 'Jawa Timur', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Palangkaraya', 'code' => 'UMPALANGKA', 'province_hint' => 'Kalimantan Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Kolaka Utara', 'code' => 'UMKOLAKA', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Muhammadiyah Buton', 'code' => 'UMBUTON', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Muhammadiyah Makassar', 'code' => 'UNISMUH', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Mataram', 'code' => 'UNRAM', 'province_hint' => 'Nusa Tenggara Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Malikussaleh', 'code' => 'UNIMAL', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Madura', 'code' => 'UNIRA', 'province_hint' => 'Jawa Timur', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Lelemuku Saumlaki', 'code' => 'UNIMOR', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Lampung', 'code' => 'UNILA', 'province_hint' => 'Lampung', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Labuhanbatu', 'code' => 'UNLABUHAN', 'province_hint' => 'Sumatera Utara', 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Kuningan', 'code' => 'UNIKU', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Kristen Indonesia Toraja', 'code' => 'UKITORAJA', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Kristen Indonesia Maluku', 'code' => 'UKIM', 'province_hint' => 'Maluku', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Komputer Indonesia', 'code' => 'UNIKOM', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Khairun', 'code' => 'UNKHAIR', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jambi', 'code' => 'UNJA', 'province_hint' => 'Jambi', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Makassar', 'code' => 'UIM', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Indonesia Timur', 'code' => 'UIT', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Ibn Khaldun Bogor', 'code' => 'UIKA', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Hasanuddin', 'code' => 'UNHAS', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Halu Oleo', 'code' => 'UHO', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Famika', 'code' => 'UFAMIKA', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Esa Unggul', 'code' => 'UEU', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Darma Agung', 'code' => 'UDA', 'province_hint' => 'Sumatera Utara', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Boyolali', 'code' => 'UBY', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Borneo Tarakan', 'code' => 'UBT', 'province_hint' => 'Kalimantan Utara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Atma Jaya Makassar', 'code' => 'UAJM', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Andalas', 'code' => 'UNAND', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Almarisah Madani', 'code' => 'UAM', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Al Azhar Indonesia', 'code' => 'UAI', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Al Asyariah Mandar', 'code' => 'UNASMAN', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Abulyatama', 'code' => 'UNAYA', 'province_hint' => 'Aceh', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas 17 Agustus 1945 Samarinda', 'code' => 'UNTAG', 'province_hint' => 'Kalimantan Timur', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Tadulako', 'code' => 'UNTAD', 'province_hint' => 'Sulawesi Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Bima Internasional MFH', 'code' => 'UBI', 'province_hint' => null, 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Universitas Nahdatul Watan Mataram', 'code' => 'UNWAM', 'province_hint' => 'Nusa Tenggara Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Negeri Tulung Agung', 'code' => 'UINTA', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Negeri Sultan Syarif Kasim Riau', 'code' => 'UIN SUSKA', 'province_hint' => 'Riau', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Bangka Belitung', 'code' => 'UBB', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Negeri Sayyid Ali Rahmatullah Tulungagung', 'code' => 'UIN SATU', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jenderal Soedirman', 'code' => 'UNSOED', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],         
            ['name' => 'Institut Teknologi Sumatera', 'code' => 'ITERA', 'province_hint' => 'Lampung', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Kalimantan', 'code' => 'ITK', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Teknologi Dan Sains Nahdlatul Ulama Pekalongan', 'code' => 'ITSNUPKL', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Institut Teknologi Dan Bisnis Maritim Balik Diwa Makassar', 'code' => 'ITBM', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'C'],
            ['name' => 'Institut Seni Budaya Indonesia Bandung', 'code' => 'ISBI BDG', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Seni Budaya Indonesia Aceh', 'code' => 'ISBI ACEH', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Pangeran Dharma Kusuma Indramayu', 'code' => 'IPDKI', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Institut Agama Hindu Negeri Tampung Penyang Palangka Raya', 'code' => 'IAHN', 'province_hint' => 'Kalimantan Tengah', 'type' => 'negeri', 'accreditation' => 'B'],           
            ['name' => 'Sekolah Tinggi Keguruan Dan Ilmu Pendidikan Al Hikmah Surabaya', 'code' => 'STKIP ALHIK', 'province_hint' => 'Jawa Timur', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Sekolah Tinggi Ilmu Manajemen Indonesia', 'code' => 'STIMI', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Sekolah Tinggi Ilmu Kesehatan Salewangan Maros', 'code' => 'STIKES MAROS', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Sekolah Tinggi Ilmu Kesehatan Masyarakat', 'code' => 'STIKMA', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Sekolah Tinggi Ilmu Ekonomi Indonesia Makassar', 'code' => 'STIEM', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],   
            ['name' => 'Politeknik Bombana', 'code' => 'POLBOM', 'province_hint' => null, 'type' => 'negeri', 'accreditation' => 'C'],          
            ['name' => 'UIN Syarif Hidayatullah Jakarta', 'code' => 'UIN JKT', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Trisakti', 'code' => 'USAKTI', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Mercu Buana', 'code' => 'UMB', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'UIN Sunan Gunung Djati Bandung', 'code' => 'UIN SGD', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Pasundan', 'code' => 'UNPAS', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'UIN Walisongo Semarang', 'code' => 'UIN WALISONGO', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Sultan Agung', 'code' => 'UNISSULA', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Semarang', 'code' => 'UNIMUS', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'UIN Sunan Kalijaga Yogyakarta', 'code' => 'UIN SUKA', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Indonesia', 'code' => 'UII', 'province_hint' => 'Yogyakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Muhammadiyah Tangerang', 'code' => 'UMT', 'province_hint' => 'Banten', 'type' => 'swasta', 'accreditation' => 'B'],
        ];
        
        // insert universitas dengan lokasi dinamis
        $createdCount = 0;
        foreach ($universitiesData as $univData) {
            $location = $getLocation($univData['province_hint'] ?? null);
            
            // skip jika tidak dapat location
            if (!$location || !$location['province_id']) {
                continue;
            }
            
            University::create([
                'name' => $univData['name'],
                'code' => $univData['code'],
                'province_id' => $location['province_id'],
                'regency_id' => $location['regency_id'],
                'type' => $univData['type'],
                'accreditation' => $univData['accreditation'],
            ]);
            
            $createdCount++;
        }
        
        echo "  -> {$createdCount} universities berhasil dibuat\n";
    }

    /**
     * seeding students dummy (400 students untuk 120+ universitas)
     */
    private function seedStudents(): void
    {
        echo "seeding students...\n";
        
        $firstNames = [
            'Andi', 'Budi', 'Citra', 'Devi', 'Eko', 'Farah', 'Gilang', 'Hana', 'Indra', 'Joko',
            'Kiki', 'Lisa', 'Maya', 'Nanda', 'Olivia', 'Putra', 'Qori', 'Rina', 'Sari', 'Tika',
            'Umar', 'Vera', 'Wawan', 'Xena', 'Yudi', 'Zara', 'Adam', 'Bella', 'Candra', 'Diana',
            'Erlangga', 'Fitri', 'Galih', 'Hesti', 'Ilham', 'Julia', 'Kevin', 'Laila', 'Mukti', 'Nisa',
            'Oscar', 'Pandu', 'Qina', 'Rafi', 'Sinta', 'Teguh', 'Ulfa', 'Vino', 'Wulan', 'Yusuf',
            'Agus', 'Bayu', 'Clara', 'Dimas', 'Elsa', 'Faisal', 'Gita', 'Hadi', 'Intan', 'Jihan',
            'Kurnia', 'Lina', 'Malik', 'Nadia', 'Omar', 'Putri', 'Qomar', 'Reza', 'Silvi', 'Toni',
            'Umi', 'Vina', 'Wahyu', 'Xaviera', 'Yoga', 'Zahra', 'Arif', 'Bunga', 'Cahya', 'Desi',
            'Eka', 'Fadli', 'Gandi', 'Hilda', 'Irfan', 'Jasmine', 'Krisna', 'Luki', 'Mira', 'Novi',
            'Oki', 'Prima', 'Qori', 'Rizki', 'Sinta', 'Tito', 'Uli', 'Vira', 'Widi', 'Yanto'
        ];
        
        $lastNames = [
            'Pratama', 'Santoso', 'Putri', 'Wijaya', 'Kusuma', 'Permata', 'Saputra', 'Lestari',
            'Sari', 'Nugroho', 'Wibowo', 'Rahayu', 'Susanto', 'Hartono', 'Setiawan', 'Anggraini',
            'Firmansyah', 'Hidayat', 'Ramadhan', 'Suryanto', 'Maharani', 'Nurfadilah', 'Maulana',
            'Syahputra', 'Syahputri', 'Rachman', 'Hakim', 'Adiputra', 'Salsabila', 'Rizqullah',
            'Azhari', 'Azzahra', 'Firdaus', 'Aisyah', 'Hasanah', 'Kamila', 'Nabila', 'Zahira'
        ];
        
        $majors = [
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Sipil', 'Arsitektur', 'Manajemen',
            'Akuntansi', 'Ilmu Komunikasi', 'Psikologi', 'Hukum', 'Kedokteran', 'Farmasi',
            'Agroteknologi', 'Ekonomi Pembangunan', 'Teknik Elektro', 'Desain Grafis',
            'Teknik Mesin', 'Teknik Industri', 'Teknik Kimia', 'Pendidikan Bahasa Inggris',
            'Pendidikan Matematika', 'Sastra Indonesia', 'Hubungan Internasional', 'Ilmu Politik',
            'Sosiologi', 'Antropologi', 'Kesehatan Masyarakat', 'Gizi', 'Keperawatan',
            'Biologi', 'Fisika', 'Kimia', 'Matematika', 'Statistika', 'Ilmu Komputer'
        ];
        
        $universities = University::all();
        
        if ($universities->isEmpty()) {
            echo "  ERROR: Tidak ada data universities!\n";
            return;
        }
        
        // buat 400 mahasiswa agar lebih realistis
        for ($i = 0; $i < 400; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $university = $universities->random();
            $major = $majors[array_rand($majors)];
            $username = strtolower($firstName . $lastName . rand(100, 9999));
            $nim = '21' . str_pad($i, 8, '0', STR_PAD_LEFT); // format: 2100000000 - 2100000399
            
            // generate email berdasarkan kode universitas
            $emailDomain = $this->getUniversityEmailDomain($university->code);
            $email = $username . '@' . $emailDomain;
            
            // buat user
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // buat student
            Student::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'university_id' => $university->id,
                'major' => $major,
                'nim' => $nim,
                'semester' => rand(4, 8),
                'phone' => '+628' . rand(1000000000, 9999999999),
            ]);
        }
        
        echo "  -> " . Student::count() . " students berhasil dibuat\n";
    }

    /**
     * seeding institutions dummy (80 institutions untuk lebih realistis)
     * DISTRIBUSI OTOMATIS ke semua provinsi dari database
     */
    private function seedInstitutions(): void
    {
        echo "seeding institutions...\n";
        
        $institutionTypes = [
            'Pemerintah Desa',
            'Dinas Kesehatan',
            'Dinas Pendidikan',
            'Dinas Pertanian',
            'Dinas Pariwisata',
            'Dinas Sosial',
            'NGO',
            'Puskesmas',
            'Kelurahan',
            'Kecamatan',
            'Dinas Lingkungan Hidup',
            'Dinas Perindustrian',
            'Dinas Perdagangan',
            'Dinas Koperasi',
            'BPBD',
            'Yayasan Sosial',
            'Lembaga Pendidikan',
            'Posyandu'
        ];
        
        $picNames = [
            'Pak Agus Hartono',
            'Bu Siti Nurhaliza',
            'Pak Bambang Setiawan',
            'Bu Rita Sari',
            'Pak Eko Prasetyo',
            'Bu Dewi Lestari',
            'Pak Hendra Kusuma',
            'Bu Maya Anggraini',
            'Pak Joko Santoso',
            'Bu Nina Rahayu',
            'Pak Ahmad Yani',
            'Bu Ratna Wijaya',
            'Pak Budi Hermawan',
            'Bu Sinta Permata',
            'Pak Dedi Kurniawan',
            'Bu Lina Marlina',
            'Pak Rizki Ramadhan',
            'Bu Fitri Handayani',
            'Pak Hadi Suryanto',
            'Bu Diana Putri'
        ];
        
        $positions = [
            'Kepala Desa',
            'Sekretaris Desa',
            'Kepala Dinas',
            'Koordinator Program',
            'Manajer Proyek',
            'Staff Program',
            'Lurah',
            'Camat',
            'Kepala Puskesmas',
            'Sekretaris',
            'Kabid Pemberdayaan',
            'Kabid Pengembangan',
            'Direktur Eksekutif',
            'Ketua Yayasan',
            'Bendahara'
        ];
        
        $provinces = Province::all();
        
        if ($provinces->isEmpty()) {
            echo "  ERROR: Tidak ada data provinces!\n";
            return;
        }
        
        // buat 80 institusi
        for ($i = 0; $i < 80; $i++) {
            $type = $institutionTypes[array_rand($institutionTypes)];
            $instName = $type . ' ' . chr(65 + ($i % 26));
            $username = strtolower(str_replace(' ', '', $instName)) . rand(1, 999);
            
            // random province dan regency
            $province = $provinces->random();
            $regencies = Regency::where('province_id', $province->id)->get();
            
            if ($regencies->isEmpty()) {
                // skip jika tidak ada regency
                continue;
            }
            
            $regency = $regencies->random();
            
            // buat user
            $user = User::create([
                'name' => ucwords($instName),
                'email' => $username . '@institution.go.id',
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // buat institution
            Institution::create([
                'user_id' => $user->id,
                'name' => ucwords($instName),
                'type' => $type,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'email' => $username . '@institution.go.id',
                'address' => 'Jl. ' . ucwords($instName) . ' No. ' . rand(1, 100) . ', ' . $regency->name,
                'phone' => '+622' . rand(100000000, 999999999),
                'pic_name' => $picNames[array_rand($picNames)],
                'pic_position' => $positions[array_rand($positions)],
                'description' => 'Instansi Yang Bergerak Di Bidang ' . $type . ' Dengan Fokus Pemberdayaan Masyarakat.',
                'is_verified' => true,
            ]);
        }
        
        echo "  -> " . Institution::count() . " institutions berhasil dibuat\n";
    }

    /**
     * dapatkan email domain berdasarkan kode universitas
     * SEMUA universitas punya email domain sendiri, tidak ada default
     */
    private function getUniversityEmailDomain($code): string
    {
        return match(strtoupper($code)) {
            'UI' => 'ui.ac.id',
            'ITB' => 'itb.ac.id',
            'IPB' => 'ipb.ac.id',
            'UNPAD' => 'unpad.ac.id',
            'UNDIP' => 'undip.ac.id',
            'UNS' => 'uns.ac.id',
            'UGM' => 'ugm.ac.id',
            'UNAIR' => 'unair.ac.id',
            'UB' => 'ub.ac.id',
            'ITS' => 'its.ac.id',    
            'UNUD' => 'unud.ac.id',
            'UTM' => 'trunojoyo.ac.id',
            'UTU' => 'utu.ac.id',
            'UT' => 'ut.ac.id',
            'UNTAN' => 'untan.ac.id',
            'UNSYIAH' => 'unsyiah.ac.id',
            'USU' => 'usu.ac.id',
            'UNTIRTA' => 'untirta.ac.id',
            'USULTRA' => 'usultra.ac.id',
            'UNSULBAR' => 'unsulbar.ac.id',
            'UNS19' => 'uns19.ac.id',
            'UNSAM' => 'unsam.ac.id',
            'UNSIQ' => 'unsiq.ac.id',
            'UNIMUDA' => 'unimuda.ac.id',
            'UPI' => 'upi.edu',
            'UNDIKSHA' => 'undiksha.ac.id',
            'UPNVJT' => 'upnjatim.ac.id',
            'UNPATTI' => 'unpatti.ac.id',
            'UNIPAS' => 'unipas.ac.id',
            'UPS' => 'upstegal.ac.id',
            'UPR' => 'upr.ac.id',
            'UNUHA' => 'unuha.ac.id',
            'UNHM' => 'unhm.ac.id',
            'UNY' => 'uny.ac.id',
            'UNESA' => 'unesa.ac.id',
            'UNNES' => 'unnes.ac.id',
            'UNP' => 'unp.ac.id',
            'UNIMED' => 'unimed.ac.id',
            'UNIMA' => 'unima.ac.id',
            'UM' => 'um.ac.id',
            'UNM' => 'unm.ac.id',
            'UNJ' => 'unj.ac.id',
            'UNG' => 'ung.ac.id',
            'UMNAW' => 'umnaw.ac.id',
            'UMI' => 'umi.ac.id',
            'UNMUS' => 'unmus.ac.id',
            'UMK' => 'umk.ac.id',
            'UNMUL' => 'unmul.ac.id',
            'UMSINJAI' => 'umsinjai.ac.id',
            'UMPWR' => 'umpwr.ac.id',
            'UMPO' => 'umpo.ac.id',
            'UMPALANGKA' => 'umpalangka.ac.id',
            'UMKOLAKA' => 'umkolaka.ac.id',
            'UMBUTON' => 'umbuton.ac.id',
            'UNISMUH' => 'unismuh.ac.id',
            'UNRAM' => 'unram.ac.id',
            'UNIMAL' => 'unimal.ac.id',
            'UNIRA' => 'unira.ac.id',
            'UNIMOR' => 'unimor.ac.id',
            'UNILA' => 'unila.ac.id',
            'UNLABUHAN' => 'unlabuhan.ac.id',
            'UNIKU' => 'uniku.ac.id',
            'UKITORAJA' => 'ukitoraja.ac.id',
            'UKIM' => 'ukim.ac.id',
            'UNIKOM' => 'unikom.ac.id',
            'UNKHAIR' => 'unkhair.ac.id',
            'UNJA' => 'unja.ac.id',
            'UIM' => 'uim.ac.id',
            'UIT' => 'uit.ac.id',
            'UIKA' => 'uika-bogor.ac.id',
            'UNHAS' => 'unhas.ac.id',
            'UHO' => 'uho.ac.id',
            'UFAMIKA' => 'ufamika.ac.id',
            'UEU' => 'esaunggul.ac.id',
            'UDA' => 'darmaagung.ac.id',
            'UBY' => 'boyolali.ac.id',
            'UBT' => 'borneo.ac.id',
            'UAJM' => 'uajm.ac.id',
            'UNAND' => 'unand.ac.id',
            'UAM' => 'almarisah.ac.id',
            'UAI' => 'uai.ac.id',
            'UNASMAN' => 'unasman.ac.id',
            'UNAYA' => 'unaya.ac.id',
            'UNTAG' => 'untag-smd.ac.id',
            'UNTAD' => 'untad.ac.id',
            'UBI' => 'bimainter.ac.id',
            'UNWAM' => 'unwam.ac.id',
            'UINTA' => 'uinta.ac.id',
            'UIN SUSKA' => 'uin-suska.ac.id',
            'UNSOED' => 'unsoed.ac.id',
            'UBB' => 'ubb.ac.id',
            'UIN SATU' => 'uinsatu.ac.id',      
            'ITERA' => 'itera.ac.id',
            'ITK' => 'itk.ac.id',
            'ITSNUPKL' => 'itsnupekalongan.ac.id',
            'ITBM' => 'itbm.ac.id',
            'ISBI BDG' => 'isbi.ac.id',
            'ISBI ACEH' => 'isbiaceh.ac.id',
            'IPDKI' => 'ipdki.ac.id',
            'IAHN' => 'iahn.ac.id',
            'STKIP ALHIK' => 'stkipalhikmah.ac.id',
            'STIMI' => 'stimi.ac.id',
            'STIKES MAROS' => 'stikesmaros.ac.id',
            'STIKMA' => 'stikma.ac.id',
            'STIEM' => 'stiem.ac.id',
            'POLBOM' => 'polbombana.ac.id',        
            'UIN JKT' => 'uinjkt.ac.id',
            'UIN SGD' => 'uinsgd.ac.id',
            'UIN WALISONGO' => 'walisongo.ac.id',
            'UIN SUKA' => 'uin-suka.ac.id',           
            'USAKTI' => 'trisakti.ac.id',
            'UMB' => 'mercubuana.ac.id',
            'UNPAS' => 'unpas.ac.id',
            'UII' => 'uii.ac.id',
            'UNISSULA' => 'unissula.ac.id',
            'UNIMUS' => 'unimus.ac.id',
            'UMT' => 'umt.ac.id',
            
            // fallback untuk kode yang tidak terdaftar - generate dari kode
            default => strtolower($code) . '.ac.id',
        };
    }
}