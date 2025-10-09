<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

/**
 * service untuk mengelola reviews
 * handle business logic untuk rating dan review system
 */
class ReviewService
{
    /**
     * dapatkan proyek yang perlu direview oleh institution
     */
    public function getPendingReviews($institutionId, $limit = 10)
    {
        return Project::where('institution_id', $institutionId)
                     ->where('status', 'completed')
                     ->whereNull('rating')
                     ->whereNull('institution_review')
                     ->with(['student.user', 'student.university', 'problem'])
                     ->latest('actual_end_date')
                     ->limit($limit)
                     ->get();
    }

    /**
     * buat review untuk proyek
     */
    public function createReview($projectId, $institutionId, $data)
    {
        DB::beginTransaction();
        
        try {
            // cek apakah proyek milik institution ini
            $project = Project::where('id', $projectId)
                             ->where('institution_id', $institutionId)
                             ->firstOrFail();

            // update project dengan rating dan review
            $project->update([
                'rating' => $data['rating'],
                'institution_review' => $data['review'],
                'reviewed_at' => now(),
            ]);

            // buat entry di tabel reviews (jika ada)
            $review = Review::create([
                'project_id' => $project->id,
                'institution_id' => $institutionId,
                'student_id' => $project->student_id,
                'rating' => $data['rating'],
                'review_text' => $data['review'],
                'recommendation' => $data['recommendation'] ?? null,
            ]);

            DB::commit();
            
            return $review;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * update review yang sudah ada
     */
    public function updateReview($reviewId, $institutionId, $data)
    {
        DB::beginTransaction();
        
        try {
            $review = Review::where('id', $reviewId)
                           ->where('institution_id', $institutionId)
                           ->firstOrFail();

            $review->update([
                'rating' => $data['rating'],
                'review_text' => $data['review'],
                'recommendation' => $data['recommendation'] ?? $review->recommendation,
            ]);

            // update juga di project
            $review->project->update([
                'rating' => $data['rating'],
                'institution_review' => $data['review'],
                'reviewed_at' => now(),
            ]);

            DB::commit();
            
            return $review;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * dapatkan review terbaru yang diberikan institution
     */
    public function getRecentInstitutionReviews($institutionId, $limit = 5)
    {
        return Review::where('institution_id', $institutionId)
                    ->with(['project.problem', 'student.user', 'student.university'])
                    ->latest()
                    ->limit($limit)
                    ->get();
    }

    /**
     * dapatkan statistik review untuk institution
     */
    public function getInstitutionReviewStats($institutionId)
    {
        $reviews = Review::where('institution_id', $institutionId);

        $totalReviews = $reviews->count();
        $avgRating = $reviews->avg('rating') ?? 0;

        // distribusi rating
        $ratingDistribution = [
            5 => (clone $reviews)->where('rating', 5)->count(),
            4 => (clone $reviews)->where('rating', 4)->count(),
            3 => (clone $reviews)->where('rating', 3)->count(),
            2 => (clone $reviews)->where('rating', 2)->count(),
            1 => (clone $reviews)->where('rating', 1)->count(),
        ];

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => round($avgRating, 2),
            'rating_distribution' => $ratingDistribution,
        ];
    }

    /**
     * dapatkan reviews untuk student (untuk portfolio)
     */
    public function getStudentReviews($studentId)
    {
        return Review::where('student_id', $studentId)
                    ->where('rating', '>=', 4) // hanya tampilkan review positif
                    ->with(['project.problem', 'institution'])
                    ->latest()
                    ->get();
    }

    /**
     * cek apakah proyek sudah direview
     */
    public function isProjectReviewed($projectId)
    {
        $project = Project::find($projectId);
        return $project && $project->rating !== null;
    }

    /**
     * dapatkan average rating untuk student
     */
    public function getStudentAverageRating($studentId)
    {
        return Review::where('student_id', $studentId)
                    ->avg('rating') ?? 0;
    }

    /**
     * dapatkan top rated students
     */
    public function getTopRatedStudents($limit = 10)
    {
        return DB::table('reviews')
                ->select('student_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
                ->groupBy('student_id')
                ->having('review_count', '>=', 3) // minimal 3 review
                ->orderBy('avg_rating', 'desc')
                ->limit($limit)
                ->get();
    }
}