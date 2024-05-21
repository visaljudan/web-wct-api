<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return (UserResource::collection($this->collection));
    }
}
