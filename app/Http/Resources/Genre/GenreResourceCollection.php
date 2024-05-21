<?php

namespace App\Http\Resources\Genre;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenreResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (GenreResource::collection($this->collection));
    }
}
