<?php

namespace App\Models\Embedding;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\Pivot;

class File extends Pivot
{

    protected $table = "file";
    protected $fillable = ["name", "path", "team_id", "imported"];

    protected $with = ["team"];

    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }
}
