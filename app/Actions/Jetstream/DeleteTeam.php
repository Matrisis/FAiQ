<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Services\BillingService;
use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     */
    public function delete(Team $team): void
    {
        BillingService::cancelSubscription($team);
        $team->purge();
    }
}
