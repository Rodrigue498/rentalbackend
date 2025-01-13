<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bookings;

class BookingsTrailerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Bookings::create([
        'user_id' => 1, // Assuming user with ID 1
        'trailer_id' => 2, // Assuming trailer with ID 2
        'start_date' => '2024-12-26',
        'end_date' => '2024-12-30',
        'status' => 'pending',
    ]);
}
}
