<?php

namespace App\Console\Commands;

use App\Models\Problem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixAcceptedStudentsCount extends Command
{
    /**
     * signature command
     */
    protected $signature = 'fix:accepted-students';

    /**
     * deskripsi command
     */
    protected $description = 'perbaiki counter accepted_students di tabel problems agar sinkron dengan aplikasi yang diterima';

    /**
     * jalankan command
     */
    public function handle()
    {
        $this->info('mulai memperbaiki counter accepted_students...');

        try {
            DB::beginTransaction();

            $problems = Problem::all();
            $fixed = 0;

            foreach ($problems as $problem) {
                // hitung jumlah aplikasi yang statusnya accepted
                $actualAcceptedCount = $problem->applications()
                    ->where('status', 'accepted')
                    ->count();

                // jika tidak sama dengan field accepted_students, perbaiki
                if ($problem->accepted_students !== $actualAcceptedCount) {
                    $oldValue = $problem->accepted_students;
                    
                    $problem->update([
                        'accepted_students' => $actualAcceptedCount
                    ]);

                    $this->line("problem ID {$problem->id}: {$oldValue} → {$actualAcceptedCount}");
                    $fixed++;
                }
            }

            DB::commit();

            if ($fixed > 0) {
                $this->info("berhasil memperbaiki {$fixed} problem");
            } else {
                $this->info('semua data sudah sinkron');
            }

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('terjadi kesalahan: ' . $e->getMessage());
            return 1;
        }
    }
}