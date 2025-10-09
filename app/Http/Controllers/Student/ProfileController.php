<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Requests\UpdateStudentPasswordRequest;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            
            // upload foto baru
            $file = $request->file('profile_photo');
            $profilePhotoPath = $this->storageService->uploadProfilePhoto($file, $student->id);
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
        
        return redirect()->route('student.profile.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * update password student
     */
    public function updatePassword(UpdateStudentPasswordRequest $request)
    {
        $user = Auth::user();
        
        // update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return redirect()->route('student.profile.index')
            ->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * tampilkan public profile student
     * FIX ERROR #3: method ini sekarang sudah punya route student.profile.public
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
            'impact_metrics' => $this->calculateTotalImpact($completedProjects),
        ];
        
        return view('student.profile.public', compact('student', 'user', 'stats', 'completedProjects', 'achievements'));
    }

    /**
     * helper: hitung achievements berdasarkan projects dan reviews
     */
    private function calculateAchievements($student, $projects, $reviews)
    {
        $achievements = [];

        // project completion milestones
        if ($projects->count() >= 1) {
            $achievements[] = [
                'title' => 'First Project',
                'description' => 'Menyelesaikan proyek KKN pertama',
                'icon' => 'check-circle',
                'color' => 'green',
            ];
        }

        if ($projects->count() >= 3) {
            $achievements[] = [
                'title' => 'Experienced Volunteer',
                'description' => 'Menyelesaikan 3+ proyek KKN',
                'icon' => 'award',
                'color' => 'blue',
            ];
        }

        // rating achievements
        $averageRating = $reviews->isEmpty() ? 0 : $reviews->avg('rating');
        
        if ($averageRating >= 4.5 && $reviews->count() >= 3) {
            $achievements[] = [
                'title' => 'Excellence Award',
                'description' => 'Mendapat rating rata-rata 4.5+ dari 3+ review',
                'icon' => 'star',
                'color' => 'purple',
            ];
        }

        // SDG diversity
        $uniqueSDGs = $this->countUniqueSDGs($projects);
        if ($uniqueSDGs >= 5) {
            $achievements[] = [
                'title' => 'SDG Champion',
                'description' => 'Berkontribusi pada 5+ kategori SDG berbeda',
                'icon' => 'globe',
                'color' => 'green',
            ];
        }

        return $achievements;
    }

    /**
     * helper: hitung unique SDG categories
     */
    private function countUniqueSDGs($projects)
    {
        $allSDGs = [];

        foreach ($projects as $project) {
            if ($project->problem && $project->problem->sdg_categories) {
                $categories = $project->problem->sdg_categories;
                
                if (is_string($categories)) {
                    $categories = json_decode($categories, true);
                }
                
                if (is_array($categories)) {
                    $allSDGs = array_merge($allSDGs, $categories);
                }
            }
        }

        return count(array_unique($allSDGs));
    }

    /**
     * helper: hitung total impact metrics
     */
    private function calculateTotalImpact($projects)
    {
        $totalBeneficiaries = 0;

        foreach ($projects as $project) {
            if ($project->impact_metrics) {
                $metrics = is_array($project->impact_metrics) 
                    ? $project->impact_metrics 
                    : json_decode($project->impact_metrics, true) ?? [];
                
                $totalBeneficiaries += $metrics['beneficiaries'] ?? 0;
            }
        }

        return $totalBeneficiaries;
    }
}