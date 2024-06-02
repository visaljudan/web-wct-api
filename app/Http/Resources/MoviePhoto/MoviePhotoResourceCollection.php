<?php

namespace App\Http\Resources\MoviePhoto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MoviePhotoResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (MoviePhotoResource::collection($this->collection));
    }
}
