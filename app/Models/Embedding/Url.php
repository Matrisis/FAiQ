<?php

namespace App\Models\Embedding;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Url extends Pivot
{
    protected $fillable = ['url'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function embedding()
    {
        return $this->belongsTo(Embedding::class);
    }
}
