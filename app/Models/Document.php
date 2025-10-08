<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * model Document
 * untuk menyimpan dokumen hasil KKN (laporan, final report, dll)
 * 
 * CATATAN PENTING: file disimpan di Supabase Storage
 * - Bucket: "kkn-go storage"
 * - Path: documents/reports/FILENAME.pdf
 * - Akses: Public URL via supabase_url() helper
 */
class Document extends Model
{
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // relasi ke project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // relasi ke uploader (user)
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

    /**
     * accessor untuk mendapatkan URL publik file dari Supabase
     * 
     * usage di blade: {{ $document->file_url }}
     * 
     * @return string URL publik file
     */
    public function getFileUrlAttribute(): string
    {
        // gunakan helper supabase_url untuk generate public URL
        return supabase_url($this->file_path);
    }

    /**
     * accessor untuk mendapatkan ukuran file dalam format readable
     * 
     * usage di blade: {{ $document->file_size_formatted }}
     * 
     * @return string ukuran file (contoh: "2.5 MB")
     */
    public function getFileSizeFormattedAttribute(): string
    {
        return format_file_size($this->file_size ?? 0);
    }

    /**
     * accessor untuk mendapatkan label kategori SDG dalam bahasa indonesia
     * 
     * usage di blade: 
     * @foreach($document->categories_labels as $label)
     *     {{ $label }}
     * @endforeach
     * 
     * @return array array of SDG labels
     */
    public function getCategoriesLabelsAttribute(): array
    {
        if (!$this->categories || !is_array($this->categories)) {
            return [];
        }
        
        return array_map(function($category) {
            return sdg_label($category);
        }, $this->categories);
    }

    /**
     * scope untuk filter dokumen yang dipublikasikan
     * 
     * usage: Document::published()->get()
     */
    public function scopePublished($query)
    {
        return $query->where('is_public', true)
                    ->where('status', 'approved');
    }

    /**
     * scope untuk filter dokumen featured
     * 
     * usage: Document::featured()->get()
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where('is_public', true)
                    ->where('status', 'approved');
    }

    /**
     * scope untuk search dokumen
     * 
     * usage: Document::search('keyword')->get()
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('author_name', 'like', "%{$keyword}%")
              ->orWhere('institution_name', 'like', "%{$keyword}%")
              ->orWhere('university_name', 'like', "%{$keyword}%");
        });
    }

    /**
     * increment download count
     */
    public function incrementDownloads(): void
    {
        $this->increment('download_count');
    }

    /**
     * increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    /**
     * increment citation count
     */
    public function incrementCitations(): void
    {
        $this->increment('citation_count');
    }
}