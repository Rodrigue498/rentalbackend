<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Bookings;
use App\Models\User;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are users and bookings in the database
        $users = User::all();
        $bookings = Bookings::all();

        if ($users->isEmpty() || $bookings->isEmpty()) {
            $this->command->error('Users or Bookings are missing. Run appropriate seeders first.');
            return;
        }

        // Seed payments
        foreach ($bookings as $booking) {
            $serviceFee = 0.05 * $booking->total_price; // 5% service fee
            $ownerPayout = 0.85 * $booking->total_price; // Deduct 15% from the owner's payout

            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()), // Dummy transaction ID
                'amount' => $booking->total_price,               // Total amount paid
                'service_fee' => $serviceFee,                    // 5% service fee
                'owner_payout' => $ownerPayout,                  // 85% owner's payout
                'status' => 'paid',                              // Payment status
                'payment_method' => 'Stripe',                    // Dummy payment method
            ]);
        }

        $this->command->info('Payments seeded successfully!');
    }
}
