<?php

namespace App\Policies;

use App\Models\Embedding\File;
use App\Models\Team;
use App\Models\User;

class FilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user, Team $team)
    {
        $user = auth()->user();
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }

    public function create(User $user) {
        return true;
    }

    public function delete(User $user, File $file ) {
        $team = File::find($file->id)->team;
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }

    public function process(User $user, File $file ) {
        $team = File::find($file->id)->team;
        return $user->belongsToTeam($team) || $user->ownsTeam($team) || $user->isAdmin();
    }
}
