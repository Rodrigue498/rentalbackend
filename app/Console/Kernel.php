<?php

namespace App\Console;

use App\Models\Bookings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingNotification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $bookings = Bookings::where('start_date', now()->addDay()->toDateString())
                                ->where('status', 'confirmed')
                                ->get();

            foreach ($bookings as $booking) {
                $details = [
                    'subject' => 'Booking Reminder',
                    'body' => "Your booking for Trailer #{$booking->trailer_id} starts tomorrow.",
                    'actionText' => 'View Booking',
                    'actionURL' => url('/bookings/' . $booking->id),
                    'sms' => "Reminder: Your booking for Trailer #{$booking->trailer_id} starts tomorrow.",
                ];

                $user = $booking->user;
                Notification::send($user, new BookingNotification($details));
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
