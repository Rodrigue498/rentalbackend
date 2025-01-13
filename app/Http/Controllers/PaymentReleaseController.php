<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Stripe\Transfer;

class PaymentReleaseController extends Controller
{
    public function releaseFunds($booking_id)
    {
        $booking = Bookings::findOrFail($booking_id);

        if ($booking->status !== 'payment_pending' || $booking->end_date > now()) {
            return response()->json(['error' => 'Funds cannot be released yet.'], 400);
        }

        try {
            $payout_amount = round($booking->total_price * 0.85, 2) * 100;

            Transfer::create([
                'amount' => $payout_amount,
                'currency' => 'usd',
                'destination' => $booking->trailer->owner->stripe_account_id,
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
            ]);

            $booking->status = 'completed';
            $booking->save();

            return response()->json(['message' => 'Funds released to owner.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
