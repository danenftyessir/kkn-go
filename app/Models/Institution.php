<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Institution
 * 
 * representasi institusi/lembaga yang menerbitkan masalah KKN
 */
class Institution extends Model
{
    use HasFactory;

    /**
     * attributes yang dapat diisi mass assignment
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'address',
        'province_id',
        'regency_id',
        'email',
        'phone',
        'logo_path',
        'pic_name',
        'pic_position',
        'verification_document_path',
        'website',
        'description',
        'is_verified',
        'verified_at',
    ];

    /**
     * attributes yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * relasi ke problems
     */
    public function problems()
    {
        return $this->hasMany(Problem::class);
    }

    /**
     * get full address
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->address,
            $this->regency?->name,
            $this->province?->name,
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * get logo URL
     * PERBAIKAN BUG: sekarang support Supabase storage
     */
    public function getLogoUrl(): string
    {
        if ($this->logo_path) {
            // cek apakah path sudah berupa URL lengkap
            if (str_starts_with($this->logo_path, 'http')) {
                return $this->logo_path;
            }
            
            // cek apakah ini adalah path dari Supabase (tidak mengandung 'public/')
            if (!str_starts_with($this->logo_path, 'public/')) {
                // ini adalah path Supabase, gunakan SupabaseStorageService
                $storageService = app(\App\Services\SupabaseStorageService::class);
                return $storageService->getPublicUrl($this->logo_path);
            }
            
            // fallback ke local storage
            return asset('storage/' . str_replace('public/', '', $this->logo_path));
        }
        
        // default logo jika tidak ada
        return asset('images/default-institution-logo.png');
    }

    /**
     * get logo URL sebagai accessor untuk compatibility
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->getLogoUrl();
    }

    /**
     * get total masalah yang dipublikasikan
     */
    public function getTotalProblemsAttribute()
    {
        return $this->problems()->count();
    }

    /**
     * get total masalah yang aktif
     */
    public function getActiveProblemsAttribute()
    {
        return $this->problems()
            ->whereIn('status', ['open', 'in_progress'])
            ->count();
    }
}