<?php

namespace App\Http\Resources\MovieCountry;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieCountryResource extends JsonResource
{
    public function toArray($request)
    {
        $countryName = Country::where('country_code', $this->country_code)->value('country_name');

        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'title' => $this->movie->title,
            'poster_image' => $this->movie->poster_image,
            'country_code' => $this->country_code,
            'country_name' => $countryName,
        ];
    }
}
