<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'title',
        'description',
        'background',
        'objectives',
        'scope',
        'province_id',
        'regency_id',
        'village',
        'detailed_location',
        'latitude',
        'longitude',
        'required_students',
        'required_skills',
        'required_majors',
        'duration_months',
        'application_deadline',
        'start_date',
        'end_date',
        'sdg_categories',
        'difficulty_level',
        'facilities_provided',
        'expected_outcomes',
        'deliverables',
        'status',
        'is_featured',
        'is_urgent',
        'views_count',
        'applications_count',
        'accepted_students',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'required_majors' => 'array',
        'sdg_categories' => 'array',
        'deliverables' => 'array',
        'facilities_provided' => 'array',
        'application_deadline' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'views_count' => 'integer',
        'applications_count' => 'integer',
        'accepted_students' => 'integer',
    ];

    /**
     * relasi ke institution
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * relasi ke province
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * relasi ke regency
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * relasi ke images
     */
    public function images()
    {
        return $this->hasMany(ProblemImage::class);
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
     * relasi ke projects
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * scope untuk problem yang open
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
                    ->where('application_deadline', '>', now());
    }

    /**
     * scope untuk problem featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * scope untuk problem urgent
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * scope untuk search
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('village', 'like', "%{$keyword}%");
        });
    }

    /**
     * scope untuk filter by province
     */
    public function scopeByProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * scope untuk filter by regency
     */
    public function scopeByRegency($query, $regencyId)
    {
        return $query->where('regency_id', $regencyId);
    }

    /**
     * scope untuk filter by SDG category
     */
    public function scopeBySDGCategory($query, $category)
    {
        return $query->whereJsonContains('sdg_categories', (string)$category);
    }

    /**
     * scope untuk filter by difficulty
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * increment applications count
     */
    public function incrementApplications()
    {
        $this->increment('applications_count');
    }

    /**
     * check jika deadline sudah lewat
     */
    public function isDeadlinePassed()
    {
        return $this->application_deadline < now();
    }

    /**
     * check jika sudah penuh (accepted students >= required students)
     */
    public function isFull()
    {
        return $this->accepted_students >= $this->required_students;
    }

    /**
     * get remaining slots
     */
    public function getRemainingSlots()
    {
        return max(0, $this->required_students - $this->accepted_students);
    }

    /**
     * Get the difficulty label for display.
     * Mendapatkan label tingkat kesulitan yang mudah dibaca.
     */
    public function getDifficultyLabel(): string
    {
        return match($this->difficulty_level) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Lanjutan',
            default => ucfirst($this->difficulty_level),
        };
    }

    /**
     * Get the badge color classes for difficulty level.
     * Mendapatkan kelas warna Tailwind CSS untuk lencana tingkat kesulitan.
     */
    public function getDifficultyBadgeColor(): string
    {
        return match($this->difficulty_level) {
            'beginner' => 'bg-green-100 text-green-700',
            'intermediate' => 'bg-yellow-100 text-yellow-700',
            'advanced' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }


    /**
     * get days until deadline
     */
    public function getDaysUntilDeadline()
    {
        if ($this->isDeadlinePassed()) {
            return 0;
        }
        return now()->diffInDays($this->application_deadline);
    }
}