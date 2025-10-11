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

        // active projects dengan progress
        $activeProjects = Project::where('institution_id', $institution->id)
                                ->where('status', 'active')
                                ->with(['student.user', 'problem', 'milestones'])
                                ->latest()
                                ->take(5)
                                ->get();

        // statistik aplikasi per bulan (3 bulan terakhir)
        $applicationsByMonth = Application::whereHas('problem', function($q) use ($institution) {
                                             $q->where('institution_id', $institution->id);
                                         })
                                         ->where('created_at', '>=', Carbon::now()->subMonths(3))
                                         ->select(
                                             DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
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
            'stats',
            'recentProblems',
            'pendingApplications',
            'activeProjects',
            'applicationsByMonth',
            'problemsByStatus',
            'notifications',
            'quickStats'
        ));
    }
}