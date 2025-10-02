<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Student;
use App\Models\Problem;
use Carbon\Carbon;

/**
 * seeder untuk data dummy applications
 * berisi berbagai status aplikasi untuk testing
 * 
 * jalankan: php artisan db:seed --class=ApplicationsSeeder
 * atau: php artisan migrate:fresh --seed
 */
class ApplicationsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        // ambil semua students dan problems yang tersedia
        $students = Student::all();
        $problems = Problem::where('status', 'open')->get();

        if ($students->isEmpty() || $problems->isEmpty()) {
            $this->command->warn('Tidak ada students atau problems. Jalankan DummyDataSeeder terlebih dahulu.');
            return;
        }

        // data motivasi untuk variasi
        $motivations = [
            'Saya sangat tertarik dengan proyek ini karena sesuai dengan bidang studi saya dan ingin berkontribusi untuk masyarakat. Saya memiliki pengalaman dalam riset dan analisis data yang dapat membantu proyek ini.',
            'Proyek ini sangat menarik dan sesuai dengan passion saya di bidang pemberdayaan masyarakat. Saya berkomitmen untuk memberikan yang terbaik selama program berlangsung.',
            'Saya tertarik untuk mengaplikasikan ilmu yang telah saya pelajari di kampus untuk membantu menyelesaikan masalah nyata di masyarakat. Saya siap belajar dan berkontribusi maksimal.',
            'Sebagai mahasiswa yang peduli dengan sustainable development, saya ingin berkontribusi dalam proyek ini untuk membawa dampak positif bagi masyarakat setempat.',
            'Saya memiliki keterampilan yang relevan dan pengalaman organisasi yang dapat membantu kesuksesan proyek ini. Saya sangat antusias untuk bergabung dalam tim.',
        ];

        $coverLetters = [
            'Dengan hormat, saya mengajukan diri untuk bergabung dalam proyek ini. Saya yakin dengan latar belakang pendidikan dan pengalaman saya, saya dapat berkontribusi positif dalam mencapai tujuan proyek.',
            'Kepada Yth. Tim Seleksi, saya tertarik untuk berpartisipasi dalam proyek ini karena visi dan misi yang sejalan dengan nilai-nilai yang saya anut. Saya berharap dapat diberikan kesempatan untuk berkontribusi.',
            'Saya mengajukan aplikasi ini dengan penuh antusias. Proyek ini memberikan kesempatan luar biasa untuk mengembangkan kompetensi sekaligus memberikan manfaat nyata bagi masyarakat.',
            'Melalui surat ini, saya ingin menyampaikan ketertarikan saya untuk terlibat dalam proyek yang Anda tawarkan. Saya percaya bahwa pengalaman ini akan sangat berharga bagi pengembangan diri saya.',
        ];

        $feedbacks = [
            'Aplikasi Anda menunjukkan motivasi yang kuat dan kesesuaian skill dengan kebutuhan proyek. Kami sangat senang menerima Anda dalam tim kami.',
            'Terima kasih atas aplikasi Anda. Setelah melalui proses review, kami memutuskan untuk memilih kandidat lain yang lebih sesuai dengan kebutuhan proyek saat ini.',
            'Profil Anda sangat menarik, namun sayangnya kuota untuk proyek ini sudah terpenuhi. Kami mengapresiasi ketertarikan Anda dan berharap dapat bekerja sama di kesempatan lain.',
            'Selamat! Aplikasi Anda diterima. Kami terkesan dengan pengalaman dan antusiasme Anda. Silakan tunggu informasi lebih lanjut mengenai briefing proyek.',
        ];

        // counter untuk tracking
        $totalCreated = 0;

        // buat applications dengan berbagai status untuk setiap student
        foreach ($students as $student) {
            // random 2-5 aplikasi per student
            $numApplications = rand(2, 5);
            
            // ambil random problems (pastikan tidak duplicate)
            $selectedProblems = $problems->random(min($numApplications, $problems->count()));
            
            foreach ($selectedProblems as $problem) {
                // random status dengan distribusi realistis
                $rand = rand(1, 100);
                if ($rand <= 30) {
                    $status = 'pending'; // 30% pending
                } elseif ($rand <= 50) {
                    $status = 'reviewed'; // 20% reviewed
                } elseif ($rand <= 75) {
                    $status = 'accepted'; // 25% accepted
                } else {
                    $status = 'rejected'; // 25% rejected
                }

                // tentukan timestamps berdasarkan status
                $appliedAt = Carbon::now()->subDays(rand(1, 30));
                $reviewedAt = null;
                $acceptedAt = null;
                $rejectedAt = null;
                $feedback = null;

                if ($status === 'reviewed') {
                    $reviewedAt = $appliedAt->copy()->addDays(rand(1, 5));
                } elseif ($status === 'accepted') {
                    $reviewedAt = $appliedAt->copy()->addDays(rand(1, 5));
                    $acceptedAt = $reviewedAt->copy()->addDays(rand(1, 3));
                    $feedback = $feedbacks[array_rand(array_filter($feedbacks, fn($f) => str_contains($f, 'Selamat') || str_contains($f, 'diterima')))];
                } elseif ($status === 'rejected') {
                    $reviewedAt = $appliedAt->copy()->addDays(rand(1, 5));
                    $rejectedAt = $reviewedAt->copy()->addDays(rand(1, 3));
                    $feedback = $feedbacks[array_rand(array_filter($feedbacks, fn($f) => str_contains($f, 'kandidat lain') || str_contains($f, 'kuota')))];
                }

                try {
                    Application::create([
                        'student_id' => $student->id,
                        'problem_id' => $problem->id,
                        'status' => $status,
                        'cover_letter' => $coverLetters[array_rand($coverLetters)],
                        'motivation' => $motivations[array_rand($motivations)],
                        'applied_at' => $appliedAt,
                        'reviewed_at' => $reviewedAt,
                        'accepted_at' => $acceptedAt,
                        'rejected_at' => $rejectedAt,
                        'feedback' => $feedback,
                    ]);

                    $totalCreated++;

                    // update applications_count di problem
                    $problem->increment('applications_count');
                    
                    // update accepted_students jika status accepted
                    if ($status === 'accepted') {
                        $problem->increment('accepted_students');
                    }
                } catch (\Exception $e) {
                    // skip jika duplicate (unique constraint)
                    $this->command->warn("Skipped duplicate application: Student {$student->id} - Problem {$problem->id}");
                }
            }
        }

        $this->command->info("✓ {$totalCreated} applications berhasil dibuat!");
        $this->command->info('Status distribusi:');
        $this->command->info('  - Pending: ' . Application::where('status', 'pending')->count());
        $this->command->info('  - Reviewed: ' . Application::where('status', 'reviewed')->count());
        $this->command->info('  - Accepted: ' . Application::where('status', 'accepted')->count());
        $this->command->info('  - Rejected: ' . Application::where('status', 'rejected')->count());
    }
}