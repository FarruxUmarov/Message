<?php

use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('user_{userId}_to_{otherUserId}', function ($user, $userId, $otherUserId) {
//    return $user && ((int)$user->id === (int)$userId || (int)$user->id === (int)$otherUserId);
//});
Broadcast::channel("room.{id}", function ($user, $id) {
    return $user-rooms()->where("id", $id)->first()->id;
});
