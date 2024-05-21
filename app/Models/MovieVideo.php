<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'video_path',
    ];

    // Define relationship with Movie model
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
