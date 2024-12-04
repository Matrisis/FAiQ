<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    /**
     * Show subscription details and invoices.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        $subscription = $team->subscription('default');

        $invoices = $team->invoices();

        return response()->json([
            'subscription' => $subscription,
            'invoices'     => $invoices,
        ]);
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        $subscription = $team->subscription('default');

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found.'], 404);
        }

        $subscription->cancel();

        return response()->json(['message' => 'Subscription cancelled successfully.']);
    }

    /**
     * Resume the subscription.
     */
    public function resume(Request $request)
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        $subscription = $team->subscription('default');

        if (!$subscription || !$subscription->onGracePeriod()) {
            return response()->json(['message' => 'Cannot resume subscription.'], 400);
        }

        $subscription->resume();

        return response()->json(['message' => 'Subscription resumed successfully.']);
    }

    /**
     * Request a refund for the last charge.
     */
    public function refund(Request $request)
    {
        $user = Auth::user();
        $team = $user->currentTeam;
        $latestInvoice = $team->invoicesIncludingPending()->first();

        if (!$latestInvoice) {
            return response()->json(['message' => 'No invoices found to refund.'], 404);
        }

        $paymentIntentId = $latestInvoice->asStripeInvoice()->payment_intent;

        try {
            $refund = Cashier::stripe()->refunds->create([
                'payment_intent' => $paymentIntentId,
            ]);

            return response()->json(['message' => 'Refund requested successfully.', 'refund' => $refund]);
        } catch (ApiErrorException $e) {
            return response()->json(['message' => 'Failed to process refund.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Download an invoice PDF.
     */
    public function downloadInvoice(Request $request, $invoiceId)
    {
        $user = Auth::user();
        $team = $user->currentTeam;

        return $team->downloadInvoice($invoiceId, [
            'vendor'  => 'Your Company Name',
            'product' => 'Your Product Name',
        ]);
    }
}
