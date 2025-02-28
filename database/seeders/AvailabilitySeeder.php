<?php

// database/seeders/AvailabilitySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Availability;
use Carbon\Carbon;

class AvailabilitySeeder extends Seeder
{
    public function run()
    {
        // Example of adding availability for a trailer
        Availability::create([
            'trailer_id' => 2,
             'available'=>true,
            'date' => Carbon::parse('2024-12-01'),
        ]);

        Availability::create([
            'trailer_id' => 3,
            'available'=>true,
            'date' => Carbon::parse('2024-12-02'),
        ]);


        // Add more availability dates as needed
    }
}
