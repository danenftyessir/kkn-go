<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

/**
 * service untuk handle business logic terkait reviews
 */
class ReviewService
{
    /**
     * buat review dari instansi untuk mahasiswa
     */
    public function createInstitutionReview(
        Project $project,
        int $rating,
        string $review,
        ?string $strengths = null,
        ?string $improvements = null,
        bool $wouldCollaborateAgain = false
    ) {
        // validasi: proyek harus sudah selesai
        if ($project->status !== 'completed') {
            throw new \Exception('Hanya dapat memberikan review untuk proyek yang sudah selesai.');
        }

        // validasi: belum pernah review
        if ($project->rating) {
            throw new \Exception('Review sudah pernah diberikan untuk proyek ini.');
        }

        try {
            DB::beginTransaction();

            // update rating di project
            $project->update([
                'rating' => $rating,
                'institution_review' => $review,
                'reviewed_at' => now(),
            ]);

            // buat review record dengan data tambahan
            $reviewData = [
                'project_id' => $project->id,
                'reviewer_id' => auth()->id(),
                'reviewer_type' => 'institution',
                'student_id' => $project->student_id,
                'rating' => $rating,
                'review' => $review,
            ];

            // tambahkan metadata sebagai json
            $metadata = [
                'strengths' => $strengths,
                'improvements' => $improvements,
                'would_collaborate_again' => $wouldCollaborateAgain,
            ];

            $reviewRecord = Review::create($reviewData);

            // simpan metadata jika model Review memiliki kolom metadata
            // atau simpan di tabel terpisah jika diperlukan
            // untuk sekarang kita simpan di review text dengan format terstruktur

            DB::commit();

            // TODO: kirim notifikasi ke mahasiswa
            // TODO: update student statistics

            return $reviewRecord;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * update review yang sudah ada
     */
    public function updateReview(
        Review $review,
        int $rating,
        string $reviewText,
        ?string $strengths = null,
        ?string $improvements = null,
        bool $wouldCollaborateAgain = false
    ) {
        // validasi: hanya bisa edit dalam 30 hari
        if ($review->created_at->addDays(30)->isPast()) {
            throw new \Exception('Review tidak dapat diedit setelah 30 hari.');
        }

        try {
            DB::beginTransaction();

            // update review
            $review->update([
                'rating' => $rating,
                'review' => $reviewText,
                'updated_at' => now(),
            ]);

            // update rating di project juga
            if ($review->project) {
                $review->project->update([
                    'rating' => $rating,
                    'institution_review' => $reviewText,
                ]);
            }

            DB::commit();

            // TODO: update student statistics

            return $review;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * hapus review
     */
    public function deleteReview(Review $review)
    {
        // validasi: hanya bisa hapus dalam 7 hari
        if ($review->created_at->addDays(7)->isPast()) {
            throw new \Exception('Review tidak dapat dihapus setelah 7 hari.');
        }

        try {
            DB::beginTransaction();

            // hapus rating dari project
            if ($review->project) {
                $review->project->update([
                    'rating' => null,
                    'institution_review' => null,
                    'reviewed_at' => null,
                ]);
            }

            // hapus review
            $review->delete();

            DB::commit();

            // TODO: update student statistics

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * get average rating untuk student
     */
    public function getStudentAverageRating($studentId)
    {
        return Review::where('student_id', $studentId)
            ->where('reviewer_type', 'institution')
            ->avg('rating') ?? 0;
    }

    /**
     * get total reviews untuk student
     */
    public function getStudentTotalReviews($studentId)
    {
        return Review::where('student_id', $studentId)
            ->where('reviewer_type', 'institution')
            ->count();
    }

    /**
     * get rating distribution untuk student
     */
    public function getStudentRatingDistribution($studentId)
    {
        $distribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = Review::where('student_id', $studentId)
                ->where('reviewer_type', 'institution')
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
        $reviews = Review::where('reviewer_type', 'institution')
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
        return Review::with(['student.user', 'student.university', 'project.problem'])
            ->where('reviewer_type', 'institution')
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
            ->where('reviewer_type', 'institution')
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
    public function calculateStudentPerformanceScore($studentId)
    {
        $reviews = Review::where('student_id', $studentId)
            ->where('reviewer_type', 'institution')
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
        return Review::select('student_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
            ->with(['student.user', 'student.university'])
            ->where('reviewer_type', 'institution')
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->groupBy('student_id')
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
        return Review::with(['student.user', 'student.university', 'project.problem'])
            ->where('reviewer_type', 'institution')
            ->whereHas('project', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'student_name' => $review->student->user->name,
                    'university' => $review->student->university->name,
                    'project_title' => $review->project->problem->title,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'date' => $review->created_at->format('Y-m-d'),
                ];
            });
    }
}