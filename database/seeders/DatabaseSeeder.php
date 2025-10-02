<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * database seeder utama
 * menjalankan semua seeder dalam urutan yang benar
 * 
 * jalankan: php artisan db:seed
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

        // jalankan seeder dalam urutan yang benar
        $this->call([
            DummyDataSeeder::class,      // provinces, regencies, universities, students, institutions
            ProblemsSeeder::class,        // problems data
            ApplicationsSeeder::class,    // applications data
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