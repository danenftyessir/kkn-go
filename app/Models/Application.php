<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * model untuk tabel applications
 * 
 * path: app/Models/Application.php (UPDATED)
 */
class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'problem_id',
        'status',
        'proposal_path',
        'cover_letter',
        'motivation',
        'applied_at',
        'reviewed_at',
        'accepted_at',
        'rejected_at',
        'feedback',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

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
     * relasi ke project (jika aplikasi diterima)
     */
    public function project()
    {
        return $this->hasOne(Project::class);
    }

    /**
     * scope untuk filter by status
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * scope untuk aplikasi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * scope untuk aplikasi accepted
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * scope untuk aplikasi rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * mark application as reviewed
     */
    public function markAsReviewed()
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);
    }

    /**
     * mark application as accepted
     */
    public function markAsAccepted($feedback = null)
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'feedback' => $feedback,
        ]);
    }

    /**
     * mark application as rejected
     */
    public function markAsRejected($feedback = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'feedback' => $feedback,
        ]);
    }

    /**
     * get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => ['text' => 'Pending', 'color' => 'yellow'],
            'reviewed' => ['text' => 'Direview', 'color' => 'blue'],
            'accepted' => ['text' => 'Diterima', 'color' => 'green'],
            'rejected' => ['text' => 'Ditolak', 'color' => 'red'],
            default => ['text' => 'Unknown', 'color' => 'gray'],
        };
    }

    /**
     * cek apakah aplikasi bisa di-withdraw
     */
    public function canWithdraw()
    {
        return in_array($this->status, ['pending', 'reviewed']);
    }

    /**
     * cek apakah aplikasi sudah memiliki project
     */
    public function hasProject()
    {
        return $this->project()->exists();
    }
}