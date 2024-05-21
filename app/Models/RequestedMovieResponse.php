<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedMovieResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_movie_id',
        'user_id',
        'response_message',
        'response_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestedMovie()
    {
        return $this->belongsTo(RequestedMovie::class);
    }
}
