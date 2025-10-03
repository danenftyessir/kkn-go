<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * model untuk tabel students
 * 
 * path: app/Models/Student.php (UPDATED)
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'university_id',
        'major',
        'nim',
        'semester',
        'phone',
        'profile_photo_path',
        'bio',
    ];

    /**
     * relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * relasi ke university
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * relasi ke applications
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * relasi ke wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * relasi ke projects
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * relasi ke project reports
     */
    public function projectReports()
    {
        return $this->hasMany(ProjectReport::class);
    }

    /**
     * cek apakah student sudah wishlist problem tertentu
     */
    public function hasWishlisted($problemId)
    {
        return $this->wishlists()->where('problem_id', $problemId)->exists();
    }

    /**
     * cek apakah student sudah apply ke problem tertentu
     */
    public function hasApplied($problemId)
    {
        return $this->applications()->where('problem_id', $problemId)->exists();
    }

    /**
     * get active projects count
     */
    public function getActiveProjectsCountAttribute()
    {
        return $this->projects()->where('status', 'active')->count();
    }

    /**
     * get completed projects count
     */
    public function getCompletedProjectsCountAttribute()
    {
        return $this->projects()->where('status', 'completed')->count();
    }

    /**
     * get average rating dari semua reviews
     */
    public function getAverageRatingAttribute()
    {
        $reviews = Review::where('type', 'institution_to_student')
                        ->where('reviewee_id', $this->user_id)
                        ->where('is_public', true)
                        ->get();

        return $reviews->isEmpty() ? 0 : round($reviews->avg('rating'), 1);
    }

    /**
     * get full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}