<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Application;

/**
 * controller untuk dashboard instansi
 */
class DashboardController extends Controller
{
    /**
     * tampilkan dashboard instansi
     */
    public function index()
    {
        $institution = auth()->user()->institution;

        // statistik dashboard
        $stats = [
            'total_problems' => Problem::where('institution_id', $institution->id)->count(),
            'open_problems' => Problem::where('institution_id', $institution->id)
                                     ->where('status', 'open')
                                     ->count(),
            'total_applications' => Application::whereHas('problem', function($q) use ($institution) {
                                        $q->where('institution_id', $institution->id);
                                    })->count(),
            'pending_applications' => Application::whereHas('problem', function($q) use ($institution) {
                                          $q->where('institution_id', $institution->id);
                                      })
                                      ->where('status', 'pending')
                                      ->count(),
        ];

        // recent problems
        $recentProblems = Problem::where('institution_id', $institution->id)
                                ->latest()
                                ->limit(5)
                                ->get();

        // recent applications
        $recentApplications = Application::with(['student.user', 'student.university', 'problem'])
                                        ->whereHas('problem', function($q) use ($institution) {
                                            $q->where('institution_id', $institution->id);
                                        })
                                        ->latest()
                                        ->limit(5)
                                        ->get();

        return view('institution.dashboard.index', compact(
            'stats',
            'recentProblems',
            'recentApplications',
            'institution'
        ));
    }
}