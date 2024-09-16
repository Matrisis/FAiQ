<?php

namespace App\Http\Controllers;

use App\Events\Ask;
use App\Jobs\AskJob;
use App\Models\Answer;
use App\Models\Team;
use App\Services\ChattingService;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Prompts\Table;

class AskController extends Controller
{

    public function indexAdmin(Request $request, Team $team) {
        $team = Team::with('parameters')->find($team->id);
       return $this->index($request, $team, 'admin');
    }

    public function indexPublic(Request $request, Team $team)
    {
        $team = Team::with('parameters')->find($team->id);
        return $this->index($request, $team, 'public');
    }

    private function index(Request $request, Team $team, string $render)
    {
        $channel = $team->id . '-' . Str::random(24);
        $instant_answers = Answer::where('team_id', $team->id)
            ->where("votes", ">", 5)
            ->orderBy('votes', 'desc')
            ->take(5)->get();
        return Inertia::render("Ask", [
            'load' => $render,
            'channel' => $channel,
            'team' => $team->only(["id", "name", "parameters"]),

            'instant_answers' => $instant_answers,
        ]);
    }

    public function create(Request $request, Team $team) {
        $validated = $request->validate([
            'question' => ['required', 'max:100', 'trim', 'string', 'min:5'],
            'channel' => ['required', 'string', 'min:24'],
        ]);

        $question = $validated['question'];
        $channel = $validated['channel'];

        try {
            $job_service = new JobService();
            $job_service->askStream(
                channel: $channel,
                team: $team,
                question: $question
            );
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'response' => $exception], 500);
        }
        return response()->json(['status' => 'success', 'channel' => $channel]);
    }

    public function vote(Request $request, Team $team, Answer $answer) {
        $validated = $request->validate([
            'vote' => ['required', Rule::in(['incr', 'decr'])],
        ]);
        $vote =  $validated['vote'] === "incr" ? 1 : -1;
        try {
            if ($vote == -1) {
                Answer::where('id', $answer->id)->update(['votes' => $answer->votes - 1]);
            } else
                Answer::where('id', $answer->id)->update(['votes' => $answer->votes + 1]);
            return response()->json(['status' => 'success', 'response' => [Answer::find($answer->id)]]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'response' => $exception], 500);
        }
    }

}
