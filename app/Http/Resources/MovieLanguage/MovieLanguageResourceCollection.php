<?php

namespace App\Http\Resources\MovieLanguage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieLanguageResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return (MovieLanguageResource::collection($this->collection));
    }
}
