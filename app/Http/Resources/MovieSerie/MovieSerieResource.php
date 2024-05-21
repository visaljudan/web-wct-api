<?php

namespace App\Http\Resources\MovieSerie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieSerieResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'season_number' => $this->season_number,
            'episode_number' => $this->episode_number,

        ];
    }
}
