<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieConllection extends JsonResource
{
    public function toArray($request)
    {
        return (MovieResource::collection($this->collection));
    }
}
