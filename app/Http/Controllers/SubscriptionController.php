<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    /**
     * Show subscription details and invoices.
     */
    public function index(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }
        $team = Team::find($team->id);
        $subscription = $team->subscription($team->pricing->name);
        $invoices = BillingService::invoices($team);
        return Inertia::render("Subscription", [
            "team" => $team,
            "active_subscription" => BillingService::isSubscribed($team),
            "invoices" => $invoices,
        ]);
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }
        BillingService::cancelSubscription($team);
        return back();
    }

    /**
     * Resume the subscription.
     */
    public function resume(Request $request, Team $team)
    {
        if ($request->user()->cannot('view', $team)) {
            abort(403);
        }
        if(BillingService::isSubscribed($team)) {
            return redirect()->route('dashboard');
        }
        return BillingService::subscribe($team);
    }

}
