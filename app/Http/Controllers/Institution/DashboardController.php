<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Application;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * controller untuk dashboard institution
 * menampilkan ringkasan aktivitas, statistik problems, dan aplikasi
 * 
 * path: app/Http/Controllers/Institution/DashboardController.php
 */
class DashboardController extends Controller
{
    /**
     * tampilkan dashboard institution
     */
    public function index()
    {
        $institution = Auth::user()->institution;

        // urgent items yang perlu perhatian
        $urgentItems = [
            'pending_applications' => Application::whereHas('problem', function($q) use ($institution) {
                                                     $q->where('institution_id', $institution->id);
                                                 })
                                                 ->where('status', 'pending')
                                                 ->count(),
            'pending_reviews' => Project::where('institution_id', $institution->id)
                                       ->where('status', 'completed')
                                       ->whereDoesntHave('institutionReview')
                                       ->count(),
            'overdue_milestones' => DB::table('project_milestones')
                                      ->join('projects', 'project_milestones.project_id', '=', 'projects.id')
                                      ->where('projects.institution_id', $institution->id)
                                      ->where('project_milestones.status', '!=', 'completed')
                                      ->where('project_milestones.target_date', '<', Carbon::now())
                                      ->count(),
        ];

        // statistik utama dengan growth calculation
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;
        
        $stats = [
            'problems' => [
                'total' => Problem::where('institution_id', $institution->id)->count(),
                'active' => Problem::where('institution_id', $institution->id)->where('status', 'open')->count(),
                'growth' => $this->calculateGrowth(
                    Problem::where('institution_id', $institution->id)->whereMonth('created_at', $currentMonth)->count(),
                    Problem::where('institution_id', $institution->id)->whereMonth('created_at', $lastMonth)->count()
                ),
            ],
            'applications' => [
                'total' => Application::whereHas('problem', function($q) use ($institution) {
                              $q->where('institution_id', $institution->id);
                          })->count(),
                'pending' => Application::whereHas('problem', function($q) use ($institution) {
                                $q->where('institution_id', $institution->id);
                            })->where('status', 'pending')->count(),
                'growth' => $this->calculateGrowth(
                    Application::whereHas('problem', function($q) use ($institution) {
                        $q->where('institution_id', $institution->id);
                    })->whereMonth('created_at', $currentMonth)->count(),
                    Application::whereHas('problem', function($q) use ($institution) {
                        $q->where('institution_id', $institution->id);
                    })->whereMonth('created_at', $lastMonth)->count()
                ),
            ],
            'projects' => [
                'total' => Project::where('institution_id', $institution->id)->count(),
                'active' => Project::where('institution_id', $institution->id)->where('status', 'active')->count(),
                'completed' => Project::where('institution_id', $institution->id)->where('status', 'completed')->count(),
                'avg_progress' => Project::where('institution_id', $institution->id)
                                       ->where('status', 'active')
                                       ->avg('progress_percentage') ?? 0,
            ],
        ];

        // recent problems
        $recentProblems = Problem::where('institution_id', $institution->id)
                                ->with(['province', 'regency', 'images'])
                                ->withCount('applications')
                                ->latest()
                                ->take(5)
                                ->get();

        // pending applications yang perlu direview
        $pendingApplications = Application::whereHas('problem', function($q) use ($institution) {
                                             $q->where('institution_id', $institution->id);
                                         })
                                         ->with(['student.user', 'student.university', 'problem'])
                                         ->where('status', 'pending')
                                         ->latest()
                                         ->take(5)
                                         ->get();

        // recent applications (semua status)
        $recentApplications = Application::whereHas('problem', function($q) use ($institution) {
                                            $q->where('institution_id', $institution->id);
                                        })
                                        ->with(['student.user', 'student.university', 'problem'])
                                        ->latest()
                                        ->take(10)
                                        ->get();

        // active projects dengan progress
        $activeProjects = Project::where('institution_id', $institution->id)
                                ->where('status', 'active')
                                ->with(['student.user', 'problem', 'milestones'])
                                ->latest()
                                ->take(5)
                                ->get();

        // statistik aplikasi per bulan (3 bulan terakhir)
        // kompatibel dengan PostgreSQL
        $applicationsByMonth = Application::whereHas('problem', function($q) use ($institution) {
                                             $q->where('institution_id', $institution->id);
                                         })
                                         ->where('created_at', '>=', Carbon::now()->subMonths(3))
                                         ->select(
                                             DB::raw("TO_CHAR(created_at, 'YYYY-MM') as month"),
                                             DB::raw('COUNT(*) as count')
                                         )
                                         ->groupBy('month')
                                         ->orderBy('month')
                                         ->get();

        // statistik problems by status
        $problemsByStatus = Problem::where('institution_id', $institution->id)
                                  ->select('status', DB::raw('COUNT(*) as count'))
                                  ->groupBy('status')
                                  ->get()
                                  ->pluck('count', 'status');

        // notifications terbaru
        $notifications = Notification::where('user_id', Auth::id())
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // quick stats untuk chart
        $quickStats = [
            'problems_this_month' => Problem::where('institution_id', $institution->id)
                                           ->whereMonth('created_at', Carbon::now()->month)
                                           ->count(),
            'applications_this_month' => Application::whereHas('problem', function($q) use ($institution) {
                                                       $q->where('institution_id', $institution->id);
                                                   })
                                                   ->whereMonth('created_at', Carbon::now()->month)
                                                   ->count(),
            'projects_completed_this_month' => Project::where('institution_id', $institution->id)
                                                     ->where('status', 'completed')
                                                     ->whereMonth('updated_at', Carbon::now()->month)
                                                     ->count(),
        ];

        return view('institution.dashboard.index', compact(
            'institution',
            'urgentItems',
            'stats',
            'recentProblems',
            'pendingApplications',
            'recentApplications',
            'activeProjects',
            'applicationsByMonth',
            'problemsByStatus',
            'notifications',
            'quickStats'
        ));
    }
}