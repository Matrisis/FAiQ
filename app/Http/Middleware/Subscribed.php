<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Team;

class Subscribed
{
    public function handle(Request $request, Closure $next): Response
    {

        $team = Team::find($request->user()->current_team_id);

        if(! $team)
            abort(404);

        if ($request->user()->cannot('view', $team))
            abort(403);

        if (! $team->hasPaid()) {
            return redirect()->route('admin.billing.index', ['team' => $team->id]);
        }

        return $next($request);
    }
}
