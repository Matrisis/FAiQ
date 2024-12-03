<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLogger extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'question',
        'new',
        'team_id',
        'paid'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
