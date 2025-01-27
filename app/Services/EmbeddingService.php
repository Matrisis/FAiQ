<?php

namespace App\Services;

use App\Models\Embedding\Embedding;
use App\Models\Team;
use App\Services\Embedding\BackendEmbeddingService;
use Pgvector\Laravel\Distance;

/**
 * Service for managing document embeddings and semantic search
 * 
 * This service handles the creation and retrieval of vector embeddings for text content,
 * enabling semantic search and similarity matching across documents and questions.
 */
class EmbeddingService
{
    private BackendEmbeddingService $embedding;
    private Team $team;

    /**
     * Create a new EmbeddingService instance
     *
     * @param Team $team Team context for embeddings
     * @param string $model AI model to use for embeddings
     */
    public function __construct(Team $team, string $model = "text-embedding-3-small")
    {
        $this->embedding = new BackendEmbeddingService($team, $model);
        $this->team = $team;
    }

    /**
     * Create embeddings for content
     *
     * @param string $type Content type ('file', 'text', 'url')
     * @param string $action Processing action ('execute', 'import')
     * @param array $content Content to embed
     * @return bool Success status
     */
    public function create(string $type, string $action, array $content) : bool
    {
        return $this->embedding->create(type: $type, action: $action, content: $content);
    }

    /**
     * Generate vector embedding for text
     *
     * @param string $text Text to embed
     * @return string JSON encoded vector embedding
     */
    public function embed(string $text): string
    {
        return $this->embedding->embed(text: $text);
    }

    /**
     * Retrieve similar content using vector similarity search
     *
     * @param string $text Query text to find similar content for
     * @param int $limit Maximum number of results
     * @param string $model Model class to search in
     * @param string $column Vector column name
     * @param string|null $neighbor_distance Maximum distance threshold
     * @return \Illuminate\Support\Collection Collection of similar items
     */
    public function retrieve(string $text, int $limit = 2, string $model = Embedding::class,
                             string $column = "embedding", string $neighbor_distance = null)
    {
        return $this->embedding
            ->retrieve(text: $text, limit: $limit, model: $model, column: $column)
            ->where('neighbor_distance', '<=', $neighbor_distance ?: $this->team->parameters->neighbor_distance);
    }
}
