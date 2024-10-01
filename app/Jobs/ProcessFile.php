<?php

namespace App\Jobs;

use App\Models\Embedding\File;
use App\Models\Team;
use App\Services\BatchingService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFile implements ShouldQueue
{
    use Batchable, Queueable;
    private Team $team;
    private File $file;

    /**
     * Create a new job instance.
     */
    public function __construct(Team $team, File $file)
    {
        $this->team = $team;
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batching_service = new BatchingService($this->team);
        $batching_service->createFile($this->file);
    }
}
