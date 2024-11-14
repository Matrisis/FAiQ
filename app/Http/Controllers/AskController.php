<?php

namespace App\Http\Controllers;

use App\Events\Ask;
use App\Jobs\AskJob;
use App\Models\Answer;
use App\Models\Team;
use App\Services\AnswerService;
use App\Services\ChattingService;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Prompts\Table;

class AskController extends Controller
{

    public function indexAdmin(Request $request, string $team) {
        $team = Team::where('slug', $team)->with('parameters')->firstOrFail();
       return $this->index($request, $team, 'admin');
    }

    public function indexPublic(Request $request, string $team)
    {
        $team = Team::where('slug', $team)->with('parameters')->firstOrFail();
        return $this->index($request, $team, 'public');
    }

    public function index(Request $request, string $team)
    {
        $team = Team::where('slug', mb_strtolower($team))->with('parameters')->firstOrFail();
        $channel = $team->id . '-' . Str::random(24);
        $instant_answers = Answer::where('team_id', $team->id)
            ->where("votes", ">", 5)
            ->orderBy('votes', 'desc')
            ->take(5)->get();
        return Inertia::render("Ask", [
            'load' => "public",
            'channel' => $channel,
            'team' => $team->only(["id", "name", "parameters"]),

            'instant_answers' => $instant_answers,
        ]);
    }

    public function create(Request $request, Team $team) {
        $validated = $request->validate([
            'question' => ['required', 'max:255', 'string', 'min:3'],
            'channel' => ['required', 'string', 'min:24'],
        ]);
        $question = $validated['question'];
        $channel = $validated['channel'];
        try {
            $ask_service = new AnswerService($team);
            $ask_service->ask($channel, $question, $request);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'response' => $exception], 500);
        }
        return response()->json(['status' => 'success', 'channel' => $channel]);
    }

    public function vote(Request $request, Team $team, Answer $answer) {
        $validated = $request->validate([
            'vote' => ['required', Rule::in(['incr', 'decr'])],
        ]);
        try {
            if ($validated['vote'] == "decr") {
                $answer->decrement('votes');
            } else
                $answer->increment('votes');
            return response()->json(['status' => 'success', 'response' => []]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'response' => $exception], 500);
        }
    }

}
