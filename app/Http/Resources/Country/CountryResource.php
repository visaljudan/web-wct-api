<?php

namespace App\Http\Resources\Country;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'country_code' => $this->country_code,
            'country_canem' => $this->country_name
        ];
    }
}
