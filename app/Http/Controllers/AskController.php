<?php

namespace App\Http\Controllers;

use App\Events\Ask;
use App\Jobs\AskJob;
use App\Models\Team;
use App\Services\ChattingService;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Prompts\Table;

class AskController extends Controller
{

    public function index(Request $request, Team $team) {
        $channel = $team->id . '-' . Str::random(24);
        return Inertia::render('Ask', [
            'channel' => $channel,
            'team' => $team->only(["id", "name"])
        ]);
    }

    public function create(Request $request, Team $team) {
        $question = $request->input('question');
        $channel = $request->input('channel');

        /*
        $job_service = new JobService();
        $job_service->ask(
            channel: $channel,
            team: $team,
            question: $question,
            tries: 1, // Define must be settable per team by team manager
            verification_prompt: 'Please just answer the question ' . $question
        );
        */

        /*
        $ask_service = new ChattingService();
        $answer = $ask_service->ask($team, $question, 3, 'Please just answer the question ' . $question);
        foreach (str_split($answer["answer"], 10) as $chunk) {
            broadcast(new Ask(answer: [
                'question' => $question,
                'answer' => str_replace("??", "é", mb_convert_encoding($chunk, "UTF-8", 'UTF-8')),
            ], channel: $channel));
        }
        //broadcast(new Ask($answer, $channel));
        */

        $job_service = new JobService();
        $job_service->askStream(
            channel: $channel,
            team: $team,
            question: $question
        );

        return response()->json(['status' => 'success', 'channel' => $channel]);
    }

}
