<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $query = Answer::query();

        // Date range filtering
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Total number of answers
        $totalAnswers = $query->count();

        // Clone the query builder to prevent modifying the original query
        $answersByTypeQuery = clone $query;
        $answersByChannelQuery = clone $query;
        $topVotedAnswersQuery = clone $query;

        // Answers by Type with Sum of Votes
        $answersByType = $answersByTypeQuery->select(
            'type',
            DB::raw('count(*) as count'),
            DB::raw('SUM(votes) as total_votes')
        )
            ->groupBy('type')
            ->orderBy('total_votes', 'desc')
            ->get();

        // Answers by Channel
        $answersByChannel = $answersByChannelQuery->select(
            'channel',
            DB::raw('count(*) as count')
        )
            ->groupBy('channel')
            ->orderBy('count', 'desc')
            ->get();

        // Top Voted Answers
        $topVotedAnswers = $topVotedAnswersQuery->orderBy('votes', 'desc')->take(5)->get();

        return response()->json([
            'totalAnswers'      => $totalAnswers,
            'answersByType'     => $answersByType,
            'topVotedAnswers'   => $topVotedAnswers,
            'answersByChannel'  => $answersByChannel,
        ]);
    }
}
