<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    // public function toArray(Request $request): array
    // {
    //     return parent::toArray($request);
    // }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'profile' => $this->profile,
            'api_token' => $this->api_token,
        ];
    }
}
