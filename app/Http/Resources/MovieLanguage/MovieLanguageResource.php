<?php

namespace App\Http\Resources\MovieLanguage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieLanguageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'movie_id' => $this->movie_id,
            'language_code' => $this->language_code,
        ];
    }
}
