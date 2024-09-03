<?php

namespace App\Models\Embedding;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

class Embedding extends Pivot
{

    use HasNeighbors;

    protected $casts = ['embedding' => Vector::class];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'content',
        'embedding',
        'file_id',
        'team_id',
    ];


    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }

}
