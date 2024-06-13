<?php

namespace App\Http\Resources\RequestedMovieResponse;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RequestedMovieResponseResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (RequestedMovieResponseResource::collection($this->collection));
    }
}
