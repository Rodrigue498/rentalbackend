<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeasonalPricing;
use App\Models\Trailer;
use Carbon\Carbon;

class SeasonalPricingSeeder extends Seeder
{
    public function run()
    {
        // Fetch existing trailers
        $trailers = Trailer::pluck('id')->toArray(); // Get all trailer IDs

        if (empty($trailers)) {
            // If no trailers exist, create a default one
            $trailer = Trailer::create([
                'user_id' => 1, // Ensure user exists
                'title' => 'Default Trailer',
                'description' => 'A sample trailer.',
                'type' => 'Utility',
                'features' => 'Basic features',
                'size' => 10,
                'capacity' => 1000,
                'available' => true,
                'approval_status' => 'approved',
                'price' => 50,
                'images' => json_encode(['uploads/trailers/default.jpg']),
            ]);

            $trailers[] = $trailer->id; // Add the new trailer ID
        }

        // Insert seasonal pricing only for existing trailers
        foreach ($trailers as $trailer_id) {
            SeasonalPricing::create([
                'trailer_id' => $trailer_id,
                'start_date' => Carbon::parse('2024-12-01')->addDays(rand(0, 30)),
                'end_date' => Carbon::parse('2024-12-10')->addDays(rand(0, 30)),
                'price' => rand(100, 200), // Randomized seasonal price
            ]);
        }
    }
}
