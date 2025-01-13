<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Bookings;

class ContractController extends Controller
{
    public function generateContract($bookingId)
    {
        $booking = Bookings::with(['user', 'trailer'])->findOrFail($bookingId);

        // Prepare data for the PDF
        $data = [
            'renterName' => $booking->user->name,
            'ownerName' => $booking->trailer->user->name,
            'trailerTitle' => $booking->trailer->title,
            'trailerType' => $booking->trailer->type,
            'trailerFeatures' => $booking->trailer->features,
            'startDate' => $booking->start_date,
            'endDate' => $booking->end_date,
            'totalPrice' => $booking->total_price,
            'conditions' => "The renter agrees to return the trailer in its original condition.",
        ];

        // Generate PDF
        $pdf = Pdf::loadView('contracts.template', $data);

        // Save or return the PDF
        return $pdf->download('Rental_Contract_' . $booking->id . '.pdf');
    }
}

