<?php

namespace App\Services;

use App\Models\Problem;
use App\Models\Application;
use App\Models\Project;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * service untuk analytics dan statistik
 * menyediakan data untuk dashboard dan reporting
 */
class AnalyticsService
{
    /**
     * dapatkan analytics untuk institution dashboard
     */
    public function getInstitutionAnalytics($institutionId)
    {
        return [
            'problems' => $this->getProblemStats($institutionId),
            'applications' => $this->getApplicationStats($institutionId),
            'projects' => $this->getProjectStats($institutionId),
            'engagement' => $this->getEngagementStats($institutionId),
        ];
    }

    /**
     * statistik problems
     */
    private function getProblemStats($institutionId)
    {
        $total = Problem::where('institution_id', $institutionId)->count();
        $open = Problem::where('institution_id', $institutionId)->where('status', 'open')->count();
        $closed = Problem::where('institution_id', $institutionId)->where('status', 'closed')->count();
        $completed = Problem::where('institution_id', $institutionId)->where('status', 'completed')->count();

        // rata-rata aplikasi per problem
        $avgApplications = Problem::where('institution_id', $institutionId)
                                  ->withCount('applications')
                                  ->avg('applications_count') ?? 0;

        // total views - perbaikan: ganti view_count menjadi views_count
        $totalViews = Problem::where('institution_id', $institutionId)
                            ->sum('views_count');

        return [
            'total' => $total,
            'open' => $open,
            'closed' => $closed,
            'completed' => $completed,
            'average_applications' => round($avgApplications, 1),
            'total_views' => $totalViews,
        ];
    }

