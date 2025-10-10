<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * model untuk tabel students
 * 
 * representasi mahasiswa yang menggunakan platform KKN-GO
 */
class Student extends Model
{
    use HasFactory;

    /**
     * attributes yang dapat diisi mass assignment
     */
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
        'skills',
        'portfolio_visible',
    ];

    /**
     * attributes yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'skills' => 'array',
        'portfolio_visible' => 'boolean',
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
        return $this->projects()
            ->whereIn('status', ['active', 'in_progress'])
            ->count();
    }

    /**
     * get completed projects count
     */
    public function getCompletedProjectsCountAttribute()
    {
        return $this->projects()
            ->where('status', 'completed')
            ->count();
    }

    /**
     * get average rating dari semua reviews
     */
    public function getAverageRatingAttribute()
    {
        $reviews = Review::where('reviewee_type', 'student')
                        ->where('reviewee_id', $this->id)
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

    /**
     * get profile photo URL
     * PERBAIKAN BUG: sekarang menggunakan Supabase URL yang benar
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            // cek apakah path sudah berupa URL lengkap atau masih path relatif
            if (str_starts_with($this->profile_photo_path, 'http')) {
                return $this->profile_photo_path;
            }
            
            // cek apakah ini adalah path dari Supabase (tidak mengandung 'public/')
            if (!str_starts_with($this->profile_photo_path, 'public/')) {
                // ini adalah path Supabase, gunakan SupabaseStorageService untuk generate URL
                $storageService = app(\App\Services\SupabaseStorageService::class);
                return $storageService->getPublicUrl($this->profile_photo_path);
            }
            
            // fallback ke local storage jika path mengandung 'public/'
            return asset('storage/' . str_replace('public/', '', $this->profile_photo_path));
        }
        
        return asset('images/default-avatar.png');
    }

    /**
     * get total SDGs addressed dari completed projects
     */
    public function getTotalSdgsAddressedAttribute()
    {
        $completedProjects = $this->projects()
            ->where('status', 'completed')
            ->with('problem')
            ->get();

        $sdgs = [];
        foreach ($completedProjects as $project) {
            if ($project->problem && $project->problem->sdg_categories) {
                $sdgs = array_merge($sdgs, $project->problem->sdg_categories);
            }
        }

        return count(array_unique($sdgs));
    }
}