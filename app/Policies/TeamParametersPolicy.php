<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\TeamParameters;
use App\Models\User;

class TeamParametersPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Team $team) {
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }

    public function update(User $user, TeamParameters $parameters) {
        $team = Team::find($parameters->team_id);
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }
}
