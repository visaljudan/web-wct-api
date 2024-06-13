<?php

namespace App\Http\Resources\HistoriedMovie;

use App\Http\Resources\HistoriedMovie\HistoriedMovieResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoriedMovieResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return (HistoriedMovieResource::collection($this->collection));
    }
}
