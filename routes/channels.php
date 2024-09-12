<?php

use Illuminate\Support\Facades\Broadcast;

// Channel for asking questions
Broadcast::channel('ask.{channel}', function () {
    return true;
});
