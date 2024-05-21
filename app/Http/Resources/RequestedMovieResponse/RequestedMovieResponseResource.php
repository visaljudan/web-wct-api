<?php

namespace App\Http\Resources\RequestedMovieResponse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestedMovieResponseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'requested_movie_id' => $this->requested_movie_id,
            'user_id' => $this->user_id,
            'response_message' => $this->response_message,
            'response_status' => $this->response_status,
        ];
    }
}
