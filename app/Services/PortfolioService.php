<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Student;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

/**
 * service untuk mengelola portfolio mahasiswa
 * handle business logic untuk portfolio display dan management
 * 
 * path: app/Services/PortfolioService.php
 */
class PortfolioService
{
    /**
     * get portfolio data untuk mahasiswa
     */
    public function getPortfolioData($studentId)
    {
        $student = Student::with('user', 'university')->findOrFail($studentId);

        // ambil completed projects dengan sorting berdasarkan updated_at
        $completedProjects = Project::with([
            'problem.institution',
            'problem.province',
            'problem.regency',
            'institutionReview'
        ])
        ->where('student_id', $studentId)
        ->where('status', 'completed')
        ->orderBy('updated_at', 'desc')
        ->get();

        // ambil reviews dari institution untuk projects
        $reviews = Review::where('type', 'institution_to_student')
            ->whereIn('project_id', $completedProjects->pluck('id'))
            ->get();

        // hitung statistics
        $statistics = $this->calculateStatistics($completedProjects, $reviews);

        // extract skills dan SDG categories
        $skills = $this->extractSkills($completedProjects);
        $sdgCategories = $this->extractSDGCategories($completedProjects);

        // FIXED: get achievements dengan 3 parameter
        $achievements = $this->getAchievements($student, $completedProjects, $reviews);

        return [
            'student' => $student,
            'projects' => $completedProjects,
            'statistics' => $statistics,
            'skills' => $skills,
            'sdg_categories' => $sdgCategories,
            'achievements' => $achievements,
        ];
    }

    /**
     * calculate statistics dari completed projects
     */
    protected function calculateStatistics($projects, $reviews)
    {
        // hitung average rating
        $averageRating = $reviews->isEmpty() ? 0 : $reviews->avg('rating');

        // hitung total impact
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
            'total_projects' => $projects->count(),
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $reviews->count(),
            'total_beneficiaries' => $totalBeneficiaries,
            'total_activities' => $totalActivities,
            'sdgs_addressed' => $this->extractSDGCategories($projects)->count(),
        ];
    }

    /**
     * extract unique skills dari completed projects
     */
    protected function extractSkills($projects)
    {
        $allSkills = [];

        foreach ($projects as $project) {
            if ($project->problem && $project->problem->required_skills) {
                $skills = is_array($project->problem->required_skills) 
                    ? $project->problem->required_skills 
                    : json_decode($project->problem->required_skills, true) ?? [];
                
                $allSkills = array_merge($allSkills, $skills);
            }
        }

        return collect(array_unique($allSkills))->take(15);
    }

    /**
     * extract unique SDG categories dari projects
     */
    protected function extractSDGCategories($projects)
    {
        $allSDGs = [];

        foreach ($projects as $project) {
            if ($project->problem && $project->problem->sdg_categories) {
                $sdgs = is_array($project->problem->sdg_categories) 
                    ? $project->problem->sdg_categories 
                    : json_decode($project->problem->sdg_categories, true) ?? [];
                
                $allSDGs = array_merge($allSDGs, $sdgs);
            }
        }

        return collect(array_unique($allSDGs));
    }

    /**
     * get portfolio achievements berdasarkan project dan review
     */
    public function getAchievements($student, $projects, $reviews)
    {
        $achievements = [];

        // project completion milestones
        if ($projects->count() >= 1) {
            $achievements[] = [
                'title' => 'First Project',
                'description' => 'Menyelesaikan proyek KKN pertama',
                'icon' => 'check-circle',
                'color' => 'green',
                'earned_at' => $projects->last()->updated_at
            ];
        }

        if ($projects->count() >= 3) {
            $achievements[] = [
                'title' => 'Experienced Volunteer',
                'description' => 'Menyelesaikan 3+ proyek KKN',
                'icon' => 'award',
                'color' => 'blue',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        if ($projects->count() >= 5) {
            $achievements[] = [
                'title' => 'Dedicated Contributor',
                'description' => 'Menyelesaikan 5+ proyek KKN',
                'icon' => 'trophy',
                'color' => 'gold',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        // rating achievements
        $averageRating = $reviews->isEmpty() ? 0 : $reviews->avg('rating');
        
        if ($averageRating >= 4.0 && $reviews->count() >= 2) {
            $achievements[] = [
                'title' => 'Quality Work',
                'description' => 'Mendapat rating rata-rata 4.0+ dari 2+ review',
                'icon' => 'star',
                'color' => 'yellow',
                'earned_at' => $reviews->first()->created_at
            ];
        }

        if ($averageRating >= 4.5 && $reviews->count() >= 3) {
            $achievements[] = [
                'title' => 'Excellence Award',
                'description' => 'Mendapat rating rata-rata 4.5+ dari 3+ review',
                'icon' => 'star',
                'color' => 'purple',
                'earned_at' => $reviews->first()->created_at
            ];
        }

        if ($averageRating >= 4.8 && $reviews->count() >= 5) {
            $achievements[] = [
                'title' => 'Outstanding Performance',
                'description' => 'Mendapat rating rata-rata 4.8+ dari 5+ review',
                'icon' => 'award',
                'color' => 'purple',
                'earned_at' => $reviews->first()->created_at
            ];
        }

        // SDG diversity
        $uniqueSDGs = $this->extractSDGCategories($projects);
        if ($uniqueSDGs->count() >= 3) {
            $achievements[] = [
                'title' => 'SDG Contributor',
                'description' => 'Berkontribusi pada 3+ kategori SDG berbeda',
                'icon' => 'globe',
                'color' => 'green',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        if ($uniqueSDGs->count() >= 5) {
            $achievements[] = [
                'title' => 'SDG Champion',
                'description' => 'Berkontribusi pada 5+ kategori SDG berbeda',
                'icon' => 'globe',
                'color' => 'green',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        // skill diversity
        $uniqueSkills = $this->extractSkills($projects);
        if ($uniqueSkills->count() >= 7) {
            $achievements[] = [
                'title' => 'Versatile Volunteer',
                'description' => 'Menguasai 7+ skill berbeda',
                'icon' => 'briefcase',
                'color' => 'blue',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        if ($uniqueSkills->count() >= 10) {
            $achievements[] = [
                'title' => 'Multi-Talented',
                'description' => 'Menguasai 10+ skill berbeda',
                'icon' => 'briefcase',
                'color' => 'blue',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        return $achievements;
    }

    /**
     * generate portfolio slug untuk public access
     */
    public function generatePortfolioSlug($student)
    {
        // gunakan username dari user
        return $student->user->username;
    }

    /**
     * get public portfolio berdasarkan slug (username)
     */
    public function getPublicPortfolio($slug)
    {
        $student = Student::whereHas('user', function($query) use ($slug) {
            $query->where('username', $slug);
        })->with('user')->firstOrFail();

        return $this->getPortfolioData($student->id);
    }

    /**
     * toggle project visibility in portfolio
     */
    public function toggleProjectVisibility($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        $project->update([
            'is_portfolio_visible' => !$project->is_portfolio_visible
        ]);

        return $project;
    }
}