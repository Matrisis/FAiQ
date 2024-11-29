<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Pricing;
use App\Models\RequestLogger;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request, Team $team)
    {
        if($request->user()->cannot('view', $team)) abort(403);

        $answersQuery = Answer::query()->where("team_id", $team->id);
        $loggerQuery = RequestLogger::query()->where('team_id', $team->id);

        // Date range filtering
        if ($request->start_date) {
            $answersQuery->whereDate('created_at', '>=', $request->start_date);
            $loggerQuery->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $answersQuery->whereDate('created_at', '<=', $request->end_date);
            $loggerQuery->whereDate('created_at', '<=', $request->end_date);
        }

        // Total number of answers
        $totalAnswers = $answersQuery->count();

        // Clone the query builder to prevent modifying the original query
        $topVotedAnswersQuery = clone $answersQuery;

        // Total new answers
        $newAnswers = $loggerQuery->where("new", true)->count();

        // Total old answers
        $oldAnswers = $loggerQuery->where("new", false)->count();

        $topVotedAnswers = $topVotedAnswersQuery->orderBy('votes', 'desc')->take(5)->get();

        $pricing = Pricing::find($team->pricing_id);

        $priceOld = $pricing->price_request;
        $priceNew = $pricing->price_request;

        $newAnswersPrice = $newAnswers * $priceNew;
        $oldAnswersPrice = $oldAnswers * $priceOld;

        return response()->json([
            'totalAnswers'      => $totalAnswers,
            'topVotedAnswers'   => $topVotedAnswers,
            'newAnswers'        => $newAnswers,
            'oldAnswers'        => $oldAnswers,
            'newAnswersPrice'   => $newAnswersPrice,
            'oldAnswersPrice'   => $oldAnswersPrice
        ]);
    }
}
