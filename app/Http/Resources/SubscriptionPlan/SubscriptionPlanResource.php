<?php

namespace App\Http\Resources\SubscriptionPlan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subscription_plan_name' => $this->subscription_plan_name,
            'subscription_plan_description' => $this->subscription_plan_description,
            'subscription_plan_price' => $this->subscription_plan_price,
            'subscription_plan_duration' => $this->subscription_plan_duration,
        ];
    }
}
