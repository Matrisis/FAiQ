<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Channel for asking questions
Broadcast::channel('ask.{channel}', function () {
    return true;
});

broadcast::channel('file.{team}', function (User $user, Team $team) {
    return $user->can('view', $team);
});
