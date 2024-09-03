<?php

namespace App\Services\Embedding;

use App\Models\Embedding\Embedding;
use App\Models\Embedding\File;
use App\Models\Team;
use OpenAI\Laravel\Facades\OpenAI;
use Pgvector\Laravel\Distance;

class BackendEmbeddingService
{

    private Team $team;
    private string $model;

    public function __construct(Team $team, string $model = "text-embedding-3-small") {
        $this->team = $team;
        $this->model = $model;
    }

    public function create(string $type, string $action, array $content) : bool
    {
        throw_if(!in_array($type, ["file", "text", "url"]), new \Exception("Type not supported"));
        throw_if(!in_array($action, ["execute", "import"]), new \Exception("Action not supported"));
        $function = "{$type}Store";
        return $this->$function($action, $content);
    }

    public function embed(string $text) : string {
        return json_encode(OpenAI::embeddings()->create([
            'model' => $this->model,
            'input' => $text
        ])->toArray()['data'][0]['embedding']);
    }

    public function retrieve(string $text, int $limit = 2)
    {
        return Embedding::query()
            ->nearestNeighbors('embedding', $this->embed($text), Distance::Cosine)->take($limit)->get();
    }

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

    private function textStore(string $action, array $content): bool
    {
        throw_if((!isset($content["original"]) && !isset($content["embedded"])),
            new \Exception("Invalid content. Requires original or embedded."));
        $text_embedding_service = new TextEmbeddingService(team: $this->team, model: $this->model);
        $embedded = $action === "import" ? $content["embedded"] : $this->embed($content["original"]);
        return $text_embedding_service->import(original: $content["original"], embedded: $embedded);
    }

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
