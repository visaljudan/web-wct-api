<?php

namespace App\Http\Resources\HistoriedMovie;

use App\Http\Resources\Movie\MovieResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoriedMovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'movie' => new MovieResource($this->whenLoaded('movie')),
        ];
    }
}
