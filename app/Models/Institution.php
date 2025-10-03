<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Institution
 */
class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'institution_name',
        'institution_type',
        'address',
        'province_id',
        'regency_id',
        'official_email',
        'phone_number',
        'logo_path',
        'pic_name',
        'pic_position',
        'verification_document_path',
        'website',
        'description',
        'is_verified',
        'verified_at',
    ];

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
     */
    public function getLogoUrl(): string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        
        // default logo jika tidak ada
        return asset('images/default-institution-logo.png');
    }
}