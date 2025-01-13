<?php

// database/seeders/SeasonalPricingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeasonalPricing;
use Carbon\Carbon;

class SeasonalPricingSeeder extends Seeder
{
    public function run()
    {
        // Example of adding seasonal pricing for a trailer
        SeasonalPricing::create([
            'trailer_id' => 2,
            'start_date' => Carbon::parse('2024-12-01'),
            'end_date' => Carbon::parse('2024-12-10'),
            'price' => 100, // Seasonal price during high demand period
        ]);

        SeasonalPricing::create([
            'trailer_id' => 4,
            'start_date' => Carbon::parse('2024-12-20'),
            'end_date' => Carbon::parse('2024-12-25'),
            'price' => 150, // Higher price for the holiday season
        ]);

        SeasonalPricing::create([
            'trailer_id' => 3,
            'start_date' => Carbon::parse('2024-12-26'),
            'end_date' => Carbon::parse('2024-12-31'),
            'price' => 120, // Another seasonal pricing for the end of the year
        ]);

        // Add more seasonal pricing as needed
    }
}
