<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoviePhoto extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        // 'photo_image_file',
        // 'photo_image_url',
        'photo_image',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // public function getPhotoImageAttribute()
    // {
    //     return $this->photo_image_url ?: $this->photo_image_file;
    // }
}
