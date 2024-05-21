<?php

namespace App\Http\Resources\Language;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'language_code' => $this->language_code,
            'lagnuage_name' => $this->lagnuage_name,
        ];
    }
}
