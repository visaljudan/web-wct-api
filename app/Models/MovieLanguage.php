<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieLanguage extends Model
{
    use HasFactory;
    protected $fillable = ['movie_id', 'language_code'];
}
