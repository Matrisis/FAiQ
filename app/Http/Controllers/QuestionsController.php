<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\AnswerService;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function get(Request $request, Team $team)
    {
        $question = $request->get('question');
        $ask_service = new AnswerService($team);
        if ($question){
            $follow_up = $ask_service->retrieve($question, 5, 'question_vector', '0.3')
                ->map(function ($fup) {
                    return $fup->only(['id', 'question', 'answer', 'votes']);
                });
            foreach ($follow_up as $elem => $answer) {
                if($answer["answer"] == "I don't know." || $answer["answer"] == "'I don't know.'"){
                    unset($follow_up[$elem]);
                }
            }
            return response()->json(["status" => "success", "response" => $follow_up], 200);
        } else {
            return response()->json(["status" => "error", "response" => "No question provided"], 500);
        }
    }
}
