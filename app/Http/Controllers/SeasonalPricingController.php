<?php

namespace App\Http\Controllers;

use App\Models\SeasonalPricing;
use Illuminate\Http\Request;

class SeasonalPricingController extends Controller
{
    public function getPricing($trailer_id)
    {
        $pricing = SeasonalPricing::where('trailer_id', $trailer_id)->get();
        return response()->json($pricing);
    }

    public function setPricing(Request $request, $trailer_id)
    {
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price' => 'required|numeric|min:0',
        ]);

        $data['trailer_id'] = $trailer_id;

        SeasonalPricing::updateOrCreate(
            ['trailer_id' => $trailer_id, 'start_date' => $data['start_date'], 'end_date' => $data['end_date']],
            ['price' => $data['price']]
        );

        return response()->json(['message' => 'Seasonal pricing updated successfully.']);
    }
}
