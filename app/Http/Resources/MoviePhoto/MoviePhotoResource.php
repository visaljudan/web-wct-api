<?php

namespace App\Http\Resources\MoviePhoto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoviePhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'photo_path' => $this->photo_path,
        ];
    }
}
