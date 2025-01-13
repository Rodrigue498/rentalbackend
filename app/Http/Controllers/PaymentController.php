<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createBookingPayment(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
        ]);

        $booking = Bookings::findOrFail($validated['booking_id']);

        if ($booking->status !== 'pending') {
            return response()->json(['error' => 'Payment already processed for this booking.'], 400);
        }

        // Calculate total price and fees
        $service_fee = round($booking->total_price * 0.05, 2);
        $total_price = round($booking->total_price + $service_fee, 2);

        // Stripe API Key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $total_price * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method'],
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'transfer_data' => [
                    'destination' => $booking->trailer->owner->stripe_account_id, // Owner's Stripe account
                ],
                'application_fee_amount' => round($booking->total_price * 0.15, 2) * 100, // 15% service fee
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
            ]);

            // Update Booking Status
            $booking->status = 'payment_pending';
            $booking->save();

            return response()->json([
                'message' => 'Payment processed and held in escrow',
                'payment_intent' => $paymentIntent,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
