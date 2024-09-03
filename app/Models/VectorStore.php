<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VectorStore extends Pivot
{
    protected $fillable = ["name", "store_id", "team_id"];
}
