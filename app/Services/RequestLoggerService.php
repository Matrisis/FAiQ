<?php

namespace App\Services;

use App\Models\RequestLogger;
use App\Models\Team;

class RequestLoggerService
{
    public static function create(Team $team, string $question, string $ip, bool $new = true) {
       return RequestLogger::create([
           'team_id' => $team->id,
           'question' => $question,
           'ip' => $ip,
           'new' => $new
       ]);
    }


}
