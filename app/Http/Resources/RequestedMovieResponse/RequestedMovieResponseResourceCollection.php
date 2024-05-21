<?php

namespace App\Http\Resources\RequestedMovieResponse;

use App\Http\Resources\RequestedMovie\RequestedMovieResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RequestedMovieResponseResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (RequestedMovieResource::collection($this->collection));
    }
}
