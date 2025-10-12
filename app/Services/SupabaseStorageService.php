<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * service untuk handle file operations dengan Supabase Storage
 * menggunakan Supabase REST API
 * 
 * dokumentasi: https://supabase.com/docs/reference/javascript/storage-from-upload
 * 
 * path: app/Services/SupabaseStorageService.php
 */
class SupabaseStorageService
{
    protected $projectId;
    protected $anonKey;
    protected $serviceKey;
    protected $bucketName;
    protected $baseUrl;
    protected $storageUrl;

    public function __construct()
    {
        $this->projectId = config('services.supabase.project_id');
        $this->anonKey = config('services.supabase.anon_key');
        $this->serviceKey = config('services.supabase.service_key');
        $this->bucketName = config('services.supabase.bucket', 'kkn-go storage');
        $this->baseUrl = "https://{$this->projectId}.supabase.co/storage/v1";
        $this->storageUrl = "https://{$this->projectId}.supabase.co/storage/v1/object/public/{$this->bucketName}";
        
        // log config untuk debugging (hanya di development)
        if (config('app.debug') && (!$this->projectId || !$this->serviceKey)) {
            Log::warning("⚠️ Supabase config tidak lengkap!", [
                'project_id' => $this->projectId ? '✅' : '❌',
                'service_key' => $this->serviceKey ? '✅' : '❌',
                'bucket' => $this->bucketName,
            ]);
        }
    }

