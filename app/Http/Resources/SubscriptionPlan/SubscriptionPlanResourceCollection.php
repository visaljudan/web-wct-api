<?php

namespace App\Http\Resources\SubscriptionPlan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionPlanResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (SubscriptionPlanResource::collection($this->collection));
    }
}
