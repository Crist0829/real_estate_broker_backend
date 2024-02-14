<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::create([
            'name' => 'administrator',
            'description' => 'Can manage real estates'
        ]);

        Role::create([
            'name' => 'agent',
            'description' => 'Its a real estate agency'
        ]);

        Role::create([
            'name' => 'client',
            'description' => 'A user who will be able to see the published properties'
        ]);

    }
}
