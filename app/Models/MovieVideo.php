<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        // 'video_file',
        // 'video_url',
        'video',
        'season_number',
        'episode_number',
        'part_number',
        'type',
        'official',
        'subscription',
        'subscription_start_date',
        'subscription_end_date',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
