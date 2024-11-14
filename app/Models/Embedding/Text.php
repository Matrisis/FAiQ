<?php

namespace App\Models\Embedding;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Pgvector\Laravel\Vector;

class Text extends Pivot
{
    protected $table = 'text';
    protected $fillable = [
        'content',
        'content_hash',
        'embedding_id',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'encrypted',
        ];
    }

    public function embedding()
    {
        return $this->belongsTo(Embedding::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
