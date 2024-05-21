<?php

namespace App\Http\Resources\RequestedMovie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestedMovieResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'url' => $this->url,
            'status' => $this->status,
        ];
    }
}
