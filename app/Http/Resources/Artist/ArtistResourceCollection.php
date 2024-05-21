<?php

namespace App\Http\Resources\Artist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (ArtistResource::collection($this->collection));
    }
}
