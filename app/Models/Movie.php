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
        'total_raters',
        'total_ratings',
        'average_rating',
        'popularity',
        'poster_image_file',
        'poster_image_url',
        'cover_image',
        'terms_status',
        'upload_status',
        'last_upload_date',
    ];

    public function tv_show()
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }
}
