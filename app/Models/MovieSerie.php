<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSerie extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'season_number',
        'episode_number',
    ];

    // Define relationships if needed
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
