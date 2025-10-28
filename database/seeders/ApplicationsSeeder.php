<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Application;
use App\Models\Student;
use App\Models\Problem;
use Carbon\Carbon;

/**
 * seeder untuk data dummy applications
 * 
 * jalankan: php artisan db:seed --class=ApplicationsSeeder
 */
class ApplicationsSeeder extends Seeder
{
    /**
     * jalankan database seeds
     */
    public function run(): void
    {
        // disable query log untuk performa lebih baik
        DB::connection()->disableQueryLog();
        
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
            'Saya sangat tertarik dengan proyek ini karena sesuai dengan bidang studi saya dan ingin berkontribusi untuk masyarakat.',
            'Proyek ini sangat menarik dan sesuai dengan passion saya di bidang pemberdayaan masyarakat.',
            'Saya tertarik untuk mengaplikasikan ilmu yang telah saya pelajari di kampus untuk membantu menyelesaikan masalah nyata.',
            'Sebagai mahasiswa yang peduli dengan sustainable development, saya ingin berkontribusi dalam proyek ini.',
            'Saya memiliki keterampilan yang relevan dan pengalaman organisasi yang dapat membantu kesuksesan proyek ini.',
            'Program KKN ini merupakan kesempatan emas untuk mengimplementasikan teori yang saya pelajari.',
            'Dengan latar belakang pendidikan saya, saya optimis dapat membantu menyelesaikan permasalahan yang ada.',
            'Saya memiliki komitmen tinggi untuk terlibat aktif dalam program ini.',
        ];

        $coverLetters = [
            'Dengan hormat, saya mengajukan diri untuk bergabung dalam proyek ini.',
            'Kepada Yth. Tim Seleksi, saya tertarik untuk berpartisipasi dalam proyek ini.',
            'Salam hormat, melalui surat ini saya bermaksud mengajukan aplikasi untuk program KKN.',
            'Yth. Panitia Seleksi, saya sangat tertarik untuk menjadi bagian dari proyek ini.',
        ];

        $this->command->info('📝 Phase 1: Creating accepted applications...');
        
        // phase 1: buat aplikasi yang diterima (untuk projects nanti)
        $targetAccepted = min(30, floor($students->count() * 0.5)); // maksimal 30 atau 50% dari students
        $acceptedData = [];
        $acceptedCount = 0;
        
        // shuffle students dan problems untuk distribusi random
        $shuffledStudents = $students->shuffle();
        $shuffledProblems = $problems->shuffle();
        
        $studentIndex = 0;
        $problemIndex = 0;
        
        while ($acceptedCount < $targetAccepted && $studentIndex < $shuffledStudents->count() && $problemIndex < $shuffledProblems->count()) {
            $student = $shuffledStudents[$studentIndex];
            $problem = $shuffledProblems[$problemIndex % $shuffledProblems->count()];
            
            // cek apakah student sudah apply ke problem ini
            $existing = collect($acceptedData)->first(function($item) use ($student, $problem) {
                return $item['student_id'] === $student->id && $item['problem_id'] === $problem->id;
            });
            
            if (!$existing) {
                $appliedAt = Carbon::now()->subDays(rand(30, 60));
                $acceptedAt = $appliedAt->copy()->addDays(rand(3, 10));
                
                $acceptedData[] = [
                    'problem_id' => $problem->id,
                    'student_id' => $student->id,
                    'status' => 'accepted',
                    'motivation' => $motivations[array_rand($motivations)],
                    'cover_letter' => $coverLetters[array_rand($coverLetters)],
                    'applied_at' => $appliedAt,
                    'reviewed_at' => $appliedAt->copy()->addDays(rand(1, 3)),
                    'accepted_at' => $acceptedAt,
                    'feedback' => 'Aplikasi Anda diterima. Kami terkesan dengan motivasi dan kualifikasi Anda.',
                    'created_at' => $appliedAt,
                    'updated_at' => $acceptedAt,
                ];
                
                $acceptedCount++;
                
                // batch insert setiap 50 records untuk performa
                if (count($acceptedData) >= 50) {
                    DB::transaction(function () use ($acceptedData) {
                        DB::table('applications')->insert($acceptedData);
                    });
                    $this->command->info("  -> {$acceptedCount} accepted applications inserted");
                    $acceptedData = [];
                    
                    // cleanup connection
                    $this->cleanupConnection();
                }
            }
            
            $studentIndex++;
            $problemIndex++;
        }
        
