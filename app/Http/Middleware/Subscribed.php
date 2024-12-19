<?php

namespace App\Http\Middleware;

use App\Services\BillingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Team;

class Subscribed
{
    /**
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {

        $team = Team::find($request->user()->current_team_id);

        if(! $team)
            abort(404);

        if ($request->user()->cannot('view', $team))
            abort(403);

        if (! $team->hasPaid()) {
            return redirect()->route('admin.billing.index', ['team' => $team->id]);
        }

        /*
        if (! $team->subscribed($team->pricing->name)) {
            return redirect()->route('admin.billing.index', ['team' => $team->id]);
        }
        */

        return $next($request);
    }
}
