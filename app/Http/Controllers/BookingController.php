<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SeasonalPricing;
use App\Models\UnavailableDate;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Notification;
class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Book a trailer
// Book a trailer
public function book(Request $request)
{
    $validated = $request->validate([
        'trailer_id' => 'required|exists:trailers,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
    ]);

    $start_date = Carbon::parse($validated['start_date']);
    $end_date = Carbon::parse($validated['end_date']);

    // Check for conflicting dates
    $conflictingDates = UnavailableDate::where('trailer_id', $validated['trailer_id'])
        ->whereBetween('date', [$start_date, $end_date])
        ->exists();

    if ($conflictingDates) {
        return response()->json(['message' => 'Selected dates are not available.'], 400);
    }

    // Create a new booking
    $trailer = Trailer::findOrFail($validated['trailer_id']);
    $days = $start_date->diffInDays($end_date) + 1;
    $total_price = $days * $trailer->price;

    $booking = Bookings::create([
        'user_id' => Auth::id(),
        'trailer_id' => $validated['trailer_id'],
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => 'pending',
        'total_price' => $total_price,
    ]);

    // Mark dates as unavailable
    $dates = CarbonPeriod::create($start_date, $end_date);
    foreach ($dates as $date) {
        UnavailableDate::create([
            'trailer_id' => $validated['trailer_id'],
            'date' => $date->format('Y-m-d'),
        ]);
    }

    return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
}

// Get unavailable dates for a trailer
public function getUnavailableDates($trailer_id)
{
    $unavailableDates = UnavailableDate::where('trailer_id', $trailer_id)->pluck('date');
    return response()->json(['unavailable_dates' => $unavailableDates]);
}

// Cancel a booking
public function cancelBooking($id)
{
    $booking = Bookings::findOrFail($id);

    // Delete unavailable dates
    UnavailableDate::where('trailer_id', $booking->trailer_id)
        ->whereBetween('date', [$booking->start_date, $booking->end_date])
        ->delete();

    // Ensure 'canceled' is passed as a string
    $booking->update(['status' => 'canceled']);

    return response()->json(['message' => 'Booking canceled successfully.']);
}

    // Calculate price with seasonal pricing consideration
    public function calculatePrice($trailer_id, $start_date, $end_date)
    {
        $dates = CarbonPeriod::create($start_date, $end_date)->toArray();
        $totalPrice = 0;

        foreach ($dates as $date) {
            $seasonalPrice = SeasonalPricing::where('trailer_id', $trailer_id)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->first();

            $price = $seasonalPrice ? $seasonalPrice->price : Trailer::find($trailer_id)->price;
            $totalPrice += $price;
        }

        return $totalPrice;
    }

    public function confirmBooking($booking)
{
    $details = [
        'subject' => 'Booking Confirmation',
        'body' => "Your booking for Trailer #{$booking->trailer_id} is confirmed.",
        'actionText' => 'View Booking',
        'actionURL' => url('/bookings/' . $booking->id),
        'sms' => "Booking confirmed for Trailer #{$booking->trailer_id}.",
    ];

    $user = $booking->user;

    // Send notification
    Notification::send($user, new BookingNotification($details));
}
}
