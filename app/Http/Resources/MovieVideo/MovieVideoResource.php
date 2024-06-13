<?php

namespace App\Http\Resources\MovieVideo;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieVideoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'video' => $this->video,
            'season_number' => $this->season_number,
            'episode_number' => $this->episode_number,
            'part_number' => $this->part_number,
            'type' => $this->type,
            'subscription' => $this->subscription,
            'subscription_start_date' => $this->subscription_start_date,
            'subscription_end_date' => $this->subscription_end_date,
        ];
    }
}
