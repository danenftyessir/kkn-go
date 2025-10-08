<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

/**
 * service untuk handle review logic
 * FIXED: ganti reviewer_type menjadi type
 */
class ReviewService
{
    /**
     * buat review dari institution ke student
     */
    public function createInstitutionReview(
        Project $project,
        int $rating,
        string $reviewText,
        ?string $strengths = null,
        ?string $improvements = null,
        bool $wouldCollaborateAgain = false
    ) {
        DB::beginTransaction();
        
        try {
            // buat review
            $review = Review::create([
                'project_id' => $project->id,
                'reviewer_id' => $project->institution->user_id,
                'reviewee_id' => $project->student->user_id,
                'type' => 'institution_to_student', // FIXED: gunakan 'type'
                'rating' => $rating,
                'review_text' => $reviewText,
                'strengths' => $strengths,
                'improvements' => $improvements,
                'is_public' => true,
            ]);

            // update project dengan rating
            $project->update([
                'rating' => $rating,
                'institution_review' => $reviewText,
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
     * get average rating untuk student
     */
    public function getStudentAverageRating($studentUserId)
    {
        return Review::where('reviewee_id', $studentUserId)
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->avg('rating') ?? 0;
    }

    /**
     * get total reviews untuk student
     */
    public function getStudentTotalReviews($studentUserId)
    {
        return Review::where('reviewee_id', $studentUserId)
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->count();
    }

    /**
     * get rating distribution untuk student
     */
    public function getStudentRatingDistribution($studentUserId)
    {
        $distribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = Review::where('reviewee_id', $studentUserId)
                ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
                ->where('rating', $i)
                ->count();
        }

        return $distribution;
    }

    /**
     * get review statistics untuk institution
     */
    public function getInstitutionReviewStats($institutionId)
    {
        $reviews = Review::where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            });

        return [
            'total' => $reviews->count(),
            'average_rating' => $reviews->avg('rating') ?? 0,
            'five_star' => $reviews->where('rating', 5)->count(),
            'four_star' => $reviews->where('rating', 4)->count(),
            'three_star' => $reviews->where('rating', 3)->count(),
            'two_star' => $reviews->where('rating', 2)->count(),
            'one_star' => $reviews->where('rating', 1)->count(),
        ];
    }

    /**
     * get recent reviews untuk institution dashboard
     */
    public function getRecentInstitutionReviews($institutionId, $limit = 5)
    {
        return Review::with(['reviewee', 'project.problem'])
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * check apakah institution sudah review project ini
     */
    public function hasReviewed(Project $project)
    {
        return Review::where('project_id', $project->id)
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->exists();
    }

    /**
     * get projects yang belum di-review oleh institution
     */
    public function getPendingReviews($institutionId, $limit = 10)
    {
        return Project::with(['student.user', 'student.university', 'problem'])
            ->where('institution_id', $institutionId)
            ->where('status', 'completed')
            ->whereNull('rating')
            ->whereNull('reviewed_at')
            ->latest('end_date')
            ->limit($limit)
            ->get();
    }

    /**
     * calculate student performance score berdasarkan reviews
     */
    public function calculateStudentPerformanceScore($studentUserId)
    {
        $reviews = Review::where('reviewee_id', $studentUserId)
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->get();

        if ($reviews->isEmpty()) {
            return 0;
        }

        $totalRating = $reviews->sum('rating');
        $maxRating = $reviews->count() * 5;

        return ($totalRating / $maxRating) * 100;
    }

    /**
     * get top rated students dari institution
     */
    public function getTopRatedStudents($institutionId, $limit = 10)
    {
        return Review::select('reviewee_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
            ->with(['reviewee'])
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->groupBy('reviewee_id')
            ->having('review_count', '>=', 1)
            ->orderBy('avg_rating', 'desc')
            ->orderBy('review_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * export review data untuk reporting
     */
    public function exportReviewData($institutionId)
    {
        return Review::with(['reviewee', 'project.problem'])
            ->where('type', 'institution_to_student') // FIXED: gunakan 'type'
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'student_name' => $review->reviewee->name,
                    'project_title' => $review->project->problem->title,
                    'rating' => $review->rating,
                    'review' => $review->review_text,
                    'date' => $review->created_at->format('Y-m-d'),
                ];
            });
    }
}