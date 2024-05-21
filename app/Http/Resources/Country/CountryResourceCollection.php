<?php

namespace App\Http\Resources\Country;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (CountryResource::collection($this->collection));
    }
}
