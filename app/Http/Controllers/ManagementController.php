<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class ManagementController extends Controller
{
    public function index(Request $request) {
        if(!$request->user()->isAdmin())
            abort(403);
        return Inertia::render("Management");
    }

    public function list(Request $request) {
        if(!$request->user()->isAdmin()) abort(403);
        $teams = Team::query();
        return DataTables::eloquent($teams)
            ->filter(function ($query) use ($request) {
                if ($request->has('name') && !empty($request->get('name'))) {
                    $query->where('name', 'like', '%' . $request->get('name') . '%');
                }
            })
            ->toJson();
    }

    public function unlock(Request $request, Team $team)
    {
        try {
            if (!$request->user()->isAdmin()) abort(403);
            if ($team->locked) {
                $team->locked = false;
                $team->save();
            }
            return response()->json(["success" => true]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "errors" => [["Une erreur est survenue"]]], 500);
        }
    }

    public function viewTeam(Request $request, Team $team)
    {
        try {
            if(!$request->user()->isAdmin()) abort(403);
            $user = $request->user();
            $user->current_team_id = $team->id;
            $user->save();
            return response()->json(["success" => true, "route" => route('admin.dashboard')]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "errors" => [["Une erreur est survenue"]]], 500);
        }
    }
}
