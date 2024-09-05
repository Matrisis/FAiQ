<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileQuestions extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'questions'
    ];
}
