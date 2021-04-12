<?php

namespace Database\Seeders;

use App\Models\ShippingCarrier;
use Illuminate\Database\Seeder;

class ShippingCarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingCarrier::truncate();

        ShippingCarrier::firstOrCreate([
            'name' => 'Free shipping',
            'status' => 1,
            'eta' => 72,
        ]);

        ShippingCarrier::firstOrCreate([
            'name' => 'Express',
            'status' => 1,
            'price' => 10,
            'eta' => 24,
        ]);
    }
}
