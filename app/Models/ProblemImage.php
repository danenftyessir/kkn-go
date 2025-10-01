<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProblemImage
 */
class ProblemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'problem_id',
        'image_path',
        'caption',
        'order',
    ];

    /**
     * relasi ke problem
     */
    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    /**
     * get image URL
     */
    public function getImageUrl(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
