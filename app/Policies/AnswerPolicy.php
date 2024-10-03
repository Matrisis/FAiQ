<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Embedding\File;
use App\Models\Team;
use App\Models\User;

class AnswerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function view(User $user, Answer $answer){
        $team = Team::find($answer->team_id);
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }

    public function update(User $user, Answer $answer){
        $team = Team::find($answer->team_id);
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }

    public function delete(User $user, Answer $answer){
        $team = Team::find($answer->team_id);
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }

}

