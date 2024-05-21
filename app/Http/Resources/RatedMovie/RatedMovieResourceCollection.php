<?php

namespace App\Http\Resources\RatedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RatedMovieResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (RatedMovieResource::collection($this->collection));
    }
}
