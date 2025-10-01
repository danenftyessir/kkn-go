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
        return $this->hasMany(ProblemImage::class);
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
     * cek apakah problem masih open untuk aplikasi
     */
    public function isOpenForApplication(): bool
    {
        return $this->status === 'open' 
            && $this->application_deadline >= now()
            && $this->accepted_students < $this->required_students;
    }

    /**
     * hitung sisa slot mahasiswa
     */
    public function getRemainingSlots(): int
    {
        return max(0, $this->required_students - $this->accepted_students);
    }

    /**
     * format durasi proyek
     */
    public function getFormattedDuration(): string
    {
        if ($this->duration_months === 1) {
            return '1 bulan';
        }
        return "{$this->duration_months} bulan";
    }

    /**
     * increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * get difficulty badge color
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
            'advanced' => 'Lanjutan',
            default => 'Tidak diketahui',
        };
    }
}