<?php

namespace App\Http\Resources\TvShow;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvShowResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tv_show_name' => $this->tv_show_name,
        ];
    }
}
