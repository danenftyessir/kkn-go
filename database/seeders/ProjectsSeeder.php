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
 * seeder untuk membuat data dummy projects, milestones, reports, documents, dan reviews
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
        // PERBAIKAN: tambahkan eager loading untuk mencegah lazy loading violation
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

        foreach ($acceptedApplications as $application) {
            // buat project dari aplikasi yang diterima
            $problem = $application->problem;
            $startDate = Carbon::parse($problem->start_date);
            $endDate = Carbon::parse($problem->end_date);
            
            // tentukan status project (80% completed, 20% active)
            $isCompleted = rand(1, 100) <= 80;
            
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
                'final_report_path' => $isCompleted ? 'projects/final-reports/dummy-report-' . $application->id . '.pdf' : null,
                'final_report_summary' => $isCompleted ? 'Ringkasan laporan akhir untuk proyek ' . $problem->title : null,
                'submitted_at' => $isCompleted ? now()->subDays(rand(1, 30)) : null,
                'rating' => $isCompleted ? rand(4, 5) : null,
                'institution_review' => $isCompleted ? $this->getRandomReviewText() : null,
                'reviewed_at' => $isCompleted ? now()->subDays(rand(1, 15)) : null,
                'impact_metrics' => json_encode([
                    'beneficiaries' => rand(50, 500),
                    'activities' => rand(5, 30),
                ]),
                'is_portfolio_visible' => true,
                'is_featured' => rand(1, 100) <= 20, // 20% featured
            ]);

            // buat milestones
            $this->createMilestones($project);

            // buat reports (2-5 reports per project)
            $this->createReports($project, rand(2, 5));

            // jika project completed, buat review dan document
            if ($isCompleted) {
                $this->createReview($project);
                $this->createDocument($project);
            }
        }

        $this->command->info('✓ ' . Project::count() . ' projects berhasil dibuat!');
    }

    /**
     * buat milestones untuk project
     */
    protected function createMilestones(Project $project)
    {
        $duration = $project->start_date->diffInMonths($project->end_date);
        
        $milestones = [
            [
                'title' => 'Orientasi dan Persiapan',
                'description' => 'Pengenalan lokasi, koordinasi dengan instansi, dan persiapan program kerja',
                'order' => 1,
                'target_date' => $project->start_date->copy()->addWeeks(1),
                'progress_percentage' => 100,
                'status' => 'completed',
                'completed_at' => $project->start_date->copy()->addWeeks(1),
            ],
            [
                'title' => 'Pelaksanaan Program Utama',
                'description' => 'Implementasi kegiatan sesuai rencana kerja yang telah disusun',
                'order' => 2,
                'target_date' => $project->start_date->copy()->addMonths(floor($duration * 0.7)),
                'progress_percentage' => $project->status === 'completed' ? 100 : rand(50, 90),
                'status' => $project->status === 'completed' ? 'completed' : 'in_progress',
                'completed_at' => $project->status === 'completed' ? $project->start_date->copy()->addMonths(floor($duration * 0.7)) : null,
            ],
            [
                'title' => 'Evaluasi dan Pelaporan',
                'description' => 'Evaluasi hasil program dan penyusunan laporan akhir',
                'order' => 3,
                'target_date' => $project->end_date->copy()->subWeeks(1),
                'progress_percentage' => $project->status === 'completed' ? 100 : rand(0, 50),
                'status' => $project->status === 'completed' ? 'completed' : 'pending',
                'completed_at' => $project->status === 'completed' ? $project->end_date->copy()->subWeeks(1) : null,
            ],
        ];

        foreach ($milestones as $milestone) {
            ProjectMilestone::create(array_merge(['project_id' => $project->id], $milestone));
        }
    }

    /**
     * buat reports untuk project
     */
/**
     * buat reports untuk project
     */
    protected function createReports(Project $project, int $count)
    {
        for ($i = 0; $i < $count; $i++) {
            $type = ['weekly', 'monthly'][rand(0, 1)];
            $weekNumber = $i + 1;
            
            ProjectReport::create([
                'project_id' => $project->id,
                'student_id' => $project->student_id, // PERBAIKAN: tambahkan student_id
                'type' => $type,
                'title' => ($type === 'weekly' ? "Laporan Mingguan - Minggu ke-{$weekNumber}" : "Laporan Bulanan - Bulan ke-{$weekNumber}"),
                'summary' => "Ringkasan kegiatan {$type} untuk proyek {$project->title}. Pada periode ini telah dilakukan berbagai kegiatan sesuai rencana kerja.",
                'activities' => "Detail kegiatan yang dilakukan:\n1. Sosialisasi program kepada masyarakat\n2. Pelaksanaan kegiatan utama\n3. Dokumentasi dan monitoring progress\n4. Koordinasi dengan tim dan institusi",
                'challenges' => rand(1, 100) <= 50 ? "Kendala yang dihadapi: cuaca tidak mendukung dan keterbatasan sumber daya" : null,
                'next_plans' => "Rencana selanjutnya: melanjutkan kegiatan sesuai jadwal dan melakukan evaluasi berkala",
                'period_start' => $project->start_date->copy()->addWeeks($i * ($type === 'weekly' ? 1 : 4)),
                'period_end' => $project->start_date->copy()->addWeeks(($i + 1) * ($type === 'weekly' ? 1 : 4)),
                'document_path' => rand(1, 100) <= 70 ? 'reports/documents/dummy-report-' . $project->id . '-' . $i . '.pdf' : null,
                'photos' => json_encode(rand(1, 100) <= 60 ? ['reports/photos/photo-1.jpg', 'reports/photos/photo-2.jpg'] : []),
                'status' => ['pending', 'reviewed', 'approved'][rand(0, 2)],
                'institution_feedback' => rand(1, 100) <= 50 ? 'Laporan sudah baik, lanjutkan kegiatan dengan semangat!' : null,
                'reviewed_at' => rand(1, 100) <= 50 ? now()->subDays(rand(1, 7)) : null,
            ]);
        }
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
/**
     * buat document untuk knowledge repository
     */
    protected function createDocument(Project $project)
    {
        // load relasi yang dibutuhkan jika belum
        $project->loadMissing(['student', 'student.user', 'student.university', 'problem', 'institution']);
        
        $student = $project->student;
        $problem = $project->problem;
        
        Document::create([
            'project_id' => $project->id,
            'uploaded_by' => $student->user_id,
            'title' => 'Laporan Akhir: ' . $project->title,
            'description' => 'Dokumentasi lengkap hasil pelaksanaan program KKN dengan fokus pada ' . strtolower($problem->title),
            'file_path' => 'documents/final-reports/report-' . $project->id . '.pdf',
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