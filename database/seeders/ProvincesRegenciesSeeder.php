<?php

// path: database/seeders/ProvincesRegenciesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesRegenciesSeeder extends Seeder
{
    /**
     * seed data provinces dan regencies indonesia
     */
    public function run(): void
    {
        // hapus data lama jika ada
        DB::table('regencies')->delete();
        DB::table('provinces')->delete();

        // data provinces dengan code BPS
        $provinces = [
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
            ['id' => 31, 'code' => '31', 'name' => 'DKI Jakarta'],
            ['id' => 32, 'code' => '32', 'name' => 'Jawa Barat'],
            ['id' => 33, 'code' => '33', 'name' => 'Jawa Tengah'],
            ['id' => 34, 'code' => '34', 'name' => 'DI Yogyakarta'],
            ['id' => 35, 'code' => '35', 'name' => 'Jawa Timur'],
            ['id' => 36, 'code' => '36', 'name' => 'Banten'],
            ['id' => 51, 'code' => '51', 'name' => 'Bali'],
            ['id' => 52, 'code' => '52', 'name' => 'Nusa Tenggara Barat'],
            ['id' => 53, 'code' => '53', 'name' => 'Nusa Tenggara Timur'],
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

        DB::table('provinces')->insert($provinces);

        // data regencies untuk jawa tengah (lengkap)
        $regenciesJateng = [
            ['id' => 3301, 'province_id' => 33, 'code' => '3301', 'name' => 'Kab. Cilacap'],
            ['id' => 3302, 'province_id' => 33, 'code' => '3302', 'name' => 'Kab. Banyumas'],
            ['id' => 3303, 'province_id' => 33, 'code' => '3303', 'name' => 'Kab. Purbalingga'],
            ['id' => 3304, 'province_id' => 33, 'code' => '3304', 'name' => 'Kab. Banjarnegara'],
            ['id' => 3305, 'province_id' => 33, 'code' => '3305', 'name' => 'Kab. Kebumen'],
            ['id' => 3306, 'province_id' => 33, 'code' => '3306', 'name' => 'Kab. Purworejo'],
            ['id' => 3307, 'province_id' => 33, 'code' => '3307', 'name' => 'Kab. Wonosobo'],
            ['id' => 3308, 'province_id' => 33, 'code' => '3308', 'name' => 'Kab. Magelang'],
            ['id' => 3309, 'province_id' => 33, 'code' => '3309', 'name' => 'Kab. Boyolali'],
            ['id' => 3310, 'province_id' => 33, 'code' => '3310', 'name' => 'Kab. Klaten'],
            ['id' => 3311, 'province_id' => 33, 'code' => '3311', 'name' => 'Kab. Sukoharjo'],
            ['id' => 3312, 'province_id' => 33, 'code' => '3312', 'name' => 'Kab. Wonogiri'],
            ['id' => 3313, 'province_id' => 33, 'code' => '3313', 'name' => 'Kab. Karanganyar'],
            ['id' => 3314, 'province_id' => 33, 'code' => '3314', 'name' => 'Kab. Sragen'],
            ['id' => 3315, 'province_id' => 33, 'code' => '3315', 'name' => 'Kab. Grobogan'],
            ['id' => 3316, 'province_id' => 33, 'code' => '3316', 'name' => 'Kab. Blora'],
            ['id' => 3317, 'province_id' => 33, 'code' => '3317', 'name' => 'Kab. Rembang'],
            ['id' => 3318, 'province_id' => 33, 'code' => '3318', 'name' => 'Kab. Pati'],
            ['id' => 3319, 'province_id' => 33, 'code' => '3319', 'name' => 'Kab. Kudus'],
            ['id' => 3320, 'province_id' => 33, 'code' => '3320', 'name' => 'Kab. Jepara'],
            ['id' => 3321, 'province_id' => 33, 'code' => '3321', 'name' => 'Kab. Demak'],
            ['id' => 3322, 'province_id' => 33, 'code' => '3322', 'name' => 'Kab. Semarang'],
            ['id' => 3323, 'province_id' => 33, 'code' => '3323', 'name' => 'Kab. Temanggung'],
            ['id' => 3324, 'province_id' => 33, 'code' => '3324', 'name' => 'Kab. Kendal'],
            ['id' => 3325, 'province_id' => 33, 'code' => '3325', 'name' => 'Kab. Batang'],
            ['id' => 3326, 'province_id' => 33, 'code' => '3326', 'name' => 'Kab. Pekalongan'],
            ['id' => 3327, 'province_id' => 33, 'code' => '3327', 'name' => 'Kab. Pemalang'],
            ['id' => 3328, 'province_id' => 33, 'code' => '3328', 'name' => 'Kab. Tegal'],
            ['id' => 3329, 'province_id' => 33, 'code' => '3329', 'name' => 'Kab. Brebes'],
            ['id' => 3371, 'province_id' => 33, 'code' => '3371', 'name' => 'Kota Magelang'],
            ['id' => 3372, 'province_id' => 33, 'code' => '3372', 'name' => 'Kota Surakarta'],
            ['id' => 3373, 'province_id' => 33, 'code' => '3373', 'name' => 'Kota Salatiga'],
            ['id' => 3374, 'province_id' => 33, 'code' => '3374', 'name' => 'Kota Semarang'],
            ['id' => 3375, 'province_id' => 33, 'code' => '3375', 'name' => 'Kota Pekalongan'],
            ['id' => 3376, 'province_id' => 33, 'code' => '3376', 'name' => 'Kota Tegal'],
        ];

        DB::table('regencies')->insert($regenciesJateng);

        // data regencies untuk jawa barat
        $regenciesJabar = [
            ['id' => 3201, 'province_id' => 32, 'code' => '3201', 'name' => 'Kab. Bogor'],
            ['id' => 3202, 'province_id' => 32, 'code' => '3202', 'name' => 'Kab. Sukabumi'],
            ['id' => 3203, 'province_id' => 32, 'code' => '3203', 'name' => 'Kab. Cianjur'],
            ['id' => 3204, 'province_id' => 32, 'code' => '3204', 'name' => 'Kab. Bandung'],
            ['id' => 3205, 'province_id' => 32, 'code' => '3205', 'name' => 'Kab. Garut'],
            ['id' => 3206, 'province_id' => 32, 'code' => '3206', 'name' => 'Kab. Tasikmalaya'],
            ['id' => 3207, 'province_id' => 32, 'code' => '3207', 'name' => 'Kab. Ciamis'],
            ['id' => 3208, 'province_id' => 32, 'code' => '3208', 'name' => 'Kab. Kuningan'],
            ['id' => 3209, 'province_id' => 32, 'code' => '3209', 'name' => 'Kab. Cirebon'],
            ['id' => 3210, 'province_id' => 32, 'code' => '3210', 'name' => 'Kab. Majalengka'],
            ['id' => 3211, 'province_id' => 32, 'code' => '3211', 'name' => 'Kab. Sumedang'],
            ['id' => 3212, 'province_id' => 32, 'code' => '3212', 'name' => 'Kab. Indramayu'],
            ['id' => 3213, 'province_id' => 32, 'code' => '3213', 'name' => 'Kab. Subang'],
            ['id' => 3214, 'province_id' => 32, 'code' => '3214', 'name' => 'Kab. Purwakarta'],
            ['id' => 3215, 'province_id' => 32, 'code' => '3215', 'name' => 'Kab. Karawang'],
            ['id' => 3216, 'province_id' => 32, 'code' => '3216', 'name' => 'Kab. Bekasi'],
            ['id' => 3217, 'province_id' => 32, 'code' => '3217', 'name' => 'Kab. Bandung Barat'],
            ['id' => 3218, 'province_id' => 32, 'code' => '3218', 'name' => 'Kab. Pangandaran'],
            ['id' => 3271, 'province_id' => 32, 'code' => '3271', 'name' => 'Kota Bogor'],
            ['id' => 3272, 'province_id' => 32, 'code' => '3272', 'name' => 'Kota Sukabumi'],
            ['id' => 3273, 'province_id' => 32, 'code' => '3273', 'name' => 'Kota Bandung'],
            ['id' => 3274, 'province_id' => 32, 'code' => '3274', 'name' => 'Kota Cirebon'],
            ['id' => 3275, 'province_id' => 32, 'code' => '3275', 'name' => 'Kota Bekasi'],
            ['id' => 3276, 'province_id' => 32, 'code' => '3276', 'name' => 'Kota Depok'],
            ['id' => 3277, 'province_id' => 32, 'code' => '3277', 'name' => 'Kota Cimahi'],
            ['id' => 3278, 'province_id' => 32, 'code' => '3278', 'name' => 'Kota Tasikmalaya'],
            ['id' => 3279, 'province_id' => 32, 'code' => '3279', 'name' => 'Kota Banjar'],
        ];

        DB::table('regencies')->insert($regenciesJabar);

        // data regencies untuk DKI Jakarta
        $regenciesJakarta = [
            ['id' => 3171, 'province_id' => 31, 'code' => '3171', 'name' => 'Kota Jakarta Selatan'],
            ['id' => 3172, 'province_id' => 31, 'code' => '3172', 'name' => 'Kota Jakarta Timur'],
            ['id' => 3173, 'province_id' => 31, 'code' => '3173', 'name' => 'Kota Jakarta Pusat'],
            ['id' => 3174, 'province_id' => 31, 'code' => '3174', 'name' => 'Kota Jakarta Barat'],
            ['id' => 3175, 'province_id' => 31, 'code' => '3175', 'name' => 'Kota Jakarta Utara'],
            ['id' => 3176, 'province_id' => 31, 'code' => '3176', 'name' => 'Kab. Kepulauan Seribu'],
        ];

        DB::table('regencies')->insert($regenciesJakarta);

        // data regencies untuk DI Yogyakarta
        $regenciesYogya = [
            ['id' => 3401, 'province_id' => 34, 'code' => '3401', 'name' => 'Kab. Kulon Progo'],
            ['id' => 3402, 'province_id' => 34, 'code' => '3402', 'name' => 'Kab. Bantul'],
            ['id' => 3403, 'province_id' => 34, 'code' => '3403', 'name' => 'Kab. Gunung Kidul'],
            ['id' => 3404, 'province_id' => 34, 'code' => '3404', 'name' => 'Kab. Sleman'],
            ['id' => 3471, 'province_id' => 34, 'code' => '3471', 'name' => 'Kota Yogyakarta'],
        ];

        DB::table('regencies')->insert($regenciesYogya);

        // data regencies untuk Banten
        $regenciesBanten = [
            ['id' => 3601, 'province_id' => 36, 'code' => '3601', 'name' => 'Kab. Pandeglang'],
            ['id' => 3602, 'province_id' => 36, 'code' => '3602', 'name' => 'Kab. Lebak'],
            ['id' => 3603, 'province_id' => 36, 'code' => '3603', 'name' => 'Kab. Tangerang'],
            ['id' => 3604, 'province_id' => 36, 'code' => '3604', 'name' => 'Kab. Serang'],
            ['id' => 3671, 'province_id' => 36, 'code' => '3671', 'name' => 'Kota Tangerang'],
            ['id' => 3672, 'province_id' => 36, 'code' => '3672', 'name' => 'Kota Cilegon'],
            ['id' => 3673, 'province_id' => 36, 'code' => '3673', 'name' => 'Kota Serang'],
            ['id' => 3674, 'province_id' => 36, 'code' => '3674', 'name' => 'Kota Tangerang Selatan'],
        ];

        DB::table('regencies')->insert($regenciesBanten);

        $this->command->info('provinces & regencies seeded successfully!');
        $this->command->info('total provinces: ' . count($provinces));
        $totalRegencies = count($regenciesJateng) + count($regenciesJabar) + count($regenciesJakarta) + count($regenciesYogya) + count($regenciesBanten);
        $this->command->info('total regencies: ' . $totalRegencies);
    }
}