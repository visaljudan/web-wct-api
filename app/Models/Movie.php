<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tv_show_id',
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
        'user_subscription' => 'boolean',
    ];

    public function tv_show()
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }
}
