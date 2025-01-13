<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Dispute;

class DisputeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Dispute::create([
            'user_id' => 1,
            'description' => 'Issue with the booking, trailer condition is poor.',
            'resolved' => false,
        ]);
    }

}
