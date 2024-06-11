<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return (PaymentResource::collection($this->collection));
    }
}
