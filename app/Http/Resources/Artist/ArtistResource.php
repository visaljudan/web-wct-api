<?php

namespace App\Http\Resources\Artist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_image' => $this->profile_image,
        ];
    }
}
