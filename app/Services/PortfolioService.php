<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Project;
use App\Models\Review;

/**
 * service untuk mengelola portfolio mahasiswa
 * berisi business logic untuk portfolio display & statistics
 * 
 * path: app/Services/PortfolioService.php
 */
class PortfolioService
{
    /**
     * get portfolio data lengkap untuk mahasiswa
     */
    public function getPortfolioData($studentId)
    {
        $student = Student::with([
            'user',
            'university',
        ])->findOrFail($studentId);

        // ambil completed projects yang visible
        $projects = Project::where('student_id', $studentId)
                          ->portfolioVisible()
                          ->with([
                              'problem',
                              'institution',
                              'reviews' => function($query) {
                                  $query->where('type', 'institution_to_student')
                                        ->where('is_public', true);
                              }
                          ])
                          ->latest()
                          ->get();

        // hitung statistics
        $stats = $this->calculateStats($studentId, $projects);

        // ambil skills dari completed projects
        $skills = $this->extractSkills($projects);

        // ambil SDG categories yang pernah dikerjakan
        $sdgAddressed = $this->extractSDGCategories($projects);

        return [
            'student' => $student,
            'projects' => $projects,
            'stats' => $stats,
            'skills' => $skills,
            'sdg_addressed' => $sdgAddressed,
        ];
    }

    /**
     * calculate portfolio statistics
     */
    protected function calculateStats($studentId, $projects)
    {
        // hitung average rating
        $reviews = Review::where('type', 'institution_to_student')
                        ->where('reviewee_id', function($query) use ($studentId) {
                            $query->select('user_id')
                                  ->from('students')
                                  ->where('id', $studentId);
                        })
                        ->where('is_public', true)
                        ->get();

        $averageRating = $reviews->isEmpty() ? 0 : $reviews->avg('rating');

        // hitung total impact
        $totalBeneficiaries = 0;
        $totalActivities = 0;

        foreach ($projects as $project) {
            if ($project->impact_metrics) {
                $totalBeneficiaries += $project->impact_metrics['beneficiaries'] ?? 0;
                $totalActivities += $project->impact_metrics['activities'] ?? 0;
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
                $allSkills = array_merge($allSkills, $project->problem->required_skills);
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
                $allSDGs = array_merge($allSDGs, $project->problem->sdg_categories);
            }
        }

        // remove duplicates dan return unique SDGs
        return array_values(array_unique($allSDGs));
    }

    /**
     * get portfolio achievements
     */
    public function getAchievements($studentId)
    {
        $projects = Project::where('student_id', $studentId)
                          ->completed()
                          ->get();

        $achievements = [];

        // achievement: first project completed
        if ($projects->count() >= 1) {
            $achievements[] = [
                'title' => 'Proyek Pertama',
                'description' => 'Menyelesaikan proyek KKN pertama',
                'icon' => 'award',
                'color' => 'blue',
            ];
        }

        // achievement: 5 projects completed
        if ($projects->count() >= 5) {
            $achievements[] = [
                'title' => 'Kontributor Aktif',
                'description' => 'Menyelesaikan 5 proyek KKN',
                'icon' => 'star',
                'color' => 'yellow',
            ];
        }

        // achievement: high rating
        $avgRating = Review::where('type', 'institution_to_student')
                          ->where('reviewee_id', function($query) use ($studentId) {
                              $query->select('user_id')
                                    ->from('students')
                                    ->where('id', $studentId);
                          })
                          ->avg('rating');

        if ($avgRating >= 4.5) {
            $achievements[] = [
                'title' => 'Bintang Lima',
                'description' => 'Mempertahankan rating rata-rata 4.5+',
                'icon' => 'trophy',
                'color' => 'yellow',
            ];
        }

        // achievement: multiple SDGs
        $sdgCount = count($this->extractSDGCategories($projects));
        if ($sdgCount >= 5) {
            $achievements[] = [
                'title' => 'SDG Champion',
                'description' => 'Berkontribusi pada 5+ kategori SDG',
                'icon' => 'target',
                'color' => 'green',
            ];
        }

        return $achievements;
    }

    /**
     * generate portfolio slug/shareable link
     */
    public function generatePortfolioSlug(Student $student)
    {
        // gunakan NIM sebagai unique identifier
        return strtolower($student->nim);
    }

    /**
     * get public portfolio by slug
     */
    public function getPublicPortfolio($slug)
    {
        // cari student by NIM (slug)
        $student = Student::where('nim', strtoupper($slug))->firstOrFail();

        return $this->getPortfolioData($student->id);
    }

    /**
     * toggle project visibility in portfolio
     */
    public function toggleProjectVisibility($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        $project->update([
            'is_portfolio_visible' => !$project->is_portfolio_visible,
        ]);

        return $project;
    }
}