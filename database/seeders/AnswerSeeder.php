<?php

namespace Database\Seeders;

use App\Models\Answer;
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

        foreach (range(1, 100) as $index) {
            Answer::create([
                'team_id' => 1,
                'question' => str_replace(".", "?", fake()->text(50)),
                'answer' => fake()->text(800),
                'votes' => rand(1, 25),
                'channel' => Str::uuid(),
                'data' => json_encode([])
            ]);
        }

    }
}
