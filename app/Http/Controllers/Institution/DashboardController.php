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
                                       ->whereDoesntHave('review')
                                       ->count(),
            'overdue_milestones' => DB::table('project_milestones')
                                      ->join('projects', 'project_milestones.project_id', '=', 'projects.id')
                                      ->where('projects.institution_id', $institution->id)
                                      ->where('project_milestones.status', '!=', 'completed')
                                      ->where('project_milestones.target_date', '<', Carbon::now())
                                      ->count(),
        ];

        // statistik utama
        $stats = [
            'total_problems' => Problem::where('institution_id', $institution->id)->count(),
            'active_problems' => Problem::where('institution_id', $institution->id)
                                       ->where('status', 'open')
                                       ->count(),
            'total_applications' => Application::whereHas('problem', function($q) use ($institution) {
                                                  $q->where('institution_id', $institution->id);
                                              })
                                              ->count(),
            'pending_applications' => Application::whereHas('problem', function($q) use ($institution) {
                                                     $q->where('institution_id', $institution->id);
                                                 })
                                                 ->where('status', 'pending')
                                                 ->count(),
            'active_projects' => Project::where('institution_id', $institution->id)
                                       ->where('status', 'active')
                                       ->count(),
            'completed_projects' => Project::where('institution_id', $institution->id)
                                          ->where('status', 'completed')
                                          ->count(),
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
                                                     ->whereMonth('completed_at', Carbon::now()->month)
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