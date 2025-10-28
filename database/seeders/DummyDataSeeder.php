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
        
        try {
            // untuk PostgreSQL gunakan TRUNCATE CASCADE
            DB::statement('TRUNCATE TABLE applications, wishlists, problem_images, problems, students, institutions, universities RESTART IDENTITY CASCADE');
            
            // hapus users non-admin
            DB::table('users')
                ->where('user_type', '!=', 'admin')
                ->delete();
            
            echo "  -> data lama berhasil dibersihkan\n";
        } catch (\Exception $e) {
            echo "  -> warning: " . $e->getMessage() . "\n";
            echo "  -> mencoba alternatif pembersihan...\n";
            
            try {
                // alternatif: disable foreign key checks, truncate, enable lagi
                DB::statement('SET session_replication_role = replica');
                
                DB::table('applications')->truncate();
                DB::table('wishlists')->truncate();
                DB::table('problem_images')->truncate();
                DB::table('problems')->truncate();
                DB::table('students')->truncate();
                DB::table('institutions')->truncate();
                DB::table('universities')->truncate();
                
                // hapus users non-admin
                DB::table('users')
                    ->where('user_type', '!=', 'admin')
                    ->delete();
                
                DB::statement('SET session_replication_role = DEFAULT');
                
                echo "  -> alternatif pembersihan berhasil\n";
            } catch (\Exception $e2) {
                echo "  -> error: " . $e2->getMessage() . "\n";
                echo "  -> mencoba delete manual...\n";
                
                // last resort: delete satu per satu
                DB::table('applications')->delete();
                DB::table('wishlists')->delete();
                DB::table('problem_images')->delete();
                DB::table('problems')->delete();
                DB::table('students')->delete();
                DB::table('institutions')->delete();
                DB::table('users')
                    ->where('user_type', '!=', 'admin')
                    ->delete();
                DB::table('universities')->delete();
                
                echo "  -> delete manual selesai\n";
            }
        }
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
        
        // daftar lengkap universitas di Indonesia
        $universities = [
            // universitas top tier
            ['name' => 'Universitas Indonesia', 'code' => 'UI', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Bandung', 'code' => 'ITB', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Gadjah Mada', 'code' => 'UGM', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'code' => 'ITS', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Airlangga', 'code' => 'UNAIR', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Padjadjaran', 'code' => 'UNPAD', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Diponegoro', 'code' => 'UNDIP', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Brawijaya', 'code' => 'UB', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Hasanuddin', 'code' => 'UNHAS', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Sumatera Utara', 'code' => 'USU', 'province_hint' => 'Sumatera Utara', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Pertanian Bogor', 'code' => 'IPB', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Sebelas Maret', 'code' => 'UNS', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            
            // universitas besar lainnya
            ['name' => 'Universitas Pendidikan Indonesia', 'code' => 'UPI', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Jakarta', 'code' => 'UNJ', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Yogyakarta', 'code' => 'UNY', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Malang', 'code' => 'UM', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Semarang', 'code' => 'UNNES', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Surabaya', 'code' => 'UNESA', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Makassar', 'code' => 'UNM', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Medan', 'code' => 'UNIMED', 'province_hint' => 'Sumatera Utara', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Negeri Padang', 'code' => 'UNP', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Andalas', 'code' => 'UNAND', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Riau', 'code' => 'UNRI', 'province_hint' => 'Riau', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sriwijaya', 'code' => 'UNSRI', 'province_hint' => 'Sumatera Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Lampung', 'code' => 'UNILA', 'province_hint' => 'Lampung', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jember', 'code' => 'UNEJ', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Udayana', 'code' => 'UNUD', 'province_hint' => 'Bali', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Mataram', 'code' => 'UNRAM', 'province_hint' => 'Nusa Tenggara Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Nusa Cendana', 'code' => 'UNDANA', 'province_hint' => 'Nusa Tenggara Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Mulawarman', 'code' => 'UNMUL', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Lambung Mangkurat', 'code' => 'ULM', 'province_hint' => 'Kalimantan Selatan', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Tanjungpura', 'code' => 'UNTAN', 'province_hint' => 'Kalimantan Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Palangka Raya', 'code' => 'UPR', 'province_hint' => 'Kalimantan Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Tadulako', 'code' => 'UNTAD', 'province_hint' => 'Sulawesi Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Halu Oleo', 'code' => 'UHO', 'province_hint' => 'Sulawesi Tenggara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Sam Ratulangi', 'code' => 'UNSRAT', 'province_hint' => 'Sulawesi Utara', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Khairun', 'code' => 'UNKHAIR', 'province_hint' => 'Maluku Utara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Pattimura', 'code' => 'UNPATTI', 'province_hint' => 'Maluku', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Cenderawasih', 'code' => 'UNCEN', 'province_hint' => 'Papua', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Syiah Kuala', 'code' => 'UNSYIAH', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Bengkulu', 'code' => 'UNIB', 'province_hint' => 'Bengkulu', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Jambi', 'code' => 'UNJA', 'province_hint' => 'Jambi', 'type' => 'negeri', 'accreditation' => 'B'],
            
            // universitas islam negeri
            ['name' => 'Universitas Islam Negeri Syarif Hidayatullah Jakarta', 'code' => 'UIN JKT', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Sunan Gunung Djati Bandung', 'code' => 'UIN SGD', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Walisongo Semarang', 'code' => 'UIN WALISONGO', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta', 'code' => 'UIN SUKA', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Sunan Ampel Surabaya', 'code' => 'UIN SUNAN AMPEL', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Maulana Malik Ibrahim Malang', 'code' => 'UIN MALANG', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Sultan Syarif Kasim Riau', 'code' => 'UIN SUSKA', 'province_hint' => 'Riau', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Raden Fatah Palembang', 'code' => 'UIN RADEN FATAH', 'province_hint' => 'Sumatera Selatan', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Negeri Ar-Raniry Banda Aceh', 'code' => 'UIN AR-RANIRY', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Imam Bonjol Padang', 'code' => 'UIN IB', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Negeri Alauddin Makassar', 'code' => 'UIN ALAUDDIN', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Islam Negeri Mataram', 'code' => 'UIN MATARAM', 'province_hint' => 'Nusa Tenggara Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Negeri Sayyid Ali Rahmatullah Tulungagung', 'code' => 'UIN SATU', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            
            // institut teknologi & institut lainnya
            ['name' => 'Institut Teknologi Sumatera', 'code' => 'ITERA', 'province_hint' => 'Lampung', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Teknologi Kalimantan', 'code' => 'ITK', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Seni Indonesia Yogyakarta', 'code' => 'ISI YK', 'province_hint' => 'Yogyakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Seni Indonesia Surakarta', 'code' => 'ISI SOLO', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Institut Seni Indonesia Denpasar', 'code' => 'ISI DENPASAR', 'province_hint' => 'Bali', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Seni Budaya Indonesia Bandung', 'code' => 'ISBI BDG', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Seni Budaya Indonesia Aceh', 'code' => 'ISBI ACEH', 'province_hint' => 'Aceh', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Agama Islam Negeri Pontianak', 'code' => 'IAIN PTK', 'province_hint' => 'Kalimantan Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Agama Islam Negeri Samarinda', 'code' => 'IAIN SMD', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Institut Agama Islam Negeri Purwokerto', 'code' => 'IAIN PUWT', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'B'],
            
            // universitas swasta terkemuka
            ['name' => 'Universitas Bina Nusantara', 'code' => 'BINUS', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Pelita Harapan', 'code' => 'UPH', 'province_hint' => 'Banten', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Atmajaya Jakarta', 'code' => 'UAJKT', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Katolik Parahyangan', 'code' => 'UNPAR', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Kristen Petra', 'code' => 'UK PETRA', 'province_hint' => 'Jawa Timur', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Trisakti', 'code' => 'USAKTI', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Mercu Buana', 'code' => 'UMB', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Pasundan', 'code' => 'UNPAS', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Indonesia', 'code' => 'UII', 'province_hint' => 'Yogyakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Muhammadiyah Yogyakarta', 'code' => 'UMY', 'province_hint' => 'Yogyakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Surakarta', 'code' => 'UMS', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Muhammadiyah Malang', 'code' => 'UMM', 'province_hint' => 'Jawa Timur', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Muhammadiyah Sumatera Utara', 'code' => 'UMSU', 'province_hint' => 'Sumatera Utara', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Makassar', 'code' => 'UNISMUH', 'province_hint' => 'Sulawesi Selatan', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Ahmad Dahlan', 'code' => 'UAD', 'province_hint' => 'Yogyakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Gunadarma', 'code' => 'UG', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Esa Unggul', 'code' => 'UEU', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Tarumanagara', 'code' => 'UNTAR', 'province_hint' => 'Jakarta', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Kristen Maranatha', 'code' => 'MARANATHA', 'province_hint' => 'Jawa Barat', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Islam Sultan Agung', 'code' => 'UNISSULA', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'A'],
            ['name' => 'Universitas Dian Nuswantoro', 'code' => 'UDINUS', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Stikubank', 'code' => 'UNISBANK', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Muhammadiyah Purwokerto', 'code' => 'UMP', 'province_hint' => 'Jawa Tengah', 'type' => 'swasta', 'accreditation' => 'B'],
            ['name' => 'Universitas Jenderal Soedirman', 'code' => 'UNSOED', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Universitas Trunojoyo Madura', 'code' => 'UTM', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Universitas Bangka Belitung', 'code' => 'UBB', 'province_hint' => 'Bangka Belitung', 'type' => 'negeri', 'accreditation' => 'B'],
            
            // politeknik negeri
            ['name' => 'Politeknik Negeri Jakarta', 'code' => 'PNJ', 'province_hint' => 'Jakarta', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Negeri Bandung', 'code' => 'POLBAN', 'province_hint' => 'Jawa Barat', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Negeri Semarang', 'code' => 'POLINES', 'province_hint' => 'Jawa Tengah', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Elektronika Negeri Surabaya', 'code' => 'PENS', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Negeri Malang', 'code' => 'POLINEMA', 'province_hint' => 'Jawa Timur', 'type' => 'negeri', 'accreditation' => 'A'],
            ['name' => 'Politeknik Negeri Medan', 'code' => 'POLMED', 'province_hint' => 'Sumatera Utara', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Politeknik Negeri Padang', 'code' => 'PNP', 'province_hint' => 'Sumatera Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Politeknik Negeri Pontianak', 'code' => 'POLNEP', 'province_hint' => 'Kalimantan Barat', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Politeknik Negeri Balikpapan', 'code' => 'POLTEKBA', 'province_hint' => 'Kalimantan Timur', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Politeknik Negeri Ujung Pandang', 'code' => 'PNUP', 'province_hint' => 'Sulawesi Selatan', 'type' => 'negeri', 'accreditation' => 'B'],
            ['name' => 'Politeknik Negeri Bali', 'code' => 'PNB', 'province_hint' => 'Bali', 'type' => 'negeri', 'accreditation' => 'B'],
        ];
        
        $createdCount = 0;
        
        foreach ($universities as $univData) {
            $location = $this->findUniversityLocation($univData['province_hint'], $allProvinces);
            
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
     * cari lokasi universitas berdasarkan province_hint
     */
    private function findUniversityLocation(?string $provinceHint, $allProvinces): ?array
    {
        if (!$provinceHint) {
            // random jika tidak ada hint
            $province = $allProvinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            return [
                'province_id' => $province->id,
                'regency_id' => $regency ? $regency->id : null,
            ];
        }
        
        // cari province yang match dengan hint (case-insensitive, partial match)
        $province = $allProvinces->first(function ($p) use ($provinceHint) {
            return stripos($p->name, $provinceHint) !== false;
        });
        
        if (!$province) {
            // fallback: random
            $province = $allProvinces->random();
        }
        
        // ambil random regency dari province
        $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
        
        return [
            'province_id' => $province->id,
            'regency_id' => $regency ? $regency->id : null,
        ];
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
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Mesin',
            'Teknik Sipil', 'Arsitektur', 'Manajemen', 'Akuntansi', 'Ekonomi Pembangunan',
            'Ilmu Komunikasi', 'Hukum', 'Psikologi', 'Kedokteran', 'Keperawatan',
            'Farmasi', 'Kesehatan Masyarakat', 'Pendidikan', 'Sastra Indonesia',
            'Sastra Inggris', 'Desain Komunikasi Visual', 'Desain Produk', 'Seni Rupa',
            'Pertanian', 'Peternakan', 'Kehutanan', 'Perikanan', 'Biologi',
            'Kimia', 'Fisika', 'Matematika', 'Statistika', 'Geografi',
            'Sosiologi', 'Ilmu Politik', 'Hubungan Internasional', 'Administrasi Publik',
            'Teknik Industri', 'Teknik Kimia', 'Teknik Lingkungan', 'Agroteknologi'
        ];
        
        $universities = University::all();
        
        if ($universities->isEmpty()) {
            echo "  ERROR: Tidak ada universities! Seed universities dulu.\n";
            return;
        }
        
        // buat 400 mahasiswa
        for ($i = 0; $i < 400; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            // generate username yang unique dengan timestamp untuk menghindari duplicate
            $baseUsername = strtolower($firstName . $lastName);
            $username = $baseUsername . rand(100, 9999);
            
            // pastikan username benar-benar unique
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . rand(100, 9999) . $counter;
                $counter++;
            }
            
            $university = $universities->random();
            
            // generate email sesuai domain universitas
            $email = $username . '@' . $this->getUniversityEmailDomain($university->code);
            
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'university_id' => $university->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'nim' => rand(1000000000, 9999999999),
                'major' => $majors[array_rand($majors)],
                'semester' => rand(1, 8),
                'phone' => '+628' . rand(1000000000, 9999999999),
            ]);
        }
        
        echo "  -> " . Student::count() . " students berhasil dibuat\n";
    }

    /**
     * seeding institutions dummy (80 institutions untuk lebih realistis)
     * DISTRIBUSI OTOMATIS ke semua provinsi dari database
     * UPDATED: nama institusi yang credible dan realistis
     */
    private function seedInstitutions(): void
    {
        echo "seeding institutions...\n";
        
        $provinces = Province::all();
        
        if ($provinces->isEmpty()) {
            echo "  ERROR: Tidak ada data provinces!\n";
            return;
        }
        
        // template nama institusi berdasarkan tipe dengan gelar PIC yang proper
        $institutionTemplates = [
            [
                'type' => 'Pemerintah Desa',
                'names' => [
                    'Desa Sukamaju', 'Desa Mekar Sari', 'Desa Sukamakmur', 'Desa Cimanggis', 
                    'Desa Tanjung Raya', 'Desa Sidorejo', 'Desa Karanganyar', 'Desa Purworejo',
                    'Desa Banjarwangi', 'Desa Margahayu', 'Desa Cibitung', 'Desa Bojong Gede'
                ],
                'pic' => [
                    ['name' => 'Drs. Agus Sutrisno', 'position' => 'Kepala Desa'],
                    ['name' => 'H. Bambang Setiawan, S.Sos', 'position' => 'Kepala Desa'],
                    ['name' => 'Ahmad Yani, S.Pd', 'position' => 'Sekretaris Desa'],
                    ['name' => 'Drs. Joko Santoso, M.Si', 'position' => 'Kepala Desa'],
                ]
            ],
            [
                'type' => 'Dinas Kesehatan',
                'names' => [
                    'Kabupaten Bandung', 'Kabupaten Bogor', 'Kabupaten Bekasi', 'Kabupaten Karawang',
                    'Kabupaten Semarang', 'Kabupaten Malang', 'Kabupaten Surabaya', 'Kota Yogyakarta',
                    'Kabupaten Tangerang', 'Kota Depok', 'Kabupaten Sukabumi', 'Kabupaten Garut'
                ],
                'pic' => [
                    ['name' => 'dr. Siti Nurhaliza, M.Kes', 'position' => 'Kepala Dinas'],
                    ['name' => 'dr. Rita Sari, Sp.PK', 'position' => 'Kepala Dinas'],
                    ['name' => 'Ir. Dewi Lestari, M.Kes', 'position' => 'Sekretaris Dinas'],
                    ['name' => 'Dr. dr. Hendra Kusuma, M.Sc', 'position' => 'Kepala Dinas'],
                ]
            ],
            [
                'type' => 'Dinas Pendidikan',
                'names' => [
                    'Kabupaten Bandung', 'Kabupaten Bogor', 'Kota Bekasi', 'Kabupaten Cianjur',
                    'Kabupaten Semarang', 'Kota Surabaya', 'Kabupaten Sidoarjo', 'Kota Malang',
                    'Kabupaten Jember', 'Kabupaten Banyuwangi', 'Kota Medan', 'Kabupaten Deli Serdang'
                ],
                'pic' => [
                    ['name' => 'Dr. Maya Anggraini, M.Pd', 'position' => 'Kepala Dinas'],
                    ['name' => 'Drs. Budi Hermawan, M.M', 'position' => 'Kepala Dinas'],
                    ['name' => 'Prof. Dr. Nina Rahayu, M.Pd', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dra. Ratna Wijaya, M.Si', 'position' => 'Sekretaris Dinas'],
                ]
            ],
            [
                'type' => 'Dinas Pertanian',
                'names' => [
                    'Kabupaten Subang', 'Kabupaten Indramayu', 'Kabupaten Karawang', 'Kabupaten Purwakarta',
                    'Kabupaten Brebes', 'Kabupaten Tegal', 'Kabupaten Pati', 'Kabupaten Demak',
                    'Kabupaten Ngawi', 'Kabupaten Magetan', 'Kabupaten Kediri', 'Kabupaten Tulungagung'
                ],
                'pic' => [
                    ['name' => 'Ir. Eko Prasetyo, M.P', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dr. Ir. Ahmad Yani, M.Sc', 'position' => 'Kepala Dinas'],
                    ['name' => 'Ir. Hadi Suryanto, M.M', 'position' => 'Kabid Pemberdayaan'],
                    ['name' => 'Drs. Joko Widodo, M.Si', 'position' => 'Sekretaris Dinas'],
                ]
            ],
            [
                'type' => 'Dinas Pariwisata Dan Kebudayaan',
                'names' => [
                    'Kabupaten Bandung', 'Kabupaten Garut', 'Kabupaten Ciamis', 'Kota Yogyakarta',
                    'Kabupaten Sleman', 'Kabupaten Bantul', 'Kabupaten Banyuwangi', 'Kabupaten Magelang',
                    'Kabupaten Lombok Barat', 'Kabupaten Badung', 'Kabupaten Gianyar', 'Kota Malang'
                ],
                'pic' => [
                    ['name' => 'Drs. Rizki Ramadhan, M.Par', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dr. Diana Putri, S.Sn, M.Hum', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dra. Fitri Handayani, M.M', 'position' => 'Kabid Pengembangan'],
                    ['name' => 'Ir. Hendra Wijaya, M.T', 'position' => 'Sekretaris Dinas'],
                ]
            ],
            [
                'type' => 'Dinas Sosial',
                'names' => [
                    'Kabupaten Bandung', 'Kota Bandung', 'Kabupaten Bogor', 'Kota Jakarta Pusat',
                    'Kabupaten Semarang', 'Kota Surabaya', 'Kabupaten Malang', 'Kota Medan',
                    'Kabupaten Tangerang', 'Kota Bekasi', 'Kabupaten Depok', 'Kabupaten Sukabumi'
                ],
                'pic' => [
                    ['name' => 'Dra. Sinta Permata, M.Si', 'position' => 'Kepala Dinas'],
                    ['name' => 'Drs. Dedi Kurniawan, M.Sos', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dr. Lina Marlina, S.Sos, M.Si', 'position' => 'Kabid Pemberdayaan'],
                    ['name' => 'Hj. Dewi Kusuma, S.Sos, M.M', 'position' => 'Sekretaris Dinas'],
                ]
            ],
            [
                'type' => 'Dinas Lingkungan Hidup',
                'names' => [
                    'Kabupaten Bandung', 'Kota Bogor', 'Kabupaten Bekasi', 'Kota Depok',
                    'Kabupaten Semarang', 'Kota Surabaya', 'Kabupaten Sidoarjo', 'Kota Malang',
                    'Kabupaten Tangerang Selatan', 'Kota Yogyakarta', 'Kabupaten Sleman', 'Kota Medan'
                ],
                'pic' => [
                    ['name' => 'Dr. Ir. Bambang Sutrisno, M.T', 'position' => 'Kepala Dinas'],
                    ['name' => 'Ir. Maya Sari, M.Si', 'position' => 'Kepala Dinas'],
                    ['name' => 'Dr. Agus Setiawan, S.T, M.Eng', 'position' => 'Kabid Pengendalian'],
                    ['name' => 'Drs. Hadi Purnomo, M.M', 'position' => 'Sekretaris Dinas'],
                ]
            ],
            [
                'type' => 'Puskesmas',
                'names' => [
                    'Puskesmas Cimanggis', 'Puskesmas Bojong Gede', 'Puskesmas Sukmajaya', 'Puskesmas Beji',
                    'Puskesmas Limo', 'Puskesmas Sawangan', 'Puskesmas Pancoran Mas', 'Puskesmas Cilodong',
                    'Puskesmas Sukamaju', 'Puskesmas Margonda', 'Puskesmas Kalimulya', 'Puskesmas Harjamukti'
                ],
                'pic' => [
                    ['name' => 'dr. Rita Handayani, M.Kes', 'position' => 'Kepala Puskesmas'],
                    ['name' => 'dr. Hendra Wijaya, Sp.PD', 'position' => 'Kepala Puskesmas'],
                    ['name' => 'dr. Siti Nurjanah, M.K.M', 'position' => 'Kepala Puskesmas'],
                    ['name' => 'dr. Ahmad Fauzi, M.Kes', 'position' => 'Kepala Puskesmas'],
                ]
            ],
            [
                'type' => 'Kecamatan',
                'names' => [
                    'Kecamatan Cimanggis', 'Kecamatan Pancoran Mas', 'Kecamatan Sukmajaya', 'Kecamatan Beji',
                    'Kecamatan Cilodong', 'Kecamatan Sawangan', 'Kecamatan Limo', 'Kecamatan Bojong Gede',
                    'Kecamatan Tapos', 'Kecamatan Cinere', 'Kecamatan Harjamukti', 'Kecamatan Cipayung'
                ],
                'pic' => [
                    ['name' => 'Drs. Joko Purnomo, M.Si', 'position' => 'Camat'],
                    ['name' => 'H. Bambang Santoso, S.Sos, M.M', 'position' => 'Camat'],
                    ['name' => 'Dra. Nina Kusuma, M.AP', 'position' => 'Sekretaris Camat'],
                    ['name' => 'Drs. Ahmad Sutrisno, M.Si', 'position' => 'Camat'],
                ]
            ],
            [
                'type' => 'Kelurahan',
                'names' => [
                    'Kelurahan Sukamaju', 'Kelurahan Mekar Jaya', 'Kelurahan Sukamakmur', 'Kelurahan Bojong Gede',
                    'Kelurahan Cilodong', 'Kelurahan Harjamukti', 'Kelurahan Margonda', 'Kelurahan Kemiri Muka',
                    'Kelurahan Pancoran Mas', 'Kelurahan Depok Jaya', 'Kelurahan Tugu', 'Kelurahan Beji Timur'
                ],
                'pic' => [
                    ['name' => 'Drs. Eko Wijaya, M.M', 'position' => 'Lurah'],
                    ['name' => 'Hj. Dewi Lestari, S.Sos', 'position' => 'Lurah'],
                    ['name' => 'H. Agus Purnomo, S.AP', 'position' => 'Lurah'],
                    ['name' => 'Dra. Maya Kusuma, M.Si', 'position' => 'Sekretaris Lurah'],
                ]
            ],
            [
                'type' => 'BPBD',
                'names' => [
                    'Kabupaten Bandung', 'Kabupaten Bogor', 'Kota Bekasi', 'Kabupaten Karawang',
                    'Kabupaten Semarang', 'Kota Surabaya', 'Kabupaten Malang', 'Kota Yogyakarta',
                    'Kabupaten Tangerang', 'Kota Depok', 'Kabupaten Sukabumi', 'Kabupaten Garut'
                ],
                'pic' => [
                    ['name' => 'Drs. Hadi Suryanto, M.M', 'position' => 'Kepala BPBD'],
                    ['name' => 'Ir. Bambang Setiawan, M.T', 'position' => 'Kepala BPBD'],
                    ['name' => 'Dr. Ahmad Fauzi, S.T, M.Eng', 'position' => 'Kepala Bidang Pencegahan'],
                    ['name' => 'Drs. Joko Widodo, M.Si', 'position' => 'Sekretaris BPBD'],
                ]
            ],
            [
                'type' => 'NGO',
                'names' => [
                    'Yayasan Peduli Indonesia', 'Yayasan Pemberdayaan Masyarakat', 'Yayasan Lingkungan Hijau',
                    'Yayasan Pendidikan Nusantara', 'Yayasan Kesehatan Masyarakat', 'Yayasan Anak Bangsa',
                    'Forum Masyarakat Peduli', 'Lembaga Swadaya Masyarakat Mandiri', 'Yayasan Gerakan Sehat',
                    'Komunitas Peduli Lingkungan', 'Yayasan Literasi Indonesia', 'Rumah Pintar Nusantara'
                ],
                'pic' => [
                    ['name' => 'Dr. Diana Putri, S.Sos, M.M', 'position' => 'Direktur Eksekutif'],
                    ['name' => 'Drs. Rizki Ramadhan, M.Si', 'position' => 'Ketua Yayasan'],
                    ['name' => 'Ir. Fitri Handayani, M.T', 'position' => 'Koordinator Program'],
                    ['name' => 'Dr. Hendra Kusuma, S.E, M.M', 'position' => 'Direktur Eksekutif'],
                ]
            ],
        ];
        
        $createdCount = 0;
        
        // buat 80 institusi dengan distribusi merata
        for ($i = 0; $i < 80; $i++) {
            // pilih random template
            $template = $institutionTemplates[array_rand($institutionTemplates)];
            $type = $template['type'];
            
            // pilih random nama dari template
            $baseName = $template['names'][array_rand($template['names'])];
            
            // untuk tipe yang membutuhkan nama kabupaten/kota, gabungkan dengan tipe
            if (in_array($type, ['Dinas Kesehatan', 'Dinas Pendidikan', 'Dinas Pertanian', 
                                 'Dinas Pariwisata Dan Kebudayaan', 'Dinas Sosial', 
                                 'Dinas Lingkungan Hidup', 'BPBD'])) {
                $instName = $type . ' ' . $baseName;
            } else {
                $instName = $baseName;
            }
            
            // pilih random PIC
            $pic = $template['pic'][array_rand($template['pic'])];
            
            // generate username dari nama institusi (bersih, lowercase, no spaces)
            $cleanName = strtolower(preg_replace('/[^a-z0-9]/i', '', $instName));
            $username = substr($cleanName, 0, 20) . rand(100, 999);
            
            // pastikan username benar-benar unique
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = substr($cleanName, 0, 18) . rand(100, 999) . $counter;
                $counter++;
            }
            
            // generate email dari nama institusi dengan random number untuk uniqueness
            $emailPrefix = strtolower(preg_replace('/[^a-z0-9]/i', '', $instName));
            $emailPrefix = substr($emailPrefix, 0, 25); // kurangi sedikit untuk beri space buat random
            
            // domain email berdasarkan tipe institusi
            $emailDomain = match($type) {
                'Pemerintah Desa' => 'desa.go.id',
                'Kelurahan' => 'kelurahan.go.id',
                'Kecamatan' => 'kecamatan.go.id',
                'Puskesmas' => 'puskesmas.kemkes.go.id',
                'NGO' => 'ngo.or.id',
                default => 'pemda.go.id'
            };
            
            // tambahkan random number untuk ensure uniqueness
            $email = $emailPrefix . rand(100, 999) . '@' . $emailDomain;
            
            // pastikan email benar-benar unique
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = $emailPrefix . rand(1000, 9999) . $counter . '@' . $emailDomain;
                $counter++;
            }
            
            // random province dan regency dari BPS
            $province = $provinces->random();
            $regencies = Regency::where('province_id', $province->id)->get();
            
            if ($regencies->isEmpty()) {
                // skip jika tidak ada regency
                continue;
            }
            
            $regency = $regencies->random();
            
            // generate alamat yang realistis berdasarkan regency
            $streetNames = [
                'Jl. Merdeka', 'Jl. Sudirman', 'Jl. Ahmad Yani', 'Jl. Diponegoro',
                'Jl. Gatot Subroto', 'Jl. Imam Bonjol', 'Jl. Veteran', 'Jl. Pemuda',
                'Jl. Pahlawan', 'Jl. Raya Bogor', 'Jl. Raya Jakarta', 'Jl. Proklamasi',
                'Jl. Kemerdekaan', 'Jl. Jenderal Sudirman', 'Jl. Kartini', 'Jl. Gajah Mada'
            ];
            
            $streetName = $streetNames[array_rand($streetNames)];
            $address = $streetName . ' No. ' . rand(1, 200) . ', ' . $regency->name . ', ' . $province->name;
            
            // buat user
            $user = User::create([
                'name' => $instName,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // buat institution
            Institution::create([
                'user_id' => $user->id,
                'name' => $instName,
                'type' => $type,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'email' => $email,
                'address' => $address,
                'phone' => '+622' . rand(100000000, 999999999),
                'pic_name' => $pic['name'],
                'pic_position' => $pic['position'],
                'description' => 'Instansi yang bergerak di bidang ' . strtolower($type) . ' dengan fokus pemberdayaan masyarakat dan pengembangan wilayah.',
                'is_verified' => true,
            ]);
            
            $createdCount++;
        }
        
        echo "  -> {$createdCount} institutions berhasil dibuat\n";
    }

    /**
     * generate email domain untuk universitas berdasarkan kode
     */
    private function getUniversityEmailDomain(string $code): string
    {
        // mapping kode universitas ke domain email
        return match($code) {
            'UI' => 'ui.ac.id',
            'ITB' => 'itb.ac.id',
            'UGM' => 'ugm.ac.id',
            'ITS' => 'its.ac.id',
            'UNAIR' => 'unair.ac.id',
            'UNPAD' => 'unpad.ac.id',
            'UNDIP' => 'undip.ac.id',
            'UB' => 'ub.ac.id',
            'UNHAS' => 'unhas.ac.id',
            'USU' => 'usu.ac.id',
            'IPB' => 'ipb.ac.id',
            'UNS' => 'uns.ac.id',
            'UPI' => 'upi.edu',
            'UNJ' => 'unj.ac.id',
            'UNY' => 'uny.ac.id',
            'UM' => 'um.ac.id',
            'UNNES' => 'unnes.ac.id',
            'UNESA' => 'unesa.ac.id',
            'UNM' => 'unm.ac.id',
            'UNIMED' => 'unimed.ac.id',
            'UNP' => 'unp.ac.id',
            'UNAND' => 'unand.ac.id',
            'UNRI' => 'unri.ac.id',
            'UNSRI' => 'unsri.ac.id',
            'UNILA' => 'unila.ac.id',
            'UNEJ' => 'unej.ac.id',
            'UNUD' => 'unud.ac.id',
            'UNRAM' => 'unram.ac.id',
            'UNDANA' => 'undana.ac.id',
            'UNMUL' => 'unmul.ac.id',
            'ULM' => 'ulm.ac.id',
            'UNTAN' => 'untan.ac.id',
            'UPR' => 'upr.ac.id',
            'UNTAD' => 'untad.ac.id',
            'UHO' => 'uho.ac.id',
            'UNSRAT' => 'unsrat.ac.id',
            'UNKHAIR' => 'unkhair.ac.id',
            'UNPATTI' => 'unpatti.ac.id',
            'UNCEN' => 'uncen.ac.id',
            'UNSYIAH' => 'unsyiah.ac.id',
            'UNIB' => 'unib.ac.id',
            'UNJA' => 'unja.ac.id',
            'UIN JKT' => 'uinjkt.ac.id',
            'UIN SGD' => 'uinsgd.ac.id',
            'UIN WALISONGO' => 'walisongo.ac.id',
            'UIN SUKA' => 'uin-suka.ac.id',
            'UIN SUNAN AMPEL' => 'uinsby.ac.id',
            'UIN MALANG' => 'uin-malang.ac.id',
            'UIN SUSKA' => 'uin-suska.ac.id',
            'UIN RADEN FATAH' => 'radenfatah.ac.id',
            'UIN AR-RANIRY' => 'ar-raniry.ac.id',
            'UIN IB' => 'uinib.ac.id',
            'UIN ALAUDDIN' => 'uin-alauddin.ac.id',
            'UIN MATARAM' => 'uinmataram.ac.id',
            'UIN SATU' => 'uinsatu.ac.id',
            'ITERA' => 'itera.ac.id',
            'ITK' => 'itk.ac.id',
            'ISI YK' => 'isi.ac.id',
            'ISI SOLO' => 'isi-ska.ac.id',
            'ISI DENPASAR' => 'isi-dps.ac.id',
            'ISBI BDG' => 'isbi.ac.id',
            'ISBI ACEH' => 'isbiaceh.ac.id',
            'IAIN PTK' => 'iainptk.ac.id',
            'IAIN SMD' => 'iain-samarinda.ac.id',
            'IAIN PUWT' => 'iainpurwokerto.ac.id',
            'BINUS' => 'binus.ac.id',
            'UPH' => 'uph.edu',
            'UAJKT' => 'atmajaya.ac.id',
            'UNPAR' => 'unpar.ac.id',
            'UK PETRA' => 'petra.ac.id',
            'USAKTI' => 'trisakti.ac.id',
            'UMB' => 'mercubuana.ac.id',
            'UNPAS' => 'unpas.ac.id',
            'UII' => 'uii.ac.id',
            'UMY' => 'umy.ac.id',
            'UMS' => 'ums.ac.id',
            'UMM' => 'umm.ac.id',
            'UMSU' => 'umsu.ac.id',
            'UNISMUH' => 'unismuh.ac.id',
            'UAD' => 'uad.ac.id',
            'UG' => 'gunadarma.ac.id',
            'UEU' => 'esaunggul.ac.id',
            'UNTAR' => 'untar.ac.id',
            'MARANATHA' => 'maranatha.edu',
            'UNISSULA' => 'unissula.ac.id',
            'UDINUS' => 'dinus.ac.id',
            'UNISBANK' => 'unisbank.ac.id',
            'UMP' => 'ump.ac.id',
            'UNSOED' => 'unsoed.ac.id',
            'UTM' => 'trunojoyo.ac.id',
            'UBB' => 'ubb.ac.id',
            'PNJ' => 'pnj.ac.id',
            'POLBAN' => 'polban.ac.id',
            'POLINES' => 'polines.ac.id',
            'PENS' => 'pens.ac.id',
            'POLINEMA' => 'polinema.ac.id',
            'POLMED' => 'polmed.ac.id',
            'PNP' => 'pnp.ac.id',
            'POLNEP' => 'polnep.ac.id',
            'POLTEKBA' => 'poltekba.ac.id',
            'PNUP' => 'pnup.ac.id',
            'PNB' => 'pnb.ac.id',
            
            // fallback untuk kode yang tidak terdaftar - generate dari kode
            default => strtolower($code) . '.ac.id',
        };
    }
}