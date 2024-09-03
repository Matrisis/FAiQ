<?php

use Illuminate\Support\Facades\Broadcast;

// Private channel for asking questions
Broadcast::channel('ask.{channel}', function () {
    return true;
});
