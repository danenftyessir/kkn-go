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
        $this->bucketName = config('services.supabase.bucket', 'kkn-go-storage');
        $this->baseUrl = "https://{$this->projectId}.supabase.co/storage/v1";
        $this->storageUrl = "https://{$this->projectId}.supabase.co/storage/v1/object/public/{$this->bucketName}";
        
        // log config untuk debugging
        if (!$this->projectId || !$this->serviceKey) {
            Log::warning("Supabase config tidak lengkap! Project ID: " . ($this->projectId ? 'OK' : 'MISSING') . ", Service Key: " . ($this->serviceKey ? 'OK' : 'MISSING'));
        }
    }

    /**
     * upload file ke supabase storage
     * 
     * @param UploadedFile $file file yang akan diupload
     * @param string $path path tujuan di bucket (contoh: profiles/students/image.jpg)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadFile(UploadedFile $file, string $path)
    {
        try {
            // baca file content
            $fileContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();

            Log::info("Uploading file to Supabase: {$path}, MIME: {$mimeType}");

            // upload ke supabase menggunakan POST method
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => $mimeType,
            ])->withBody($fileContent, $mimeType)
              ->post("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            if ($response->successful()) {
                Log::info("File berhasil diupload ke Supabase: {$path}");
                return $path;
            }

            Log::error("Gagal upload file ke Supabase. Status: " . $response->status() . ", Body: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Exception saat upload file ke Supabase: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * upload foto profil student ke supabase
     * 
     * @param UploadedFile $file file foto profil
     * @param int $studentId ID student
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProfilePhoto(UploadedFile $file, int $studentId)
    {
        try {
            // generate unique filename dengan student id dan timestamp
            $extension = $file->getClientOriginalExtension();
            $filename = 'student-' . $studentId . '-' . time() . '.' . $extension;
            $path = 'profiles/students/' . $filename;

            Log::info("Memulai upload foto profil untuk student ID {$studentId}");

            // upload menggunakan method uploadFile yang sudah ada
            $uploadedPath = $this->uploadFile($file, $path);

            if ($uploadedPath) {
                Log::info("Foto profil berhasil diupload untuk student ID {$studentId}: {$uploadedPath}");
                return $uploadedPath;
            }

            Log::error("Gagal upload foto profil untuk student ID {$studentId}");
            return false;

        } catch (\Exception $e) {
            Log::error("Exception saat upload foto profil student ID {$studentId}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * upload logo institution ke supabase
     * 
     * @param UploadedFile $file file logo
     * @param int $institutionId ID institution
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadInstitutionLogo(UploadedFile $file, int $institutionId)
    {
        try {
            // generate unique filename dengan institution id dan timestamp
            $extension = $file->getClientOriginalExtension();
            $filename = 'institution-' . $institutionId . '-' . time() . '.' . $extension;
            $path = 'profiles/institutions/' . $filename;

            Log::info("Memulai upload logo untuk institution ID {$institutionId}");

            // upload menggunakan method uploadFile yang sudah ada
            $uploadedPath = $this->uploadFile($file, $path);

            if ($uploadedPath) {
                Log::info("Logo institution berhasil diupload untuk institution ID {$institutionId}: {$uploadedPath}");
                return $uploadedPath;
            }

            Log::error("Gagal upload logo untuk institution ID {$institutionId}");
            return false;

        } catch (\Exception $e) {
            Log::error("Exception saat upload logo institution ID {$institutionId}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
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
        try {
            $extension = $file->getClientOriginalExtension();
            $prefix = $isCover ? 'cover' : 'img';
            $filename = "problem-{$problemId}-{$prefix}-" . time() . '.' . $extension;
            $path = 'problems/' . $filename;

            Log::info("Memulai upload gambar problem ID {$problemId}");

            $uploadedPath = $this->uploadFile($file, $path);

            if ($uploadedPath) {
                Log::info("Gambar problem berhasil diupload untuk problem ID {$problemId}: {$uploadedPath}");
                return $uploadedPath;
            }

            Log::error("Gagal upload gambar problem ID {$problemId}");
            return false;

        } catch (\Exception $e) {
            Log::error("Exception saat upload gambar problem ID {$problemId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * upload document ke supabase
     * 
     * @param UploadedFile $file file document (PDF, DOCX, dll)
     * @param string $category kategori document (reports, certificates, dll)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadDocument(UploadedFile $file, string $category = 'documents')
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $originalName . '-' . time() . '.' . $extension;
            $path = $category . '/' . $filename;

            Log::info("Memulai upload document: {$filename}");

            $uploadedPath = $this->uploadFile($file, $path);

            if ($uploadedPath) {
                Log::info("Document berhasil diupload: {$uploadedPath}");
                return $uploadedPath;
            }

            Log::error("Gagal upload document: {$filename}");
            return false;

        } catch (\Exception $e) {
            Log::error("Exception saat upload document: " . $e->getMessage());
            return false;
        }
    }

    /**
     * get public URL untuk file di Supabase
     * 
     * @param string $path path file di bucket
     * @return string public URL
     */
    public function getPublicUrl(string $path)
    {
        // hilangkan slash di awal jika ada
        $path = ltrim($path, '/');
        
        return "{$this->storageUrl}/{$path}";
    }

    /**
     * delete file dari supabase storage
     * 
     * @param string $path path file yang akan dihapus
     * @return bool true jika berhasil, false jika gagal
     */
    public function delete(string $path)
    {
        try {
            Log::info("Menghapus file dari Supabase: {$path}");

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->delete("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            if ($response->successful()) {
                Log::info("File berhasil dihapus dari Supabase: {$path}");
                return true;
            }

            Log::warning("Gagal menghapus file dari Supabase (mungkin tidak ada): {$path}");
            return false;

        } catch (\Exception $e) {
            Log::error("Exception saat hapus file dari Supabase: " . $e->getMessage());
            return false;
        }
    }

    /**
     * list files dalam folder tertentu di bucket
     * 
     * @param string $folder path folder (contoh: documents/reports)
     * @return array daftar file paths
     */
    public function listFiles(string $folder = '')
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->post("{$this->baseUrl}/object/list/{$this->bucketName}", [
                'prefix' => $folder,
                'limit' => 1000,
                'offset' => 0,
            ]);

            if ($response->successful()) {
                $files = $response->json();
                
                // ekstrak path dari response
                $filePaths = [];
                foreach ($files as $file) {
                    if (isset($file['name']) && !empty($file['name'])) {
                        $filePath = $folder ? $folder . '/' . $file['name'] : $file['name'];
                        $filePaths[] = $filePath;
                    }
                }
                
                return $filePaths;
            }

            Log::error("Gagal list files dari Supabase folder: {$folder}");
            return [];

        } catch (\Exception $e) {
            Log::error("Exception saat list files dari Supabase: " . $e->getMessage());
            return [];
        }
    }

    /**
     * cek apakah file exists di bucket
     * 
     * @param string $path path file
     * @return bool true jika exists, false jika tidak
     */
    public function exists(string $path)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->head("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            return $response->successful();

        } catch (\Exception $e) {
            return false;
        }
    }
}