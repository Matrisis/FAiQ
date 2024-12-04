<?php

namespace App\Services;

use App\Models\Pricing;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Subscription;

class BillingService
{
    /**
     * Initiate the checkout process for the one-time signup fee.
     */
    public static function checkout(Team $team)
    {
        $pricing = $team->pricing;
        $priceId = $pricing->price_init_id;
        $user = auth()->user();

        // Ensure the team has a Stripe customer ID
        if (!$team->hasStripeId()) {
            $team->createAsStripeCustomer([
                'email' => $user->email,
            ]);
        } else {
            // Update the Stripe customer's email if it has changed
            $team->updateStripeCustomer([
                'email' => $user->email,
            ]);
        }

        // Include 'customer' in $sessionOptions to lock the email
        $sessionOptions = [
            'success_url' => route('admin.billing.success', ['team' => $team->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('admin.billing.cancel', ['team' => $team->id]),
            'customer'    => $team->stripe_id, // Lock the email field
            'mode'        => 'payment',
        ];

        return $team->checkout([$priceId => 1], $sessionOptions);
    }

    public static function invoices(Team $team): \Illuminate\Support\Collection|array
    {
        return $team->subscription($team->pricing->name)->invoices();
    }

    public static function isSubscribed(Team $team): bool
    {
        $subscription = $team->subscription($team->pricing->name);
        return $team->subscribed($team->pricing->name) && !$subscription->canceled();
    }

    /**
     * @throws \Exception
     */
    public static function subscribe(Team $team)
    {
        $pricing = $team->pricing;
        $subscriptionPriceId = $pricing->subscription_price_id; // Metered price ID
        return $team->newSubscription($pricing->name)
            ->meteredPrice($subscriptionPriceId)
        ->checkout([
            'success_url' => route('admin.billing.success', ['team' => $team->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('admin.billing.cancel', ['team' => $team->id]),
        ]);
    }
    /**
     * Handle the success callback after the one-time fee payment.
     */
    public static function success(Request $request, Team $team)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            abort(422, 'No session ID provided');
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId, []);

        if ($session->payment_status !== 'paid') {
            abort(422, 'Payment not successful');
        }


        // Payment was successful; create the metered subscription
        try {
            self::createSubscription($team);
        } catch (\Exception $e) {
            return self::subscribe($team);
        }

        $team->update(['has_paid' => true]);

        return Redirect::route('admin.billing.index', ['team' => $team->id]);
    }

    /**
     * Create a metered subscription for the team.
     */
    public static function createSubscription(Team $team)
    {
        $pricing = $team->pricing;
        $subscriptionPriceId = $pricing->subscription_price_id; // Metered price ID

        try {
            $team->newSubscription($pricing->name)
                ->meteredPrice($subscriptionPriceId)
                ->create();

        } catch (IncompletePayment $exception) {
            return Redirect::route('billing.payment', ['team' => $team->id]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Reports usage to Stripe for metered billing.
     */
    public static function reportUsage(Team $team, int $quantity): void
    {
        $subscription = $team->subscription($team->pricing->name);

        if (!$subscription) {
            throw new \Exception('Team does not have an active subscription.');
        }

        $subscription->reportUsage();
    }

    public static function cancelSubscription(Team $team): void
    {
        $team->subscription($team->pricing->name)->cancelNowAndInvoice();
    }

}
