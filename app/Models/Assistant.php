<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Assistant extends Pivot
{
    protected $fillable = [
        'name',
        'instruction',
        'assistant_id',
        'model',
        'store_id',
        'team_id',
    ];

    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }
}
