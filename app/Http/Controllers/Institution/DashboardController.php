<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Application;
use App\Models\Problem;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * controller untuk dashboard mahasiswa
 * menampilkan ringkasan aktivitas, recommendations, dan quick access
 * 
 * path: app/Http/Controllers/Student/DashboardController.php
 */
class DashboardController extends Controller
{
    /**
     * tampilkan dashboard mahasiswa
     */
    public function index()
    {
        $student = Auth::user()->student;

        // statistik utama
        $stats = [
            'active_projects' => Project::where('student_id', $student->id)
                                       ->where('status', 'active')
                                       ->count(),
            'total_applications' => Application::where('student_id', $student->id)->count(),
            'pending_applications' => Application::where('student_id', $student->id)
                                                 ->where('status', 'pending')
                                                 ->count(),
            'completed_projects' => Project::where('student_id', $student->id)
                                          ->where('status', 'completed')
                                          ->count(),
        ];

        // active projects dengan progress
        $activeProjects = Project::where('student_id', $student->id)
                                ->where('status', 'active')
                                ->with(['problem', 'problem.institution', 'milestones'])
                                ->latest()
                                ->take(3)
                                ->get();

        // recent applications
        $recentApplications = Application::where('student_id', $student->id)
                                        ->with(['problem.institution', 'problem.province', 'problem.regency'])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        // recommended problems berdasarkan jurusan dan skills
        $recommendedProblems = Problem::where('status', 'open')
                                     ->where('application_deadline', '>=', Carbon::now())
                                     ->with(['institution', 'province', 'regency', 'images'])
                                     ->when($student->major, function($query) use ($student) {
                                         // filter berdasarkan jurusan jika ada
                                         $query->where(function($q) use ($student) {
                                             $q->whereJsonContains('required_majors', $student->major)
                                               ->orWhereNull('required_majors');
                                         });
                                     })
                                     ->withCount('applications')
                                     ->latest()
                                     ->take(4)
                                     ->get();

        // notifikasi terbaru
        $notifications = Notification::where('user_id', Auth::id())
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // unread notifications count
        $unreadCount = Notification::where('user_id', Auth::id())
                                  ->where('is_read', false)
                                  ->count();

        return view('student.dashboard.index', compact(
            'stats',
            'activeProjects',
            'recentApplications',
            'recommendedProblems',
            'notifications',
            'unreadCount',
            'student'
        ));
    }
}