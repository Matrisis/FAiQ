<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\TeamParameters;
use App\Models\User;
use App\Services\Old\Assistant\TeamGenesisService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Team
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => false,
        ]));

        $user->update(["current_team_id" => $team->id]);

        TeamParameters::create([
           "team_id" => $team->id,
            "team_name" => $input['name'],
        ]);

        TeamParameters::firstOrCreate([
            "team_id" => $team->id,
        ], [
            "team_id" => $team->id,
        ]);

        return $team;
    }
}
