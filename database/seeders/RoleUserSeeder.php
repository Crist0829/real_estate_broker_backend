<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql')->table('role_users')->insert([
            'role_id' => 1,
            'user_id' => 1
        ]);

        DB::connection('mysql')->table('role_users')->insert([
            'role_id' => 2,
            'user_id' => 2
        ]);

        DB::connection('mysql')->table('role_users')->insert([
            'role_id' => 3,
            'user_id' => 3
        ]);
    }
}
