<?php

namespace App\Http\Resources\Year;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YearResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'year' => $this->year,
        ];
    }
}
