<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (RoleResource::collection($this->collection));
    }
}
