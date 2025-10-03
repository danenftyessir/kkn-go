<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * database seeder utama
 * menjalankan semua seeder dalam urutan yang benar
 * * jalankan: php artisan db:seed
 * atau: php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * seed database aplikasi
     */
    public function run(): void
    {
        $this->command->info('==========================================');
        $this->command->info('   SEEDING DATABASE KKN-GO');
        $this->command->info('==========================================');
        $this->command->newLine();

        // PERBAIKAN: Memanggil seeder dalam urutan yang benar dan logis.
        // 1. Panggil seeder untuk data master (provinsi & kabupaten) terlebih dahulu.
        //    Ini memastikan data lokasi tersedia sebelum data lain yang bergantung padanya dibuat.
        $this->call([
            ProvincesRegenciesSeeder::class, // Menggunakan seeder yang lebih andal dengan ID statis
        ]);

        // 2. Panggil seeder lain yang bergantung pada data master.
        //    DummyDataSeeder akan mengisi data universitas, mahasiswa, dan instansi.
        //    ProblemsSeeder akan mengisi data proyek.
        //    ApplicationsSeeder akan mengisi data pendaftaran mahasiswa ke proyek.
        $this->call([
            DummyDataSeeder::class,      
            ProblemsSeeder::class,       
            ApplicationsSeeder::class,    
            ProjectsSeeder::class,
        ]);


        $this->command->newLine();
        $this->command->info('==========================================');
        $this->command->info('   SEEDING SELESAI!');
        $this->command->info('==========================================');
        $this->command->newLine();
        
        // tampilkan statistik
        $this->showStatistics();
    }

    /**
     * tampilkan statistik data yang telah di-seed
     */
    private function showStatistics(): void
    {
        $stats = [
            'Users' => \App\Models\User::count(),
            'Students' => \App\Models\Student::count(),
            'Institutions' => \App\Models\Institution::count(),
            'Provinces' => \App\Models\Province::count(),
            'Regencies' => \App\Models\Regency::count(),
            'Universities' => \App\Models\University::count(),
            'Problems' => \App\Models\Problem::count(),
            'Applications' => \App\Models\Application::count(),
        ];

        $this->command->table(
            ['Entity', 'Count'],
            collect($stats)->map(fn($count, $name) => [$name, $count])->values()
        );

        $this->command->newLine();
        $this->command->info('🔑 Default Login Credentials:');
        $this->command->line('   Student: budi.santoso@ui.ac.id / password123');
        $this->command->line('   Institution: desa.sukamaju@example.com / password123');
        $this->command->newLine();
    }
}