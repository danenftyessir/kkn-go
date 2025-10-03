<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;

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
        'duration_months',
        'application_deadline',
        'start_date',
        'end_date',
        'sdg_categories',
        'difficulty_level',
        'facilities_provided',
        'expected_outcomes',
        'status',
        'is_featured',
        'is_urgent',
        'views_count',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'sdg_categories' => 'array',
        'application_deadline' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
     * scope untuk problem yang open/terbuka
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
                    ->where('application_deadline', '>=', now());
    }

    /**
     * scope untuk search
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('background', 'like', "%{$keyword}%")
              ->orWhere('village', 'like', "%{$keyword}%");
        });
    }

    /**
     * increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * cek apakah problem sudah disimpan/wishlist oleh student tertentu
     */
    public function isSavedBy($student)
    {
        if (!$student) {
            return false;
        }

        try {
            return $this->wishlists()
                        ->where('student_id', $student->id)
                        ->exists();
        } catch (\Exception $e) {
            // jika table wishlists belum ada atau error
            return false;
        }
    }

    /**
     * get aplikasi count untuk problem ini
     */
    public function getApplicationsCountAttribute()
    {
        return $this->applications()->count();
    }

    /**
     * get sisa slot mahasiswa
     */
    public function getRemainingSlots()
    {
        $acceptedCount = $this->applications()
                             ->where('status', 'accepted')
                             ->count();
        
        return max(0, $this->required_students - $acceptedCount);
    }

    /**
     * cek apakah masih ada slot tersedia
     */
    public function hasSlotsAvailable()
    {
        return $this->getRemainingSlots() > 0;
    }

    /**
     * get badge color berdasarkan difficulty
     */
    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty_level) {
            'beginner' => 'green',
            'intermediate' => 'yellow',
            'advanced' => 'red',
            default => 'gray'
        };
    }

    /**
     * get badge color class untuk difficulty
     */
    public function getDifficultyBadgeColor()
    {
        return match($this->difficulty_level) {
            'beginner' => 'bg-green-100 text-green-700',
            'intermediate' => 'bg-yellow-100 text-yellow-700',
            'advanced' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700'
        };
    }

    /**
     * get difficulty label dalam bahasa indonesia
     */
    public function getDifficultyLabel()
    {
        return match($this->difficulty_level) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Lanjutan',
            default => ucfirst($this->difficulty_level)
        };
    }

    /**
     * get status label dalam bahasa indonesia
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'open' => 'Terbuka',
            'in_progress' => 'Berlangsung',
            'completed' => 'Selesai',
            'closed' => 'Ditutup',
            default => ucfirst($this->status)
        };
    }

    /**
     * cek apakah deadline sudah dekat (7 hari atau kurang)
     */
    public function isDeadlineNear()
    {
        $daysLeft = now()->diffInDays($this->application_deadline, false);
        return $daysLeft <= 7 && $daysLeft >= 0;
    }

    /**
     * get koordinat untuk map (default jika tidak ada)
     */
    public function getMapCoordinates()
    {
        return [
            'lat' => $this->latitude ?? $this->getDefaultLatitude(),
            'lng' => $this->longitude ?? $this->getDefaultLongitude()
        ];
    }

    /**
     * get default latitude berdasarkan province
     * TODO: isi dengan koordinat real per province
     */
    private function getDefaultLatitude()
    {
        // sementara return koordinat tengah Indonesia
        return -2.5;
    }

    /**
     * get default longitude berdasarkan province
     * TODO: isi dengan koordinat real per province
     */
    private function getDefaultLongitude()
    {
        // sementara return koordinat tengah Indonesia
        return 118;
    }
}