<?php

namespace App\Http\Resources\MovieVideo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieVideoResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return (MovieVideoResource::collection($this->collection));
    }
}
