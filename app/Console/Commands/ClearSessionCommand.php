<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearSessionsCommand extends Command
{
    /**
     * nama dan signature dari console command
     */
    protected $signature = 'session:clear-all 
                            {--force : force clear tanpa konfirmasi}';

    /**
     * deskripsi console command
     */
    protected $description = 'hapus semua session dari database (untuk debugging)';

    /**
     * execute console command
     */
    public function handle()
    {
        if (config('session.driver') !== 'database') {
            $this->error('command ini hanya untuk SESSION_DRIVER=database');
            return 1;
        }

        $sessionCount = DB::table('sessions')->count();
        
        if ($sessionCount === 0) {
            $this->info('tidak ada session yang perlu dihapus');
            return 0;
        }

        $this->info("ditemukan {$sessionCount} session di database");

        if (!$this->option('force')) {
            if (!$this->confirm('apakah anda yakin ingin menghapus semua session?')) {
                $this->info('operasi dibatalkan');
                return 0;
            }
        }

        DB::table('sessions')->truncate();
        
        $this->info("berhasil menghapus {$sessionCount} session dari database");
        $this->warn('catatan: semua user yang sedang login akan logout otomatis');
        
        return 0;
    }
}