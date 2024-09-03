<?php

namespace App\Jobs\Batch;

use App\Models\Embedding\File;
use App\Services\BatchingService;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BatchEmbedFile implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    private File $file;
    private BatchingService $batching_service;

    /**
     * Create a new job instance.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->batching_service = new BatchingService($file->team);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->batching_service->createFile(file: $this->file);
    }
}
