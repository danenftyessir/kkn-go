<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * command untuk upload files dari local storage ke supabase
 * 
 * jalankan:
 * php artisan upload:supabase --type=documents
 * php artisan upload:supabase --type=problems
 * php artisan upload:supabase --type=all
 */
class UploadFilesToSupabase extends Command
{
    protected $signature = 'upload:supabase {--type=all : Type file yang diupload (documents|problems|all)}';
    
    protected $description = 'Upload files dari local storage ke Supabase storage';

    public function handle()
    {
        $type = $this->option('type');
        
        $this->info("🚀 Memulai upload files ke Supabase...");
        $this->info("Type: {$type}");
        $this->newLine();

        if ($type === 'all' || $type === 'documents') {
            $this->uploadDocuments();
        }

        if ($type === 'all' || $type === 'problems') {
            $this->uploadProblemImages();
        }

        $this->newLine();
        $this->info("✅ Upload selesai!");
    }

    /**
     * upload dokumen PDF ke supabase
     */
    protected function uploadDocuments()
    {
        $this->info("📄 Uploading documents...");
        
        $localPath = storage_path('app/public/documents/reports');
        
        if (!File::exists($localPath)) {
            $this->warn("⚠️  Folder {$localPath} tidak ditemukan!");
            return;
        }

        $files = File::files($localPath);
        
        if (empty($files)) {
            $this->warn("⚠️  Tidak ada file di folder documents/reports");
            return;
        }

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $uploaded = 0;
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $extension = strtolower($file->getExtension());
            
            // hanya upload PDF, DOC, DOCX
            if (!in_array($extension, ['pdf', 'doc', 'docx'])) {
                $bar->advance();
                continue;
            }

            $fileContent = File::get($file->getPathname());
            $supabasePath = 'documents/reports/' . $fileName;

            // upload ke supabase
            try {
                Storage::disk('supabase')->put($supabasePath, $fileContent, 'public');
                $uploaded++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error upload {$fileName}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Berhasil upload {$uploaded} dokumen");
    }

    /**
     * upload gambar problems ke supabase
     */
    protected function uploadProblemImages()
    {
        $this->info("🖼️  Uploading problem images...");
        
        $localPath = storage_path('app/public/problems');
        
        if (!File::exists($localPath)) {
            $this->warn("⚠️  Folder {$localPath} tidak ditemukan!");
            return;
        }

        $files = File::files($localPath);
        
        if (empty($files)) {
            $this->warn("⚠️  Tidak ada file di folder problems");
            return;
        }

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $uploaded = 0;
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $extension = strtolower($file->getExtension());
            
            // hanya upload gambar
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $bar->advance();
                continue;
            }

            $fileContent = File::get($file->getPathname());
            $supabasePath = 'problems/' . $fileName;

            // upload ke supabase
            try {
                Storage::disk('supabase')->put($supabasePath, $fileContent, 'public');
                $uploaded++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error upload {$fileName}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Berhasil upload {$uploaded} gambar");
    }
}