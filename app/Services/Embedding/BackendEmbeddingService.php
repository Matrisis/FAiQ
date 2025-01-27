<?php

namespace App\Services\Embedding;

use App\Models\Answer;
use App\Models\Embedding\Embedding;
use App\Models\Embedding\File;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use OpenAI\Laravel\Facades\OpenAI;
use Pgvector\Laravel\Distance;

/**
 * Backend service for managing embedding operations
 * 
 * This service handles the low-level embedding operations including:
 * - Creating embeddings via OpenAI API
 * - Managing different types of content embeddings (file, text, URL)
 * - Storing and retrieving embeddings
 */
class BackendEmbeddingService
{
    private Team $team;
    private string $model;

    /**
     * Create a new BackendEmbeddingService instance
     *
     * @param Team $team Team context for embeddings
     * @param string $model AI model to use for embeddings
     */
    public function __construct(Team $team, string $model = "text-embedding-3-small")
    {
        $this->team = $team;
        $this->model = $model;
    }

    /**
     * Create embeddings for different types of content
     *
     * @param string $type Content type ('file', 'text', 'url')
     * @param string $action Processing action ('execute', 'import')
     * @param array $content Content data to process
     * @return bool Success status
     * @throws \Exception If type or action not supported
     */
    public function create(string $type, string $action, array $content) : bool
    {
        throw_if(!in_array($type, ["file", "text", "url"]), new \Exception("Type not supported"));
        throw_if(!in_array($action, ["execute", "import"]), new \Exception("Action not supported"));
        $function = "{$type}Store";
        return $this->$function($action, $content);
    }

    /**
     * Generate embedding vector for text
     *
     * @param string $text Text to embed
     * @return string JSON encoded embedding vector
     */
    public function embed(string $text) : string {
        return json_encode(OpenAI::embeddings()->create([
            'model' => $this->model,
            'input' => $text
        ])->toArray()['data'][0]['embedding']);
    }

    /**
     * Retrieve similar content using vector similarity
     *
     * @param string $text Query text
     * @param int $limit Maximum results to return
     * @param string $model Model class to search
     * @param string $column Vector column name
     * @return \Illuminate\Support\Collection
     */
    public function retrieve(string $text, int $limit = 2, string $model = Embedding::class, string $column = "embedding")
    {
        return $model::query()
            ->nearestNeighbors($column, $this->embed($text), Distance::Cosine)->take($limit)->get();
    }

    /**
     * Store file embeddings
     *
     * @param string $action Processing action
     * @param array $content File content data
     * @return bool Success status
     * @throws \Exception If content invalid
     */
    private function fileStore(string $action, array $content) : bool {
        throw_if((!isset($content["file"])),
            new \Exception("Invalid content. Requires original or embedded."));
        try {
            $file_embedding_service = new FileEmbeddingService(team: $this->team);
            if($action === "import") {
                print("Importing file : " . $content["file"]->name . "\n");
                $file_embedding_service->import(file: $content["file"], content: $content["lines"]);
            } else {
                $lines = [];
                foreach ($file_embedding_service->getLines($content["file"], tries: 3) as $line)
                    $lines[] = ["original" => $line["cleaned"], "embedded" => $this->embed($line["cleaned"])];
                $file_embedding_service->import(file: $content["file"], content: $lines);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Store text embeddings
     *
     * @param string $action Processing action
     * @param array $content Text content data
     * @return bool Success status
     * @throws \Exception If content invalid
     */
    private function textStore(string $action, array $content): bool
    {
        throw_if((!isset($content["original"]) && !isset($content["embedded"])),
            new \Exception("Invalid content. Requires original or embedded."));
        $text_embedding_service = new TextEmbeddingService(team: $this->team, model: $this->model);
        $embedded = $action === "import" ? $content["embedded"] : $this->embed($content["original"]);
        return $text_embedding_service->import(original: $content["original"], embedded: $embedded);
    }

    /**
     * Store URL embeddings
     *
     * @param string $action Processing action
     * @param array $content URL content data
     * @return bool Success status
     * @throws \Exception If content invalid
     */
    private function urlStore(string $action, array $content): bool
    {
        throw_if((!isset($content["original"]) && !isset($content["embedded"]))
            || !array_key_exists("url", $content),
            new \Exception("Invalid content. Requires original or embedded with url."));
        $url_embedding_service = new UrlEmbeddingService(team: $this->team, model: $this->model);
        $embedded = $action === "import" ? $content["embedded"] : $this->embed($content["original"]);
        return $url_embedding_service->import(original: $content["original"], embedded: $embedded);
    }
}
