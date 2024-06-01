<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'overview',
        'run_time',
        'release_date',
        'total_likes',
        'total_ratings',
        'average_rating',
        'trailer_url',
        'popularity',
        'poster_image',
        'cover_image',
        'terms_status',
        'upload_status',
        'last_upload_date',
        'user_subscription',
        'expire_subscription',
    ];

    protected $casts = [
        'release_date' => 'date',
        'last_upload_date' => 'datetime',
        'user_subscription' => 'boolean',
        'expire_subscription' => 'date',
    ];
}
