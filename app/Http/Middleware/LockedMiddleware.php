<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class LockedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $team = Team::find($request->user()->current_team_id);

        if(! $team)
            abort(404);

        if ($request->user()->cannot('view', $team))
            abort(403);

        if ($team->isLocked()) {
            return Inertia::render("Locked", ["team" => $team]);
        }

        return $next($request);
    }
}
