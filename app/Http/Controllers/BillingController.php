<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BillingController extends Controller
{
    /**
     * Display the billing page.
     */
    public function index(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }

        if ($team->hasPaid()) {
            return Redirect::route('dashboard');
        }

        return Inertia::render('Billing', [
            'team' => $team,
        ]);
    }

    /**
     * Initiate the checkout process for the one-time signup fee.
     */
    public function checkout(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }

        //return BillingService::checkout($team);
        return BillingService::subscribe($team);
    }

    /**
     * Handle the success callback after the one-time fee payment.
     */
    public function success(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }

        return BillingService::success($request, $team);
    }

    /**
     * Handle the cancellation of the payment process.
     */
    public function cancel(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }

        // Handle cancellation logic
        return Redirect::route('admin.billing.index', ['team' => $team->id])
            ->with('message', 'Payment was canceled.');
    }
}
