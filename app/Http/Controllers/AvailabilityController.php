<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function getAvailability($trailer_id)
    {
        $availability = Availability::where('trailer_id', $trailer_id)->get();
        return response()->json($availability);
    }

    public function updateAvailability(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            '*.date' => 'required|date',
            '*.available' => 'required|boolean',
        ]);

        // Loop through the validated data and update or create entries
        foreach ($validated as $availability) {
            Availability::updateOrCreate(
                [
                    'trailer_id' => $id,
                    'date' => $availability['date'],
                ],
                [
                    'available' => $availability['available'],
                ]
            );
        }

        return response()->json(['message' => 'Availability updated successfully']);
    }

}
