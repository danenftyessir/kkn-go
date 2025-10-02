<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Student
 * 
 * representasi data mahasiswa dalam sistem
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
        'skills',
        'portfolio_visible',
    ];

    protected $casts = [
        'portfolio_visible' => 'boolean',
        'skills' => 'array',
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
     * relasi ke wishlists
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * relasi ke wishlisted problems (many-to-many melalui wishlist)
     */
    public function wishlistedProblems()
    {
        return $this->belongsToMany(Problem::class, 'wishlists')
                    ->withTimestamps()
                    ->withPivot('notes');
    }

    /**
     * cek apakah student sudah save problem tertentu
     */
    public function hasWishlisted($problemId): bool
    {
        return $this->wishlists()
                    ->where('problem_id', $problemId)
                    ->exists();
    }

    /**
     * TODO: relasi ke projects (completed)
     */
    // public function completedProjects()
    // {
    //     return $this->hasMany(Project::class)->where('status', 'completed');
    // }

    /**
     * TODO: relasi ke reviews/ratings
     */
    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

    /**
     * get full name
     */
    public function getFullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->getFullName();
    }

    /**
     * get profile photo url
     */
    public function getProfilePhotoUrl(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        
        // default avatar dengan initial
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->getFullName()) . '&color=3B82F6&background=EFF6FF';
    }

    /**
     * get statistics untuk dashboard/portfolio
     */
    public function getStatistics(): array
    {
        return [
            'total_applications' => $this->applications()->count(),
            'pending_applications' => $this->applications()->where('status', 'pending')->count(),
            'accepted_applications' => $this->applications()->where('status', 'accepted')->count(),
            'wishlist_count' => $this->wishlists()->count(),
            // TODO: tambahkan completed projects count
            // 'completed_projects' => $this->completedProjects()->count(),
        ];
    }
}