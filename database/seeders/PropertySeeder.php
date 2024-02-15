<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $property = new Property();
        $property->name = 'Casa ejemplo';
        $property->description = 'Hermosa casa para estrenar';
        $property->location = 'Calle gutierrez numero 2';
        $property->status = 'available';
        $property->floors = 1;
        $property->bedrooms = 4;
        $property->livingrooms = 2;
        $property->bathrooms = 2;
        $property->kitchens = 1;
        $property->garage = true;
        $property->user_id = 1;
        $property->save();

        
    }
}
