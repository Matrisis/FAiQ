<?php

namespace App\Services\Embedding;

use App\Models\Embedding\Embedding;
use App\Models\Embedding\Text;
use App\Models\Team;

/**
 * Service for managing text content embeddings
 * 
 * This service handles:
 * - Creating and storing text embeddings
 * - Managing text-specific embedding operations
 * - Handling text content import/export
 */
class TextEmbeddingService
{
    private Team $team;
    private string $model;

    /**
     * Create a new TextEmbeddingService instance
     *
     * @param Team $team Team context
     * @param string $model AI model to use
     */
    public function __construct(Team $team, string $model = "text-embedding-small-3")
    {
        $this->team = $team;
        $this->model = $model;
    }

    /**
     * Import text content with embedding
     *
     * @param string $original Original text content
     * @param string $embedded Vector embedding
     * @return bool Success status
     */
    public function import(string $original, string $embedded) : bool
    {
        $embedding = Embedding::create([
            'type' => 'text',
            'content' => $original,
            'embedding' => $embedded,
            'team_id' => $this->team->id
        ]);
        return $this->create(content: $original, embedding_id: $embedding);
    }

    /**
     * Create text record with embedding
     *
     * @param string $content Text content
     * @param int $embedding_id Associated embedding ID
     * @return bool Success status
     */
    private function create(string $content, int $embedding_id) : bool {
        return Text::insert([
            "content" => $content,
            "content_hash" => hash("md5", $content),
            "embedding_id" => $embedding_id,
            "team_id" => $this->team->id
        ]);
    }
}
