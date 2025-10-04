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
        'institution_notes',
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
     * get status badge color class (untuk compatibility dengan view lama)
     */
    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'reviewed' => 'bg-blue-100 text-blue-700 border-blue-300',
            'accepted' => 'bg-green-100 text-green-700 border-green-300',
            'rejected' => 'bg-red-100 text-red-700 border-red-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    /**
     * get status label (untuk compatibility dengan view lama)
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Direview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
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