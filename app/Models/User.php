<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification;
/**
 * model user
 * 
 * model utama untuk autentikasi user di sistem
 * support multi-type user: student, institution, admin
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * attributes yang dapat diisi mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'user_type',
        'is_active',
        'email_verified_at',
        'email_verification_token', 
    ];

    /**
     * attributes yang harus disembunyikan saat serialisasi
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * attributes yang di-cast ke tipe data tertentu
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * relasi ke student (one to one)
     * 
     * setiap user dengan user_type = 'student' memiliki 1 data student
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * relasi ke institution (one to one)
     * 
     * setiap user dengan user_type = 'institution' memiliki 1 data institution
     */
    public function institution()
    {
        return $this->hasOne(Institution::class);
    }

    /**
     * cek apakah user adalah student
     */
    public function isStudent(): bool
    {
        return $this->user_type === 'student';
    }

    /**
     * cek apakah user adalah institution
     */
    public function isInstitution(): bool
    {
        return $this->user_type === 'institution';
    }

    /**
     * cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    /**
     * scope untuk filter berdasarkan user type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * scope untuk filter user yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * scope untuk filter user yang sudah verified email
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * get display name
     * 
     * untuk student: nama lengkap dari data student
     * untuk institution: nama instansi
     * fallback: field name dari user
     */
    public function getDisplayName(): string
    {
        if ($this->isStudent() && $this->student) {
            return $this->student->getFullName();
        }
        
        if ($this->isInstitution() && $this->institution) {
            return $this->institution->name;
        }
        
        return $this->name;
    }

    /**
     * get profile photo url
     * 
     * untuk student: foto dari data student
     * untuk institution: logo dari data institution
     */
    public function getProfilePhotoUrl(): ?string
    {
        if ($this->isStudent() && $this->student) {
            return $this->student->getProfilePhotoUrl();
        }
        
        if ($this->isInstitution() && $this->institution) {
            return $this->institution->getLogoUrl();
        }
        
        return null;
    }

    /**
     * cek apakah user sudah melengkapi profil
     */
    public function hasCompletedProfile(): bool
    {
        if ($this->isStudent()) {
            return $this->student !== null;
        }
        
        if ($this->isInstitution()) {
            return $this->institution !== null;
        }
        
        return false;
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * dapatkan notifikasi yang belum dibaca
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false)->latest();
    }

    /**
     * hitung jumlah notifikasi yang belum dibaca
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}

