<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $team = $request->route()->parameters()['team'] ?? null;
        if(!$team)
            abort(404);
        if(!$team->parameters->accessible)
            return redirect()->route('public.ask.maintenance', ['team' => $team->id]);
        return $next($request);
    }
}
