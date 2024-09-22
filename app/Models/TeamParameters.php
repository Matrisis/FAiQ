<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamParameters extends Pivot
{
    protected $fillable = [
        'team_id',
        'title',
        'background_color',
        'question_background_color',
        'text_color',
        'title_color',
        'logo_path',
        'icon_path',
    ];

    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }

    // Accesseur pour icon_path
    public function getIconPathAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    // Accesseur pour logo_path
    public function getLogoPathAttribute($value)
    {
        return $value ? asset($value) : null;
    }
}
