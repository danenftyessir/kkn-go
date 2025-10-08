<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectReport;
use App\Models\Document;
use App\Models\Review;
use App\Models\Application;
use App\Models\Student;
use Carbon\Carbon;

/**
 * seeder untuk membuat data projects, milestones, reports, documents, dan reviews
 * 
 * FIXED: tidak pakai Storage::disk()->files() lagi, pakai hardcode list
 * 
 * path: database/seeders/ProjectsSeeder.php
 * jalankan: php artisan db:seed --class=ProjectsSeeder
 */
class ProjectsSeeder extends Seeder
{
    /**
     * run seeder
     */
    public function run(): void
    {
        // ambil aplikasi yang diterima dengan eager loading
        $acceptedApplications = Application::with([
            'problem',
            'problem.institution',
            'problem.institution.user',
            'student',
            'student.user',
            'student.university'
        ])
        ->where('status', 'accepted')
        ->get();

        if ($acceptedApplications->isEmpty()) {
            $this->command->warn('Tidak ada aplikasi dengan status accepted. Jalankan ApplicationsSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat data projects...');

        // hardcode list file PDF yang ada di supabase (sama seperti DocumentsSeeder)
        // FIXED: tidak pakai Storage::disk()->files() yang menyebabkan error S3 endpoint
        $pdfFiles = [
            'documents/reports/1.FORMAT-DAN-CONTOH-LAPORAN-INDIVIDU-KKN.pdf',
            'documents/reports/3341b-laporan_kkn_hasbi_mudzaki_fix-1-.pdf',
            'documents/reports/bc4f599c360deae829ef0952f9200a4f.pdf',
            'documents/reports/d5460592f2ee74a2f9f5910138d650e6.pdf',
            'documents/reports/download_252705030541_laporan-panitia-kegiatan-kknpmm-reguler-periode-i-tahun-2025.pdf',
            'documents/reports/f3f3ec539ee2d963e804d3a964b3290f.pdf',
            'documents/reports/KKN_III.D.3_REG.96_2022.pdf',
            'documents/reports/LAPORAN AKHIR KKN .pdf',
            'documents/reports/laporan akhir KKN PPM OK.pdf',
            'documents/reports/LAPORAN KKN DEMAPESA.pdf',
            'documents/reports/LAPORAN KKN KELOMPOK 2250.pdf',
            'documents/reports/LAPORAN KKN Kelompok 5 fakultas teknik.pdf',
            'documents/reports/LAPORAN KKN_1.A.2_REG.119_2024.pdf',
            'documents/reports/LAPORAN KKN.pdf',
            'documents/reports/laporan_3460160906115724.pdf',
            'documents/reports/laporan_akhir_201_35_2.pdf',
            'documents/reports/laporan_akhir_3011_45_5.pdf',
            'documents/reports/Laporan-Akademik-KKN-Persemakmuran-2022.pdf',
            'documents/reports/laporan-kelompok.pdf',
            'documents/reports/Laporan-Tugas-Akhir-KKN-156.pdf',
            'documents/reports/Partisipasi-Berbasis-Komunitas-Dalam-Rangka-Percepatan-Penurunan-Stunting.pdf',
            'documents/reports/Peraturan_Akademik_UNP.pdf',
            'documents/reports/Laporan-KKN-2019.pdf',
            'documents/reports/Stimulasi-Masyarakat-Desa-Tiyohu-berbasis-Ekonomi-dan-Pengetahuan-Hukum-di-Kabupaten-Gorontalo.pdf',
        ];

        if (!empty($pdfFiles)) {
            $this->command->info('📄 Menggunakan ' . count($pdfFiles) . ' file PDF untuk final reports');
        }

        $pdfIndex = 0;

        foreach ($acceptedApplications as $application) {
            // buat project dari aplikasi yang diterima
            $problem = $application->problem;
            $startDate = Carbon::parse($problem->start_date);
            $endDate = Carbon::parse($problem->end_date);
            
            // tentukan status project (80% completed, 20% active)
            $isCompleted = rand(1, 100) <= 80;
            
            // tentukan final report path (gunakan file real dari supabase jika ada)
            $finalReportPath = null;
            if ($isCompleted && !empty($pdfFiles)) {
                // cycle through available PDF files
                if ($pdfIndex >= count($pdfFiles)) {
                    $pdfIndex = 0;
                }
                $finalReportPath = $pdfFiles[$pdfIndex];
                $pdfIndex++;
            }
            
            $project = Project::create([
                'application_id' => $application->id,
                'student_id' => $application->student_id,
                'problem_id' => $application->problem_id,
                'institution_id' => $problem->institution_id,
                'title' => $problem->title,
                'description' => $problem->description,
                'status' => $isCompleted ? 'completed' : 'active',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'actual_start_date' => $startDate->copy()->addDays(rand(0, 7)),
                'actual_end_date' => $isCompleted ? $endDate->copy()->subDays(rand(0, 7)) : null,
                'progress_percentage' => $isCompleted ? 100 : rand(30, 90),
                'final_report_path' => $finalReportPath,
                'final_report_summary' => $isCompleted ? 'Ringkasan laporan akhir untuk proyek ' . $problem->title : null,
                'submitted_at' => $isCompleted ? now()->subDays(rand(1, 30)) : null,
                'rating' => $isCompleted ? rand(4, 5) : null,
                'institution_review' => $isCompleted ? $this->getRandomReviewText() : null,
                'reviewed_at' => $isCompleted ? now()->subDays(rand(1, 15)) : null,
                'role_in_team' => $this->getRandomRole(),
            ]);

            // buat milestones untuk project
            $this->createMilestones($project);

            // buat progress reports
            $this->createProgressReports($project);

            // jika completed, buat review dan document
            if ($isCompleted) {
                $this->createReview($project);
                $this->createDocument($project);
            }
        }

        $this->command->info('✅ Berhasil membuat ' . $acceptedApplications->count() . ' projects');
    }

    /**
     * dapatkan random role in team
     */
    protected function getRandomRole(): string
    {
        $roles = [
            'Project Leader',
            'Field Coordinator',
            'Documentation Specialist',
            'Community Liaison',
            'Technical Advisor',
            'Research Analyst',
            'Team Member',
        ];

        return $roles[array_rand($roles)];
    }

    /**
     * buat milestones untuk project
     */
    protected function createMilestones(Project $project)
    {
        $milestones = [
            [
                'title' => 'Survei dan Pemetaan Masalah',
                'description' => 'Melakukan survei lapangan dan identifikasi masalah utama',
                'target_date' => $project->start_date->copy()->addWeeks(1),
                'status' => 'completed',
            ],
            [
                'title' => 'Perencanaan Program',
                'description' => 'Menyusun rencana program dan strategi pelaksanaan',
                'target_date' => $project->start_date->copy()->addWeeks(2),
                'status' => $project->progress_percentage >= 40 ? 'completed' : 'in_progress',
            ],
            [
                'title' => 'Pelaksanaan Kegiatan Utama',
                'description' => 'Implementasi program sesuai rencana yang telah disusun',
                'target_date' => $project->start_date->copy()->addWeeks(6),
                'status' => $project->progress_percentage >= 70 ? 'completed' : 'pending',
            ],
            [
                'title' => 'Evaluasi dan Dokumentasi',
                'description' => 'Evaluasi hasil kegiatan dan penyusunan laporan akhir',
                'target_date' => $project->end_date,
                'status' => $project->status === 'completed' ? 'completed' : 'pending',
            ],
        ];

        foreach ($milestones as $milestone) {
            ProjectMilestone::create([
                'project_id' => $project->id,
                'title' => $milestone['title'],
                'description' => $milestone['description'],
                'target_date' => $milestone['target_date'],
                'status' => $milestone['status'],
                'completed_at' => $milestone['status'] === 'completed' 
                    ? $milestone['target_date']->copy()->subDays(rand(0, 3)) 
                    : null,
            ]);
        }
    }

    /**
     * buat progress reports untuk project
     */
    protected function createProgressReports(Project $project)
    {
        $reportCount = rand(2, 4);
        
        for ($i = 0; $i < $reportCount; $i++) {
            // tentukan tipe report
            $type = $i === 0 ? 'weekly' : ($i === $reportCount - 1 ? 'final' : 'monthly');
            
            ProjectReport::create([
                'project_id' => $project->id,
                'student_id' => $project->student_id,
                'type' => $type,
                'title' => $this->getReportTitle($type, $i + 1),
                'summary' => 'Ringkasan kegiatan minggu/bulan ini untuk project ' . $project->title,
                'activities' => 'Kegiatan yang telah dilaksanakan: sosialisasi program, koordinasi dengan masyarakat, dan pelaksanaan kegiatan lapangan.',
                'challenges' => rand(1, 100) <= 70 ? 'Cuaca yang kurang mendukung dan keterbatasan akses transportasi.' : null,
                'next_plans' => 'Melanjutkan kegiatan sesuai rencana dan melakukan evaluasi berkala.',
                'period_start' => $project->start_date->copy()->addWeeks($i * ($type === 'weekly' ? 1 : 4)),
                'period_end' => $project->start_date->copy()->addWeeks(($i + 1) * ($type === 'weekly' ? 1 : 4)),
                'document_path' => null,
                'photos' => json_encode([]),
                'status' => ['pending', 'reviewed', 'approved'][rand(0, 2)],
                'institution_feedback' => rand(1, 100) <= 50 ? 'Laporan sudah baik, lanjutkan kegiatan dengan semangat!' : null,
                'reviewed_at' => rand(1, 100) <= 50 ? now()->subDays(rand(1, 7)) : null,
            ]);
        }
    }

    /**
     * dapatkan judul report berdasarkan tipe
     */
    protected function getReportTitle(string $type, int $number): string
    {
        $titles = [
            'weekly' => "Laporan Mingguan #{$number}",
            'monthly' => "Laporan Bulanan #{$number}",
            'final' => 'Laporan Akhir Pelaksanaan Program',
        ];

        return $titles[$type] ?? "Laporan #{$number}";
    }

    /**
     * buat review untuk completed project
     */
    protected function createReview(Project $project)
    {
        $rating = rand(4, 5);
        
        // load relasi yang dibutuhkan jika belum
        $project->loadMissing(['institution.user', 'student.user']);
        
        Review::create([
            'project_id' => $project->id,
            'reviewer_id' => $project->institution->user_id,
            'reviewee_id' => $project->student->user_id,
            'type' => 'institution_to_student',
            'rating' => $rating,
            'professionalism_rating' => rand(4, 5),
            'communication_rating' => rand(4, 5),
            'quality_rating' => rand(4, 5),
            'timeliness_rating' => rand(3, 5),
            'review_text' => $this->getRandomReviewText(),
            'strengths' => 'Mahasiswa menunjukkan dedikasi tinggi, komunikasi baik, dan hasil kerja memuaskan.',
            'improvements' => rand(1, 100) <= 50 ? 'Bisa lebih proaktif dalam mengatasi kendala lapangan.' : null,
            'is_public' => true,
            'is_featured' => rand(1, 100) <= 15,
        ]);
    }

    /**
     * buat document untuk knowledge repository
     */
    protected function createDocument(Project $project)
    {
        // load relasi yang dibutuhkan jika belum
        $project->loadMissing(['student', 'student.user', 'student.university', 'problem', 'institution']);
        
        $student = $project->student;
        $problem = $project->problem;
        
        // hanya buat document jika project punya final report path
        if (empty($project->final_report_path)) {
            return;
        }
        
        Document::create([
            'project_id' => $project->id,
            'uploaded_by' => $student->user_id,
            'title' => 'Laporan Akhir: ' . $project->title,
            'description' => 'Dokumentasi lengkap hasil pelaksanaan program KKN dengan fokus pada ' . strtolower($problem->title),
            'file_path' => $project->final_report_path, // gunakan path real dari supabase
            'file_type' => 'pdf',
            'file_size' => rand(1000000, 5000000), // 1-5 MB
            'categories' => $problem->sdg_categories,
            'tags' => json_encode(['KKN', 'Community Service', 'Final Report']),
            'author_name' => $student->user->name,
            'institution_name' => $project->institution->name,
            'university_name' => $student->university->name,
            'year' => now()->year,
            'province_id' => $problem->province_id,
            'regency_id' => $problem->regency_id,
            'download_count' => rand(10, 100),
            'view_count' => rand(50, 500),
            'citation_count' => rand(0, 20),
            'is_public' => true,
            'is_featured' => rand(1, 100) <= 10,
            'status' => 'approved',
            'approved_at' => now()->subDays(rand(1, 30)),
        ]);
    }

    /**
     * generate random review text
     */
    protected function getRandomReviewText(): string
    {
        $reviews = [
            'Mahasiswa menunjukkan kinerja yang sangat baik selama program berlangsung. Komunikasi lancar dan hasil kerja memuaskan.',
            'Pelaksanaan program berjalan dengan baik. Mahasiswa proaktif dan mampu beradaptasi dengan kondisi lapangan.',
            'Sangat terkesan dengan dedikasi dan profesionalisme mahasiswa. Program berjalan sesuai rencana dan memberikan dampak positif.',
            'Mahasiswa memiliki inisiatif tinggi dan mampu menyelesaikan tantangan dengan baik. Hasil program sangat memuaskan.',
            'Kerja sama yang baik, komunikasi efektif, dan hasil program yang berkualitas. Sangat merekomendasikan mahasiswa ini.',
        ];

        return $reviews[array_rand($reviews)];
    }
}