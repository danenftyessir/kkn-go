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

    /**
     * tampilkan public profile student
     */
    public function publicProfile($username)
    {
        // cari student berdasarkan username dari user
        $student = \App\Models\Student::whereHas('user', function($query) use ($username) {
            $query->where('username', $username);
        })
        ->with(['user', 'university'])
        ->firstOrFail();
        
        // ambil user dari relasi student
        $user = $student->user;
        
        // ambil completed projects dari database dengan relasi
        $completedProjects = \App\Models\Project::with([
            'problem.institution',
            'problem.province',
            'problem.regency',
            'institutionReview'
        ])
        ->where('student_id', $student->id)
        ->where('status', 'completed')
        ->orderBy('completed_at', 'desc')
        ->get();
        
        // ambil reviews dari institution untuk projects
        $reviews = \App\Models\Review::where('type', 'institution_to_student')
            ->whereIn('project_id', $completedProjects->pluck('id'))
            ->get();
        
        // hitung achievements berdasarkan completed projects
        $achievements = $this->calculateAchievements($student, $completedProjects, $reviews);
        
        // hitung statistik untuk portfolio
        $stats = [
            'total_projects' => $completedProjects->count(),
            'sdgs_addressed' => $this->countUniqueSDGs($completedProjects),
            'positive_reviews' => $reviews->where('rating', '>=', 4)->count(),
            'average_rating' => $reviews->isEmpty() ? 0 : round($reviews->avg('rating'), 1),
            'total_impact_hours' => $this->calculateTotalImpactHours($completedProjects),
        ];
        
        return view('student.profile.public', compact('user', 'student', 'completedProjects', 'reviews', 'achievements', 'stats'));
    }

    /**
     * helper: hitung achievements berdasarkan completed projects
     */
    private function calculateAchievements($student, $completedProjects, $reviews)
    {
        $achievements = [];
        
        // achievement: first project completed
        if ($completedProjects->count() >= 1) {
            $achievements[] = [
                'title' => 'Proyek Pertama',
                'description' => 'Menyelesaikan proyek KKN pertama',
                'icon' => 'trophy',
                'earned_at' => $completedProjects->last()->completed_at,
            ];
        }
        
        // achievement: multiple projects
        if ($completedProjects->count() >= 3) {
            $achievements[] = [
                'title' => 'Kontributor Aktif',
                'description' => 'Menyelesaikan 3 proyek KKN',
                'icon' => 'star',
                'earned_at' => $completedProjects->sortBy('completed_at')->skip(2)->first()->completed_at,
            ];
        }
        
        // achievement: high rating
        if ($reviews->where('rating', '>=', 4)->count() >= 3) {
            $achievements[] = [
                'title' => 'Mahasiswa Berprestasi',
                'description' => 'Mendapat 3 ulasan positif dari instansi',
                'icon' => 'award',
                'earned_at' => now(),
            ];
        }
        
        return $achievements;
    }

    /**
     * helper: hitung unique SDGs dari completed projects
     */
    private function countUniqueSDGs($completedProjects)
    {
        $sdgs = [];
        
        foreach ($completedProjects as $project) {
            if ($project->problem && $project->problem->sdg_categories) {
                $sdgs = array_merge($sdgs, $project->problem->sdg_categories);
            }
        }
        
        return count(array_unique($sdgs));
    }

    /**
     * helper: hitung total impact hours dari projects
     */
    private function calculateTotalImpactHours($completedProjects)
    {
        $totalHours = 0;
        
        foreach ($completedProjects as $project) {
            if ($project->actual_start_date && $project->actual_end_date) {
                $days = \Carbon\Carbon::parse($project->actual_start_date)
                    ->diffInDays(\Carbon\Carbon::parse($project->actual_end_date));
                
                // estimasi 8 jam per hari
                $totalHours += $days * 8;
            }
        }
        
        return $totalHours;
    }
}