<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Team;
use App\Services\EmbeddingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnswerController extends Controller
{

    public function index(Request $request, Team $team){
        if($request->user()->cannot('view', $team)) abort(403);
        return Inertia::render("Answers", ["team" => $team]);
    }

    public function get(Request $request, Team $team){
        if($request->user()->cannot('view', $team)) abort(403);
        $paginate = $request->get("rowsPerPage", 10);
        $page = $request->get("page", 1);
        $sortBy = $request->get("sortBy", "answer");
        $sortType = $request->get("sortType", "asc");
        $search = $request->get("search", "");

        $answers = Answer::where('team_id', $team->id);
        if($search) {
            $answers = $answers->where("question", "like", "%$search%")
                ->orWhere("answer", "like", "%$search%");
        }
        $answers = $answers->orderBy($sortBy, $sortType)
            ->paginate(perPage: $paginate, columns: [
                "question",
                "answer",
                "id",
            ], page: $page);
        return response()->json(['status' => 'success', 'response' => $answers]);
    }

    public function update(Request $request, Team $team, Answer $answer){
        if($request->user()->cannot('update', $answer)) abort(403);
        try {
            $validated = $request->validate([
                'answer' => ['required', 'string', 'min:5'],
            ]);
            $validate_answer = $validated['answer'];
            $embedding_service = new EmbeddingService($team);
            $validated_answer_embed = $embedding_service->embed($validated['answer']);
            $answer->update([
               "answer" => $validate_answer,
               "answer_vector" => $validated_answer_embed
            ]);
            return response()->json(['status' => 'success', 'response' => []]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'response' => $exception], 500);
        }
    }

    public function delete(Request $request, Team $team, Answer $answer){
        if($request->user()->cannot('delete', $answer)) abort(403);
        try {
            $answer->delete();
            return response()->json(['status' => 'success', 'response' => []]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'response' => $exception], 500);
        }
    }

}
