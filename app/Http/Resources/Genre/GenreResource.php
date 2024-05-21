<?php

namespace App\Http\Resources\Genre;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'genre_name' => $this->genre_name,
        ];
    }
}
