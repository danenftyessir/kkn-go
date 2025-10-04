<?php

namespace App\Services;

use App\Models\Problem;
use App\Models\Application;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * service untuk analytics dan reporting institution
 */
class AnalyticsService
{
    /**
     * get dashboard overview statistics
     */
    public function getDashboardStats($institutionId)
    {
        return [
            'problems' => $this->getProblemStats($institutionId),
            'applications' => $this->getApplicationStats($institutionId),
            'projects' => $this->getProjectStats($institutionId),
            'reviews' => $this->getReviewStats($institutionId),
            'engagement' => $this->getEngagementStats($institutionId),
        ];
    }

    /**
     * statistik problems
     */
    private function getProblemStats($institutionId)
    {
        $total = Problem::where('institution_id', $institutionId)->count();
        $thisMonth = Problem::where('institution_id', $institutionId)
            ->whereMonth('created_at', now()->month)
            ->count();
        $lastMonth = Problem::where('institution_id', $institutionId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();

        return [
            'total' => $total,
            'draft' => Problem::where('institution_id', $institutionId)->where('status', 'draft')->count(),
            'open' => Problem::where('institution_id', $institutionId)->where('status', 'open')->count(),
            'in_progress' => Problem::where('institution_id', $institutionId)->where('status', 'in_progress')->count(),
            'completed' => Problem::where('institution_id', $institutionId)->where('status', 'completed')->count(),
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'growth' => $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0,
            'total_views' => Problem::where('institution_id', $institutionId)->sum('views_count'),
            'avg_views_per_problem' => $total > 0 ? Problem::where('institution_id', $institutionId)->avg('views_count') : 0,
        ];
    }

    /**
     * statistik applications
     */
    private function getApplicationStats($institutionId)
    {
        $query = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        });

        $total = $query->count();
        $thisMonth = (clone $query)->whereMonth('created_at', now()->month)->count();
        $lastMonth = (clone $query)->whereMonth('created_at', now()->subMonth()->month)->count();

