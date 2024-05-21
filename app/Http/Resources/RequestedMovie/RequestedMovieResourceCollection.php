<?php

namespace App\Http\Resources\RequestedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RequestedMovieResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (RequestedMovieResource::collection($this->collection));
    }
}
