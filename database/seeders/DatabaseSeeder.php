<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * seed database aplikasi
     */
    public function run(): void
    {
        // panggil seeder lainnya
        $this->call([
            DummyDataSeeder::class,
        ]);
    }
}