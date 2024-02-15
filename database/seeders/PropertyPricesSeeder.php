<?php

namespace Database\Seeders;

use App\Models\PropertyPrice;
use Illuminate\Database\Seeder;

class PropertyPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $property_price = new PropertyPrice();
        $property_price->name = 'Precio por mes';
        $property_price->description = 'Descuento por alquilar 6 meses';
        $property_price->price = 180;
        $property_price->property_id = 1;
        $property_price->type = 'rent';
        $property_price->save();
    }
}
