<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];

    /**
     * attributes yang di-cast ke tipe data tertentu
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
     * relasi ke notifikasi
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
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
     * get profile data berdasarkan user type
     */
    public function getProfileAttribute()
    {
        if ($this->isStudent()) {
            return $this->student;
        } elseif ($this->isInstitution()) {
            return $this->institution;
        }
        
        return null;
    }

    /**
     * get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->isStudent() && $this->student?->profile_photo_path) {
            return asset('storage/' . $this->student->profile_photo_path);
        } elseif ($this->isInstitution() && $this->institution?->logo_path) {
            return asset('storage/' . $this->institution->logo_path);
        }
        
        // default avatar
        return asset('images/default-avatar.png');
    }

    /**
     * get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()
            ->where('is_read', false)
            ->count();
    }
}