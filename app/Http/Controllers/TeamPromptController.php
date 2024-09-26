<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamPrompt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamPromptController extends Controller
{


    public function index(Request $request, Team $team) {
        $teamPrompt = TeamPrompt::where('team_id', $team->id)->first();
        if (!$teamPrompt)
            if ($request->user()->cannot('create', TeamPrompt::class)) abort(403);
        if ($request->user()->cannot('update', TeamPrompt::class)) abort(403);
        return Inertia::render('Prompts/Prompt', [
            'team' => $team->only(['id', 'name', 'prompts']),
        ]);
    }

    public function update(Request $request, Team $team) {
        $teamPrompt = TeamPrompt::where('team_id', $team->id)->first();
        if (!$teamPrompt)
            if ($request->user()->cannot('create', TeamPrompt::class)) abort(403);
        if ($request->user()->cannot('update', TeamPrompt::class)) abort(403);
        try {
            $validated = $request->validate([
                'prompt' => ['required', 'string', 'min:5'],
            ]);
            if ($teamPrompt) {
                $teamPrompt->prompt = $validated['prompt'];
                $teamPrompt->save();
            } else {
                $teamPrompt = TeamPrompt::create([
                    'team_id' => $team->id,
                    'prompt' => $validated['prompt'],
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Prompt updated successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

}
