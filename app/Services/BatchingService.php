<?php

namespace App\Services;

use App\Models\Embedding\File;
use App\Models\Team;
use App\Services\Batching\BackendBatchingService;
use App\Services\Embedding\FileEmbeddingService;

class BatchingService
{

    private BackendBatchingService $batching_service;
    private Team $team;

    public function __construct(Team $team, string $duration = "24h")
    {
        $this->batching_service = new BackendBatchingService($team, $duration);
        $this->team = $team;
    }

    public function create(string $action, string $ype, array|string $content) : bool
    {
        return $this->batching_service->create(action: $action, type: $ype, content: $content);
    }

    public function createFile(File $file)
    {
        $text_embedding_service = new FileEmbeddingService(team: $this->team);
        $content = [
            "lines" => $text_embedding_service->getLines(file: $file),
        ];
        return $this->batching_service->create("embedding", "file", $content);
    }

    public function publish(string $type) : bool
    {
        return $this->batching_service->publish(type: $type);
    }

    public function retrieve() : bool
    {
        return $this->batching_service->retrieve();
    }

}
