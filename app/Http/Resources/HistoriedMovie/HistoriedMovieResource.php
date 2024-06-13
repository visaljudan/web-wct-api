<?php

namespace App\Http\Resources\HistoriedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoriedMovieResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
