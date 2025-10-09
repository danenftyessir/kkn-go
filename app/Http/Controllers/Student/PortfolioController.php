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
        
        $portfolioData = $this->portfolioService->getPortfolioData($student->id);
        $achievements = $this->portfolioService->getAchievements($student->id);
        $portfolioSlug = $this->portfolioService->generatePortfolioSlug($student);

        return view('student.portfolio.index', array_merge($portfolioData, [
            'achievements' => $achievements,
            'portfolio_slug' => $portfolioSlug,
        ]));
    }

    /**
     * tampilkan public portfolio (dapat diakses siapa saja)
     */
    public function publicView($slug)
    {
        try {
            $portfolioData = $this->portfolioService->getPublicPortfolio($slug);
            $achievements = $this->portfolioService->getAchievements($portfolioData['student']->id);

            return view('student.portfolio.public', array_merge($portfolioData, [
                'achievements' => $achievements,
            ]));
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
     * download portfolio as PDF
     * TODO: implement PDF generation menggunakan library seperti dompdf atau snappy
     */
    public function downloadPDF()
    {
        $student = Auth::user()->student;
        
        // TODO: generate PDF dari portfolio data
        // $pdf = PDF::loadView('student.portfolio.pdf', $portfolioData);
        // return $pdf->download('portfolio-' . $student->nim . '.pdf');
        
        return back()->with('info', 'Fitur download PDF sedang dalam pengembangan');
    }

    /**
     * share portfolio link
     */
    public function getShareLink()
    {
        $student = Auth::user()->student;
        $slug = $this->portfolioService->generatePortfolioSlug($student);
        
        $shareUrl = route('portfolio.public', $slug);

        return response()->json([
            'success' => true,
            'url' => $shareUrl,
            'slug' => $slug,
        ]);
    }
}