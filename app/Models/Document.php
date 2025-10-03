<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'is_public',
        'is_featured',
        'citation_count',
    ];

    protected $casts = [
        'categories' => 'array',
        'tags' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * scope untuk dokumen publik
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * scope untuk dokumen featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->orderBy('view_count', 'desc')
                    ->orderBy('download_count', 'desc');
    }

    /**
     * scope untuk filter by kategori
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereJsonContains('categories', $category);
    }

    /**
     * scope untuk filter by tag
     */
    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * scope untuk search
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('author_name', 'like', "%{$keyword}%");
        });
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
     * get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    /**
     * accessor untuk readable file size
     */
    public function getReadableFileSizeAttribute()
    {
        return $this->getFormattedFileSizeAttribute();
    }
}