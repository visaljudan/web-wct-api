<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'subscription_plan_id' => $this->subscription_plan_id,
            'card_number' => $this->card_number,
            'expiry' => $this->expiry,
            'name' => $this->name,
            'description' => $this->description,
            'stripeToken' => $this->stripeToken,
            'transaction_id' => $this->transaction_id,
            'payment_method' => $this->payment_method,
            'receipt_url' => $this->receipt_url,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
