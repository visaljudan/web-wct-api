<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        // 'profile_image_file',
        // 'profile_image_url',
        'profile_image',

    ];

    // public function getProfileImageAttribute()
    // {
    //     return $this->profile_image_url ?: $this->profile_image_file;
    // }
}
