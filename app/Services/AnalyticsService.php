<?php

namespace App\Services;

use App\Models\Problem;
use App\Models\Application;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * service untuk analytics dan statistik
 * digunakan di dashboard institution dan student
 */
class AnalyticsService
{
    /**
     * get comprehensive analytics untuk institution dashboard
     */
    public function getInstitutionAnalytics($institutionId)
    {
        return [
            'problems' => $this->getProblemStats($institutionId),
            'applications' => $this->getApplicationStats($institutionId),
            'projects' => $this->getProjectStats($institutionId),
            'reviews' => $this->getReviewStats($institutionId),
            'impact' => $this->getImpactStats($institutionId),
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
            'closed' => Problem::where('institution_id', $institutionId)->where('status', 'closed')->count(),
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
     * ✅ PERBAIKAN: ganti reviewer_type dengan type
     */
    private function getReviewStats($institutionId)
    {
        // ✅ PERBAIKAN: ganti where('reviewer_type', 'institution') dengan where('type', 'institution_to_student')
        $query = Review::where('type', 'institution_to_student')
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
     * statistik impact
     */
    private function getImpactStats($institutionId)
    {
        $projects = Project::where('institution_id', $institutionId)
                          ->where('status', 'completed')
                          ->get();

        $totalBeneficiaries = 0;
        $totalActivities = 0;

        foreach ($projects as $project) {
            if ($project->impact_metrics) {
                $metrics = is_array($project->impact_metrics) 
                    ? $project->impact_metrics 
                    : json_decode($project->impact_metrics, true) ?? [];
                
                $totalBeneficiaries += $metrics['beneficiaries'] ?? 0;
                $totalActivities += $metrics['activities'] ?? 0;
            }
        }

        return [
            'total_beneficiaries' => $totalBeneficiaries,
            'total_activities' => $totalActivities,
            'sdgs_addressed' => $this->getUniqueSDGsAddressed($institutionId),
            'students_collaborated' => Project::where('institution_id', $institutionId)
                                            ->distinct('student_id')
                                            ->count('student_id'),
        ];
    }

    /**
     * get average review time untuk applications
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
        foreach ($applications as $app) {
            $totalHours += $app->applied_at->diffInHours($app->reviewed_at);
        }

        return round($totalHours / $applications->count(), 1);
    }

    /**
     * get unique SDGs addressed
     */
    private function getUniqueSDGsAddressed($institutionId)
    {
        $problems = Problem::where('institution_id', $institutionId)
                          ->where('status', 'completed')
                          ->get();

        $allSDGs = [];
        foreach ($problems as $problem) {
            if ($problem->sdg_categories) {
                $categories = is_array($problem->sdg_categories) 
                    ? $problem->sdg_categories 
                    : json_decode($problem->sdg_categories, true) ?? [];
                
                $allSDGs = array_merge($allSDGs, $categories);
            }
        }

        return count(array_unique($allSDGs));
    }

    /**
     * get time series data untuk chart (applications per day)
     */
    public function getTimeSeriesData($institutionId, $days = 30)
    {
        $startDate = now()->subDays($days);
        
        $data = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })
        ->where('created_at', '>=', $startDate)
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date')
        ->toArray();

        // fill missing dates dengan 0
        $result = [];
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($days - $i - 1)->format('Y-m-d');
            $result[$date] = $data[$date] ?? 0;
        }

        return $result;
    }

    /**
     * get top problems berdasarkan applications
     */
    public function getTopProblems($institutionId, $limit = 5)
    {
        return Problem::where('institution_id', $institutionId)
            ->orderBy('applications_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * get application funnel untuk conversion metrics
     */
    public function getApplicationFunnel($institutionId)
    {
        $total = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })->count();

        $reviewed = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })->whereNotNull('reviewed_at')->count();

        $accepted = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })->where('status', 'accepted')->count();

        return [
            'total' => $total,
            'reviewed' => $reviewed,
            'accepted' => $accepted,
            'review_rate' => $total > 0 ? ($reviewed / $total) * 100 : 0,
            'acceptance_rate' => $reviewed > 0 ? ($accepted / $reviewed) * 100 : 0,
        ];
    }
}