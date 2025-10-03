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
        'completed_at',
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
        'completed_at' => 'datetime',
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
     * relasi ke institution review (dari tabel reviews)
     * review yang diberikan institution ke student untuk project ini
     */
    public function institutionReview()
    {
        return $this->hasOne(Review::class, 'project_id')
                    ->where('type', 'institution_to_student');
    }

    /**
     * relasi ke student review (dari tabel reviews)
     * review yang diberikan student ke institution untuk project ini
     */
    public function studentReview()
    {
        return $this->hasOne(Review::class, 'project_id')
                    ->where('type', 'student_to_institution');
    }

    /**
     * relasi ke semua reviews untuk project ini
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'project_id');
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
        return $this->hasMany(ProjectReport::class)->orderBy('created_at', 'desc');
    }

    /**
     * relasi ke documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * scope untuk filter by status
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * scope untuk ongoing projects
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * scope untuk completed projects
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * scope untuk portfolio visible projects
     */
    public function scopePortfolioVisible($query)
    {
        return $query->where('is_portfolio_visible', true);
    }

    /**
     * update progress percentage berdasarkan milestones
     */
    public function updateProgress()
    {
        $totalMilestones = $this->milestones()->count();
        
        if ($totalMilestones === 0) {
            return;
        }

        $completedMilestones = $this->milestones()->completed()->count();
        $progressPercentage = round(($completedMilestones / $totalMilestones) * 100);

        $this->update(['progress_percentage' => $progressPercentage]);
    }

    /**
     * mark project as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'actual_end_date' => now(),
            'progress_percentage' => 100,
        ]);
    }

    /**
     * submit final report
     */
    public function submitFinalReport($filePath, $summary)
    {
        $this->update([
            'final_report_path' => $filePath,
            'final_report_summary' => $summary,
            'submitted_at' => now(),
        ]);
    }

    /**
     * get status badge info
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => ['text' => 'Menunggu', 'color' => 'yellow'],
            'ongoing' => ['text' => 'Berlangsung', 'color' => 'blue'],
            'completed' => ['text' => 'Selesai', 'color' => 'green'],
            'cancelled' => ['text' => 'Dibatalkan', 'color' => 'red'],
            default => ['text' => 'Unknown', 'color' => 'gray'],
        };
    }

    /**
     * get progress status
     */
    public function getProgressStatusAttribute()
    {
        if ($this->status === 'completed') {
            return 'Selesai';
        }

        if ($this->progress_percentage >= 75) {
            return 'Hampir Selesai';
        } elseif ($this->progress_percentage >= 50) {
            return 'Setengah Jalan';
        } elseif ($this->progress_percentage >= 25) {
            return 'Tahap Awal';
        } else {
            return 'Baru Dimulai';
        }
    }

    /**
     * check jika project overdue
     */
    public function isOverdue()
    {
        if ($this->status === 'completed') {
            return false;
        }

        return now()->isAfter($this->end_date);
    }

    /**
     * get days remaining
     */
    public function getDaysRemaining()
    {
        if ($this->status === 'completed') {
            return 0;
        }

        $days = now()->diffInDays($this->end_date, false);
        return max(0, $days);
    }
}