<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Support\Facades\Storage;

class DocumentsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     * membuat dokumen dummy untuk knowledge repository
     * 
     * jalankan: php artisan db:seed --class=DocumentsSeeder
     */
    public function run(): void
    {
        echo "🔄 Membuat direktori dan file dummy...\n";
        
        // pastikan direktori documents ada
        if (!Storage::exists('public/documents')) {
            Storage::makeDirectory('public/documents');
            echo "✅ Direktori documents dibuat\n";
        }

        // buat dummy PDF file untuk testing
        $pdfPath = $this->createDummyPDFFile();
        echo "✅ File PDF dummy dibuat: {$pdfPath}\n";

        // ambil user untuk uploader (ambil beberapa user random)
        $uploaders = User::where('user_type', 'student')->limit(5)->get();
        
        if ($uploaders->isEmpty()) {
            echo "⚠️  Warning: Tidak ada user student. Jalankan seeder user terlebih dahulu.\n";
            // buat minimal 1 user student untuk testing
            $user = User::create([
                'name' => 'Test Student',
                'email' => 'student@test.com',
                'username' => 'teststudent',
                'password' => bcrypt('password'),
                'user_type' => 'student',
                'email_verified_at' => now(),
            ]);
            
            $student = \App\Models\Student::create([
                'user_id' => $user->id,
                'university_id' => 1,
                'first_name' => 'Test',
                'last_name' => 'Student',
                'nim' => '1234567890',
                'major' => 'Teknik Informatika',
                'semester' => 6,
                'whatsapp_number' => '081234567890',
            ]);
            
            $uploaders = collect([$user]);
            echo "✅ User student dummy dibuat\n";
        }

        // ambil provinces dan regencies
        $provinces = Province::all();
        if ($provinces->isEmpty()) {
            echo "⚠️  Warning: Tidak ada data provinsi. Jalankan seeder provinsi terlebih dahulu.\n";
            return;
        }

        $projects = Project::all();

        // data dokumen dummy
        $documents = [
            [
                'title' => 'Laporan KKN Pengembangan UMKM di Desa Sukamaju',
                'description' => 'Dokumentasi lengkap program KKN dalam pengembangan usaha mikro kecil menengah di Desa Sukamaju, Kabupaten Bandung.',
                'categories' => ['decent_work', 'reduced_inequality'],
                'tags' => ['UMKM', 'Ekonomi', 'Desa'],
                'author_name' => 'Tim KKN Universitas Indonesia',
                'institution_name' => 'Desa Sukamaju',
                'university_name' => 'Universitas Indonesia',
                'year' => 2024,
            ],
            [
                'title' => 'Program Literasi Digital untuk Guru SD',
                'description' => 'Pelatihan literasi digital dan pemanfaatan teknologi dalam pembelajaran untuk guru sekolah dasar.',
                'categories' => ['quality_education', 'industry_innovation'],
                'tags' => ['Pendidikan', 'Digital', 'Guru'],
                'author_name' => 'Mahasiswa KKN ITB',
                'institution_name' => 'Dinas Pendidikan Kabupaten Bandung',
                'university_name' => 'Institut Teknologi Bandung',
                'year' => 2024,
            ],
            [
                'title' => 'Pengelolaan Sampah Berbasis Masyarakat',
                'description' => 'Implementasi sistem pengelolaan sampah terpadu dengan melibatkan partisipasi aktif masyarakat desa.',
                'categories' => ['sustainable_cities', 'responsible_consumption'],
                'tags' => ['Lingkungan', 'Sampah', 'Bank Sampah'],
                'author_name' => 'KKN Universitas Padjadjaran',
                'institution_name' => 'Desa Cibeunying',
                'university_name' => 'Universitas Padjadjaran',
                'year' => 2023,
            ],
            [
                'title' => 'Pemberdayaan Perempuan melalui Pelatihan Kerajinan Tangan',
                'description' => 'Program pelatihan kerajinan tangan untuk meningkatkan pendapatan ibu rumah tangga di pedesaan.',
                'categories' => ['gender_equality', 'decent_work'],
                'tags' => ['Pemberdayaan', 'Perempuan', 'Kerajinan'],
                'author_name' => 'Tim KKN UGM',
                'institution_name' => 'PKK Desa Sumberejo',
                'university_name' => 'Universitas Gadjah Mada',
                'year' => 2023,
            ],
            [
                'title' => 'Sosialisasi Hidup Sehat dan Pencegahan Stunting',
                'description' => 'Edukasi masyarakat tentang pola hidup sehat dan pencegahan stunting pada balita melalui posyandu.',
                'categories' => ['good_health', 'zero_hunger'],
                'tags' => ['Kesehatan', 'Stunting', 'Posyandu'],
                'author_name' => 'Mahasiswa KKN UNAIR',
                'institution_name' => 'Puskesmas Kecamatan Sukodono',
                'university_name' => 'Universitas Airlangga',
                'year' => 2024,
            ],
        ];

        echo "🔄 Membuat dokumen di database...\n";
        
        // buat dokumen
        foreach ($documents as $index => $docData) {
            // pilih uploader random
            $uploader = $uploaders->random();
            
            // pilih province dan regency random
            $province = $provinces->random();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            
            // pilih project random (jika ada)
            $project = $projects->isNotEmpty() ? $projects->random() : null;

            Document::create([
                'project_id' => $project?->id,
                'uploaded_by' => $uploader->id,
                'title' => $docData['title'],
                'description' => $docData['description'],
                'file_path' => $pdfPath, // gunakan path yang sama untuk semua
                'file_type' => 'pdf',
                'file_size' => Storage::size('public/' . $pdfPath), // ukuran file sebenarnya
                'categories' => json_encode($docData['categories']),
                'tags' => json_encode($docData['tags']),
                'author_name' => $docData['author_name'],
                'institution_name' => $docData['institution_name'],
                'university_name' => $docData['university_name'],
                'year' => $docData['year'],
                'province_id' => $province->id,
                'regency_id' => $regency?->id,
                'download_count' => rand(10, 500),
                'view_count' => rand(50, 1000),
                'citation_count' => rand(0, 50),
                'is_public' => true,
                'is_featured' => $index < 3, // 3 dokumen pertama jadi featured
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        echo "\n";
        echo "✅ Berhasil membuat " . count($documents) . " dokumen dummy\n";
        echo "✅ File PDF tersimpan di: storage/app/public/{$pdfPath}\n";
        echo "✅ Akses via browser: http://127.0.0.1:8000/storage/{$pdfPath}\n";
        echo "\n";
        echo "🎉 Seeder selesai! Silakan test download di: http://127.0.0.1:8000/student/repository\n";
    }

    /**
     * buat dummy PDF file untuk testing download
     * return path relatif dari storage/app/public
     */
    protected function createDummyPDFFile()
    {
        // path relatif dari storage/app/public
        $relativePath = 'documents/dummy-kkn-report.pdf';
        
        // konten PDF minimal yang valid
        $pdfContent = "%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj
2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj
3 0 obj
<<
/Type /Page
/Parent 2 0 R
/Resources <<
/Font <<
/F1 4 0 R
>>
>>
/MediaBox [0 0 612 792]
/Contents 5 0 R
>>
endobj
4 0 obj
<<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
>>
endobj
5 0 obj
<<
/Length 200
>>
stream
BT
/F1 24 Tf
50 700 Td
(LAPORAN KKN - DOKUMEN DUMMY) Tj
0 -30 Td
/F1 14 Tf
(Universitas XYZ) Tj
0 -25 Td
(Tahun 2024) Tj
0 -40 Td
/F1 12 Tf
(Ini adalah dokumen PDF dummy untuk testing) Tj
0 -20 Td
(sistem download di platform KKN-GO.) Tj
ET
endstream
endobj
xref
0 6
0000000000 65535 f
0000000009 00000 n
0000000058 00000 n
0000000115 00000 n
0000000274 00000 n
0000000361 00000 n
trailer
<<
/Size 6
/Root 1 0 R
>>
startxref
612
%%EOF";

        // simpan file
        Storage::put('public/' . $relativePath, $pdfContent);
        
        return $relativePath;
    }
}