        return [
            'total' => $total,
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'under_review' => (clone $query)->where('status', 'under_review')->count(),
            'accepted' => (clone $query)->where('status', 'accepted')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'growth' => $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0,
            'acceptance_rate' => $total > 0 ? ((clone $query)->where('status', 'accepted')->count() / $total) * 100 : 0,
            'avg_review_time' => $this->getAverageReviewTime($institutionId),
        ];
    }

    /**
     * statistik projects
     */
    private function getProjectStats($institutionId)
    {
        $total = Project::where('institution_id', $institutionId)->count();
        $completed = Project::where('institution_id', $institutionId)->where('status', 'completed')->count();

        return [
            'total' => $total,
            'planning' => Project::where('institution_id', $institutionId)->where('status', 'planning')->count(),
            'active' => Project::where('institution_id', $institutionId)->where('status', 'active')->count(),
            'review' => Project::where('institution_id', $institutionId)->where('status', 'review')->count(),
            'completed' => $completed,
            'cancelled' => Project::where('institution_id', $institutionId)->where('status', 'cancelled')->count(),
            'completion_rate' => $total > 0 ? ($completed / $total) * 100 : 0,
            'avg_progress' => Project::where('institution_id', $institutionId)->avg('progress_percentage') ?? 0,
        ];
    }

    /**
     * statistik reviews
     */
    private function getReviewStats($institutionId)
    {
        $query = Review::where('reviewer_type', 'institution')
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            });

        $total = $query->count();

        return [
            'total' => $total,
            'average_rating' => $query->avg('rating') ?? 0,
            'five_star' => (clone $query)->where('rating', 5)->count(),
            'four_star' => (clone $query)->where('rating', 4)->count(),
            'three_star' => (clone $query)->where('rating', 3)->count(),
            'two_star' => (clone $query)->where('rating', 2)->count(),
            'one_star' => (clone $query)->where('rating', 1)->count(),
            'pending_reviews' => Project::where('institution_id', $institutionId)
                ->where('status', 'completed')
                ->whereNull('rating')
                ->count(),
        ];
    }

    /**
     * statistik engagement
     */
    private function getEngagementStats($institutionId)
    {
        $problems = Problem::where('institution_id', $institutionId)->get();
        $totalViews = $problems->sum('views_count');
        $totalApplications = $problems->sum('applications_count');

        return [
            'total_views' => $totalViews,
            'total_applications' => $totalApplications,
            'avg_applications_per_problem' => $problems->count() > 0 ? $totalApplications / $problems->count() : 0,
            'conversion_rate' => $totalViews > 0 ? ($totalApplications / $totalViews) * 100 : 0,
        ];
    }

    /**
     * get time series data untuk grafik (30 hari terakhir)
     */
    public function getTimeSeriesData($institutionId, $days = 30)
    {
        $dates = collect();
        $startDate = Carbon::now()->subDays($days);

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dates->push($date->format('Y-m-d'));
        }

        // applications per day
        $applicationsPerDay = Application::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereHas('problem', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        // views per day (kita perlu tracking views per day di database)
        // untuk sekarang kita estimate dari problem views

        // projects completed per day
        $projectsPerDay = Project::select(
                DB::raw('DATE(updated_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('institution_id', $institutionId)
            ->where('status', 'completed')
            ->where('updated_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        return [
            'dates' => $dates,
            'applications' => $dates->mapWithKeys(fn($date) => [$date => $applicationsPerDay->get($date, 0)]),
            'projects_completed' => $dates->mapWithKeys(fn($date) => [$date => $projectsPerDay->get($date, 0)]),
        ];
    }

    /**
     * get top performing problems (most applications)
     */
    public function getTopProblems($institutionId, $limit = 10)
    {
        return Problem::where('institution_id', $institutionId)
            ->orderBy('applications_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * get problem performance by category (SDG)
     */
    public function getProblemsBySdgCategory($institutionId)
    {
        $problems = Problem::where('institution_id', $institutionId)->get();
        
        $sdgStats = [];
        
        foreach ($problems as $problem) {
            $sdgCategories = is_array($problem->sdg_categories) 
                ? $problem->sdg_categories 
                : json_decode($problem->sdg_categories, true) ?? [];
            
            foreach ($sdgCategories as $sdg) {
                if (!isset($sdgStats[$sdg])) {
                    $sdgStats[$sdg] = [
                        'count' => 0,
                        'applications' => 0,
                        'views' => 0,
                    ];
                }
                
                $sdgStats[$sdg]['count']++;
                $sdgStats[$sdg]['applications'] += $problem->applications_count;
                $sdgStats[$sdg]['views'] += $problem->views_count;
            }
        }

        return $sdgStats;
    }

    /**
     * get application funnel (conversion rates)
     */
    public function getApplicationFunnel($institutionId)
    {
        $totalProblems = Problem::where('institution_id', $institutionId)
            ->where('status', 'open')
            ->count();
        
        $totalViews = Problem::where('institution_id', $institutionId)
            ->where('status', 'open')
            ->sum('views_count');
        
        $totalApplications = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })->count();
        
        $accepted = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })->where('status', 'accepted')->count();

        return [
            'problems_published' => $totalProblems,
            'total_views' => $totalViews,
            'total_applications' => $totalApplications,
            'accepted_applications' => $accepted,
            'view_to_apply_rate' => $totalViews > 0 ? ($totalApplications / $totalViews) * 100 : 0,
            'apply_to_accept_rate' => $totalApplications > 0 ? ($accepted / $totalApplications) * 100 : 0,
        ];
    }

    /**
     * get average review time (dari application submit sampai reviewed)
     */
    private function getAverageReviewTime($institutionId)
    {
        $applications = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })
        ->whereNotNull('reviewed_at')
        ->get();

        if ($applications->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        
        foreach ($applications as $application) {
            $createdAt = Carbon::parse($application->created_at);
            $reviewedAt = Carbon::parse($application->reviewed_at);
            $totalHours += $createdAt->diffInHours($reviewedAt);
        }

        return round($totalHours / $applications->count(), 2);
    }

    /**
     * get student university distribution
     */
    public function getUniversityDistribution($institutionId)
    {
        return Application::select('universities.name', DB::raw('COUNT(*) as count'))
            ->join('students', 'applications.student_id', '=', 'students.id')
            ->join('universities', 'students.university_id', '=', 'universities.id')
            ->whereHas('problem', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->where('applications.status', 'accepted')
            ->groupBy('universities.name')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * get geographic distribution of problems
     */
    public function getGeographicDistribution($institutionId)
    {
        return Problem::select('provinces.name', DB::raw('COUNT(*) as count'))
            ->join('provinces', 'problems.province_id', '=', 'provinces.id')
            ->where('problems.institution_id', $institutionId)
            ->groupBy('provinces.name')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * export full report untuk download
     */
    public function exportFullReport($institutionId, $format = 'array')
    {
        $report = [
            'generated_at' => now()->toDateTimeString(),
            'institution_id' => $institutionId,
            'overview' => $this->getDashboardStats($institutionId),
            'top_problems' => $this->getTopProblems($institutionId, 10),
            'sdg_distribution' => $this->getProblemsBySdgCategory($institutionId),
            'university_distribution' => $this->getUniversityDistribution($institutionId),
            'geographic_distribution' => $this->getGeographicDistribution($institutionId),
            'application_funnel' => $this->getApplicationFunnel($institutionId),
            'time_series' => $this->getTimeSeriesData($institutionId, 30),
        ];

        if ($format === 'json') {
            return json_encode($report, JSON_PRETTY_PRINT);
        }

        return $report;
    }
}