<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('movie-artists', function ($user) {
    return true; // Allow all users to listen to this channel
});

Broadcast::channel('new-movie-channel', function () {
    return true;
});
