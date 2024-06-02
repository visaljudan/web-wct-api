<?php

namespace App\Http\Resources\Year;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class YearResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (YearResource::collection($this->collection));
    }
}
