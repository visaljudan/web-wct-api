<?php

namespace App\Http\Resources\MovieCountry;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieCountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $countryName = Country::where('country_code', $this->country_code)->value('country_name');

        return [
            'movie_id' => $this->movie_id,
            'country_code' => $this->country_code,
            'country_name' => $countryName,
        ];
    }
}
