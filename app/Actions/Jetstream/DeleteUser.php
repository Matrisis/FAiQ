<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\TeamParameters;
use App\Models\User;
use App\Services\BillingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected DeletesTeams $deletesTeams)
    {
    }

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->name = "Deleted User" . Str::random(10);
            $user->email = "Deleted Email - ". Str::random(10);
            $user->password = bcrypt(Str::random(30));
            $user->save();
            $this->deleteTeams($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * Delete the teams and team associations attached to the user.
     */
    protected function deleteTeams(User $user): void
    {
        // $user->teams()->detach();

        $user->ownedTeams->each(function (Team $team) {
            $team->name = $team->name ." - Deleted - ". Str::random(10);
            $team->slug = Str::random(12);
            $team->save();
            $team_parameters = TeamParameters::where("team_id", $team->id)->first();
            if ($team_parameters) {
                $team_parameters->accessible = false;
                $team_parameters->save();
            }
            BillingService::cancelSubscription($team);
            $team->delete();
        });
    }
}
