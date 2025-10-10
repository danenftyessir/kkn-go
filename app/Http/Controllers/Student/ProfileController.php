<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Requests\UpdateStudentPasswordRequest;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * controller untuk mengelola profil mahasiswa
 * 
 * path: app/Http/Controllers/Student/ProfileController.php
 */
class ProfileController extends Controller
{
    protected $storageService;

    public function __construct(SupabaseStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * tampilkan halaman profil mahasiswa (private view)
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        // hitung statistik untuk profil
        $stats = [
            'total_projects' => $student->projects()->count(),
            'active_projects' => $student->projects()->where('status', 'active')->count(),
            'completed_projects' => $student->projects()->where('status', 'completed')->count(),
            'total_applications' => $student->applications()->count(),
            'pending_applications' => $student->applications()->where('status', 'pending')->count(),
        ];

        return view('student.profile.index', compact('user', 'student', 'stats'));
    }

    /**
     * tampilkan halaman edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        $student = $user->student;
        
        // ambil data universitas untuk dropdown
        $universities = \App\Models\University::orderBy('name')->get();

        return view('student.profile.edit', compact('user', 'student', 'universities'));
    }

    /**
     * update profil mahasiswa
     */
    public function update(UpdateStudentProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $student = $user->student;
            
            // update data user
            $user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ]);
            
            // handle profile photo upload
            $profilePhotoPath = $student->profile_photo_path;
            
            if ($request->hasFile('profile_photo')) {
                // hapus foto lama jika ada
                if ($profilePhotoPath) {
                    $this->storageService->delete($profilePhotoPath);
                }
                
                // upload foto baru ke Supabase
                $file = $request->file('profile_photo');
                $uploadedPath = $this->storageService->uploadProfilePhoto($file, $student->id);
                
                if ($uploadedPath) {
                    $profilePhotoPath = $uploadedPath;
                    Log::info("Foto profil berhasil diupload untuk student ID {$student->id}: {$uploadedPath}");
                } else {
                    Log::error("Gagal upload foto profil untuk student ID {$student->id}");
                    return back()->with('error', 'Gagal mengupload foto profil. Silakan coba lagi.');
                }
            }
            
            // update data student
            $student->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'university_id' => $request->university_id,
                'major' => $request->major,
                'nim' => $request->nim,
                'semester' => $request->semester,
                'whatsapp_number' => $request->whatsapp_number,
                'profile_photo_path' => $profilePhotoPath,
                'bio' => $request->bio,
                'skills' => $request->skills ? json_encode($request->skills) : null,
                'interests' => $request->interests ? json_encode($request->interests) : null,
            ]);
            
            Log::info("Profil student ID {$student->id} berhasil diupdate");
            
            return redirect()->route('student.profile.index')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error("Error saat update profil student: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.');
        }
    }

    /**
     * update password student
     */
    public function updatePassword(UpdateStudentPasswordRequest $request)
    {
        try {
            $user = Auth::user();
            
            // update password
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            Log::info("Password berhasil diupdate untuk user ID {$user->id}");
            
            return redirect()->route('student.profile.index')
                ->with('success', 'Password berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error("Error saat update password: " . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.');
        }
    }
}