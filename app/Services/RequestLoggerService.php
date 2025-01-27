<?php

namespace App\Services;

use App\Models\RequestLogger;
use App\Models\Team;

/**
 * Service for logging and tracking API requests
 * 
 * This service handles:
 * - Request logging
 * - Usage tracking
 * - Billing integration
 */
class RequestLoggerService
{
    /**
     * Create new request log entry
     *
     * @param Team $team Team context
     * @param string $question Question asked
     * @param string $ip Client IP address
     * @param bool $new Whether this is a new question
     * @return RequestLogger Created log entry
     * @throws \Exception On creation failure
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
