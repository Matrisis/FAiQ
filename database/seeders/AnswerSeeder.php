<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Team;
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
            Answer::create([
                'team_id' => 1,
                'question' => $question,
                'answer' => $answer,
                'votes' => rand(1, 25),
                'channel' => Str::uuid(),
                'data' => json_encode([]),
                'question_vector' => $embedding_service->embed($question),
                'answer_vector' => $embedding_service->embed($answer),
            ]);
        }

    }
}
