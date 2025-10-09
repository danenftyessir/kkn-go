<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Application;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * controller untuk dashboard instansi
 * menampilkan ringkasan aktivitas, statistik, dan quick access
 */
class DashboardController extends Controller
{
    /**
     * tampilkan dashboard instansi
     */
    public function index()
    {
        $institution = Auth::user()->institution;

        // statistik problems
        $problemsStats = [
            'total' => Problem::where('institution_id', $institution->id)->count(),
            'draft' => Problem::where('institution_id', $institution->id)
                             ->where('status', 'draft')
                             ->count(),
            'open' => Problem::where('institution_id', $institution->id)
                            ->where('status', 'open')
                            ->count(),
            'in_progress' => Problem::where('institution_id', $institution->id)
                                   ->where('status', 'in_progress')
                                   ->count(),
            'completed' => Problem::where('institution_id', $institution->id)
                                 ->where('status', 'completed')
                                 ->count(),
        ];

        // statistik applications
        $totalApplications = Application::whereHas('problem', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })->count();
        
        $pendingApplications = Application::whereHas('problem', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })->where('status', 'pending')->count();
        
        $acceptedApplications = Application::whereHas('problem', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })->where('status', 'accepted')->count();

        $applicationsStats = [
            'total' => $totalApplications,
            'pending' => $pendingApplications,
            'accepted' => $acceptedApplications,
            'acceptance_rate' => $totalApplications > 0 
                ? ($acceptedApplications / $totalApplications) * 100 
                : 0,
        ];

        // statistik projects
        $totalProjects = Project::where('institution_id', $institution->id)->count();
        $activeProjects = Project::where('institution_id', $institution->id)
                                ->where('status', 'active')
                                ->count();
        $completedProjects = Project::where('institution_id', $institution->id)
                                   ->where('status', 'completed')
                                   ->count();
        
        // hitung rata-rata progress dari active projects
        $activeProjectsWithProgress = Project::where('institution_id', $institution->id)
                                            ->where('status', 'active')
                                            ->get();
        
        $avgProgress = 0;
        if ($activeProjectsWithProgress->count() > 0) {
            $totalProgress = 0;
            foreach ($activeProjectsWithProgress as $project) {
                $totalMilestones = $project->milestones()->count();
                $completedMilestones = $project->milestones()->where('status', 'completed')->count();
                $progress = $totalMilestones > 0 ? ($completedMilestones / $totalMilestones) * 100 : 0;
                $totalProgress += $progress;
            }
            $avgProgress = $totalProgress / $activeProjectsWithProgress->count();
        }

        $projectsStats = [
            'total' => $totalProjects,
            'active' => $activeProjects,
            'completed' => $completedProjects,
            'avg_progress' => $avgProgress,
        ];

        // recent applications (5 terbaru)
        $recentApplications = Application::with([
            'student.user',
            'student.university',
            'problem'
        ])
        ->whereHas('problem', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })
        ->orderBy('applied_at', 'desc')
        ->take(5)
        ->get();

        // active projects (5 terbaru)
        $activeProjectsList = Project::with([
            'student.user',
            'student.university',
            'problem',
            'milestones'
        ])
        ->where('institution_id', $institution->id)
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        // recent reviews dari mahasiswa
        $recentReviews = Review::with([
            'reviewer',
            'project.problem'
        ])
        ->where('type', 'student_to_institution')
        ->whereHas('project', function($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        // problems yang membutuhkan perhatian (deadline dekat atau aplikasi banyak)
        $problemsNeedAttention = Problem::where('institution_id', $institution->id)
            ->where('status', 'open')
            ->where('deadline', '<=', Carbon::now()->addDays(7))
            ->withCount('applications')
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();

        return view('institution.dashboard.index', compact(
            'problemsStats',
            'applicationsStats',
            'projectsStats',
            'recentApplications',
            'activeProjectsList',
            'recentReviews',
            'problemsNeedAttention'
        ));
    }
}