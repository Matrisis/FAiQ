<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Team;
use App\Models\TeamParameters;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class Parameters extends Controller
{
    public function index(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }
        $channel = $team->id . '-' . Str::random(24);
        $instant_answers = Answer::where('team_id', $team->id)
            ->where("votes", ">", 5)
            ->orderBy('votes', 'desc')
            ->take(5)->get();
        return Inertia::render("Parameters", [
            'load' => "admin",
            'channel' => $channel,
            'team' => $team->only(["id", "name", "parameters"]),
            'instant_answers' => $instant_answers,
        ]);
    }

    public function update(Request $request, $params)
    {
        try {
            $params = TeamParameters::findOrFail($params);
            if ($request->user()->cannot('update', $params)) {
                abort(403);
            }

            $validated = $request->validate([
                'title' => ['max:100', 'string', 'min:5'],
                'background_color' => ['max:7', 'string', 'min:7'],
                'question_background_color' => ['max:7', 'string', 'min:7'],
                'text_color' => ['max:7', 'string', 'min:7'],
                'title_color' => ['max:7', 'string', 'min:7'],
                'accessible' => ['boolean'],
                'icon' => ['nullable', 'image', 'max:2048'],
                'logo' => ['nullable', 'image', 'max:2048'],
            ]);

            $params->title = $validated['title'] ?? $params->title;
            $params->background_color = $validated['background_color'] ?? $params->background_color;
            $params->question_background_color = $validated['question_background_color'] ?? $params->question_background_color;
            $params->text_color = $validated['text_color'] ?? $params->text_color;
            $params->title_color = $validated['title_color'] ?? $params->title_color;
            $params->accessible = $validated['accessible'] ?? $params->accessible;

            // Gestion du téléchargement de l'icône
            if ($request->hasFile('icon')) {
                // Supprimer l'ancienne icône si elle existe
                if ($params->icon_path) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $params->icon_path));
                }
                $iconPath = $request->file('icon')->store('icons', 'public');
                $params->icon_path = '/storage/' . $iconPath;
            }

            // Gestion du téléchargement du logo
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo si il existe
                if ($params->logo_path) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $params->logo_path));
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $params->logo_path = '/storage/' . $logoPath;
            }

            $params->save();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'errors' => [["Une erreur est survenue"]]], 500);
        }

        return response()->json(['success' => true, 'params' => $params]);
    }
}
