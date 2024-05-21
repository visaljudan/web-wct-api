<?php

namespace App\Http\Resources\SavedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SavedMovieResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (SavedMovieResource::collection($this->collection));
    }
}
