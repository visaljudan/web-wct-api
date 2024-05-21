<?php

namespace App\Http\Resources\RatedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatedMovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'movie_id' => $this->movie_id,
            'rated_value' => $this->rated_value,
        ];
    }
}
