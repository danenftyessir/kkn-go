<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * model untuk tabel documents
 * mengelola dokumen untuk knowledge repository
 * 
 * path: app/Models/Document.php
 */
class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'categories',
        'tags',
        'author_name',
        'institution_name',
        'university_name',
        'year',
        'province_id',
        'regency_id',
        'download_count',
        'view_count',
        'citation_count',
        'is_public',
        'is_featured',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'categories' => 'array',
        'tags' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * relasi ke project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * relasi ke uploader
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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
     * scope untuk filter public documents
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true)
                     ->where('status', 'approved');
    }

    /**
     * scope untuk filter by status
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * scope untuk search
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'like', '%' . $keyword . '%')
                     ->orWhere('description', 'like', '%' . $keyword . '%')
                     ->orWhere('author_name', 'like', '%' . $keyword . '%');
    }

    /**
     * scope untuk filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereJsonContains('categories', (int)$category);
    }

    /**
     * scope untuk filter by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * scope untuk featured documents
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * increment download count
     */
    public function incrementDownloads()
    {
        $this->increment('download_count');
    }

    /**
     * increment view count
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * increment citation count
     */
    public function incrementCitations()
    {
        $this->increment('citation_count');
    }

    /**
     * get file size in readable format
     */
    public function getReadableFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * get file type icon
     */
    public function getFileIconAttribute()
    {
        return match($this->file_type) {
            'pdf' => 'file-text',
            'docx', 'doc' => 'file-text',
            'xlsx', 'xls' => 'file-spreadsheet',
            'pptx', 'ppt' => 'file-presentation',
            default => 'file',
        };
    }

    /**
     * approve document
     */
    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}