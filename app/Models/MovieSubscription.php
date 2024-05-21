<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieSubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'status',
        'subscription_start_date',
        'subscription_end_date',
    ];

    // Define relationship with Movie model
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
