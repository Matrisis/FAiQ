<?php

namespace App\Jobs\Batch;

use App\Models\Team;
use App\Services\BatchingService;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BatchRetrieve implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;
    private Team $team;

    /**
     * Create a new job instance.
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batching_service = new BatchingService(team: $this->team);
        $batching_service->retrieve();
    }
}
