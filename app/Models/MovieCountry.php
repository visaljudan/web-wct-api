<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'country_code',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'country_code');
    }
}
