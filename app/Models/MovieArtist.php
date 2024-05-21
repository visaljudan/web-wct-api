<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieArtist extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'artist_id',
        'role_id',
        'movie_artist_name'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
