<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $users = [
            [
                'name' => 'John Doe',
                'email' => 'johnDO@example.com',
                'address'=>'123 Main market Lahore',
                'phone'=>'03314216767',
                'password' => Hash::make('password'),
                'role' => 'owner',

            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane1@example.com',
                'address'=>'123 Main market Lahore',
                'phone'=>'03314216763',
                'password' => Hash::make('password'),
                'role' => 'renter',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin1@example.com',
                'address'=>'123 Main market Lahore',
                'phone'=>'03314216744',
                'password' => Hash::make('password'),
                'role' => 'administrator',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
