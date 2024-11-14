<?php

namespace App\Services\Embedding;

use App\Models\Embedding\Embedding;
use App\Models\Embedding\Text;
use App\Models\Team;

class TextEmbeddingService
{
    private Team $team;
    private string $model;

    public function __construct(Team $team, string $model = "text-embedding-small-3")
    {
        $this->team = $team;
        $this->model = $model;
    }

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

    private function create(string $content, int $embedding_id) : bool {
        return Text::insert([
            "content" => $content,
            "content_hash" => hash("md5", $content),
            "embedding_id" => $embedding_id,
            "team_id" => $this->team->id
        ]);
    }
}
