<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * model Problem
 */
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
     * prioritas: is_cover = true, fallback ke gambar pertama
     * 
     * usage di blade: {{ $problem->coverImage->image_url }}
     * 
     * @return ProblemImage|null
     */
    public function getCoverImageAttribute(): ?ProblemImage
    {
        // cari gambar dengan is_cover = true
        $coverImage = $this->images->where('is_cover', true)->first();
        
        // fallback ke gambar pertama jika tidak ada cover
        if (!$coverImage) {
            $coverImage = $this->images->first();
        }
        
        return $coverImage;
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
        $sdgLabels = [
            1 => 'Tanpa Kemiskinan',
            2 => 'Tanpa Kelaparan',
            3 => 'Kehidupan Sehat Dan Sejahtera',
            4 => 'Pendidikan Berkualitas',
            5 => 'Kesetaraan Gender',
            6 => 'Air Bersih Dan Sanitasi Layak',
            7 => 'Energi Bersih Dan Terjangkau',
            8 => 'Pekerjaan Layak Dan Pertumbuhan Ekonomi',
            9 => 'Industri, Inovasi, Dan Infrastruktur',
            10 => 'Berkurangnya Kesenjangan',
            11 => 'Kota Dan Komunitas Berkelanjutan',
            12 => 'Konsumsi Dan Produksi Bertanggung Jawab',
            13 => 'Penanganan Perubahan Iklim',
            14 => 'Ekosistem Lautan',
            15 => 'Ekosistem Daratan',
            16 => 'Perdamaian, Keadilan, Dan Kelembagaan Yang Tangguh',
            17 => 'Kemitraan Untuk Mencapai Tujuan',
        ];

        $labels = [];
        foreach ($this->sdg_categories ?? [] as $sdgNumber) {
            if (isset($sdgLabels[$sdgNumber])) {
                $labels[] = $sdgLabels[$sdgNumber];
            }
        }

        return $labels;
    }

    /**
     * scope untuk filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * scope untuk problem yang sedang open
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
                    ->where('application_deadline', '>=', now());
    }

    /**
     * scope untuk filter by difficulty
     */
    public function scopeDifficulty($query, string $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    /**
     * scope untuk filter by province
     */
    public function scopeProvince($query, int $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * scope untuk filter by regency
     */
    public function scopeRegency($query, int $regencyId)
    {
        return $query->where('regency_id', $regencyId);
    }

    /**
     * increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }
}