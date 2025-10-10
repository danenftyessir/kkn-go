<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * service untuk handle file operations dengan Supabase Storage
 * menggunakan Supabase REST API tanpa S3 driver
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

    public function __construct()
    {
        $this->projectId = config('services.supabase.project_id');
        $this->anonKey = config('services.supabase.anon_key');
        $this->serviceKey = config('services.supabase.service_key');
        $this->bucketName = config('services.supabase.bucket', 'kkn-go storage');
        $this->baseUrl = "https://{$this->projectId}.supabase.co/storage/v1";
    }

    /**
     * upload file ke supabase storage
     * 
     * @param UploadedFile $file file yang akan diupload
     * @param string $path path tujuan di bucket (contoh: problems/image.jpg)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadFile(UploadedFile $file, string $path)
    {
        try {
            // baca file content
            $fileContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();

            // upload ke supabase menggunakan PUT method untuk upsert
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => $mimeType,
            ])->withBody($fileContent, $mimeType)
              ->post("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            if ($response->successful()) {
                Log::info("File berhasil diupload ke Supabase: {$path}");
                return $path;
            }

            Log::error("Gagal upload file ke Supabase: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Error saat upload file ke Supabase: " . $e->getMessage());
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

            // upload menggunakan method uploadFile yang sudah ada
            $uploadedPath = $this->uploadFile($file, $path);

            if ($uploadedPath) {
                Log::info("Foto profil berhasil diupload untuk student ID {$studentId}: {$uploadedPath}");
                return $uploadedPath;
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Error saat upload foto profil student ID {$studentId}: " . $e->getMessage());
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

            // upload menggunakan method uploadFile yang sudah ada
            $uploadedPath = $this->uploadFile($file, $path);

            if ($uploadedPath) {
                Log::info("Logo institution berhasil diupload untuk institution ID {$institutionId}: {$uploadedPath}");
                return $uploadedPath;
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Error saat upload logo institution ID {$institutionId}: " . $e->getMessage());
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
                        // jika ada folder prefix, gabungkan
                        $filePath = $folder ? $folder . '/' . $file['name'] : $file['name'];
                        
                        // skip jika folder (biasanya ditandai dengan metadata)
                        if (isset($file['metadata']) && !empty($file['metadata'])) {
                            $filePaths[] = $filePath;
                        }
                    }
                }
                
                return $filePaths;
            }

            Log::warning("Gagal list files dari Supabase: " . $response->body());
            return [];

        } catch (\Exception $e) {
            Log::error("Error saat list files dari Supabase: " . $e->getMessage());
            return [];
        }
    }

    /**
     * delete file dari supabase storage
     * 
     * @param string $path path file yang akan dihapus
     * @return bool true jika berhasil, false jika gagal
     */
    public function deleteFile(string $path): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->delete("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            if ($response->successful()) {
                Log::info("File berhasil dihapus dari Supabase: {$path}");
                return true;
            }

            // tidak error jika file tidak ditemukan (sudah terhapus)
            if ($response->status() === 404) {
                Log::info("File tidak ditemukan di Supabase (mungkin sudah dihapus): {$path}");
                return true;
            }

            Log::error("Gagal hapus file dari Supabase: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Error saat hapus file dari Supabase: " . $e->getMessage());
            return false;
        }
    }

    /**
     * alias untuk deleteFile - untuk backward compatibility
     * 
     * @param string $path path file yang akan dihapus
     * @return bool true jika berhasil, false jika gagal
     */
    public function delete(string $path): bool
    {
        return $this->deleteFile($path);
    }

    /**
     * dapatkan public URL untuk file
     * 
     * @param string $path path file di bucket
     * @return string URL publik file
     */
    public function getPublicUrl(string $path): string
    {
        // clean path
        $cleanPath = ltrim($path, '/');
        
        // encode bucket name untuk handle spasi
        $encodedBucket = rawurlencode($this->bucketName);
        
        // return public URL
        return "https://{$this->projectId}.supabase.co/storage/v1/object/public/{$encodedBucket}/{$cleanPath}";
    }

    /**
     * cek apakah file exists di storage
     * 
     * @param string $path path file
     * @return bool true jika file ada
     */
    public function fileExists(string $path): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->head("{$this->baseUrl}/object/{$this->bucketName}/{$path}");

            return $response->successful();

        } catch (\Exception $e) {
            Log::error("Error saat cek file existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * upload multiple files sekaligus
     * 
     * @param array $files array of UploadedFile
     * @param string $folder folder tujuan
     * @param string $prefix prefix untuk nama file
     * @return array array of uploaded paths
     */
    public function uploadMultipleFiles(array $files, string $folder, string $prefix = ''): array
    {
        $uploadedPaths = [];

        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $filename = $prefix . time() . '-' . $index . '.' . $file->extension();
                $path = $folder . '/' . $filename;
                
                $uploadedPath = $this->uploadFile($file, $path);
                
                if ($uploadedPath) {
                    $uploadedPaths[] = $uploadedPath;
                }
            }
        }

        return $uploadedPaths;
    }

    /**
     * delete multiple files sekaligus
     * 
     * @param array $paths array of file paths
     * @return int jumlah file yang berhasil dihapus
     */
    public function deleteMultipleFiles(array $paths): int
    {
        $deletedCount = 0;

        foreach ($paths as $path) {
            if ($this->deleteFile($path)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}