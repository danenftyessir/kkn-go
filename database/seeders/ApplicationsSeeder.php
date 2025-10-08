<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Student;
use App\Models\Problem;
use Carbon\Carbon;

/**
 * seeder untuk data dummy applications
 * UPDATED: meningkatkan jumlah accepted applications untuk menghasilkan 25+ projects
 * 
 * path: database/seeders/ApplicationsSeeder.php
 * jalankan: php artisan db:seed --class=ApplicationsSeeder
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

        $this->command->info('🎯 Target: minimal 25-30 accepted applications untuk projects');
        $this->command->info("📊 Available: {$students->count()} students, {$problems->count()} problems");

        // data motivasi untuk variasi
        $motivations = [
            'Saya sangat tertarik dengan proyek ini karena sesuai dengan bidang studi saya dan ingin berkontribusi untuk masyarakat. Saya memiliki pengalaman dalam riset dan analisis data yang dapat membantu proyek ini.',
            'Proyek ini sangat menarik dan sesuai dengan passion saya di bidang pemberdayaan masyarakat. Saya berkomitmen untuk memberikan yang terbaik selama program berlangsung.',
            'Saya tertarik untuk mengaplikasikan ilmu yang telah saya pelajari di kampus untuk membantu menyelesaikan masalah nyata di masyarakat. Saya siap belajar dan berkontribusi maksimal.',
            'Sebagai mahasiswa yang peduli dengan sustainable development, saya ingin berkontribusi dalam proyek ini untuk membawa dampak positif bagi masyarakat setempat.',
            'Saya memiliki keterampilan yang relevan dan pengalaman organisasi yang dapat membantu kesuksesan proyek ini. Saya sangat antusias untuk bergabung dalam tim.',
            'Program KKN ini merupakan kesempatan emas untuk mengimplementasikan teori yang saya pelajari. Saya yakin dapat memberikan kontribusi signifikan.',
            'Dengan latar belakang pendidikan saya di bidang ini, saya optimis dapat membantu menyelesaikan permasalahan yang ada dengan pendekatan inovatif.',
            'Saya memiliki komitmen tinggi untuk terlibat aktif dalam program ini dan memberikan dampak positif bagi masyarakat.',
        ];

        $coverLetters = [
            'Dengan hormat, saya mengajukan diri untuk bergabung dalam proyek ini. Saya yakin dengan latar belakang pendidikan dan pengalaman saya, saya dapat berkontribusi positif dalam mencapai tujuan proyek.',
            'Kepada Yth. Tim Seleksi, saya tertarik untuk berpartisipasi dalam proyek ini karena visi dan misi yang sejalan dengan nilai-nilai yang saya anut. Saya berharap dapat diberikan kesempatan untuk berkontribusi.',
            'Saya mengajukan aplikasi ini dengan penuh antusias. Proyek ini memberikan kesempatan luar biasa untuk mengembangkan kompetensi sekaligus memberikan manfaat nyata bagi masyarakat.',
            'Melalui surat ini, saya ingin menyampaikan ketertarikan saya untuk terlibat dalam proyek yang Anda tawarkan. Saya percaya bahwa pengalaman ini akan sangat berharga bagi pengembangan diri saya.',
            'Sebagai mahasiswa yang berdedikasi, saya sangat tertarik untuk berkontribusi dalam program KKN ini. Saya yakin pengalaman dan skill saya akan sangat bermanfaat.',
        ];

        $acceptedFeedbacks = [
            'Aplikasi Anda menunjukkan motivasi yang kuat dan kesesuaian skill dengan kebutuhan proyek. Kami sangat senang menerima Anda dalam tim kami.',
            'Selamat! Aplikasi Anda diterima. Kami terkesan dengan pengalaman dan antusiasme Anda. Silakan tunggu informasi lebih lanjut mengenai briefing proyek.',
            'Profil dan motivasi Anda sangat sesuai dengan kebutuhan proyek. Selamat bergabung dalam tim kami!',
            'Kami sangat senang menerima Anda dalam program ini. Pengalaman dan dedikasi Anda akan sangat berharga bagi kesuksesan proyek.',
        ];

        $rejectedFeedbacks = [
            'Terima kasih atas aplikasi Anda. Setelah melalui proses review, kami memutuskan untuk memilih kandidat lain yang lebih sesuai dengan kebutuhan proyek saat ini.',
            'Profil Anda sangat menarik, namun sayangnya kuota untuk proyek ini sudah terpenuhi. Kami mengapresiasi ketertarikan Anda dan berharap dapat bekerja sama di kesempatan lain.',
            'Kami menghargai ketertarikan Anda, namun untuk saat ini kami memilih kandidat dengan skill yang lebih spesifik sesuai kebutuhan proyek.',
        ];

        // counter untuk tracking
        $totalCreated = 0;
        $acceptedCount = 0;
        $targetAccepted = 30; // target minimal 30 accepted applications

        // strategi: prioritaskan membuat accepted applications terlebih dahulu
        $this->command->info("\n📝 Phase 1: Creating accepted applications...");
        
        // buat accepted applications dulu (untuk memastikan minimal 30)
        $studentsForAccepted = $students->shuffle();
        $problemsForAccepted = $problems->shuffle();
        
        $acceptedApplicationsToCreate = min($targetAccepted, $studentsForAccepted->count(), $problemsForAccepted->count());
        
        for ($i = 0; $i < $acceptedApplicationsToCreate; $i++) {
            $student = $studentsForAccepted[$i];
            $problem = $problemsForAccepted[$i % $problemsForAccepted->count()];
            
            // cek apakah kombinasi student-problem sudah ada
            $exists = Application::where('student_id', $student->id)
                                ->where('problem_id', $problem->id)
                                ->exists();
            
            if ($exists) {
                continue;
            }
            
            $appliedAt = Carbon::now()->subDays(rand(10, 30));
            $reviewedAt = $appliedAt->copy()->addDays(rand(2, 5));
            $acceptedAt = $reviewedAt->copy()->addDays(rand(1, 3));
            
            try {
                Application::create([
                    'student_id' => $student->id,
                    'problem_id' => $problem->id,
                    'status' => 'accepted',
                    'cover_letter' => $coverLetters[array_rand($coverLetters)],
                    'motivation' => $motivations[array_rand($motivations)],
                    'applied_at' => $appliedAt,
                    'reviewed_at' => $reviewedAt,
                    'accepted_at' => $acceptedAt,
                    'rejected_at' => null,
                    'feedback' => $acceptedFeedbacks[array_rand($acceptedFeedbacks)],
                ]);

                $totalCreated++;
                $acceptedCount++;

                // update problem counters
                $problem->increment('applications_count');
                $problem->increment('accepted_students');
                
            } catch (\Exception $e) {
                $this->command->warn("Skipped: " . $e->getMessage());
            }
        }

        $this->command->info("✅ Created {$acceptedCount} accepted applications");

        // phase 2: buat aplikasi lainnya dengan status bervariasi
        $this->command->info("\n📝 Phase 2: Creating other applications...");
        
        // buat aplikasi tambahan untuk variasi
        $remainingStudents = $students->shuffle();
        
        foreach ($remainingStudents as $student) {
            // setiap student buat 2-4 aplikasi dengan berbagai status
            $numApplications = rand(2, 4);
            
            // ambil random problems untuk student ini
            $selectedProblems = $problems->random(min($numApplications, $problems->count()));
            
            foreach ($selectedProblems as $problem) {
                // cek apakah sudah ada aplikasi
                $exists = Application::where('student_id', $student->id)
                                    ->where('problem_id', $problem->id)
                                    ->exists();
                
                if ($exists) {
                    continue;
                }
                
                // random status dengan distribusi:
                // 20% pending, 15% reviewed, 35% rejected
                // (accepted sudah dibuat di phase 1)
                $rand = rand(1, 100);
                if ($rand <= 20) {
                    $status = 'pending';
                } elseif ($rand <= 35) {
                    $status = 'reviewed';
                } else {
                    $status = 'rejected';
                }

                // tentukan timestamps berdasarkan status
                $appliedAt = Carbon::now()->subDays(rand(1, 30));
                $reviewedAt = null;
                $acceptedAt = null;
                $rejectedAt = null;
                $feedback = null;

                if ($status === 'reviewed') {
                    $reviewedAt = $appliedAt->copy()->addDays(rand(1, 5));
                } elseif ($status === 'rejected') {
                    $reviewedAt = $appliedAt->copy()->addDays(rand(1, 5));
                    $rejectedAt = $reviewedAt->copy()->addDays(rand(1, 3));
                    $feedback = $rejectedFeedbacks[array_rand($rejectedFeedbacks)];
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
                    
                } catch (\Exception $e) {
                    // skip jika duplicate
                    continue;
                }
            }
        }

        // tampilkan statistik final
        $this->command->newLine();
        $this->command->info("✅ {$totalCreated} applications berhasil dibuat!");
        $this->command->newLine();
        $this->command->info('📊 Status distribusi:');
        $this->command->table(
            ['Status', 'Count', 'Percentage'],
            [
                ['Pending', Application::where('status', 'pending')->count(), round(Application::where('status', 'pending')->count() / $totalCreated * 100, 1) . '%'],
                ['Reviewed', Application::where('status', 'reviewed')->count(), round(Application::where('status', 'reviewed')->count() / $totalCreated * 100, 1) . '%'],
                ['Accepted', Application::where('status', 'accepted')->count(), round(Application::where('status', 'accepted')->count() / $totalCreated * 100, 1) . '%'],
                ['Rejected', Application::where('status', 'rejected')->count(), round(Application::where('status', 'rejected')->count() / $totalCreated * 100, 1) . '%'],
            ]
        );
        
        $finalAcceptedCount = Application::where('status', 'accepted')->count();
        if ($finalAcceptedCount >= 25) {
            $this->command->info("\n🎉 Success! {$finalAcceptedCount} accepted applications (target: 25+)");
        } else {
            $this->command->warn("\n⚠️ Warning: Only {$finalAcceptedCount} accepted applications (target: 25+)");
        }
    }
}