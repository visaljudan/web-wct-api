<?php

namespace App\Http\Resources\Artist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'artist_name' => $this->artist_name,
            'artist_profile' => $this->artist_profile,
        ];
    }
}
