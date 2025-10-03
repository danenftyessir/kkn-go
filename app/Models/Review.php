<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * model untuk tabel reviews
 * mengelola review dari institusi ke mahasiswa dan sebaliknya
 * 
 * path: app/Models/Review.php
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'reviewer_id',
        'reviewee_id',
        'type',
        'rating',
        'professionalism_rating',
        'communication_rating',
        'quality_rating',
        'timeliness_rating',
        'review_text',
        'strengths',
        'improvements',
        'is_public',
        'is_featured',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'responded_at' => 'datetime',
    ];

    /**
     * relasi ke project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * relasi ke reviewer
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * relasi ke reviewee
     */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    /**
     * scope untuk public reviews
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * scope untuk filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * scope untuk reviews to student
     */
    public function scopeToStudent($query, $studentUserId)
    {
        return $query->where('type', 'institution_to_student')
                     ->where('reviewee_id', $studentUserId);
    }

    /**
     * scope untuk reviews to institution
     */
    public function scopeToInstitution($query, $institutionUserId)
    {
        return $query->where('type', 'student_to_institution')
                     ->where('reviewee_id', $institutionUserId);
    }

    /**
     * hitung average rating dari detail ratings
     */
    public function calculateDetailedRating()
    {
        if ($this->type !== 'institution_to_student') {
            return $this->rating;
        }

        $ratings = [
            $this->professionalism_rating,
            $this->communication_rating,
            $this->quality_rating,
            $this->timeliness_rating,
        ];

        $filteredRatings = array_filter($ratings);
        
        return !empty($filteredRatings) 
            ? round(array_sum($filteredRatings) / count($filteredRatings), 1)
            : $this->rating;
    }

    /**
     * add response to review
     */
    public function addResponse($responseText)
    {
        $this->update([
            'response' => $responseText,
            'responded_at' => now(),
        ]);
    }

    /**
     * get star rating display
     */
    public function getStarRatingAttribute()
    {
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return [
            'full' => $fullStars,
            'half' => $halfStar,
            'empty' => $emptyStars,
        ];
    }

    /**
     * get rating color class
     */
    public function getRatingColorAttribute()
    {
        return match(true) {
            $this->rating >= 4.5 => 'text-green-600',
            $this->rating >= 3.5 => 'text-blue-600',
            $this->rating >= 2.5 => 'text-yellow-600',
            default => 'text-red-600',
        };
    }
}