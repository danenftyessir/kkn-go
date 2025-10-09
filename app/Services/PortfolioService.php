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
     * FIX ERROR #2: method ini return achievements di dalamnya
     */
    public function getPortfolioData($studentId)
    {
        $student = Student::with(['user', 'university'])->findOrFail($studentId);

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

        // get achievements - FIX: gunakan 3 parameter yang benar
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
        ];
    }

    /**
     * extract skills dari completed projects
     */
    protected function extractSkills($projects)
    {
        $allSkills = [];

        foreach ($projects as $project) {
            if ($project->problem && $project->problem->required_skills) {
                $skills = $project->problem->required_skills;
                
                // pastikan skills adalah array
                if (is_string($skills)) {
                    $skills = json_decode($skills, true);
                }
                
                // jika masih bukan array atau decode gagal, skip
                if (!is_array($skills)) {
                    continue;
                }
                
                $allSkills = array_merge($allSkills, $skills);
            }
        }

        // remove duplicates dan return unique skills
        return array_values(array_unique($allSkills));
    }

    /**
     * extract SDG categories dari completed projects
     */
    protected function extractSDGCategories($projects)
    {
        $allSDGs = [];

        foreach ($projects as $project) {
            if ($project->problem && $project->problem->sdg_categories) {
                $categories = $project->problem->sdg_categories;
                
                // pastikan categories adalah array
                if (is_string($categories)) {
                    $categories = json_decode($categories, true);
                }
                
                // jika masih bukan array atau decode gagal, skip
                if (!is_array($categories)) {
                    continue;
                }
                
                $allSDGs = array_merge($allSDGs, $categories);
            }
        }

        // remove duplicates dan return unique SDGs
        return array_values(array_unique($allSDGs));
    }

    /**
     * get portfolio achievements berdasarkan project dan review
     * FIX ERROR #2: method ini sudah menerima 3 parameter yang benar
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
                'title' => 'Veteran Volunteer',
                'description' => 'Menyelesaikan 5+ proyek KKN',
                'icon' => 'trophy',
                'color' => 'yellow',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        // rating achievements
        $averageRating = $reviews->isEmpty() ? 0 : $reviews->avg('rating');
        
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
                'icon' => 'zap',
                'color' => 'yellow',
                'earned_at' => $reviews->first()->created_at
            ];
        }

        // SDG diversity
        $uniqueSDGs = $this->extractSDGCategories($projects);
        if (count($uniqueSDGs) >= 5) {
            $achievements[] = [
                'title' => 'SDG Champion',
                'description' => 'Berkontribusi pada 5+ kategori SDG berbeda',
                'icon' => 'globe',
                'color' => 'green',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        if (count($uniqueSDGs) >= 10) {
            $achievements[] = [
                'title' => 'SDG Master',
                'description' => 'Berkontribusi pada 10+ kategori SDG berbeda',
                'icon' => 'target',
                'color' => 'blue',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        // skill diversity
        $uniqueSkills = $this->extractSkills($projects);
        if (count($uniqueSkills) >= 10) {
            $achievements[] = [
                'title' => 'Multi-Talented',
                'description' => 'Menguasai 10+ skill berbeda',
                'icon' => 'briefcase',
                'color' => 'blue',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        if (count($uniqueSkills) >= 15) {
            $achievements[] = [
                'title' => 'Jack of All Trades',
                'description' => 'Menguasai 15+ skill berbeda',
                'icon' => 'layers',
                'color' => 'purple',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        // impact achievements
        $totalBeneficiaries = 0;
        foreach ($projects as $project) {
            if ($project->impact_metrics) {
                $metrics = is_array($project->impact_metrics) 
                    ? $project->impact_metrics 
                    : json_decode($project->impact_metrics, true) ?? [];
                
                $totalBeneficiaries += $metrics['beneficiaries'] ?? 0;
            }
        }

        if ($totalBeneficiaries >= 100) {
            $achievements[] = [
                'title' => 'Community Hero',
                'description' => 'Membantu 100+ penerima manfaat',
                'icon' => 'users',
                'color' => 'green',
                'earned_at' => $projects->first()->updated_at
            ];
        }

        if ($totalBeneficiaries >= 500) {
            $achievements[] = [
                'title' => 'Impact Maker',
                'description' => 'Membantu 500+ penerima manfaat',
                'icon' => 'trending-up',
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
        // gunakan NIM atau ID sebagai slug
        return $student->nim ?? $student->id;
    }

    /**
     * get public portfolio berdasarkan slug
     */
    public function getPublicPortfolio($slug)
    {
        // cari student berdasarkan NIM atau ID
        $student = Student::with('user')
            ->where('nim', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // check visibility (jika ada field portfolio_visible)
        // if (!$student->portfolio_visible) {
        //     abort(403, 'Portfolio is private');
        // }

        return $this->getPortfolioData($student->id);
    }

    /**
     * toggle project visibility in portfolio
     */
    public function toggleProjectVisibility($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // toggle is_portfolio_visible jika field ada
        // $project->is_portfolio_visible = !$project->is_portfolio_visible;
        // $project->save();

        return $project;
    }

    /**
     * check jika portfolio visible untuk public
     */
    public function isPortfolioVisible($studentId)
    {
        $student = Student::findOrFail($studentId);
        // return $student->portfolio_visible ?? true;
        return true; // default visible untuk saat ini
    }

    /**
     * toggle portfolio visibility
     */
    public function togglePortfolioVisibility($studentId)
    {
        $student = Student::findOrFail($studentId);
        // $student->portfolio_visible = !$student->portfolio_visible;
        // $student->save();

        // return $student->portfolio_visible;
        return true;
    }
}