        // insert sisa accepted data
        if (!empty($acceptedData)) {
            DB::transaction(function () use ($acceptedData) {
                DB::table('applications')->insert($acceptedData);
            });
            $this->command->info("  -> {$acceptedCount} accepted applications inserted");
        }
        
        $this->command->info("✅ Created {$acceptedCount} accepted applications");

        $this->command->info('📝 Phase 2: Creating other applications...');
        
        // phase 2: buat aplikasi dengan status lain (pending, reviewed, rejected)
        $otherApplicationsData = [];
        $otherCount = 0;
        $targetOther = min(100, $students->count() * 2); // maksimal 100 atau 2x jumlah students
        
        $availableStudents = $students->whereNotIn('id', collect($shuffledStudents)->slice(0, $studentIndex)->pluck('id'));
        
        foreach ($availableStudents as $student) {
            if ($otherCount >= $targetOther) break;
            
            // setiap student bisa apply ke 1-3 problems
            $numApplications = rand(1, min(3, $problems->count()));
            $selectedProblems = $problems->random($numApplications);
            
            foreach ($selectedProblems as $problem) {
                $status = $this->randomStatus();
                
                $appliedAt = Carbon::now()->subDays(rand(5, 45));
                $reviewedAt = in_array($status, ['reviewed', 'rejected']) 
                    ? $appliedAt->copy()->addDays(rand(1, 5)) 
                    : null;
                $rejectedAt = $status === 'rejected' 
                    ? $reviewedAt->copy()->addDays(rand(1, 2)) 
                    : null;
                
                $feedback = match($status) {
                    'reviewed' => 'Aplikasi Anda sedang dalam proses review lebih lanjut.',
                    'rejected' => 'Terima kasih atas minat Anda. Saat ini kami memilih kandidat yang lebih sesuai dengan kebutuhan proyek.',
                    default => null,
                };
                
                $otherApplicationsData[] = [
                    'problem_id' => $problem->id,
                    'student_id' => $student->id,
                    'status' => $status,
                    'motivation' => $motivations[array_rand($motivations)],
                    'cover_letter' => $coverLetters[array_rand($coverLetters)],
                    'applied_at' => $appliedAt,
                    'reviewed_at' => $reviewedAt,
                    'rejected_at' => $rejectedAt,
                    'feedback' => $feedback,
                    'created_at' => $appliedAt,
                    'updated_at' => $reviewedAt ?? $appliedAt,
                ];
                
                $otherCount++;
                
                // batch insert setiap 50 records
                if (count($otherApplicationsData) >= 50) {
                    try {
                        DB::transaction(function () use ($otherApplicationsData) {
                            DB::table('applications')->insert($otherApplicationsData);
                        });
                        $this->command->info("  -> {$otherCount} other applications inserted");
                        $otherApplicationsData = [];
                        
                        // cleanup connection
                        $this->cleanupConnection();
                    } catch (\Exception $e) {
                        // skip jika duplicate
                        $this->command->warn("  -> Skipping duplicate applications");
                        $otherApplicationsData = [];
                    }
                }
            }
        }
        
        // insert sisa data
        if (!empty($otherApplicationsData)) {
            try {
                DB::transaction(function () use ($otherApplicationsData) {
                    DB::table('applications')->insert($otherApplicationsData);
                });
                $this->command->info("  -> {$otherCount} other applications inserted");
            } catch (\Exception $e) {
                // skip jika duplicate
                $this->command->warn("  -> Skipping duplicate applications");
            }
        }
        
        $this->command->info("✅ Created {$otherCount} other applications");

        // CRITICAL FIX: update applications_count dengan bulk query, bukan loop
        $this->command->info('📊 Updating problem statistics...');
        $this->updateProblemStatistics();

