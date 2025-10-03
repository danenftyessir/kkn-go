<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * model untuk tabel project_reports
 * mengelola laporan progress proyek dari mahasiswa
 * 
 * path: app/Models/ProjectReport.php
 */
class ProjectReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'student_id',
        'type',
        'title',
        'summary',
        'activities',
        'challenges',
        'next_plans',
        'period_start',
        'period_end',
        'document_path',
        'photos',
        'status',
        'institution_feedback',
        'reviewed_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'photos' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * relasi ke project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * relasi ke student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * scope untuk filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * scope untuk filter pending review
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * scope untuk filter approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * mark as reviewed
     */
    public function markAsReviewed($status, $feedback = null)
    {
        $this->update([
            'status' => $status,
            'institution_feedback' => $feedback,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * get status badge info
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => ['text' => 'Menunggu Review', 'color' => 'yellow'],
            'reviewed' => ['text' => 'Direview', 'color' => 'blue'],
            'approved' => ['text' => 'Disetujui', 'color' => 'green'],
            'revision_needed' => ['text' => 'Perlu Revisi', 'color' => 'red'],
            default => ['text' => 'Unknown', 'color' => 'gray'],
        };
    }

    /**
     * get type label
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'weekly' => 'Laporan Mingguan',
            'monthly' => 'Laporan Bulanan',
            'final' => 'Laporan Akhir',
            default => ucfirst($this->type),
        };
    }

    /**
     * get period duration
     */
    public function getPeriodDurationAttribute()
    {
        return $this->period_start->diffInDays($this->period_end);
    }
}