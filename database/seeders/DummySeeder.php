<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductTypeSeeder::class,
            ProductCategorySeeder::class,
            ProductVariatSeeder::class,
            ProductSeeder::class,
            ShippingCarrierSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}
