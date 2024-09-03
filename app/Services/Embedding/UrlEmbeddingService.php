<?php

namespace App\Services\Embedding;

use App\Models\Embedding\Embedding;
use App\Models\Embedding\Url;
use App\Models\Team;

class UrlEmbeddingService
{
    private Team $team;
    private string $model;

    public function __construct(Team $team, $model = "text-embedding-small-3")
    {
        $this->team = $team;
        $this->model = $model;
    }

    public function import(string $original, string $embedded) : bool
    {
        $embedding = Embedding::insertGetId([
            'type' => 'url',
            'content' => $original,
            'embedding' => $embedded,
            'team_id' => $this->team->id
        ]);
        return $this->create(url: $original, embedding_id: $embedding);
    }

    private function create(string $url, int $embedding_id)
    {
        return Url::insert([
            "url" => $url,
            "embedding_id" => $embedding_id,
            "team_id" => $this->team->id
        ]);
    }

}
