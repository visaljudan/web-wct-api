<?php

namespace App\Http\Resources\MovieArtist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieArtistResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (MovieArtistResource::collection($this->collection));
    }
}
