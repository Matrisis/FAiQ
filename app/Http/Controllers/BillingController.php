<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BillingController extends Controller
{
    public function index(Request $request, Team $team)
    {
        if($request->user()->cannot('view', $team)) abort(403);
        if($team->hasPaid()) {
            return Redirect::route('dashboard');
        }
        return Inertia::render('Billing', [
            'team' => $team,
        ]);
    }

    public function checkout(Request $request, Team $team)
    {
        if($request->user()->cannot('view', $team)) abort(403);
        return BillingService::checkout(team: $team);
    }

    public function success(Request $request, Team $team)
    {
        if($request->user()->cannot('view', $team)) abort(403);
        return BillingService::success(request: $request, team: $team);
    }
}
