<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamParameters extends Pivot
{

    public $fillable = ['background_color', 'text_color', 'logo_path', 'icon_path'];

    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }


}
