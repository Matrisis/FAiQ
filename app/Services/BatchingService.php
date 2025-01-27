<?php

namespace App\Services;

use App\Models\Embedding\File;
use App\Models\Team;
use App\Services\Batching\BackendBatchingService;
use App\Services\Embedding\FileEmbeddingService;

/**
 * Service for managing batch processing operations
 * 
 * This service coordinates batch processing of files and embeddings,
 * delegating work to specialized services for different content types.
 */
class BatchingService
{
    private BackendBatchingService $batching_service;
    private Team $team;

    /**
     * Create a new BatchingService instance
     *
     * @param Team $team Team context for batching operations
     * @param string $duration Duration for batch processing window
     */
    public function __construct(Team $team, string $duration = "24h")
    {
        $this->batching_service = new BackendBatchingService($team, $duration);
        $this->team = $team;
    }

    /**
     * Create a new batch processing job
     *
     * @param string $action Type of action ('embedding', 'chatting')
     * @param string $type Content type ('file', 'text')
     * @param array|string $content Content to process
     * @return bool Success status
     */
    public function create(string $action, string $type, array|string $content) : bool
    {
        return $this->batching_service->create(action: $action, type: $type, content: $content);
    }

    /**
     * Create a batch processing job for a file
     *
     * @param File $file File to process
     * @return bool Success status
     */
    public function createFile(File $file)
    {
        $text_embedding_service = new FileEmbeddingService(team: $this->team);
        $content = [
            "lines" => $text_embedding_service->getLines(file: $file),
        ];
        return $this->batching_service->create("embedding", "file", $content);
    }

    /**
     * Publish a batch for processing
     *
     * @param string $type Type of batch to publish
     * @return bool Success status
     */
    public function publish(string $type) : bool
    {
        return $this->batching_service->publish(type: $type);
    }

    /**
     * Retrieve processed batch results
     *
     * @return bool Success status
     */
    public function retrieve() : bool
    {
        return $this->batching_service->retrieve();
    }
}
