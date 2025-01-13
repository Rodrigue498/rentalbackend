<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTrailerSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'renter']);
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'admin']);
    }
}
