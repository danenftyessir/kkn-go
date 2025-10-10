<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * controller untuk mengelola portfolio mahasiswa
 * 
 * path: app/Http/Controllers/Student/PortfolioController.php
 */
class PortfolioController extends Controller
{
    protected $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    /**
     * tampilkan portfolio page (private view)
     */
    public function index()
    {
        $student = Auth::user()->student;
        
        // getPortfolioData sudah return achievements di dalamnya
        $portfolioData = $this->portfolioService->getPortfolioData($student->id);
        $portfolioSlug = $this->portfolioService->generatePortfolioSlug($student);

        return view('student.portfolio.index', array_merge($portfolioData, [
            'portfolio_slug' => $portfolioSlug,
        ]));
    }

    /**
     * tampilkan public portfolio (dapat diakses siapa saja)
     * PERBAIKAN: sekarang langsung terima username, bukan slug
     */
    public function publicView($username)
    {
        try {
            // cari student berdasarkan username
            $student = \App\Models\Student::whereHas('user', function($query) use ($username) {
                $query->where('username', $username);
            })
            ->with(['user', 'university'])
            ->firstOrFail();

            // ambil completed projects
            $completedProjects = \App\Models\Project::with([
                'problem.institution',
                'problem.province',
                'problem.regency',
                'institutionReview'
            ])
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->orderBy('actual_end_date', 'desc')
            ->get();

            // ambil reviews
            $reviews = \App\Models\Review::where('type', 'institution_to_student')
                ->whereIn('project_id', $completedProjects->pluck('id'))
                ->get();

            // hitung statistik
            $statistics = [
                'completed_projects' => $completedProjects->count(),
                'sdgs_addressed' => $this->countUniqueSDGs($completedProjects),
                'positive_reviews' => $reviews->where('rating', '>=', 4)->count(),
                'average_rating' => $reviews->isEmpty() ? 0 : round($reviews->avg('rating'), 1),
            ];

            return view('student.portfolio.public', compact('student', 'completed_projects', 'reviews', 'statistics'));

        } catch (\Exception $e) {
            abort(404, 'Portfolio tidak ditemukan');
        }
    }

    /**
     * toggle project visibility in portfolio
     */
    public function toggleProjectVisibility(Request $request, $projectId)
    {
        $student = Auth::user()->student;
        
        // pastikan project milik student ini
        $project = \App\Models\Project::where('id', $projectId)
                                      ->where('student_id', $student->id)
                                      ->firstOrFail();

        try {
            $updatedProject = $this->portfolioService->toggleProjectVisibility($projectId);

            return response()->json([
                'success' => true,
                'message' => $updatedProject->is_portfolio_visible 
                    ? 'Proyek ditampilkan di portfolio' 
                    : 'Proyek disembunyikan dari portfolio',
                'is_visible' => $updatedProject->is_portfolio_visible,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah visibility: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * helper: hitung unique SDGs dari completed projects
     */
    private function countUniqueSDGs($completedProjects)
    {
        $sdgs = [];
        
        foreach ($completedProjects as $project) {
            if ($project->problem && $project->problem->sdg_categories) {
                $sdgs = array_merge($sdgs, $project->problem->sdg_categories);
            }
        }
        
        return count(array_unique($sdgs));
    }
}