<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Team;
use App\Services\AnswerService;
use App\Services\EmbeddingService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        $embedding_service = new EmbeddingService(Team::find(1));
        foreach (range(1, 5) as $index) {
            $question = str_replace(".", "?", fake()->text(50));
            $answer = fake()->text(800);
            $answer_service = new AnswerService(Team::find(1));
            $answer_service->create($question, $answer, Str::uuid(), [], rand(1, 25));
        }


    }
}
