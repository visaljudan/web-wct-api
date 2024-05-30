<?php

namespace App\Http\Resources\TvShow;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TvShowResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return (TvShowResource::collection($this->collection));
    }
}
