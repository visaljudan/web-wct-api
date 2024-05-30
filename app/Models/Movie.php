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
        'popularity',
        'poster_image',
        'cover_image',
        'trailer_url',
        'last_upload_date',
        'subscription_only',
        'expired_subscription_only',
    ];

    protected $casts = [
        'release_date' => 'date',
        'last_upload_date' => 'datetime',
        'subscription_only' => 'boolean',
        'expired_subscription_only' => 'date',
    ];
}
