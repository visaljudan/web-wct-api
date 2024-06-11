<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'type',
        'offical',
        'subscription',
        'subscription_start_date',
        'subscription_end_date',
        'video_path',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
