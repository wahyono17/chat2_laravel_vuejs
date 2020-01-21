<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
|'App.User.{id}
*/
Broadcast::channel('post.{id}', function ($user, $id) {
    //dd((int) $user->id === (int) $id);
    return (int) $user->id === (int) $id;
});

Broadcast::channel('messages.{id}', function ($user, $id) {
    //dd($user->id === (int) $id);
    return $user->id === (int) $id;
});