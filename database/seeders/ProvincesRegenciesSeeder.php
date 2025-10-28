<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\BpsApiService;

/**
 * seeder untuk data provinces dan regencies dari BPS API
 * FIXED: reset sequence PostgreSQL untuk menghindari duplicate key error
 * menggunakan BpsApiService untuk modularitas
 * 
 * jalankan: php artisan db:seed --class=ProvincesRegenciesSeeder
 */
class ProvincesRegenciesSeeder extends Seeder
{
    /**
     * BPS API service
     */
    private $bpsService;

    /**
     * constructor
     */
    public function __construct(BpsApiService $bpsService)
    {
        $this->bpsService = $bpsService;
    }

    /**
     * seed data provinces dan regencies indonesia
     */
    public function run(): void
    {
        $this->command->info('🌏 Mengambil data provinsi dan kabupaten dari BPS API...');
        $this->command->newLine();

        try {
            // hapus data lama jika ada
            $this->cleanOldData();

            // test koneksi terlebih dahulu
            if (!$this->testBpsConnection()) {
                throw new \Exception('Koneksi ke BPS API gagal');
            }

            // ambil dan seed provinces
            $this->seedProvinces();

            // ambil dan seed regencies
            $this->seedRegencies();

            // tampilkan summary
            $this->showSummary();

        } catch (\Exception $e) {
            $this->command->error('❌ Error: ' . $e->getMessage());
            Log::error('BPS Seeder Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // fallback ke data minimal jika API gagal
            $this->command->warn('⚠️  Menggunakan data fallback minimal...');
            $this->seedFallbackData();
            $this->showSummary();
        }
    }

    /**
     * bersihkan data lama dengan reset sequence PostgreSQL
     * CRITICAL FIX: reset sequence untuk menghindari duplicate key error
     */
    private function cleanOldData(): void
    {
        $this->command->info('🗑️  Membersihkan data lama...');
        
        try {
            // untuk PostgreSQL: TRUNCATE dengan RESTART IDENTITY CASCADE
            DB::statement('TRUNCATE TABLE regencies, provinces RESTART IDENTITY CASCADE');
            $this->command->info('   ✓ Data lama dihapus dengan TRUNCATE CASCADE');
            
        } catch (\Exception $e) {
            $this->command->warn('   ⚠️  TRUNCATE gagal: ' . $e->getMessage());
            $this->command->info('   → Mencoba alternatif dengan DELETE...');
            
            try {
                // alternatif 1: DELETE dengan reset sequence manual
                DB::table('regencies')->delete();
                DB::table('provinces')->delete();
                
                // CRITICAL: reset sequence di PostgreSQL
                $this->resetSequences();
                
                $this->command->info('   ✓ Data lama dihapus dengan DELETE + sequence reset');
                
            } catch (\Exception $e2) {
                $this->command->error('   ❌ Gagal membersihkan data: ' . $e2->getMessage());
                throw $e2;
            }
        }
        
        $this->command->newLine();
    }

    /**
     * reset auto-increment sequences di PostgreSQL
     * CRITICAL untuk menghindari duplicate key error saat insert
     */
    private function resetSequences(): void
    {
        try {
            // reset sequence provinces
            DB::statement("SELECT setval(pg_get_serial_sequence('provinces', 'id'), 1, false)");
            $this->command->info('   ✓ Provinces sequence direset');
            
            // reset sequence regencies
            DB::statement("SELECT setval(pg_get_serial_sequence('regencies', 'id'), 1, false)");
            $this->command->info('   ✓ Regencies sequence direset');
            
        } catch (\Exception $e) {
            $this->command->warn('   ⚠️  Reset sequence warning: ' . $e->getMessage());
            
            // alternatif: reset manual dengan nilai max
            try {
                DB::statement("ALTER SEQUENCE provinces_id_seq RESTART WITH 1");
                DB::statement("ALTER SEQUENCE regencies_id_seq RESTART WITH 1");
                $this->command->info('   ✓ Sequences direset dengan ALTER SEQUENCE');
            } catch (\Exception $e2) {
                $this->command->warn('   ⚠️  Could not reset sequences: ' . $e2->getMessage());
            }
        }
    }

    /**
     * test koneksi ke BPS API
     */
    private function testBpsConnection(): bool
    {
        $this->command->info('🔌 Mengecek koneksi ke BPS API...');
        
        $result = $this->bpsService->testConnection();

        if ($result['success']) {
            $this->command->info('   ✓ Koneksi berhasil');
            $this->command->line("   Response time: {$result['response_time']}");
            return true;
        } else {
            $this->command->error('   ✗ Koneksi gagal: ' . ($result['error'] ?? 'Unknown error'));
            return false;
        }

        $this->command->newLine();
    }

    /**
     * seed provinces dari BPS API
     */
    private function seedProvinces(): void
    {
        $this->command->info('📍 Mengambil data provinsi...');

        $provinces = $this->bpsService->getProvinces();

        if (empty($provinces)) {
            throw new \Exception('Tidak ada data provinsi dari BPS API');
        }

        // insert ke database dengan batch dan transaction
        foreach (array_chunk($provinces, 50) as $chunk) {
            DB::transaction(function () use ($chunk) {
                DB::table('provinces')->insert($chunk);
            });
        }
        
        $count = count($provinces);
        $this->command->info("   ✓ {$count} provinsi berhasil disimpan");
        $this->command->newLine();
    }

    /**
     * seed regencies dari BPS API untuk setiap provinsi
     */
    private function seedRegencies(): void
    {
        $this->command->info('🏙️  Mengambil data kabupaten/kota...');

        $provinces = DB::table('provinces')->get();
        $totalRegencies = 0;
        $successCount = 0;
        $failCount = 0;

        $progressBar = $this->command->getOutput()->createProgressBar(count($provinces));
        $progressBar->start();

        foreach ($provinces as $province) {
            try {
                $regencies = $this->bpsService->getRegencies($province->code);

                if (!empty($regencies)) {
                    // insert dengan batch dan transaction
                    foreach (array_chunk($regencies, 100) as $chunk) {
                        DB::transaction(function () use ($chunk) {
                            DB::table('regencies')->insert($chunk);
                        });
                    }
                    
                    $totalRegencies += count($regencies);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
                Log::warning("Gagal mengambil regencies untuk provinsi {$province->name}", [
                    'error' => $e->getMessage()
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->newLine();
        $this->command->info("   ✓ {$totalRegencies} kabupaten/kota berhasil disimpan");
        $this->command->info("   ✓ Berhasil: {$successCount} provinsi");
        
        if ($failCount > 0) {
            $this->command->warn("   ⚠️  Gagal: {$failCount} provinsi");
        }
        
        $this->command->newLine();
    }

    /**
     * tampilkan summary hasil seeding
     */
    private function showSummary(): void
    {
        $provincesCount = DB::table('provinces')->count();
        $regenciesCount = DB::table('regencies')->count();

        $this->command->info('📊 Summary:');
        $this->command->table(
            ['Tipe', 'Jumlah'],
            [
                ['Provinsi', $provincesCount],
                ['Kabupaten/Kota', $regenciesCount],
            ]
        );
    }

    /**
     * seed data fallback minimal jika BPS API gagal
     * data minimal Indonesia untuk testing
     */
    private function seedFallbackData(): void
    {
        $this->command->info('📦 Menggunakan data fallback minimal...');
        
        // reset data lama terlebih dahulu
        DB::table('regencies')->delete();
        DB::table('provinces')->delete();
        $this->resetSequences();
        
        // data minimal provinsi Indonesia
        $provinces = [
            ['id' => 31, 'code' => '31', 'name' => 'DKI Jakarta'],
            ['id' => 32, 'code' => '32', 'name' => 'Jawa Barat'],
            ['id' => 33, 'code' => '33', 'name' => 'Jawa Tengah'],
            ['id' => 34, 'code' => '34', 'name' => 'DI Yogyakarta'],
            ['id' => 35, 'code' => '35', 'name' => 'Jawa Timur'],
            ['id' => 36, 'code' => '36', 'name' => 'Banten'],
            ['id' => 51, 'code' => '51', 'name' => 'Bali'],
            ['id' => 52, 'code' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['id' => 53, 'code' => '53', 'name' => 'Nusa Tenggara Timur'],
            ['id' => 11, 'code' => '11', 'name' => 'Aceh'],
            ['id' => 12, 'code' => '12', 'name' => 'Sumatera Utara'],
            ['id' => 13, 'code' => '13', 'name' => 'Sumatera Barat'],
            ['id' => 14, 'code' => '14', 'name' => 'Riau'],
            ['id' => 15, 'code' => '15', 'name' => 'Jambi'],
            ['id' => 16, 'code' => '16', 'name' => 'Sumatera Selatan'],
            ['id' => 17, 'code' => '17', 'name' => 'Bengkulu'],
            ['id' => 18, 'code' => '18', 'name' => 'Lampung'],
            ['id' => 19, 'code' => '19', 'name' => 'Kepulauan Bangka Belitung'],
            ['id' => 21, 'code' => '21', 'name' => 'Kepulauan Riau'],
            ['id' => 61, 'code' => '61', 'name' => 'Kalimantan Barat'],
            ['id' => 62, 'code' => '62', 'name' => 'Kalimantan Tengah'],
            ['id' => 63, 'code' => '63', 'name' => 'Kalimantan Selatan'],
            ['id' => 64, 'code' => '64', 'name' => 'Kalimantan Timur'],
            ['id' => 65, 'code' => '65', 'name' => 'Kalimantan Utara'],
            ['id' => 71, 'code' => '71', 'name' => 'Sulawesi Utara'],
            ['id' => 72, 'code' => '72', 'name' => 'Sulawesi Tengah'],
            ['id' => 73, 'code' => '73', 'name' => 'Sulawesi Selatan'],
            ['id' => 74, 'code' => '74', 'name' => 'Sulawesi Tenggara'],
            ['id' => 75, 'code' => '75', 'name' => 'Gorontalo'],
            ['id' => 76, 'code' => '76', 'name' => 'Sulawesi Barat'],
            ['id' => 81, 'code' => '81', 'name' => 'Maluku'],
            ['id' => 82, 'code' => '82', 'name' => 'Maluku Utara'],
            ['id' => 91, 'code' => '91', 'name' => 'Papua'],
            ['id' => 92, 'code' => '92', 'name' => 'Papua Barat'],
        ];

        DB::transaction(function () use ($provinces) {
            DB::table('provinces')->insert($provinces);
        });

        // data minimal kabupaten/kota (sample dari berbagai provinsi)
        $regencies = [
            // DKI Jakarta
            ['id' => 3101, 'province_id' => 31, 'code' => '3101', 'name' => 'Kab. Kepulauan Seribu'],
            ['id' => 3171, 'province_id' => 31, 'code' => '3171', 'name' => 'Kota Jakarta Pusat'],
            ['id' => 3172, 'province_id' => 31, 'code' => '3172', 'name' => 'Kota Jakarta Utara'],
            ['id' => 3173, 'province_id' => 31, 'code' => '3173', 'name' => 'Kota Jakarta Barat'],
            ['id' => 3174, 'province_id' => 31, 'code' => '3174', 'name' => 'Kota Jakarta Selatan'],
            ['id' => 3175, 'province_id' => 31, 'code' => '3175', 'name' => 'Kota Jakarta Timur'],
            
            // Jawa Barat (sample)
            ['id' => 3201, 'province_id' => 32, 'code' => '3201', 'name' => 'Kab. Bogor'],
            ['id' => 3273, 'province_id' => 32, 'code' => '3273', 'name' => 'Kota Bandung'],
            ['id' => 3276, 'province_id' => 32, 'code' => '3276', 'name' => 'Kota Depok'],
            
            // Jawa Tengah (sample)
            ['id' => 3301, 'province_id' => 33, 'code' => '3301', 'name' => 'Kab. Cilacap'],
            ['id' => 3371, 'province_id' => 33, 'code' => '3371', 'name' => 'Kota Semarang'],
            ['id' => 3372, 'province_id' => 33, 'code' => '3372', 'name' => 'Kota Surakarta'],
            
            // DI Yogyakarta (lengkap)
            ['id' => 3401, 'province_id' => 34, 'code' => '3401', 'name' => 'Kab. Kulon Progo'],
            ['id' => 3402, 'province_id' => 34, 'code' => '3402', 'name' => 'Kab. Bantul'],
            ['id' => 3403, 'province_id' => 34, 'code' => '3403', 'name' => 'Kab. Gunung Kidul'],
            ['id' => 3404, 'province_id' => 34, 'code' => '3404', 'name' => 'Kab. Sleman'],
            ['id' => 3471, 'province_id' => 34, 'code' => '3471', 'name' => 'Kota Yogyakarta'],
            
            // Jawa Timur (sample)
            ['id' => 3501, 'province_id' => 35, 'code' => '3501', 'name' => 'Kab. Pacitan'],
            ['id' => 3578, 'province_id' => 35, 'code' => '3578', 'name' => 'Kota Surabaya'],
            ['id' => 3579, 'province_id' => 35, 'code' => '3579', 'name' => 'Kota Malang'],
            
            // Banten (sample)
            ['id' => 3601, 'province_id' => 36, 'code' => '3601', 'name' => 'Kab. Pandeglang'],
            ['id' => 3671, 'province_id' => 36, 'code' => '3671', 'name' => 'Kota Tangerang'],
            
            // Bali (sample)
            ['id' => 5101, 'province_id' => 51, 'code' => '5101', 'name' => 'Kab. Jembrana'],
            ['id' => 5171, 'province_id' => 51, 'code' => '5171', 'name' => 'Kota Denpasar'],
            
            // Provinsi lain (minimal 1 kab/kota per provinsi)
            ['id' => 5201, 'province_id' => 52, 'code' => '5201', 'name' => 'Kab. Lombok Barat'],
            ['id' => 5301, 'province_id' => 53, 'code' => '5301', 'name' => 'Kab. Sumba Barat'],
            ['id' => 1101, 'province_id' => 11, 'code' => '1101', 'name' => 'Kab. Aceh Selatan'],
            ['id' => 1201, 'province_id' => 12, 'code' => '1201', 'name' => 'Kab. Nias'],
            ['id' => 1301, 'province_id' => 13, 'code' => '1301', 'name' => 'Kab. Kepulauan Mentawai'],
            ['id' => 1401, 'province_id' => 14, 'code' => '1401', 'name' => 'Kab. Kuantan Singingi'],
            ['id' => 1501, 'province_id' => 15, 'code' => '1501', 'name' => 'Kab. Kerinci'],
            ['id' => 1601, 'province_id' => 16, 'code' => '1601', 'name' => 'Kab. Ogan Komering Ulu'],
            ['id' => 1701, 'province_id' => 17, 'code' => '1701', 'name' => 'Kab. Bengkulu Selatan'],
            ['id' => 1801, 'province_id' => 18, 'code' => '1801', 'name' => 'Kab. Lampung Barat'],
            ['id' => 1901, 'province_id' => 19, 'code' => '1901', 'name' => 'Kab. Bangka'],
            ['id' => 2101, 'province_id' => 21, 'code' => '2101', 'name' => 'Kab. Karimun'],
            ['id' => 6101, 'province_id' => 61, 'code' => '6101', 'name' => 'Kab. Sambas'],
            ['id' => 6201, 'province_id' => 62, 'code' => '6201', 'name' => 'Kab. Kotawaringin Barat'],
            ['id' => 6301, 'province_id' => 63, 'code' => '6301', 'name' => 'Kab. Tanah Laut'],
            ['id' => 6401, 'province_id' => 64, 'code' => '6401', 'name' => 'Kab. Paser'],
            ['id' => 6501, 'province_id' => 65, 'code' => '6501', 'name' => 'Kab. Malinau'],
            ['id' => 7101, 'province_id' => 71, 'code' => '7101', 'name' => 'Kab. Bolaang Mongondow'],
            ['id' => 7201, 'province_id' => 72, 'code' => '7201', 'name' => 'Kab. Banggai Kepulauan'],
            ['id' => 7301, 'province_id' => 73, 'code' => '7301', 'name' => 'Kab. Kepulauan Selayar'],
            ['id' => 7401, 'province_id' => 74, 'code' => '7401', 'name' => 'Kab. Buton'],
            ['id' => 7501, 'province_id' => 75, 'code' => '7501', 'name' => 'Kab. Boalemo'],
            ['id' => 7601, 'province_id' => 76, 'code' => '7601', 'name' => 'Kab. Majene'],
            ['id' => 8101, 'province_id' => 81, 'code' => '8101', 'name' => 'Kab. Maluku Tenggara Barat'],
            ['id' => 8201, 'province_id' => 82, 'code' => '8201', 'name' => 'Kab. Halmahera Barat'],
            ['id' => 9101, 'province_id' => 91, 'code' => '9101', 'name' => 'Kab. Merauke'],
            ['id' => 9201, 'province_id' => 92, 'code' => '9201', 'name' => 'Kab. Fakfak'],
        ];

        DB::transaction(function () use ($regencies) {
            DB::table('regencies')->insert($regencies);
        });

        $this->command->info('   ✓ Fallback data berhasil disimpan');
    }
}