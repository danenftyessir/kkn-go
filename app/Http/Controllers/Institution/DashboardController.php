<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Application;
use App\Models\Problem;
use App\Models\Notification;
use App\Models\ProjectMilestone;
use App\Models\Wishlist;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * controller untuk dashboard mahasiswa
 * menampilkan ringkasan aktivitas, recommendations, dan quick access
 * 
 * path: app/Http/Controllers/Student/DashboardController.php
 */
class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * tampilkan dashboard mahasiswa
     */
    public function index()
    {
        $student = Auth::user()->student;
        $userId = Auth::id();

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
            'accepted_applications' => Application::where('student_id', $student->id)
                                                  ->where('status', 'accepted')
                                                  ->count(),
            'rejected_applications' => Application::where('student_id', $student->id)
                                                  ->where('status', 'rejected')
                                                  ->count(),
            'wishlist_count' => Wishlist::where('student_id', $student->id)->count(),
        ];

        // active projects dengan progress dan milestones
        $activeProjects = Project::where('student_id', $student->id)
                                ->where('status', 'active')
                                ->with([
                                    'problem.institution', 
                                    'problem.province', 
                                    'problem.regency',
                                    'milestones' => function($query) {
                                        $query->orderBy('target_date', 'asc');
                                    }
                                ])
                                ->latest()
                                ->take(3)
                                ->get()
                                ->map(function($project) {
                                    // hitung progress berdasarkan milestones
                                    $totalMilestones = $project->milestones->count();
                                    $completedMilestones = $project->milestones->where('status', 'completed')->count();
                                    
                                    $project->progress = $totalMilestones > 0 
                                        ? round(($completedMilestones / $totalMilestones) * 100) 
                                        : 0;
                                    
                                    return $project;
                                });

        // recent applications dengan detail
        $recentApplications = Application::where('student_id', $student->id)
                                        ->with([
                                            'problem.institution', 
                                            'problem.province', 
                                            'problem.regency',
                                            'problem.images' => function($query) {
                                                $query->where('is_cover', true);
                                            }
                                        ])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        // recommended problems berdasarkan jurusan, skills, dan lokasi
        $recommendedProblems = $this->getRecommendedProblems($student);

        // upcoming milestones dari semua active projects
        $upcomingMilestones = ProjectMilestone::whereHas('project', function($query) use ($student) {
                                                $query->where('student_id', $student->id)
                                                      ->where('status', 'active');
                                            })
                                            ->where('status', '!=', 'completed')
                                            ->where('target_date', '>=', Carbon::now())
                                            ->with(['project.problem.institution'])
                                            ->orderBy('target_date', 'asc')
                                            ->take(5)
                                            ->get();

        // overdue milestones (milestones yang terlewat deadline)
        $overdueMilestones = ProjectMilestone::whereHas('project', function($query) use ($student) {
                                                $query->where('student_id', $student->id)
                                                      ->where('status', 'active');
                                            })
                                            ->where('status', '!=', 'completed')
                                            ->where('target_date', '<', Carbon::now())
                                            ->with(['project.problem.institution'])
                                            ->orderBy('target_date', 'desc')
                                            ->take(3)
                                            ->get();

        // notifikasi terbaru (unread notifications)
        $unreadNotifications = Notification::where('user_id', $userId)
                                          ->where('is_read', false)
                                          ->latest()
                                          ->take(5)
                                          ->get();

        // total unread notifications count
        $unreadCount = Notification::where('user_id', $userId)
                                  ->where('is_read', false)
                                  ->count();

        // all recent notifications untuk sidebar
        $notifications = Notification::where('user_id', $userId)
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // profile completion check
        $profileCompletion = $this->calculateProfileCompletion($student);

        // analytics data untuk student
        $analytics = $this->analyticsService->getStudentAnalytics($student->id);

        // application success rate
        $applicationStats = [
            'total' => $stats['total_applications'],
            'pending' => $stats['pending_applications'],
            'accepted' => $stats['accepted_applications'],
            'rejected' => $stats['rejected_applications'],
            'success_rate' => $stats['total_applications'] > 0 
                ? round(($stats['accepted_applications'] / $stats['total_applications']) * 100, 1) 
                : 0,
        ];

        // trending problems (populer minggu ini)
        $trendingProblems = Problem::where('status', 'open')
                                  ->where('application_deadline', '>=', Carbon::now())
                                  ->withCount([
                                      'applications' => function($query) {
                                          $query->where('created_at', '>=', Carbon::now()->subWeek());
                                      }
                                  ])
                                  ->with(['institution', 'province', 'regency', 'images'])
                                  ->orderBy('applications_count', 'desc')
                                  ->take(3)
                                  ->get();

        // deadlines approaching (problems dengan deadline < 7 hari)
        $approachingDeadlines = Problem::where('status', 'open')
                                      ->where('application_deadline', '>=', Carbon::now())
                                      ->where('application_deadline', '<=', Carbon::now()->addWeek())
                                      ->whereDoesntHave('applications', function($query) use ($student) {
                                          $query->where('student_id', $student->id);
                                      })
                                      ->with(['institution', 'province', 'regency', 'images'])
                                      ->orderBy('application_deadline', 'asc')
                                      ->take(3)
                                      ->get();

        // wishlist problems
        $wishlistProblems = Wishlist::where('student_id', $student->id)
                                   ->with([
                                       'problem.institution',
                                       'problem.province',
                                       'problem.regency',
                                       'problem.images'
                                   ])
                                   ->latest()
                                   ->take(3)
                                   ->get()
                                   ->pluck('problem');

        // recent activities timeline
        $recentActivities = $this->getRecentActivities($student);

        // sdgs yang pernah dikerjakan
        $sdgsWorkedOn = Project::where('student_id', $student->id)
                              ->where('status', 'completed')
                              ->with('problem')
                              ->get()
                              ->pluck('problem.sdg_category')
                              ->unique()
                              ->filter()
                              ->values();

        return view('student.dashboard.index', compact(
            'student',
            'stats',
            'activeProjects',
            'recentApplications',
            'recommendedProblems',
            'upcomingMilestones',
            'overdueMilestones',
            'unreadNotifications',
            'unreadCount',
            'notifications',
            'profileCompletion',
            'analytics',
            'applicationStats',
            'trendingProblems',
            'approachingDeadlines',
            'wishlistProblems',
            'recentActivities',
            'sdgsWorkedOn'
        ));
    }

    /**
     * dapatkan recommended problems untuk student
     */
    private function getRecommendedProblems($student)
    {
        $query = Problem::where('status', 'open')
                       ->where('application_deadline', '>=', Carbon::now())
                       ->with(['institution', 'province', 'regency', 'images'])
                       ->withCount('applications');

        // filter berdasarkan jurusan jika ada
        if ($student->major) {
            $query->where(function($q) use ($student) {
                $q->whereJsonContains('required_majors', $student->major)
                  ->orWhereNull('required_majors');
            });
        }

        // prioritaskan problems di provinsi yang sama
        if ($student->university && $student->university->province_id) {
            $query->orderByRaw('CASE WHEN province_id = ? THEN 0 ELSE 1 END', [$student->university->province_id]);
        }

        // filter berdasarkan skills jika ada
        if ($student->skills) {
            $skills = is_array($student->skills) ? $student->skills : json_decode($student->skills, true);
            if (!empty($skills)) {
                $query->where(function($q) use ($skills) {
                    foreach ($skills as $skill) {
                        $q->orWhereJsonContains('required_skills', $skill);
                    }
                });
            }
        }

        return $query->latest()
                    ->take(4)
                    ->get();
    }

    /**
     * hitung persentase kelengkapan profil
     */
    private function calculateProfileCompletion($student)
    {
        $fields = [
            'profile_photo' => !empty($student->profile_photo_path) ? 1 : 0,
            'bio' => !empty($student->bio) ? 1 : 0,
            'skills' => (!empty($student->skills) && is_array($student->skills) && count($student->skills) > 0) ? 1 : 0,
            'whatsapp' => !empty($student->whatsapp_number) ? 1 : 0,
            'semester' => !empty($student->semester) ? 1 : 0,
            'nim' => !empty($student->nim) ? 1 : 0,
            'major' => !empty($student->major) ? 1 : 0,
        ];

        $completed = array_sum($fields);
        $total = count($fields);
        $percentage = ($completed / $total) * 100;

        // dapatkan missing fields
        $missingFields = [];
        foreach ($fields as $field => $value) {
            if ($value === 0) {
                $missingFields[] = $this->getFieldLabel($field);
            }
        }

        return [
            'percentage' => round($percentage),
            'completed' => $completed,
            'total' => $total,
            'fields' => $fields,
            'missing_fields' => $missingFields,
            'is_complete' => $percentage == 100,
        ];
    }

    /**
     * dapatkan label untuk field profil
     */
    private function getFieldLabel($field)
    {
        $labels = [
            'profile_photo' => 'Foto Profil',
            'bio' => 'Bio/Deskripsi',
            'skills' => 'Skills & Keahlian',
            'whatsapp' => 'Nomor WhatsApp',
            'semester' => 'Semester',
            'nim' => 'NIM',
            'major' => 'Jurusan',
        ];

        return $labels[$field] ?? $field;
    }

    /**
     * dapatkan recent activities untuk timeline
     */
    private function getRecentActivities($student)
    {
        $activities = collect();

        // ambil aplikasi terbaru
        $recentApps = Application::where('student_id', $student->id)
                                ->with('problem')
                                ->latest()
                                ->take(3)
                                ->get()
                                ->map(function($app) {
                                    return [
                                        'type' => 'application',
                                        'title' => 'Melamar ke ' . $app->problem->title,
                                        'description' => 'Status: ' . ucfirst($app->status),
                                        'timestamp' => $app->created_at,
                                        'icon' => 'document',
                                        'color' => $this->getStatusColor($app->status),
                                    ];
                                });

        // ambil milestone yang baru selesai
        $recentMilestones = ProjectMilestone::whereHas('project', function($query) use ($student) {
                                            $query->where('student_id', $student->id);
                                        })
                                        ->where('status', 'completed')
                                        ->with('project.problem')
                                        ->latest('updated_at')
                                        ->take(3)
                                        ->get()
                                        ->map(function($milestone) {
                                            return [
                                                'type' => 'milestone',
                                                'title' => 'Menyelesaikan milestone: ' . $milestone->title,
                                                'description' => $milestone->project->problem->title,
                                                'timestamp' => $milestone->updated_at,
                                                'icon' => 'check',
                                                'color' => 'green',
                                            ];
                                        });

        // gabung dan sort berdasarkan timestamp
        $activities = $activities->merge($recentApps)
                               ->merge($recentMilestones)
                               ->sortByDesc('timestamp')
                               ->take(5)
                               ->values();

        return $activities;
    }

    /**
     * dapatkan warna berdasarkan status
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'yellow',
            'reviewed' => 'blue',
            'accepted' => 'green',
            'rejected' => 'red',
            'active' => 'blue',
            'completed' => 'green',
        ];

        return $colors[$status] ?? 'gray';
    }
}