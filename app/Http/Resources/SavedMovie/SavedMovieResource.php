<?php

namespace App\Http\Resources\SavedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedMovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'movie_id' => $this->movie_id
        ];
    }
}