    /**
     * upload file ke supabase storage
     * 
     * @param UploadedFile $file file yang akan diupload
     * @param string $path path tujuan di bucket (contoh: proposals/file.pdf)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadFile(UploadedFile $file, string $path)
    {
        try {
            // validasi config
            if (!$this->projectId || !$this->serviceKey) {
                Log::error("❌ Supabase config tidak lengkap - tidak bisa upload");
                return false;
            }
            
            // baca file content
            $fileContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            Log::info("📤 Uploading to Supabase", [
                'path' => $path,
                'mime' => $mimeType,
                'size' => $fileSize . ' bytes',
                'bucket' => $this->bucketName,
            ]);

            // upload ke supabase menggunakan POST method
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                    'Content-Type' => $mimeType,
                    'x-upsert' => 'false', // jangan overwrite jika sudah ada
                ])
                ->withBody($fileContent, $mimeType)
                ->post("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            // check response
            if ($response->successful()) {
                $publicUrl = $this->getPublicUrl($path);
                
                Log::info("✅ Upload SUCCESS", [
                    'path' => $path,
                    'status' => $response->status(),
                    'public_url' => $publicUrl,
                ]);
                
                return $path;
            }

            // log error detail
            Log::error("❌ Upload FAILED", [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
            ]);
            
            return false;

        } catch (\Exception $e) {
            Log::error("❌ Upload EXCEPTION", [
                'path' => $path,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return false;
        }
    }

    /**
     * get public URL untuk file di Supabase
     * 
     * @param string $path path file di bucket
     * @return string public URL
     */
    public function getPublicUrl(string $path): string
    {
        // hilangkan slash di awal jika ada
        $path = ltrim($path, '/');
        
        // encode path untuk URL
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));
        
        // encode bucket name (ganti spasi dengan %20)
        $encodedBucket = str_replace(' ', '%20', $this->bucketName);
        
        return "https://{$this->projectId}.supabase.co/storage/v1/object/public/{$encodedBucket}/{$encodedPath}";
    }

    /**
     * cek apakah file exists di bucket
     * 
     * @param string $path path file
     * @return bool true jika exists, false jika tidak
     */
    public function exists(string $path): bool
    {
        try {
            Log::info("🔍 Checking if file exists", ['path' => $path]);
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ])
                ->head("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            $exists = $response->successful();
            
            Log::info($exists ? "✅ File exists" : "❌ File tidak ditemukan", ['path' => $path]);
            
            return $exists;

        } catch (\Exception $e) {
            Log::error("❌ Check file EXCEPTION", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * hapus file dari supabase storage
     * 
     * @param string $path path file yang akan dihapus
     * @return bool true jika berhasil, false jika gagal
     */
    public function delete(string $path): bool
    {
        try {
            if (!$this->projectId || !$this->serviceKey) {
                Log::error("❌ Supabase config tidak lengkap - tidak bisa delete");
                return false;
            }

            Log::info("🗑️ Deleting file from Supabase", ['path' => $path]);

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ])
                ->delete("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            if ($response->successful()) {
                Log::info("✅ Delete SUCCESS", ['path' => $path]);
                return true;
            }

            Log::error("❌ Delete FAILED", [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            return false;

        } catch (\Exception $e) {
            Log::error("❌ Delete EXCEPTION", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * list files di folder tertentu
     * 
     * @param string $folder folder path (contoh: proposals/)
     * @return array list file paths
     */
    public function listFiles(string $folder = ''): array
    {
        try {
            Log::info("📂 Listing files in folder", ['folder' => $folder]);

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ])
                ->post("{$this->baseUrl}/object/list/{$this->bucketName}", [
                    'prefix' => $folder,
                ]);

            if ($response->successful()) {
                $files = $response->json();
                
                Log::info("✅ List files SUCCESS", [
                    'folder' => $folder,
                    'count' => count($files),
                ]);
                
                // ambil hanya name dari setiap file
                $filePaths = [];
                foreach ($files as $file) {
                    if (isset($file['name'])) {
                        $filePath = $folder ? $folder . '/' . $file['name'] : $file['name'];
                        $filePaths[] = $filePath;
                    }
                }
                
                return $filePaths;
            }

            Log::error("❌ List files FAILED", [
                'folder' => $folder,
                'status' => $response->status(),
            ]);
            
            return [];

        } catch (\Exception $e) {
            Log::error("❌ List files EXCEPTION", [
                'folder' => $folder,
                'error' => $e->getMessage(),
            ]);
            
            return [];
        }
    }

    /**
     * upload foto profil mahasiswa ke supabase
     * 
     * @param UploadedFile $file file foto
     * @param int $studentId ID mahasiswa
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProfilePhoto(UploadedFile $file, int $studentId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "student-{$studentId}-profile-" . time() . '.' . $extension;
        $path = 'students/profiles/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload logo institusi ke supabase
     * 
     * @param UploadedFile $file file logo
     * @param int $institutionId ID institusi
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadInstitutionLogo(UploadedFile $file, int $institutionId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "institution-{$institutionId}-logo-" . time() . '.' . $extension;
        $path = 'institutions/logos/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload gambar problem ke supabase
     * 
     * @param UploadedFile $file file gambar
     * @param int $problemId ID problem
     * @param bool $isCover apakah ini cover image
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProblemImage(UploadedFile $file, int $problemId, bool $isCover = false)
    {
        $extension = $file->getClientOriginalExtension();
        $prefix = $isCover ? 'cover' : 'img';
        $filename = "problem-{$problemId}-{$prefix}-" . time() . '.' . $extension;
        $path = 'problems/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload document ke supabase
     * 
     * @param UploadedFile $file file document
     * @param string $category kategori document (reports, certificates, dll)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadDocument(UploadedFile $file, string $category = 'documents')
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $originalName . '-' . time() . '.' . $extension;
        $path = $category . '/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload proposal application ke supabase
     * 
     * @param UploadedFile $file file proposal
     * @param int $applicationId ID aplikasi
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProposal(UploadedFile $file, int $applicationId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "application-{$applicationId}-proposal-" . time() . '.' . $extension;
        $path = 'proposals/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload project report ke supabase
     * 
     * @param UploadedFile $file file report
     * @param int $projectId ID project
     * @param string $reportType tipe report (progress, final)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProjectReport(UploadedFile $file, int $projectId, string $reportType = 'progress')
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "project-{$projectId}-{$reportType}-report-" . time() . '.' . $extension;
        $path = 'reports/' . $filename;

        return $this->uploadFile($file, $path);
    }
}