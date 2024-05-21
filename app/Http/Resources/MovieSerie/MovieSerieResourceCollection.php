<?php

namespace App\Http\Resources\MovieSerie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieSerieResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (MovieSerieResource::collection($this->collection));
    }
}
