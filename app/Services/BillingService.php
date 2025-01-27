<?php

namespace App\Services;

use App\Models\Pricing;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Subscription;

/**
 * Service for managing billing and subscription operations
 * 
 * This service handles:
 * - Subscription management
 * - Payment processing
 * - Usage tracking and reporting
 * - Billing-related customer operations
 */
class BillingService
{
    /**
     * Initiate checkout process for one-time signup fee
     *
     * @param Team $team Team to process checkout for
     * @return mixed Checkout session
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

    /**
     * Get team's subscription invoices
     *
     * @param Team $team Team to get invoices for
     * @return \Illuminate\Support\Collection|array|null Collection of invoices
     */
    public static function invoices(Team $team): \Illuminate\Support\Collection|array|null
    {
        return $team->subscription($team->pricing->name)?->invoices();
    }

    /**
     * Check if team has active subscription
     *
     * @param Team $team Team to check
     * @return bool Subscription status
     */
    public static function isSubscribed(Team $team): bool
    {
        $subscription = $team->subscription($team->pricing->name);
        return $team->subscribed($team->pricing->name) && !$subscription->canceled();
    }

    /**
     * Create new subscription for team
     *
     * @param Team $team Team to subscribe
     * @return mixed Checkout session
     * @throws \Exception On subscription creation failure
     */
    public static function subscribe(Team $team)
    {
        $user = $team->owner()->first();
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
        $pricing = $team->pricing;
        return $team->newSubscription($pricing->name, $pricing->subscription_price_id)
            ->meteredPrice($pricing->meter_price_id)
            ->trialDays(7)
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('admin.billing.success', ['team' => $team->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('admin.billing.cancel', ['team' => $team->id]),
            ]);
    }

    /**
     * Handle successful payment callback
     *
     * @param Request $request HTTP request
     * @param Team $team Team context
     * @return mixed Redirect response
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

        $team->update(['has_paid' => true]);

        return Redirect::route('admin.billing.index', ['team' => $team->id]);
    }

    /**
     * Create metered subscription
     *
     * @param Team $team Team to create subscription for
     * @throws \Exception On creation failure
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
     * Report usage for metered billing
     *
     * @param Team $team Team to report usage for
     * @param int $quantity Usage quantity
     * @throws \Exception If no active subscription
     */
    public static function reportUsage(Team $team, int $quantity): void
    {
        $subscription = $team->subscription($team->pricing->name);

        if (!$subscription) {
            throw new \Exception('Team does not have an active subscription.');
        }

        $subscription->reportUsage();
    }

    /**
     * Get team's usage records
     *
     * @param Team $team Team to get usage for
     * @return mixed Usage records
     * @throws \Exception If no active subscription
     */
    public static function getUsage(Team $team)
    {
        $subscription = $team->subscription($team->pricing->name);

        if (!$subscription) {
            throw new \Exception('Team does not have an active subscription.');
        }
        return $team->subscription($team->pricing->name)->usageRecords()->first();
    }

    /**
     * Cancel team's subscription
     *
     * @param Team $team Team to cancel subscription for
     */
    public static function cancelSubscription(Team $team): void
    {
        $team->subscription($team->pricing->name)->cancelNowAndInvoice();
    }
}
