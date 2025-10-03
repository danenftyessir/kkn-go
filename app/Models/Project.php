<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * model untuk tabel projects
 * mengelola proyek KKN yang sedang/sudah dikerjakan mahasiswa
 * 
 * path: app/Models/Project.php
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'student_id',
        'problem_id',
        'institution_id',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'actual_start_date',
        'actual_end_date',
        'progress_percentage',
        'final_report_path',
        'final_report_summary',
        'submitted_at',
        'rating',
        'institution_review',
        'reviewed_at',
        'impact_metrics',
        'is_portfolio_visible',
        'is_featured',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'impact_metrics' => 'array',
        'is_portfolio_visible' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * relasi ke application
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * relasi ke student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * relasi ke problem
     */
    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    /**
     * relasi ke institution
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * relasi ke milestones
     */
    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('order');
    }

    /**
     * relasi ke reports
     */
    public function reports()
    {
        return $this->hasMany(ProjectReport::class)->latest();
    }

    /**
     * relasi ke documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * relasi ke reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * scope untuk filter proyek aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * scope untuk filter proyek completed
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * scope untuk filter proyek visible di portfolio
     */
    public function scopePortfolioVisible($query)
    {
        return $query->where('is_portfolio_visible', true)
                     ->where('status', 'completed');
    }

    /**
     * scope untuk search by title
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'like', '%' . $keyword . '%')
                     ->orWhere('description', 'like', '%' . $keyword . '%');
    }

    /**
     * hitung durasi proyek dalam hari
     */
    public function getDurationDaysAttribute()
    {
        if ($this->actual_end_date && $this->actual_start_date) {
            return $this->actual_start_date->diffInDays($this->actual_end_date);
        }
        
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * cek apakah proyek overdue
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'completed') {
            return false;
        }
        
        return now()->isAfter($this->end_date);
    }

    /**
     * hitung progress dari milestones
     */
    public function calculateProgress()
    {
        $milestones = $this->milestones;
        
        if ($milestones->isEmpty()) {
            return 0;
        }
        
        $totalProgress = $milestones->sum('progress_percentage');
        return round($totalProgress / $milestones->count());
    }

    /**
     * update progress percentage
     */
    public function updateProgress()
    {
        $this->progress_percentage = $this->calculateProgress();
        $this->save();
    }

    /**
     * cek apakah sudah ada review dari institusi
     */
    public function hasInstitutionReview()
    {
        return $this->reviews()
                    ->where('type', 'institution_to_student')
                    ->exists();
    }

    /**
     * get average rating dari review
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()
                    ->where('type', 'institution_to_student')
                    ->avg('rating') ?? 0;
    }
}