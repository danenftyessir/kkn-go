<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * model untuk tabel applications
 * file proposal disimpan langsung di database sebagai base64
 * 
 * path: app/Models/Application.php
 */
class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'problem_id',
        'status',
        'proposal_path', // deprecated, untuk backward compatibility
        'proposal_content', // base64 encoded file content
        'proposal_filename', // nama file original
        'proposal_mime_type', // mime type file
        'proposal_size', // ukuran file dalam bytes
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
     * cek apakah aplikasi memiliki proposal
     */
    public function hasProposal(): bool
    {
        return !empty($this->proposal_content);
    }

    /**
     * get ukuran file dalam format readable
     */
    public function getProposalSizeFormattedAttribute(): string
    {
        if (!$this->proposal_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->proposal_size;
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
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
}