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

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('sending-notification', function ($user) {
    return (int)$user->id === 1;
});

Broadcast::channel('c-{cid}-{mid}', function ($user, $cid) {
    return \App\Models\ConversationMember::query()
        ->where('conversation_id', $cid)
        ->where('user_id', $user->id)
        ->limit(1)
        ->exists();
});
