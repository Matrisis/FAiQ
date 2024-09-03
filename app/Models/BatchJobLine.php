<?php

namespace App\Models;

use App\Models\Embedding\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchJobLine extends Model
{
    use HasFactory;


    protected $fillable = ['custom_id', 'type', 'body', 'original_text', 'batch_job_id', 'file_id'];

    protected $with = ['file'];


    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }
}
