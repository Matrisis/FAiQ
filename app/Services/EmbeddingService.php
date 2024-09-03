<?php

namespace App\Services;

use App\Models\Team;
use App\Services\Embedding\BackendEmbeddingService;

class EmbeddingService
{

    private BackendEmbeddingService $embedding;

    public function __construct(Team $team, string $model = "text-embedding-3-small")
    {
        $this->embedding = new BackendEmbeddingService($team, $model);
    }

    public function create(string $type, string $action, array $content) : bool
    {
        return $this->embedding->create(type: $type, action: $action, content: $content);
    }

    public function embed(string $text): string
    {
        return $this->embedding->embed(text: $text);
    }


    public function retrieve(string $text, int $limit = 2)
    {
        return $this->embedding->retrieve(text: $text, limit: $limit);
    }

}
