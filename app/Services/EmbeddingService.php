<?php

namespace App\Services;

use App\Models\Embedding\Embedding;
use App\Models\Team;
use App\Services\Embedding\BackendEmbeddingService;

class EmbeddingService
{

    private BackendEmbeddingService $embedding;
    private Team $team;

    public function __construct(Team $team, string $model = "text-embedding-3-small")
    {
        $this->embedding = new BackendEmbeddingService($team, $model);
        $this->team = $team;
    }

    public function create(string $type, string $action, array $content) : bool
    {
        return $this->embedding->create(type: $type, action: $action, content: $content);
    }

    public function embed(string $text): string
    {
        return $this->embedding->embed(text: $text);
    }


    public function retrieve(string $text, int $limit = 2, string $model = Embedding::class,
                             string $column = "embedding", string $neighbor_distance = null)
    {
        return $this->embedding
            ->retrieve(text: $text, limit: $limit, model: $model, column: $column)
            ->where('neighbor_distance', '>=', $neighbor_distance ?: $this->team->parameters->neighbor_distance);
    }

}
