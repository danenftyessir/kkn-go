<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'nim',
        'university_id',
        'major',
        'semester',
        'whatsapp',
        'phone',
        'bio',
        'skills',
        'profile_photo_path',
    ];

    protected $casts = [
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
     * get full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * cek apakah student sudah wishlist problem tertentu
     */
    public function hasWishlisted($problemId)
    {
        try {
            return $this->wishlists()
                        ->where('problem_id', $problemId)
                        ->exists();
        } catch (\Exception $e) {
            // jika table wishlists belum ada atau error
            return false;
        }
    }

    /**
     * toggle wishlist untuk problem
     */
    public function toggleWishlist($problemId, $notes = null)
    {
        $wishlist = $this->wishlists()
                        ->where('problem_id', $problemId)
                        ->first();

        if ($wishlist) {
            $wishlist->delete();
            return ['action' => 'removed', 'saved' => false];
        } else {
            $this->wishlists()->create([
                'problem_id' => $problemId,
                'notes' => $notes
            ]);
            return ['action' => 'added', 'saved' => true];
        }
    }

    /**
     * get completed projects count
     */
    public function getCompletedProjectsCount()
    {
        return $this->applications()
                    ->where('status', 'accepted')
                    ->whereHas('problem', function($query) {
                        $query->where('status', 'completed');
                    })
                    ->count();
    }

    /**
     * get pending applications count
     */
    public function getPendingApplicationsCount()
    {
        return $this->applications()
                    ->whereIn('status', ['pending', 'under_review'])
                    ->count();
    }

    /**
     * get accepted applications count
     */
    public function getAcceptedApplicationsCount()
    {
        return $this->applications()
                    ->where('status', 'accepted')
                    ->count();
    }

    /**
     * cek apakah sudah apply ke problem tertentu
     */
    public function hasAppliedTo($problemId)
    {
        return $this->applications()
                    ->where('problem_id', $problemId)
                    ->exists();
    }
}