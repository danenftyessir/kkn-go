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
        'skills' => 'array', // jika skills disimpan sebagai json
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
        
        // default avatar dengan inisial
        return $this->getDefaultAvatarUrl();
    }

    /**
     * get default avatar url (untuk future implementation)
     */
    protected function getDefaultAvatarUrl(): string
    {
        // TODO: bisa gunakan service seperti ui-avatars.com
        // atau generate default avatar image
        return asset('images/default-avatar.png');
    }

    /**
     * scope untuk filter berdasarkan university
     */
    public function scopeFromUniversity($query, $universityId)
    {
        return $query->where('university_id', $universityId);
    }

    /**
     * scope untuk filter berdasarkan semester
     */
    public function scopeInSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * cek apakah portfolio visible untuk publik
     */
    public function isPortfolioVisible(): bool
    {
        return (bool) $this->portfolio_visible;
    }

    /**
     * TODO: method untuk menghitung statistik
     */
    // public function getStatistics()
    // {
    //     return [
    //         'total_projects' => $this->completedProjects()->count(),
    //         'sdgs_addressed' => $this->completedProjects()->distinct('sdg_category')->count(),
    //         'positive_reviews' => $this->reviews()->where('rating', '>=', 4)->count(),
    //         'average_rating' => $this->reviews()->avg('rating') ?? 0,
    //     ];
    // }
}