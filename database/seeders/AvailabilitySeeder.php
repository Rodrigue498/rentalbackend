<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Availability;
use App\Models\Trailer;
use Carbon\Carbon;

class AvailabilitySeeder extends Seeder
{
    public function run()
    {
        // Fetch existing trailers
        $trailers = Trailer::pluck('id')->toArray(); // Get an array of existing trailer IDs

        if (empty($trailers)) {
            // If no trailers exist, create at least one
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

            $trailers[] = $trailer->id; // Add the new trailer ID to the list
        }

        // Insert availability only for existing trailers
        foreach ($trailers as $trailer_id) {
            Availability::create([
                'trailer_id' => $trailer_id,
                'available' => true,
                'date' => Carbon::parse('2024-12-01')->addDays(rand(0, 10)),
            ]);
        }
    }
}
