<?php

namespace App\Http\Resources\Language;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LanguageResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (LanguageResource::collection($this->collection));
    }
}
