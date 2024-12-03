<?php

namespace App\Services;

use App\Models\RequestLogger;
use App\Models\Team;

class RequestLoggerService
{
    /**
     * @throws \Exception
     */
    public static function create(Team $team, string $question, string $ip, bool $new = true) {
        BillingService::reportUsage($team, 1);
        return RequestLogger::create([
           'team_id' => $team->id,
           'question' => $question,
           'ip' => $ip,
           'new' => $new,
           'paid' => false
       ]);
    }


}
