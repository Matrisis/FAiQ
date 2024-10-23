<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Facades\Redirect;
use Laravel\Cashier\Cashier;

class BillingService
{
    /**
     * @var array|string[]
     */
    private static array $products = ["default" => "price_1QD54YAUBCE5hHWSW9NZ4MF1"];

    public static function checkout(Team $team)
    {
        $product_id = self::$products["default"];
        $quantity = 1;

        try {
            return $team->checkout([$product_id => $quantity],
            [
                'success_url' => route('admin.billing.success', ['team' => $team->id]).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('admin.billing.index', ['team' => $team->id]),
            ]);
        } catch (\Exception $exception) {
            dd($exception);
        }

    }

    public static function success($request, Team $team) {
        $sessionId = $request->get('session_id');
        if ($sessionId === null) abort(422, 'No session id provided');
        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);
        if ($session->payment_status !== 'paid') abort(422, 'Payment not successful');

        if ($session->payment_status === 'paid' && ! $team->hasPaid()) {
            $team->update([
                'has_paid' => true,
            ]);
        }

        return Redirect::route('admin.billing.index', ['team' => $team->id]);
    }
}