    /**
     * statistik applications
     */
    private function getApplicationStats($institutionId)
    {
        $applications = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        });

        $total = $applications->count();
        $pending = (clone $applications)->where('status', 'pending')->count();
        $underReview = (clone $applications)->where('status', 'under_review')->count();
        $accepted = (clone $applications)->where('status', 'accepted')->count();
        $rejected = (clone $applications)->where('status', 'rejected')->count();

        // acceptance rate
        $acceptanceRate = $total > 0 ? ($accepted / $total) * 100 : 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'under_review' => $underReview,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'acceptance_rate' => round($acceptanceRate, 1),
        ];
    }

    /**
     * statistik projects
     */
    private function getProjectStats($institutionId)
    {
        $total = Project::where('institution_id', $institutionId)->count();
        $active = Project::where('institution_id', $institutionId)->where('status', 'active')->count();
        $completed = Project::where('institution_id', $institutionId)->where('status', 'completed')->count();
        $onHold = Project::where('institution_id', $institutionId)->where('status', 'on_hold')->count();

        // rata-rata rating
        $avgRating = Project::where('institution_id', $institutionId)
                           ->whereNotNull('rating')
                           ->avg('rating') ?? 0;

        // completion rate
        $completionRate = $total > 0 ? ($completed / $total) * 100 : 0;

        // projects dengan rating tinggi (>= 4)
        $highRatedProjects = Project::where('institution_id', $institutionId)
                                   ->where('rating', '>=', 4)
                                   ->count();

        return [
            'total' => $total,
            'active' => $active,
            'completed' => $completed,
            'on_hold' => $onHold,
            'average_rating' => round($avgRating, 2),
            'completion_rate' => round($completionRate, 1),
            'high_rated' => $highRatedProjects,
        ];
    }

    /**
     * statistik engagement
     */
    private function getEngagementStats($institutionId)
    {
        // unique students yang apply
        $uniqueStudents = Application::whereHas('problem', function($q) use ($institutionId) {
                                     $q->where('institution_id', $institutionId);
                                 })
                                 ->distinct('student_id')
                                 ->count('student_id');

        // unique universities
        $uniqueUniversities = Student::whereHas('applications.problem', function($q) use ($institutionId) {
                                     $q->where('institution_id', $institutionId);
                                 })
                                 ->distinct('university_id')
                                 ->count('university_id');

        // total likes/wishlist
        $totalWishlists = DB::table('wishlists')
                           ->whereIn('problem_id', function($query) use ($institutionId) {
                               $query->select('id')
                                     ->from('problems')
                                     ->where('institution_id', $institutionId);
                           })
                           ->count();

        return [
            'unique_students' => $uniqueStudents,
            'unique_universities' => $uniqueUniversities,
            'total_wishlists' => $totalWishlists,
        ];
    }

    /**
     * dapatkan top problems berdasarkan jumlah aplikasi
     */
    public function getTopProblems($institutionId, $limit = 5)
    {
        // langsung gunakan kolom applications_count yang sudah ada di tabel
        // tidak perlu withCount karena kolom sudah di-maintain oleh sistem
        return Problem::where('institution_id', $institutionId)
                     ->orderBy('problems.applications_count', 'desc')
                     ->take($limit)
                     ->get();
    }

    /**
     * dapatkan time series data untuk chart
     */
    public function getTimeSeriesData($institutionId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        // applications per hari
        $applications = Application::whereHas('problem', function($q) use ($institutionId) {
                                    $q->where('institution_id', $institutionId);
                                })
                                ->where('created_at', '>=', $startDate)
                                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get()
                                ->keyBy('date')
                                ->map(fn($item) => $item->count);

        // views per hari - perbaikan: ganti view_count menjadi views_count
        $views = Problem::where('institution_id', $institutionId)
                       ->where('updated_at', '>=', $startDate)
                       ->selectRaw('DATE(updated_at) as date, SUM(views_count) as count')
                       ->groupBy('date')
                       ->orderBy('date')
                       ->get()
                       ->keyBy('date')
                       ->map(fn($item) => $item->count);

        // buat array untuk semua tanggal dengan default 0
        $dates = [];
        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - $i - 1)->format('Y-m-d');
            $dates[] = [
                'date' => $date,
                'applications' => $applications[$date] ?? 0,
                'views' => $views[$date] ?? 0,
            ];
        }

        return $dates;
    }

    /**
     * dapatkan application funnel untuk conversion metrics
     */
    public function getApplicationFunnel($institutionId)
    {
        // perbaikan: ganti view_count menjadi views_count
        $problemViews = Problem::where('institution_id', $institutionId)
                              ->sum('views_count');

        $applications = Application::whereHas('problem', function($q) use ($institutionId) {
                                    $q->where('institution_id', $institutionId);
                                })->count();

        $accepted = Application::whereHas('problem', function($q) use ($institutionId) {
                                $q->where('institution_id', $institutionId);
                            })
                            ->where('status', 'accepted')
                            ->count();

        $completed = Project::where('institution_id', $institutionId)
                           ->where('status', 'completed')
                           ->count();

        return [
            'views' => $problemViews,
            'applications' => $applications,
            'accepted' => $accepted,
            'completed' => $completed,
            'view_to_apply_rate' => $problemViews > 0 ? round(($applications / $problemViews) * 100, 1) : 0,
            'apply_to_accept_rate' => $applications > 0 ? round(($accepted / $applications) * 100, 1) : 0,
            'accept_to_complete_rate' => $accepted > 0 ? round(($completed / $accepted) * 100, 1) : 0,
        ];
    }

    /**
     * dapatkan analytics untuk student
     */
    public function getStudentAnalytics($studentId)
    {
        $totalApplications = Application::where('student_id', $studentId)->count();
        $acceptedApplications = Application::where('student_id', $studentId)
                                          ->where('status', 'accepted')
                                          ->count();
        $completedProjects = Project::where('student_id', $studentId)
                                   ->where('status', 'completed')
                                   ->count();
        $avgRating = Project::where('student_id', $studentId)
                           ->whereNotNull('rating')
                           ->avg('rating') ?? 0;

        return [
            'total_applications' => $totalApplications,
            'accepted_applications' => $acceptedApplications,
            'completed_projects' => $completedProjects,
            'average_rating' => round($avgRating, 2),
            'acceptance_rate' => $totalApplications > 0 
                ? round(($acceptedApplications / $totalApplications) * 100, 1) 
                : 0,
        ];
    }

    /**
     * dapatkan problems berdasarkan kategori sdg
     */
    public function getProblemsBySdgCategory($institutionId)
    {
        return Problem::where('institution_id', $institutionId)
                     ->selectRaw('sdg_category, COUNT(*) as count')
                     ->groupBy('sdg_category')
                     ->get()
                     ->map(function($item) {
                         return [
                             'category' => $item->sdg_category,
                             'count' => $item->count,
                         ];
                     });
    }

    /**
     * export full report untuk institution
     */
    public function exportFullReport($institutionId, $format = 'json')
    {
        $analytics = $this->getInstitutionAnalytics($institutionId);
        $topProblems = $this->getTopProblems($institutionId, 10);
        $timeSeriesData = $this->getTimeSeriesData($institutionId, 90);
        $applicationFunnel = $this->getApplicationFunnel($institutionId);
        $sdgDistribution = $this->getProblemsBySdgCategory($institutionId);

        $report = [
            'generated_at' => Carbon::now()->toIso8601String(),
            'institution_id' => $institutionId,
            'analytics' => $analytics,
            'top_problems' => $topProblems,
            'time_series_data' => $timeSeriesData,
            'application_funnel' => $applicationFunnel,
            'sdg_distribution' => $sdgDistribution,
        ];

        return $report;
    }
}