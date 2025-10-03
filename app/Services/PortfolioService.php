<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Student;
use App\Models\Review;  // ✅ PERBAIKAN: gunakan Review bukan InstitutionReview
use Illuminate\Support\Facades\DB;

class PortfolioService
{
    /**
     * get portfolio data untuk mahasiswa
     */
    public function getPortfolioData($studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        // ambil completed projects
        $completedProjects = Project::with([
            'problem.institution',
            'problem.province',
            'problem.regency',
            'institutionReview'  // relasi ini sudah diperbaiki di Project model
        ])
        ->where('student_id', $studentId)
        ->where('status', 'completed')
        ->orderBy('completed_at', 'desc')
        ->get();

        // ✅ PERBAIKAN: gunakan Review bukan InstitutionReview
        $reviews = Review::where('type', 'institution_to_student')
            ->whereIn('project_id', $completedProjects->pluck('id'))
            ->get();

        // hitung statistics
        $statistics = $this->calculateStatistics($completedProjects, $reviews);

        // extract skills dan SDG categories
        $skills = $this->extractSkills($completedProjects);
        $sdgCategories = $this->extractSDGCategories($completedProjects);

        // get achievements
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
     * get portfolio achievements
     */
    public function getAchievements($student, $projects, $reviews)
    {
        $achievements = [];

        // project completion milestones
        $projectCount = $projects->count();
        if ($projectCount >= 1) {
            $achievements[] = [
                'title' => 'First Project',
                'description' => 'Menyelesaikan proyek KKN pertama',
                'icon' => 'star',
                'color' => 'blue',
                'earned_at' => $projects->first()->completed_at
            ];
        }

        if ($projectCount >= 5) {
            $achievements[] = [
                'title' => 'Experienced Contributor',
                'description' => 'Menyelesaikan 5 proyek KKN',
                'icon' => 'award',
                'color' => 'green',
                'earned_at' => $projects->take(5)->last()->completed_at
            ];
        }

        if ($projectCount >= 10) {
            $achievements[] = [
                'title' => 'Master Contributor',
                'description' => 'Menyelesaikan 10 proyek KKN',
                'icon' => 'trophy',
                'color' => 'yellow',
                'earned_at' => $projects->take(10)->last()->completed_at
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

        // SDG diversity
        $uniqueSDGs = $this->extractSDGCategories($projects);
        if (count($uniqueSDGs) >= 5) {
            $achievements[] = [
                'title' => 'SDG Champion',
                'description' => 'Berkontribusi pada 5+ kategori SDG berbeda',
                'icon' => 'globe',
                'color' => 'green',
                'earned_at' => $projects->first()->completed_at
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
                'earned_at' => $projects->first()->completed_at
            ];
        }

        return $achievements;
    }

    /**
     * check jika portfolio visible untuk public
     */
    public function isPortfolioVisible($studentId)
    {
        $student = Student::findOrFail($studentId);
        return $student->portfolio_visible;
    }

    /**
     * toggle portfolio visibility
     */
    public function togglePortfolioVisibility($studentId)
    {
        $student = Student::findOrFail($studentId);
        $student->portfolio_visible = !$student->portfolio_visible;
        $student->save();

        return $student->portfolio_visible;
    }

    /**
     * get public portfolio data
     */
    public function getPublicPortfolioData($studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        // check jika portfolio visible
        if (!$student->portfolio_visible) {
            return null;
        }

        return $this->getPortfolioData($studentId);
    }
}