        // tampilkan statistik final
        $totalCreated = $acceptedCount + $otherCount;
        $this->command->newLine();
        $this->command->info("✅ {$totalCreated} applications berhasil dibuat!");
        $this->command->newLine();
        $this->command->info('📊 Status distribusi:');
        $this->command->table(
            ['Status', 'Count', 'Percentage'],
            [
                ['Pending', Application::where('status', 'pending')->count(), round(Application::where('status', 'pending')->count() / max($totalCreated, 1) * 100, 1) . '%'],
                ['Reviewed', Application::where('status', 'reviewed')->count(), round(Application::where('status', 'reviewed')->count() / max($totalCreated, 1) * 100, 1) . '%'],
                ['Accepted', Application::where('status', 'accepted')->count(), round(Application::where('status', 'accepted')->count() / max($totalCreated, 1) * 100, 1) . '%'],
                ['Rejected', Application::where('status', 'rejected')->count(), round(Application::where('status', 'rejected')->count() / max($totalCreated, 1) * 100, 1) . '%'],
            ]
        );
        
        $finalAcceptedCount = Application::where('status', 'accepted')->count();
        if ($finalAcceptedCount >= 25) {
            $this->command->info("\n🎉 Success! {$finalAcceptedCount} accepted applications (target: 25+)");
        } else {
            $this->command->warn("\n⚠️ Warning: Only {$finalAcceptedCount} accepted applications (target: 25+)");
        }
    }

    /**
     * update problem statistics dengan BULK query (menghindari N+1 loop)
     * CRITICAL FIX untuk prepared statement error
     */
    private function updateProblemStatistics(): void
    {
        // CARA LAMA (ERROR):
        // foreach ($problems as $problem) {
        //     $count = Application::where('problem_id', $problem->id)->count();
        //     $problem->update(['applications_count' => $count]);  // ← N queries!
        // }

        // CARA BARU (OPTIMIZED):
        // hitung semua sekaligus dengan group by
        $counts = DB::table('applications')
            ->select('problem_id', DB::raw('count(*) as total'))
            ->groupBy('problem_id')
            ->pluck('total', 'problem_id')
            ->toArray();

        $this->command->info("  -> Counted applications for " . count($counts) . " problems");

        // update dengan raw query untuk semua problems sekaligus
        // gunakan CASE WHEN untuk bulk update
        if (!empty($counts)) {
            $caseStatements = [];
            $problemIds = [];
            
            foreach ($counts as $problemId => $count) {
                $caseStatements[] = "WHEN {$problemId} THEN {$count}";
                $problemIds[] = $problemId;
            }
            
            $caseString = implode(' ', $caseStatements);
            $idsString = implode(',', $problemIds);
            $now = now()->format('Y-m-d H:i:s');
            
            // single query untuk update semua problems
            DB::statement("
                UPDATE problems 
                SET applications_count = CASE id {$caseString} END,
                    updated_at = '{$now}'
                WHERE id IN ({$idsString})
            ");
            
            $this->command->info("  ✓ Updated statistics for " . count($counts) . " problems");
        }
        
        // set 0 untuk problems yang tidak punya applications
        DB::statement("
            UPDATE problems 
            SET applications_count = 0,
                updated_at = '" . now()->format('Y-m-d H:i:s') . "'
            WHERE id NOT IN (SELECT DISTINCT problem_id FROM applications)
        ");
        
        $this->command->info("  ✓ Reset statistics for problems with no applications");
    }

    /**
     * cleanup connection untuk clear prepared statements
     */
    private function cleanupConnection(): void
    {
        try {
            // commit pending transactions
            while (DB::transactionLevel() > 0) {
                DB::commit();
            }
            
            // untuk PostgreSQL: deallocate prepared statements
            if (DB::connection()->getDriverName() === 'pgsql') {
                try {
                    DB::statement("DEALLOCATE ALL");
                } catch (\Exception $e) {
                    // silent fail
                }
            }
            
        } catch (\Exception $e) {
            // silent fail
        }
    }

    /**
     * generate random status (untuk other applications)
     */
    private function randomStatus(): string
    {
        $statuses = ['pending', 'reviewed', 'rejected'];
        $weights = [40, 30, 30]; // persentase
        
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $statuses[$index];
            }
        }
        
        return 'pending';
    }
}