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
*/

Broadcast::channel('controller-events.{controllerId}', function ($user, $controllerId) {
    $controller = App\Controller::find($controllerId);
    $user = $controller->organization->users()->where('user_id', $user->id)->first();

    if ($user) {
        return true;
    }
});
