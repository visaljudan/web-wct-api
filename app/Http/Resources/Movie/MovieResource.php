<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'overview' => $this->overview,
            'run_time' => $this->run_time,
            'release_date' => $this->release_date,
            'poster_image' => $this->poster_image,
            'cover_image' => $this->cover_image,
            'trailer_url' => $this->trailer_url,
            'total_likes' => $this->total_likes,
            'total_ratings' => $this->total_ratings,
            'average_rating' => $this->average_rating,
            'popularity' => $this->popularity,
            'terms_status' => $this->terms_status,
            'upload_status' => $this->upload_status,
            'user_subscription' => $this->user_subscription,
            'expire_subscription' => $this->expire_subscription,
            'last_upload_date' => $this->last_upload_date,
        ];
    }
}
