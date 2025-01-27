<?php

namespace App\Services\Embedding;

use App\Models\Embedding\Embedding;
use App\Models\Embedding\Url;
use App\Models\Team;

/**
 * Service for managing URL content embeddings
 * 
 * This service handles:
 * - Creating and storing URL content embeddings
 * - Managing URL-specific embedding operations
 * - Handling URL content import/export
 */
class UrlEmbeddingService
{
    private Team $team;
    private string $model;

    /**
     * Create a new UrlEmbeddingService instance
     *
     * @param Team $team Team context
     * @param string $model AI model to use
     */
    public function __construct(Team $team, $model = "text-embedding-small-3")
    {
        $this->team = $team;
        $this->model = $model;
    }

    /**
     * Import URL content with embedding
     *
     * @param string $original Original content
     * @param string $embedded Vector embedding
     * @return bool Success status
     */
    public function import(string $original, string $embedded) : bool
    {
        $embedding = Embedding::create([
            'type' => 'url',
            'content' => $original,
            'embedding' => $embedded,
            'team_id' => $this->team->id
        ]);
        return $this->create(url: $original, embedding_id: $embedding);
    }

    /**
     * Create URL record with embedding
     *
     * @param string $url URL to store
     * @param int $embedding_id Associated embedding ID
     * @return bool Success status
     */
    private function create(string $url, int $embedding_id)
    {
        return Url::insert([
            "url" => $url,
            "embedding_id" => $embedding_id,
            "team_id" => $this->team->id
        ]);
    }
}
