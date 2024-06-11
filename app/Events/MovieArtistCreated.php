<?php

namespace App\Events;

use App\Models\Artist;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MovieArtistCreated  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $movieArtist;

    public function __construct($movieArtist)
    {
        $this->movieArtist = $movieArtist;
    }

    public function broadcastOn()
    {
        return ['movie-artists'];
    }
}
