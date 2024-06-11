<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (MovieResource::collection($this->collection));
    }
}
