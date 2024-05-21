<?php

namespace App\Http\Resources\MovieArtist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieArtistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'artist_id' => $this->artist_id,
            'role_id' => $this->role_id,
            'movie_artist_name' => $this->movie_artist_name,
        ];
    }
}
