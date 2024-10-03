<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

class Answer extends Model
{
    use HasNeighbors;

    protected $fillable = [
        'channel',
        'question',
        'answer',
        'data',
        'type',
        'votes',
        'team_id',
        'question_vector',
        'answer_vector',
    ];

    protected $casts = [
        'question_vector' => Vector::class,
        'answer_vector' => Vector::class
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
