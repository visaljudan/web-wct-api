<?php

namespace App\Http\Resources\MovieGenre;

use App\Models\MovieGenre;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieGenreResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (MovieGenreResource::collection($this->collection));
    }
}
