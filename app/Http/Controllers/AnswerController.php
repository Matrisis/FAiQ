<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Team;
use App\Services\EmbeddingService;
use Illuminate\Http\Request;

class AnswerController extends Controller
{

    public function index(Request $request, Team $team){
        if($request->user()->cannot('view', $team)) abort(403);
    }

    public function update(Request $request, Team $team, Answer $answer){
        if($request->user()->cannot('update', $answer)) abort(403);
        try {
            $validated = $request->validate([
                'answer' => ['required', 'max:255', 'string', 'min:5'],
            ]);
            $answer->answer = $validated['answer'];
            $embedding_service = new EmbeddingService($team);
            $answer->answer_vector = $embedding_service->embed($validated['answer']);
            $answer->save();
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
