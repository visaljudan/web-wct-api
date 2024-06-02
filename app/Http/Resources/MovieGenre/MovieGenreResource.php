<?php

namespace App\Http\Resources\MovieGenre;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieGenreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'movie_id' => $this->movie_id,
            'genre_id' => $this->genre_id,
            'genre_name' => $this->genre->genre_name,
        ];
    }
}
