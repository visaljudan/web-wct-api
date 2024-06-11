<?php

namespace App\Events;

use App\Models\Movie;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMovieEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $movie;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function broadcastOn()
    {
        return new Channel('new-movie-channel');
    }
}
