<?php

namespace App\Http\Resources\RequestedMovie;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestedMovieResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'status' => $this->status,
        ];
    }
}
