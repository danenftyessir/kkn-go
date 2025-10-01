<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * seed database dengan data testing
     */
    public function run(): void
    {
        // ===================================
        // DATA TESTING MAHASISWA
        // ===================================
        
        // mahasiswa 1 - budi santoso
        $studentUser1 = User::create([
            'email' => 'budi.santoso@mail.ugm.ac.id',
            'username' => 'budisantoso',
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Student::create([
            'user_id' => $studentUser1->id,
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'university_id' => 1, // TODO: sesuaikan dengan id universitas
            'major' => 'Teknik Informatika',
            'nim' => '21/234567/TK/12345',
            'semester' => 6,
            'whatsapp_number' => '+6281234567890',
            'profile_photo_url' => null,
            'portfolio_visible' => true,
            'show_email' => true,
            'show_phone' => false
        ]);

        // mahasiswa 2 - siti nurhaliza
        $studentUser2 = User::create([
            'email' => 'siti.nurhaliza@ui.ac.id',
            'username' => 'sitinur',
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Student::create([
            'user_id' => $studentUser2->id,
            'first_name' => 'Siti',
            'last_name' => 'Nurhaliza',
            'university_id' => 2, // TODO: sesuaikan dengan id universitas
            'major' => 'Kesehatan Masyarakat',
            'nim' => '2006123456',
            'semester' => 8,
            'whatsapp_number' => '+6281298765432',
            'profile_photo_url' => null,
            'portfolio_visible' => true,
            'show_email' => true,
            'show_phone' => true
        ]);

        // mahasiswa 3 - ahmad rizki
        $studentUser3 = User::create([
            'email' => 'ahmad.rizki@student.its.ac.id',
            'username' => 'ahmadrizki',
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Student::create([
            'user_id' => $studentUser3->id,
            'first_name' => 'Ahmad',
            'last_name' => 'Rizki',
            'university_id' => 3, // TODO: sesuaikan dengan id universitas
            'major' => 'Sistem Informasi',
            'nim' => '05111940000001',
            'semester' => 4,
            'whatsapp_number' => '+6285612345678',
            'profile_photo_url' => null,
            'portfolio_visible' => false,
            'show_email' => false,
            'show_phone' => false
        ]);

        // ===================================
        // DATA TESTING INSTANSI
        // ===================================
        
        // instansi 1 - pemerintah desa makmur
        $institutionUser1 = User::create([
            'email' => 'admin@desamakmur.go.id',
            'username' => 'desamakmur',
            'password' => Hash::make('password123'),
            'user_type' => 'institution',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Institution::create([
            'user_id' => $institutionUser1->id,
            'institution_name' => 'Pemerintah Desa Makmur',
            'institution_type' => 'pemerintah_desa',
            'address' => 'Jl. Raya Desa No. 123, Makmur, Sleman',
            'province_id' => 1, // TODO: sesuaikan dengan id provinsi
            'regency_id' => 1, // TODO: sesuaikan dengan id kabupaten
            'pic_name' => 'Bapak Suharto',
            'pic_position' => 'Kepala Desa',
            'phone_number' => '+6281234567890',
            'website' => 'https://desamakmur.go.id',
            'description' => 'Pemerintah Desa Makmur berkomitmen untuk meningkatkan kesejahteraan masyarakat melalui berbagai program pemberdayaan.',
            'logo_url' => null,
            'verification_document_url' => null,
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => null
        ]);

        // instansi 2 - dinas kesehatan kota yogyakarta
        $institutionUser2 = User::create([
            'email' => 'info@dinkes-jogja.go.id',
            'username' => 'dinkesjogja',
            'password' => Hash::make('password123'),
            'user_type' => 'institution',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Institution::create([
            'user_id' => $institutionUser2->id,
            'institution_name' => 'Dinas Kesehatan Kota Yogyakarta',
            'institution_type' => 'dinas',
            'address' => 'Jl. Kenari No. 56, Yogyakarta',
            'province_id' => 1, // TODO: sesuaikan dengan id provinsi
            'regency_id' => 2, // TODO: sesuaikan dengan id kabupaten
            'pic_name' => 'dr. Siti Rahayu, M.Kes',
            'pic_position' => 'Kepala Dinas',
            'phone_number' => '+6274123456',
            'website' => 'https://dinkes.jogjakota.go.id',
            'description' => 'Dinas Kesehatan Kota Yogyakarta bertanggung jawab terhadap pembangunan kesehatan di wilayah Kota Yogyakarta.',
            'logo_url' => null,
            'verification_document_url' => null,
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => null
        ]);

        // instansi 3 - yayasan peduli lingkungan
        $institutionUser3 = User::create([
            'email' => 'contact@pedulilingkungan.org',
            'username' => 'pedulilingkungan',
            'password' => Hash::make('password123'),
            'user_type' => 'institution',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Institution::create([
            'user_id' => $institutionUser3->id,
            'institution_name' => 'Yayasan Peduli Lingkungan Indonesia',
            'institution_type' => 'ngo',
            'address' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
            'province_id' => 2, // TODO: sesuaikan dengan id provinsi
            'regency_id' => 3, // TODO: sesuaikan dengan id kabupaten
            'pic_name' => 'Ibu Dewi Kusuma',
            'pic_position' => 'Direktur Eksekutif',
            'phone_number' => '+62217654321',
            'website' => 'https://pedulilingkungan.org',
            'description' => 'Yayasan yang fokus pada pelestarian lingkungan dan pemberdayaan masyarakat dalam pengelolaan sumber daya alam.',
            'logo_url' => null,
            'verification_document_url' => null,
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => null
        ]);

        // instansi 4 - puskesmas sehat sejahtera (belum diverifikasi)
        $institutionUser4 = User::create([
            'email' => 'admin@puskesmassehat.id',
            'username' => 'puskesmassehat',
            'password' => Hash::make('password123'),
            'user_type' => 'institution',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        Institution::create([
            'user_id' => $institutionUser4->id,
            'institution_name' => 'Puskesmas Sehat Sejahtera',
            'institution_type' => 'puskesmas',
            'address' => 'Jl. Kesehatan No. 45, Bandung',
            'province_id' => 3, // TODO: sesuaikan dengan id provinsi
            'regency_id' => 4, // TODO: sesuaikan dengan id kabupaten
            'pic_name' => 'dr. Bambang Wijaya',
            'pic_position' => 'Kepala Puskesmas',
            'phone_number' => '+62227890123',
            'website' => null,
            'description' => 'Puskesmas yang melayani kesehatan masyarakat di wilayah Bandung Timur.',
            'logo_url' => null,
            'verification_document_url' => null,
            'is_verified' => false, // belum diverifikasi
            'verified_at' => null,
            'verified_by' => null
        ]);

        // ===================================
        // DATA TESTING ADMIN
        // ===================================
        
        $adminUser = User::create([
            'email' => 'admin@kkngo.id',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        // output informasi
        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('DATA TESTING BERHASIL DI-SEED!');
        $this->command->info('==============================================');
        $this->command->info('');
        $this->command->info('AKUN MAHASISWA:');
        $this->command->info('----------------------------------------------');
        $this->command->info('1. username: budisantoso');
        $this->command->info('   email: budi.santoso@mail.ugm.ac.id');
        $this->command->info('   password: password123');
        $this->command->info('   role: student (mahasiswa)');
        $this->command->info('');
        $this->command->info('2. username: sitinur');
        $this->command->info('   email: siti.nurhaliza@ui.ac.id');
        $this->command->info('   password: password123');
        $this->command->info('   role: student (mahasiswa)');
        $this->command->info('');
        $this->command->info('3. username: ahmadrizki');
        $this->command->info('   email: ahmad.rizki@student.its.ac.id');
        $this->command->info('   password: password123');
        $this->command->info('   role: student (mahasiswa)');
        $this->command->info('');
        $this->command->info('AKUN INSTANSI:');
        $this->command->info('----------------------------------------------');
        $this->command->info('1. username: desamakmur');
        $this->command->info('   email: admin@desamakmur.go.id');
        $this->command->info('   password: password123');
        $this->command->info('   role: institution (terverifikasi)');
        $this->command->info('');
        $this->command->info('2. username: dinkesjogja');
        $this->command->info('   email: info@dinkes-jogja.go.id');
        $this->command->info('   password: password123');
        $this->command->info('   role: institution (terverifikasi)');
        $this->command->info('');
        $this->command->info('3. username: pedulilingkungan');
        $this->command->info('   email: contact@pedulilingkungan.org');
        $this->command->info('   password: password123');
        $this->command->info('   role: institution (terverifikasi)');
        $this->command->info('');
        $this->command->info('4. username: puskesmassehat');
        $this->command->info('   email: admin@puskesmassehat.id');
        $this->command->info('   password: password123');
        $this->command->info('   role: institution (belum diverifikasi)');
        $this->command->info('');
        $this->command->info('AKUN ADMIN:');
        $this->command->info('----------------------------------------------');
        $this->command->info('username: admin');
        $this->command->info('email: admin@kkngo.id');
        $this->command->info('password: admin123');
        $this->command->info('role: admin');
        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('CATATAN:');
        $this->command->info('- semua akun sudah terverifikasi email');
        $this->command->info('- instansi 1-3 sudah diverifikasi admin');
        $this->command->info('- instansi 4 belum diverifikasi admin');
        $this->command->info('- untuk login bisa gunakan email atau username');
        $this->command->info('==============================================');
    }
}