<?php

namespace App\Http\Resources\MovieCountry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCountryResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return (MovieCountryResource::collection($this->collection));
    }
}
