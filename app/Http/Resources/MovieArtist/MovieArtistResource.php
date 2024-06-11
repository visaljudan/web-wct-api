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
            'artist_name' => $this->artist->name,
            'artist_profile_image' => $this->artist->profile_image,
            'movie_artist_name' => $this->movie_artist_name,
            'role_id' => $this->role_id,
            'role_name' => $this->role->role_name,
        ];
    }
}
