<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * model Problem
 * untuk menyimpan masalah/proyek yang dipublikasikan oleh instansi
 */
class Problem extends Model
{
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
        'sdg_categories',
        'required_students',
        'required_skills',
        'required_majors',
        'start_date',
        'end_date',
        'application_deadline',
        'duration_months',
        'difficulty_level',
        'expected_outcomes',
        'deliverables',
        'facilities_provided',
        'contact_person',
        'contact_phone',
        'contact_email',
        'status',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // relasi ke institution
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    // relasi ke province
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    // relasi ke regency
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    // relasi ke images
    public function images(): HasMany
    {
        return $this->hasMany(ProblemImage::class)->orderBy('order', 'asc');
    }

    // relasi ke applications
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // relasi ke wishlists
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // relasi ke projects
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * accessor untuk mendapatkan gambar pertama
     * 
     * usage di blade: 
     * <img src="{{ $problem->first_image_url }}" alt="{{ $problem->title }}">
     * 
     * @return string URL gambar pertama atau placeholder
     */
    public function getFirstImageUrlAttribute(): string
    {
        $firstImage = $this->images()->first();
        
        if ($firstImage) {
            return $firstImage->image_url;
        }
        
        // return placeholder jika tidak ada gambar
        return asset('images/placeholder-problem.jpg');
    }

    /**
     * accessor untuk mendapatkan semua URLs gambar
     * 
     * usage di blade:
     * @foreach($problem->image_urls as $imageUrl)
     *     <img src="{{ $imageUrl }}" alt="{{ $problem->title }}">
     * @endforeach
     * 
     * @return array array of image URLs
     */
    public function getImageUrlsAttribute(): array
    {
        return $this->images->map(function($image) {
            return $image->image_url;
        })->toArray();
    }

    /**
     * accessor untuk mendapatkan label SDG categories dalam bahasa indonesia
     * 
     * usage di blade:
     * @foreach($problem->sdg_labels as $label)
     *     <span>{{ $label }}</span>
     * @endforeach
     * 
     * @return array array of SDG labels
     */
    public function getSdgLabelsAttribute(): array
    {
        if (!$this->sdg_categories || !is_array($this->sdg_categories)) {
            return [];
        }
        
        return array_map(function($category) {
            return sdg_label($category);
        }, $this->sdg_categories);
    }

    /**
     * accessor untuk cek apakah deadline sudah dekat (kurang dari 7 hari)
     * 
     * usage di blade: @if($problem->is_deadline_near) ... @endif
     * 
     * @return bool
     */
    public function getIsDeadlineNearAttribute(): bool
    {
        if (!$this->application_deadline) {
            return false;
        }
        
        return $this->application_deadline->diffInDays(now()) <= 7;
    }

    /**
     * accessor untuk cek apakah sudah full
     * 
     * usage di blade: @if($problem->is_full) ... @endif
     * 
     * @return bool
     */
    public function getIsFullAttribute(): bool
    {
        return $this->accepted_students >= $this->required_students;
    }

    /**
     * accessor untuk slot yang tersisa
     * 
     * usage di blade: {{ $problem->remaining_slots }} slot tersisa
     * 
     * @return int
     */
    public function getRemainingSlotsAttribute(): int
    {
        return max(0, $this->required_students - $this->accepted_students);
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
              ->orWhere('village', 'like', "%{$keyword}%")
              ->orWhereHas('institution', function($q) use ($keyword) {
                  $q->where('name', 'like', "%{$keyword}%");
              });
        });
    }

    /**
     * scope untuk filter by SDG categories
     */
    public function scopeWithSdgCategories($query, array $categories)
    {
        return $query->where(function($q) use ($categories) {
            foreach ($categories as $category) {
                $q->orWhereJsonContains('sdg_categories', $category);
            }
        });
    }

    /**
     * scope untuk filter by difficulty level
     */
    public function scopeWithDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * scope untuk filter by duration
     */
    public function scopeWithDuration($query, $minMonths, $maxMonths = null)
    {
        $query->where('duration_months', '>=', $minMonths);
        
        if ($maxMonths !== null) {
            $query->where('duration_months', '<=', $maxMonths);
        }
        
        return $query;
    }

    /**
     * increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * increment applications count
     */
    public function incrementApplications(): void
    {
        $this->increment('applications_count');
    }

    /**
     * increment accepted students count
     */
    public function incrementAcceptedStudents(): void
    {
        $this->increment('accepted_students');
    }

    /**
     * decrement accepted students count
     */
    public function decrementAcceptedStudents(): void
    {
        $this->decrement('accepted_students');
    }
}