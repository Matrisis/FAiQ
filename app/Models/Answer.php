<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'question',
        'answer',
        'data',
        'type',
        'votes',
        'team_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
