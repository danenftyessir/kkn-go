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
        'sdg_categories',
        'required_students',
        'required_skills',
        'required_majors',
        'start_date',
        'end_date',
        'application_deadline',
        'duration_months',
        'difficulty_level',
        'status',
        'expected_outcomes',
        'deliverables',
        'facilities_provided',
        'views_count',
        'applications_count',
        'accepted_students',
        'is_featured',
        'is_urgent',
    ];

    protected $casts = [
        'sdg_categories' => 'array',
        'required_skills' => 'array',
        'required_majors' => 'array',
        'deliverables' => 'array',
        'facilities_provided' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'application_deadline' => 'date',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    /**
     * accessor untuk required_skills - ensure always returns array
     */
    public function getRequiredSkillsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        // jika string, decode JSON
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * accessor untuk sdg_categories - ensure always returns array
     */
    public function getSdgCategoriesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * accessor untuk required_majors - ensure always returns array
     */
    public function getRequiredMajorsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * accessor untuk deliverables - ensure always returns array
     */
    public function getDeliverablesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * accessor untuk facilities_provided - ensure always returns array
     */
    public function getFacilitiesProvidedAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

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
     * relasi ke applications
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * relasi ke problem images
     */
    public function images()
    {
        return $this->hasMany(ProblemImage::class)->orderBy('order');
    }

    /**
     * relasi ke wishlists
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * relasi ke students yang wishlist problem ini (many-to-many)
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(Student::class, 'wishlists')
                    ->withTimestamps()
                    ->withPivot('notes');
    }

    /**
     * scope untuk filter by status
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
                    ->where('application_deadline', '>=', now());
    }

    /**
     * scope untuk filter by difficulty
     */
    public function scopeDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    /**
     * scope untuk filter by location
     */
    public function scopeInProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * scope untuk filter by regency
     */
    public function scopeInRegency($query, $regencyId)
    {
        return $query->where('regency_id', $regencyId);
    }

    /**
     * scope untuk filter by SDG category
     */
    public function scopeHasSdg($query, $sdgNumber)
    {
        return $query->whereJsonContains('sdg_categories', $sdgNumber);
    }

    /**
     * scope untuk search
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhereHas('institution', function($q) use ($keyword) {
                  $q->where('name', 'like', "%{$keyword}%");
              });
        });
    }

    /**
     * increment views counter
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * cek apakah masih menerima aplikasi
     */
    public function isAcceptingApplications(): bool
    {
        return $this->status === 'open' 
            && $this->application_deadline >= now()
            && $this->accepted_students < $this->required_students;
    }

    /**
     * get status badge color class
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'open' => 'bg-green-100 text-green-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'closed' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }

    /**
     * get difficulty badge color class
     */
    public function getDifficultyBadgeColor(): string
    {
        return match($this->difficulty_level) {
            'beginner' => 'bg-green-100 text-green-800',
            'intermediate' => 'bg-yellow-100 text-yellow-800',
            'advanced' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * get difficulty label
     */
    public function getDifficultyLabel(): string
    {
        return match($this->difficulty_level) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Lanjut',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * get days until deadline
     */
    public function getDaysUntilDeadline(): int
    {
        return now()->diffInDays($this->application_deadline, false);
    }

    /**
     * cek apakah deadline sudah lewat
     */
    public function isDeadlinePassed(): bool
    {
        return $this->application_deadline < now();
    }

    /**
     * get remaining slots
     */
    public function getRemainingSlots(): int
    {
        return max(0, $this->required_students - $this->accepted_students);
    }

    /**
     * get fill percentage
     */
    public function getFillPercentage(): int
    {
        if ($this->required_students == 0) return 0;
        return (int) (($this->accepted_students / $this->required_students) * 100);
    }
}