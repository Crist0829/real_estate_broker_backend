<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql')->table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin_123'),
        ]);

        DB::connection('mysql')->table('users')->insert([
            'name' => 'Inmobiliaria EJ',
            'email' => 'operator@tl300.com',
            'password' => Hash::make('realestate_123'),
        ]);

        DB::connection('mysql')->table('users')->insert([
            'name' => 'client',
            'email' => 'client@tl300.com',
            'password' => Hash::make('client_123'),
        ]);

    }
}