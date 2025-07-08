<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Voting;
use App\Models\Petition;

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

// Канал для всех пользователей
Broadcast::channel('all-users', function ($user) {
    return true;
});

// Канал для уведомлений конкретного пользователя
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Канал для уведомлений о голосованиях
Broadcast::channel('voting.{id}', function ($user, $id) {
    return true; // В будущем можно добавить проверку доступа
});

// Канал для уведомлений о петициях
Broadcast::channel('petition.{id}', function ($user, $id) {
    return true; // В будущем можно добавить проверку доступа
}); 